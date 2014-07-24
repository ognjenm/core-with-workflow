<?php

namespace Telenok\Core\Interfaces\Eloquent\Object;

abstract class Model extends \Illuminate\Database\Eloquent\Model {

	use \Illuminate\Database\Eloquent\SoftDeletingTrait;
	
	public $incrementing = false;
	public $timestamps = true;

	protected $hasVersioning = true;
	protected $ruleList = [];
	protected $multilanguageList = []; 
	protected $dates = ['deleted_at'];

	protected static $staticListField = [];
	protected static $staticListFillable = [];
	protected static $staticListMultilanguage = [];
	protected static $staticListFieldDate = [];

	public static function boot()
	{
		parent::boot();

		static::creating(function($model)
		{
			$model->generateKeyId();
		});

		static::saved(function($model)
		{
			$model->translateSync();
		});

		static::deleting(function($model)
		{
			$model->deleteSequence();
		});
	}

	protected function generateKeyId()
	{
		if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
		{
			if ($this->getKey())
			{
				$sequence = new \Telenok\Object\Sequence();
				$sequence->{$sequence->getKeyName()} = $this->getKey();
				$sequence->class_model = get_class($this);
				$sequence->save();
			}
			else
			{
				$sequence = \Telenok\Object\Sequence::create(['class_model' => get_class($this)]);
			}

			$this->{$this->getKeyName()} = $sequence->getKey();
		}
	}

	protected function deleteSequence()
	{
		if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
		{
			$sequence = \Telenok\Object\Sequence::find($this->getKey());
			
			if ($this->forceDeleting)
			{
				$sequence->forceDelete();
			}
			else
			{
				$sequence->delete();
			}
		}
	}

	protected function translateSync()
	{
		if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
		{
			\Telenok\Object\Translation::where('translation_object_model_id', $this->getKey())->forceDelete();

			foreach ($this->getMultilanguage() as $fieldCode)
			{
				$value = $this->$fieldCode->toArray();

				foreach ($value as $language => $string)
				{
					\Telenok\Object\Translation::create([
						'translation_object_model_id' => $this->getKey(),
						'translation_object_field_code' => $fieldCode,
						'translation_object_language' => $language,
						'translation_object_string' => $string,
					]);
				}
			}

			$type = $this->type();

			$this->sequence->fill([
				'title' => ($this->title instanceof \Illuminate\Support\Collection ? $this->title->toArray() : $this->title),
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
				'deleted_at' => $this->deleted_at,
				'active' => $this->active,
				'start_at' => $this->start_at,
				'end_at' => $this->end_at,
				'created_by_user' => $this->created_by_user,
				'updated_by_user' => $this->updated_by_user,
				'sequences_object_type' => $type->getKey(),
				'treeable' => $type->treeable,
			])->save();
		}
	}

	public function sequence()
	{
		return $this->hasOne('\Telenok\Object\Sequence', 'id');
	}
	
	public function type()
	{
		return \Telenok\Object\Type::whereCode($this->getTable())->first();
	} 

	public function hasVersioning()
	{
		return $this->hasVersioning;
	}

	public function classController()
	{
		return $this->class_controller;
	}

	public function treeForming()
	{
		return $this->type()->treeable;
	}

	public static function eraseStatic($model)
	{
		$class = get_class($model);
		
		static::$staticListField[$class] = null; 
		static::$staticListFillable[$class] = null;
		static::$staticListMultilanguage[$class] = null;
		
		$model->getObjectField();
		$model->getFillable();
		$model->getMultilanguage();
	}
	
	public function fill(array $attributes)
	{
		foreach ($this->fillableFromArray($attributes) as $key => $value)
		{
			$key = $this->removeTableFromKey($key); 
			
			if ($this->isFillable($key))
			{
				$this->__set($key, $value);
			}
		}

		$this->addDateField();

		return $this;
	}

	public function addFillable($attributes)
	{
		$this->fillable = array_unique(array_merge($this->fillable, (array) $attributes));

		return $this;
	} 

	protected function fillableFromArray(array $attributes)
	{
		$this->fillable = array_unique(array_merge($this->fillable, $this->getFillable()));

		return parent::fillableFromArray($attributes);
	}

	public function storeOrUpdate($input = [])
	{
		if ($this instanceof \Telenok\Core\Model\Object\Sequence)
		{
			throw new \Exception('Cant storeOrUpdate sequence model directly');
		} 
 
		try
		{
			$type = $this->type();
		}
		catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			throw new \Exception("Telenok\Core\Interfaces\Eloquent\Object\Model::storeOrUpdate() - Error: 'type of object not found, please, define it'");
		}

		$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array) $input);
		
		try
		{
			if (!$this->exists)
			{
				$model = $this->findOrFail($input->get($this->getKeyName()));
			}
			else
			{
				$model = $this;
			}
		} 
		catch (\Exception $ex) 
		{
			$model = new static();
		}

		foreach($model->fillable as $fillable)
		{ 
			if ($input->has($fillable))
			{
				$model->__set($fillable, $input->get($fillable));
			}
			else if (!$model->exists)
			{
				$this->__set($fillable, null);
				$input->put($fillable, null);
			}
			else
			{
				$input->put($fillable, $model->$fillable);
			} 
		} 
		
		try
		{
			$this->validateStoreOrUpdatePermission($type, $input);

			\DB::transaction(function() use ($type, $input, $model)
			{  
				$classControllerObject = null;

				$exists = $model->exists;
				
				if (!$exists && !\Auth::can('create', "object_type.{$type->code}"))
				{
					throw new \LogicException('Cant create. Access denied.');
				}
				else if ($exists && !\Auth::can('update', "object_type.{$type->code}"))
				{
					throw new \LogicException('Cant update. Access denied.');
				}

				\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.before', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}"));

				if ($type->classController())
				{
					$classControllerObject = \App::build($type->classController());

					$classControllerObject->preProcess($model, $type, $input);
				}

				$model->preProcess($type, $input);  

				$validator = new \Telenok\Core\Interfaces\Validator\Model($model, $input, $this->LL('error'), $this->validatorCustomAttributes());

				if ($validator->fails())
				{
					throw (new \Telenok\Core\Interfaces\Exception\Validate())->setMessageError($validator->messages());
				}

				if ($type->classController())
				{
					$classControllerObject->validate($model, $input);
				} 
				
				$model->fill($input->all())->push();

				if (!$exists && $type->treeable)
				{ 
					$model->makeRoot(); 
				}

				$model->postProcess($type, $input);

				if ($type->classController())
				{
					$classControllerObject->postProcess($model, $type, $input);
				} 

				\Event::fire('workflow.' . ($exists ? 'update' : 'store') . '.after', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}")->setResource($model));
			});
		}
		catch (\Telenok\Core\Interfaces\Exception\Validate $e)
		{
			throw $e;
		}
		catch (\Exception $e)
		{
			throw $e;
		}

		return $model;
	}

	protected function validatorCustomAttributes()
	{
		static $attr = null;
		
		if (empty($attr))
		{
			$attr = [];
			
			$attr['table'] = $this->getTable();
			
			foreach($this->getFieldForm() as $field)
			{
				$attr[$field->code] = $field->translate('title');
			}
		}
		
		return $attr;
	}

	protected function validateStoreOrUpdatePermission($type = null, $input = null)
	{
		if (!$type)
		{
			$type = $this->type();
		}

		if (!$this->exists && !\Auth::can('create', "object_type.{$type->code}"))
		{
			throw new \LogicException('Cant create. Access denied.');
		}
		else if ($this->exists && !\Auth::can('update', $this->getKey()))
		{
			throw new \LogicException('Cant update. Access denied.');
		}
	}

	public function preProcess($type, $input)
	{
		return $this;
	}

	public function postProcess($type, $input)
	{
		$config = \App::make('telenok.config')->getObjectFieldController();

		foreach ($type->field()->get() as $field)
		{
			$config->get($field->key)->saveModelField($field, $this, $input);
		}

		if ($this->hasVersioning())
		{
			\Telenok\Object\Version::add($this);
		}

		return $this;
	}

	public function __get($key)
	{
		try
		{
			$value = parent::__get($key);
		}
		catch (\Exception $e)
		{
			$value = null;
		}
		
		$f = $this->getObjectField()->get($key);

		$f_ = \App::make('telenok.config')->getObjectFieldController();

		if ($f)
		{
			$field = $f_->get($f->key);
			
			return $field->getModelAttribute($this, $key, $value, $f);
		} 
		else
		{
			if ($this instanceof \Telenok\Core\Model\Object\Field && ($fieldController = $f_->get($this->key)) && in_array($key, $fieldController->getSpecialField()))
			{
				return $fieldController->getModelSpecialAttribute($this, $key, $value);
			}
			else
			{
				foreach ($this->getObjectField()->toArray() as $key_ => $field_)
				{
					$fieldController = $f_->get($field_->key);

					if ($fieldController && in_array($key, $fieldController->getModelField($this, $field_)))
					{
						return $fieldController->getModelAttribute($this, $key, $value, $field_);
					}
				}
			}
		}
		
		return $value;
	}

	public function __set($key, $value)
	{  
		$f = $this->getObjectField()->get($key);

		$f_ = \App::make('telenok.config')->getObjectFieldController();

		if ($f)
		{
			$f_->get($f->key)->setModelAttribute($this, $key, $value, $f);
		}
		else if ($this instanceof \Telenok\Core\Model\Object\Field && ($fieldController = $f_->get($this->key)) && in_array($key, $fieldController->getSpecialField()))
		{ 
			$fieldController->setModelSpecialAttribute($this, $key, $value);
		}
		else
		{  
			foreach ($this->getObjectField()->toArray() as $key_ => $field_)
			{
				if ($fieldController = $f_->get($field_->key))
				{
					if ($this instanceof \Telenok\Core\Model\Object\Field && in_array($key, $fieldController->getSpecialField()))
					{
						$fieldController->setModelSpecialAttribute($this, $key, $value);
						
						return;
					}
					else if (in_array($key, $fieldController->getModelField($this, $field_)))
					{
						$fieldController->setModelAttribute($this, $key, $value, $field_);
						
						return;
					}
				}
			}

			parent::__set($key, $value);
		}
	}

	protected function getObjectField()
	{   
		$class = get_class($this);
 
		if (!isset(static::$staticListField[$class]))
		{
			$now = \Carbon\Carbon::now();
			
			$type = \DB::table('object_type')->where('code', $this->getTable())->first();

			$f = \DB::table('object_field')
					->where('field_object_type', $type->id)
					->where('active', '=', 1)
					->where('start_at', '<=', $now)
					->where('end_at', '>=', $now)
					->get();

			static::$staticListField[$class] = new \Illuminate\Support\Collection(array_combine(array_pluck($f, 'code'), $f));
		}

		return static::$staticListField[$class];
	}

	public function getFieldList()
	{
		$type = $this->type();
		
		return $type->field()->active()->get()->filter(function($item) use ($type)
				{
					return $item->show_in_list == 1 && \Auth::can('read', 'object_field.' . $type->code . '.' . $item->code);
				});
	}

	public function getFieldForm()
	{
		$type = $this->type();
		
		return $type->field()->active()->get()->filter(function($item) use ($type)
				{
					return $item->show_in_form == 1 && \Auth::can('read', 'object_field.' . $type->code . '.' . $item->code);
				});
	}

	public function getMultilanguage()
	{ 
		$class = get_class($this);

		if (!isset(static::$staticListMultilanguage[$class]))
		{
			static::$staticListMultilanguage[$class] = (array)$this->multilanguageList;
			
			$fields = \App::make('telenok.config')->getObjectFieldController();
						
			foreach ($this->getObjectField()->toArray() as $key => $field)
			{
				$fieldController = $fields->get($field->key);

				if ($fieldController)
				{
					static::$staticListMultilanguage[$class] = array_merge(static::$staticListMultilanguage[$class], (array) $fieldController->getMultilanguage($this, $field));
				}
			}
		}
		
		return static::$staticListMultilanguage[$class];
	}
	
	public function addMultilanguage($fieldCode)
	{ 
		$class = get_class($this);
	
		static::$staticListMultilanguage[$class][] = $fieldCode;
		
		static::$staticListMultilanguage[$class] = array_unique(static::$staticListMultilanguage[$class]);
		
		return $this; 
	}
	

	public function isFillable($key)
	{
		if (in_array($key, $this->getFillable()))
		{
			return true;
		}
		else
		{
			return parent::isFillable($key);
		}
	}
	
	public function getDates()
	{
		return array_merge(parent::getDates(), $this->dates);
	}

	public function addDateField($dateField = [])
	{  
		$class = get_class($this); 

		if (!isset(static::$staticListFieldDate[$class]))
		{
			static::$staticListFieldDate[$class] = [];

			$fields = \App::make('telenok.config')->getObjectFieldController();

			foreach ($this->getObjectField()->toArray() as $key => $field)
			{ 
				if ($fieldController = $fields->get($field->key))
				{
					static::$staticListFieldDate[$class] = array_merge(static::$staticListFieldDate[$class], (array) $fieldController->getDateField($this, $field)); 
				}
			} 
		} 
 
		$this->dates = array_merge($this->getDates(), (array)static::$staticListFieldDate[$class], (array)$dateField);

		return $this;
	}

	public function getFillable()
	{ 
		$class = get_class($this); 
		
		if (!isset(static::$staticListFillable[$class]))
		{
			static::$staticListFillable[$class] = [];

			$fields = \App::make('telenok.config')->getObjectFieldController();

			foreach ($this->getObjectField()->toArray() as $key => $field)
			{
				$fieldController = $fields->get($field->key);

				if ($fieldController)
				{
					static::$staticListFillable[$class] = array_merge(static::$staticListFillable[$class], (array) $fieldController->getModelField($this, $field)); 
				}
			} 
			
			static::$staticListFillable[$class] = array_unique(static::$staticListFillable[$class]);
		}

		return static::$staticListFillable[$class];
	}

	public function getRule()
	{
		static $rule = [];

		$class = get_class($this);

		if (!isset($rule[$class]))
		{
			$rule[$class] = [];

			foreach ($this->ruleList as $key => $value)
			{
				foreach ($value as $key_ => $value_)
				{
					$rule[$class][$key][head(explode(':', $value_))] = $value_;
				}
			}

			foreach ($this->type()->field()->active()->get() as $key => $field)
			{ 
				if ($field->rule instanceof \Illuminate\Support\Collection)
				{ 
					foreach ($field->rule->toArray() as $key => $value)
					{
						$rule[$class][$field->code][head(explode(':', $value))] = $value;
					}
				}
			}
		}

		return $rule[$class];
	}

	public function translate($field, $locale = '')
	{
		$locale = $locale ? : \Config::get('app.locale');

		if ($this->$field instanceof \Illuminate\Support\Collection)
		{
			$translated = $this->$field->get($locale);

			return $translated ? : $this->$field->get(\Config::get('app.localeDefault'));
		}
		else if ( ($this->$field instanceof \ArrayAccess && ($v = $this->$field)) || (($v = json_decode($this->$field, true)) && json_last_error()===JSON_ERROR_NONE))
		{
			if ( isset($v[$locale]) )
			{
				return $v[$locale];
			}
			else if (isset($v[\Config::get('app.localeDefault')]))
			{
				return $v[\Config::get('app.localeDefault')];
			}
			else
			{
				return $this->$field;
			}
		}
		else
		{
			return $this->$field;
		}
	}

	public function scopeActive($query, $table = null)
	{
		$table = $table ?: $this->getTable();
		$now = \Carbon\Carbon::now();

		return $query->where(function($query) use ($table, $now)
		{
			$query->where($table . '.active', 1)
				->where($table . '.start_at', '<=', $now)
				->where($table . '.end_at', '>=', $now);
		}); 
	}

	public function scopeNotActive($query, $table = null)
	{
		$table = $table ?: $this->getTable();
		$now = \Carbon\Carbon::now();

		return $query->where(function($query) use ($table, $now)
		{
			$query->where($table . '.active', 0)
				->orWhere($table . '.start_at', '>=', $now)
				->orWhere($table . '.end_at', '<=', $now);
		}); 
	}

	// ->permission() - can current user read (read - by default)
	// ->permission('write', null) - can current user read
	// ->permission(null, 'user_authorized') - can authorized user read 
	// ->permission('read', 'user_authorized', ['object-type', 'own'])
	public function scopeWithPermission($query, $permissionCode = 'read', $subjectCode = null, $filterCode = null)
	{
        if (!\Config::get('app.acl.enabled')) 
        {
			return $query;
        }
		
		if (empty($subjectCode))
		{
			if (\Auth::guest())
			{
				$subject = \Telenok\Security\Resource::where('code', 'user_unauthorized')->active()->first();
			}
			else if (\Auth::check())
			{
				if (\Auth::hasRole('super_administrator'))
				{
					return $query;
				}
				else
				{
					$subject = \Auth::user();
				}
			}
		}
		else
		{
			$subject = \Telenok\Object\Sequence::where('id', $subjectCode)->active()->first();
		}
		
		$permission = \Telenok\Security\Permission::where('id', $permissionCode)->orWhere('code', $permissionCode)->active()->first();

		if (!$subject || !$permission)
		{ 
			return $query->where($this->getTable() . '.id', 'Error: permission code');
		}

		$now = \Carbon\Carbon::now();
		$spr = new \Telenok\Security\SubjectPermissionResource();
		$sequence = new \Telenok\Object\Sequence();
		$type = new \Telenok\Object\Type();
		
		$query->addSelect($this->getTable() . '.*');
		
		$query->join($sequence->getTable() . ' as osequence', function($join) use ($spr, $subject, $permission)
		{
			$join->on($this->getTable() . '.id', '=', 'osequence.id');
		});

		$query->join($type->getTable() . ' as otype', function($join) use ($type, $now)
		{
			$join->on('osequence.sequences_object_type', '=', 'otype.id');
			$join->on('otype.' . $type->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('otype.active', '=', 1);
			$join->where('otype.start_at', '<=', $now);
			$join->where('otype.end_at', '>=', $now);
		});

		$query->where(function($queryWhere) use ($query, $filterCode, $permission, $subject)
		{
			$queryWhere->where(\DB::raw(1), 0);

			$filters = \App::make('telenok.config')->getAclResourceFilter();

			if (!empty($filterCode))
			{
				$filters->only((array) $filterCode);
			}

			$filters->each(function($item) use ($query, $queryWhere, $permission, $subject)
			{
				$item->filter($query, $queryWhere, $this, $permission, $subject);
			});
		});

		return $query;
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* Treeable section */
	
	public function sequenceTreeable($modelOrId = null)
	{
		$modelOrId = $modelOrId !== null ? $modelOrId : $this;

		$sequence = $modelOrId;

		if (!($modelOrId instanceof \Telenok\Core\Model\Object\Sequence))
		{
			if ($modelOrId instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
			{
				$sequence = $modelOrId->sequence;
			}
			else
			{
				$sequence = \Telenok\Object\Sequence::findOrFail($modelOrId);
			}
		} 

		if ($sequence->treeable)
		{
			$el = $sequence->pivotTreeSequenceExtraAttr()->first();

			if (!$el)
			{
				\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->insert(
				[
					'tree_id' => $sequence->getKey(),
					'tree_path' => '.0.',
					'tree_pid' => 0,
					'tree_depth' => 0,
					'tree_order' => (\DB::table('pivot_relation_m2m_tree')->where('tree_pid', 0)->max('tree_order') + 1)
				]);

				$el = $sequence->pivotTreeSequenceExtraAttr()->first();
			}

			return $el;
		}
		else
		{
			throw new \Exception('Model "' . $sequence->class_model . '" is not treeable');
		}
	}

	public function treeParent()
	{
        return $this->belongsToMany('Telenok\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_pid', 'tree_id');
	}

	public function treeChild()
	{
        return $this->belongsToMany('Telenok\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_id', 'tree_pid');
	}

	public function scopePivotTreeLinkedExtraAttr($query)
	{
        return $query->leftJoin('pivot_relation_m2m_tree', $this->getTable() . '.id', '=', 'pivot_relation_m2m_tree.tree_id')
					->addSelect(['pivot_relation_m2m_tree.*', $this->getTable() . '.*']);
	} 

	public function scopePivotTreeSequenceExtraAttr($query)
	{
        return $query->join('pivot_relation_m2m_tree', $this->getTable() . '.id', '=', 'pivot_relation_m2m_tree.tree_id')
					->addSelect(['pivot_relation_m2m_tree.*', $this->getTable() . '.*'])->where($this->getTable() . '.id', $this->getKey());
	} 

	public function makeRoot()
	{
		$sequence = $this->sequenceTreeable(); 

		\DB::transaction(function() use ($sequence)
		{
			$childs = \DB::table('pivot_relation_m2m_tree')->where('tree_path', 'LIKE', '%.' . $sequence->getKey() . '.%')->get();

			foreach ($childs as $item)
			{
				\DB::table('pivot_relation_m2m_tree')->where('id', $item->id)->update(
				[
					'tree_path' => '.0.' . $sequence->getKey() . '.' . preg_replace('/.+\.' . $sequence->getKey() . '\./', '.0.' . $sequence->getKey() . '.', $item->tree_path),
				]);
			}

			if (\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->count())
			{
				\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update(
						[
							'tree_path' => '.0.',
							'tree_pid' => 0,
							'tree_depth' => 0,
							'tree_order' => (\DB::table('pivot_relation_m2m_tree')->where('tree_pid', 0)->max('tree_order') + 1)
				]);
			}
			else
			{
				\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->insert(
						[
							'tree_id' => $sequence->getKey(),
							'tree_path' => '.0.',
							'tree_pid' => 0,
							'tree_depth' => 0,
							'tree_order' => (\DB::table('pivot_relation_m2m_tree')->where('tree_pid', 0)->max('tree_order') + 1)
				]);
			}
		});

		return $sequence;
	}

	public function children($depth = 0)
	{
		$sequence = $this->sequenceTreeable(); 
		
		$query = $this->pivotTreeLinkedExtraAttr();

		if ($depth == 1)
		{
			$query->where('pivot_relation_m2m_tree.tree_pid', '=', $sequence->getKey());
		}
		else
		{
			$query->where('pivot_relation_m2m_tree.tree_path', 'like', $sequence->tree_path . $sequence->getKey() . '.%');
		}

		if ($depth)
		{
			$query->where('pivot_relation_m2m_tree.tree_depth', '<=', $sequence->tree_depth + $depth);
		}

		return $query;
	}

	public function makeLastChildOf($parent)
	{
		$sequence = $this->sequenceTreeable();
		$sequenceParent = $this->sequenceTreeable($parent);

		if ($sequence->isAncestor($sequenceParent))
		{
			throw new \Exception('Cant move Ancestor to Descendant');
		}

		\DB::transaction(function() use ($sequence, $sequenceParent)
		{
			$children = $sequence->children()->get();

			foreach ($children->toArray() as $child)
			{
				\DB::table('pivot_relation_m2m_tree')->where('tree_id', $child->getKey())->update(
				[
					'tree_path' => str_replace($sequence->tree_path, $sequenceParent->tree_path . $sequenceParent->getKey() . '.', $child->tree_path),
					'tree_depth' => ( $sequenceParent->tree_depth + 1 + ($child->tree_depth - $sequence->tree_depth) ),
				]);
			}

			\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update(
			[
				'tree_path' => $sequenceParent->tree_path . $sequenceParent->getKey() . '.',
				'tree_pid' => $sequenceParent->getKey(),
				'tree_order' => ($sequenceParent->children(1)->where('tree_id', '<>', $sequence->getKey())->max('tree_order') + 1),
				'tree_depth' => ($sequenceParent->tree_depth + 1)
			]);
		});

		return $sequence;
	}

	public function makeFirstChildOf($parent)
	{
		$sequence = $this->sequenceTreeable($this);
		$sequenceParent = $this->sequenceTreeable($parent);

		if ($sequence->isAncestor($sequenceParent))
		{
			throw new \Exception('Cant move Ancestor to Descendant');
		}

		\DB::transaction(function() use ($sequence, $sequenceParent)
		{
			$sequenceParent->children(1)->increment('tree_order');

			$children = $sequence->children()->get();

			foreach ($children->toArray() as $child)
			{
				\DB::table('pivot_relation_m2m_tree')->where('tree_id', $child->getKey())->update(
				[
					'tree_path' => str_replace($sequence->tree_path, $sequenceParent->tree_path . $sequenceParent->getKey() . '.', $child->tree_path),
					'tree_depth' => ( $sequenceParent->tree_depth + 1 + ($child->tree_depth - $sequence->tree_depth) ),
				]);
			}

			\DB::table('pivot_relation_m2m_tree')->where('tree_id', $sequence->getKey())->update(
			[
				'tree_path' => $sequenceParent->tree_path . $sequenceParent->getKey() . '.',
				'tree_pid' => $sequenceParent->getKey(),
				'tree_order' => 0,
				'tree_depth' => ($sequenceParent->tree_depth + 1)
			]);
		});

		return $sequence;
	}

	public function isAncestor($descendant)
	{
		$sequence = $this->sequenceTreeable();
		$sequenceDescendant = $this->sequenceTreeable($descendant);

		return strpos($sequenceDescendant->tree_path, $sequence->tree_path . $sequence->getKey() . '.') !== false && $sequenceDescendant->tree_path !== $sequence->tree_path;
	}

    public function isDescendant($ancestor)
    { 
		$sequence = $this->sequenceTreeable();
		$sequenceAncestor = $this->sequenceTreeable($ancestor);

        return strpos($sequence->tree_path, $sequenceAncestor->tree_path . $sequenceAncestor->getKey() . '.') !== false && $sequenceAncestor->tree_path !== $sequence->tree_path;
    }

    protected function processSiblingOf($sibling, $op)
    {  
		$sequence = $this->sequenceTreeable();
		$sequenceSibling = $this->sequenceTreeable($sibling);

        if ($sequence->isAncestor($sequenceSibling)) 
		{
			throw new \Exception('Cant move Ancestor to Descendant');
		}
		
        \DB::transaction(function() use ($sequence, $sequenceSibling, $op)
        { 
            $sequenceSibling->sibling()->where('tree_order', $op, $sequenceSibling->tree_order)->increment('tree_order');

			$children = $sequence->children()->get();

			foreach($children as $child) 
			{
				$child->update([
					'tree_path' => str_replace($sequence->tree_path, $sequenceSibling->getTreePath(), $child->getTreePath()),
					'tree_depth' => ( $sequenceSibling->tree_depth + ($child->getTreeDepth() - $sequence->getTreeDepth()) ),
				]);
			}

            $sequence->fill([
                'tree_path' => $sequenceSibling->tree_path,
                'tree_pid' => $sequenceSibling->tree_pid,
                'tree_order' => $sequenceSibling->tree_order + ($op == '>' ? 1 : 0),
                'tree_depth' => $sequenceSibling->tree_depth,
            ]);

            $sequence->save();
        });

        return $sequence;
    }   

	public function makePreviousSiblingOf($sibling)
	{
		return $this->processSiblingOf($sibling, '>=');
	}

	public function makeNextSiblingOf($sibling)
	{
		return $this->processSiblingOf($sibling, '>');
	}

	public function sibling()
	{
		$sequence = $this->sequenceTreeable();

		return \Telenok\Core\Module\Objects\Sequence::where('tree_pid', '=', $sequence->tree_pid);
	}

	public function parents()
	{
		$sequence = $this->sequenceTreeable();

		return \Telenok\Core\Module\Objects\Sequence::pivotTreeLinkedExtraAttr()->where('id', '=', $sequence->tree_pid);
	}


	
	 

	public function isLeaf()
	{
		$sequence = $this->sequenceTreeable();

		return !$sequence->children(1)->count();
	}

	public function calculateRelativeDepth($object)
	{
		$sequence = $this->sequenceTreeable();
		$sequenceObject = $this->sequenceTreeable($object);

		return abs($sequence->tree_depth - $sequenceObject->tree_depth);
	}

	public static function allRoot()
	{
		$query = \Telenok\Core\Module\Objects\Sequence::pivotTreeLinkedExtraAttr()->where($sequence->tree_pid, '=', 0);

		return $query;
	}

	public static function allDepth($depth = 0)
	{
		$query = \Telenok\Core\Module\Objects\Sequence::pivotTreeLinkedExtraAttr()->whereIn($sequence->tree_depth, (array) $depth);

		return $query;
	}

	public static function allLeaf()
	{
		\Telenok\Core\Module\Objects\Sequence::pivotTreeLinkedExtraAttr()->join('pivot_relation_m2m_tree', function($join)
		{
			$join->on($this->getTable() . '.tree_id', '=', 'pivot_relation_m2m_tree.tree_pid');
		})
		->whereNull($this->getTable() . '.tree_id')
		->select($this->getTable() . '.*');

		return $query;
	}
	/* ~Treeable section */
	
	
	
	

	public function LL($key = '', $param = [])
	{
		return \Lang::get("core::default.$key", $param);
	}

	public function createdByUser()
	{
		return $this->belongsTo('\Telenok\User\User', 'created_by_user');
	}

	public function updatedByUser()
	{
		return $this->belongsTo('\Telenok\User\User', 'updated_by_user');
	}

	public function aclSubject()
	{
		return $this->hasMany('\Telenok\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
	}
}

?>