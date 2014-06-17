<?php

namespace Telenok\Core\Interfaces\Eloquent\Object;

abstract class Model extends \Illuminate\Database\Eloquent\Model {

	use \Illuminate\Database\Eloquent\SoftDeletingTrait;
	
	
	public $incrementing = false;
	public $timestamps = true;
	public $softDelete = true;

	protected $hasVersioning = true;
	protected $ruleList = [];
	protected $multilanguageList = []; 

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
				$sequence = new \Telenok\Core\Model\Object\Sequence();
				$sequence->{$sequence->getKeyName()} = $this->getKey();
				$sequence->class_model = get_class($this);
				$sequence->save();
			}
			else
			{
				$sequence = \Telenok\Core\Model\Object\Sequence::create(['class_model' => get_class($this)]);
			}

			$this->{$this->getKeyName()} = $sequence->getKey();
		}
	}

	protected function deleteSequence()
	{
		if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
		{
			$sequence = \Telenok\Core\Model\Object\Sequence::find($this->getKey());

			if ($this->softDelete)
			{
				$sequence->delete();
			}
			else
			{
				$sequence->forceDelete();
			}
		}
	}

	protected function translateSync()
	{
		if (!($this instanceof \Telenok\Core\Model\Object\Sequence))
		{
			\Telenok\Core\Model\Object\Translation::where('translation_object_model_id', $this->getKey())->forceDelete();

			foreach ($this->getMultilanguage() as $fieldCode)
			{
				$value = $this->$fieldCode->toArray();

				foreach ($value as $language => $string)
				{
					\Telenok\Core\Model\Object\Translation::create([
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
		return $this->hasOne('\Telenok\Core\Model\Object\Sequence', 'id');
	}
	
	public function type()
	{
		return \Telenok\Core\Model\Object\Type::whereCode($this->getTable())->first();
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
		$this->fillable = array_merge($this->fillable, $this->getFillable());

		return parent::fillableFromArray($attributes);
	}

	public function storeOrUpdate($input = [])
	{
		if ($this instanceof \Telenok\Core\Model\Object\Sequence)
		{
			throw new \Exception('Cant storeOrUpdate sequence model directly');
		}
		
		foreach($this->getAttributes() as $key => $attribute)
		{
			$input[$key] = isset($input[$key]) ? $input[$key] : $this->$key;
		} 

		$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array) $input);
 
		try
		{
			$type = $this->type();
		}
		catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e)
		{
			throw new \Exception("Telenok\Core\Interfaces\Eloquent\Object\Model::storeOrUpdate() - Error: 'type of object not found, please, define it'");
		}

		$model = null;
		
		try
		{
			$this->validateTypePermission($type, $input);

			\DB::transaction(function() use ($type, $input, &$model)
			{  
				$exists = $this->exists;
				$classControllerObject = null;

				if ($this->exists || !$input->get($this->getKeyName()))
				{
					$model = $this;

					$exists = $this->exists;
				} 
				else
				{
					$model = $this->findOrFail($input->get($this->getKeyName()));

					$exists = true;
				}

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

	protected function validateTypePermission($type = null, $input = null)
	{
		if (!$type)
		{
			$type = $this->type();
		}

		if (!$this->exists && !\Auth::can('create', "object_type.{$type->code}"))
		{
			throw new \LogicException('Cant create. Access denied.');
		}
		else if ($this->exists && !\Auth::can('update', "object_type.{$type->code}"))
		{
			throw new \LogicException('Cant update. Access denied.');
		}
	}

	public function preProcess($type, $input)
	{
		$config = \App::make('telenok.config')->getObjectFieldController();

		foreach ($type->field()->get() as $field)
		{
			$config->get($field->key)->fill($field, $this, $input);
		}

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
			\Telenok\Core\Model\Object\Version::add($this);
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
				$fieldController = $f_->get($field_->key); 

				if ($fieldController && in_array($key, $fieldController->getModelField($this, $field_)))
				{
					$fieldController->setModelAttribute($this, $key, $value, $field_);

					return;
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
			$type = \DB::table('object_type')->where('code', $this->getTable())->first();

			$f = \DB::table('object_field')->where('field_object_type', $type->id)->get();

			static::$staticListField[$class] = new \Illuminate\Support\Collection(array_combine(array_pluck($f, 'code'), $f));
		}

		return static::$staticListField[$class];
	}

	public function getFieldList()
	{
		return $this->type()->field()->get()->filter(function($item)
				{
					return $item->show_in_list == 1;
				});
	}

	public function getFieldForm()
	{
		return $this->type()->field()->get()->filter(function($item)
				{
					return $item->show_in_form == 1;
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
		$defaults = ['deleted_at'];

		return array_merge(parent::getDates(), $defaults);
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
				$fieldController = $fields->get($field->key);

				if ($fieldController)
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

			foreach ($this->getObjectField()->toArray() as $key => $field)
			{
				$fieldController = \App::make('telenok.config')->getObjectFieldController()->get($field->key);

				foreach ($fieldController->getRule($field) as $key => $value)
				{
					foreach ($value as $key_ => $value_)
					{
						$rule[$class][$key][head(explode(':', $value_))] = $value_;
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
		else
		{
			return $this->$field;
		}
	}

	public function scopeActive($query)
	{
		return $query->where($this->getTable() . '.active', 1);
	}

	public function scopeNotActive($query)
	{
		return $query->where($this->getTable() . '.active', 0);
	}

	// ->permission() - can current user read
	// ->permission('write', null) - can current user read
	// ->permission(null, 'user_authorized') - can current user read (read - default)
	// ->permission('read', 'user_authorized', ['all', 'own'])
	public function scopeWithPermission($query, $permissionCode = 'read', $subjectCode = null, $filterCode = null)
	{
		$subject = null;
		$permission = null;
		$resource = null;

		if (empty($subjectCode))
		{
			if (\Auth::guest())
			{
				$subject = \Telenok\Core\Model\Security\Resource::where('code', 'user_unauthorized')->active()->first();
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
			$subject = \Telenok\Core\Model\Object\Sequence::where('id', $subjectCode)->active()->first();
		}

		$permission = \Telenok\Core\Model\Security\Permission::where('id', $permissionCode)->orWhere('code', $permissionCode)->active()->first();

		if (!$subject instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model || !$permission instanceof \Telenok\Core\Model\Security\Permission)
		{
			return $query->where($this->getTable() . '.id', 'nonexistsvalue');
		}

		$spr = new \Telenok\Core\Model\Security\SubjectPermissionResource();

		$sequence = new \Telenok\Core\Model\Object\Sequence();

		$type = new \Telenok\Core\Model\Object\Type();

		$query->join($sequence->getTable() . ' as osequence', function($join) use ($spr, $subject, $permission)
		{
			$join->on($this->getTable() . '.id', '=', 'osequence.id');
		});

		$query->join($type->getTable() . ' as otype', function($join) use ($type)
		{
			$join->on('osequence.sequences_object_type', '=', 'otype.id');
			$join->on('otype.' . $type->getDeletedAtColumn(), ' is ', \DB::raw('null'));
			$join->on('otype.active', '=', \DB::raw('1'));
		});

		//for direct right on resource
		$query->leftJoin($spr->getTable() . ' as spr_permission_direct', function($join) use ($spr, $subject, $permission)
		{
			$join->on($this->getTable() . '.id', '=', 'spr_permission_direct.acl_resource_object_sequence');
			$join->on('spr_permission_direct.acl_subject_object_sequence', '=', \DB::raw($subject->getKey()));
			$join->on('spr_permission_direct.acl_permission_permission', '=', \DB::raw($permission->getKey()));
			$join->on('spr_permission_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
			$join->on('spr_permission_direct.active', '=', \DB::raw('1'));
		});

		// for logined user's right on resource
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$userGroupRole = \Telenok\Core\Model\User\User::with([
						'group' => function($query)
				{
					$query->whereActive(1);
				},
						'group.role' => function($query)
				{
					$query->whereActive(1);
				}])
					->whereId($subject->getKey())
					->active()
					->get();

			$roles = [0];

			$userGroupRole->each(function($user) use (&$roles)
			{
				$user->group->each(function($group) use (&$roles)
				{
					$group->role->each(function($role) use (&$roles)
					{
						$roles[] = $role->getKey();
					});
				});
			});

			$query->leftJoin($spr->getTable() . ' as spr_permission_user', function($join) use ($spr, $roles, $permission)
			{
				$join->on($this->getTable() . '.id', '=', 'spr_permission_user.acl_resource_object_sequence');
				$join->on('spr_permission_user.acl_subject_object_sequence', ' in ', \DB::raw('(' . implode(',', $roles) . ')'));
				$join->on('spr_permission_user.acl_permission_permission', '=', \DB::raw($permission->getKey()));
				$join->on('spr_permission_user.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
				$join->on('spr_permission_user.active', '=', \DB::raw('1'));
			});
		}

		$query->where(function($query_) use ($query, $filterCode, $permission, $subject)
		{
			$query_->whereNotNull('spr_permission_direct.id');

			if ($subject instanceof \Telenok\Core\Model\User\User)
			{
				$query_->orWhereNotNull('spr_permission_user.id');
			}

			$filters = \App::make('telenok.config')->getAclResourceFilter();

			if (!empty($filterCode))
			{
				$filters->only((array) $filterCode);
			}

			$filters->each(function($item) use ($query, $query_, $permission, $subject)
			{
				$item->filter($query, $query_, $this, $permission, $subject);
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
				$sequence = \Telenok\Core\Model\Object\Sequence::findOrFail($modelOrId);
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
        return $this->belongsToMany('Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_pid', 'tree_id');
	}

	public function treeChild()
	{
        return $this->belongsToMany('Telenok\Core\Model\Object\Sequence', 'pivot_relation_m2m_tree', 'tree_id', 'tree_pid');
	}

	public function scopePivotTreeLinkedExtraAttr($query)
	{
        return $query->newQuery()->join('pivot_relation_m2m_tree', $this->getTable() . '.id', '=', 'pivot_relation_m2m_tree.tree_id')
					->select([$this->getTable() . '.*', 'pivot_relation_m2m_tree.*', $this->getTable() . '.id']);
	} 

	public function scopePivotTreeSequenceExtraAttr($query)
	{
        return $query->join('pivot_relation_m2m_tree', $this->getTable() . '.id', '=', 'pivot_relation_m2m_tree.tree_id')
					->select([$this->getTable() . '.*', 'pivot_relation_m2m_tree.*', $this->getTable() . '.id'])->where($this->getTable() . '.id', $this->getKey());
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
		return $this->belongsTo('\Telenok\Core\Model\User\User', 'created_by_user');
	}

	public function updatedByUser()
	{
		return $this->belongsTo('\Telenok\Core\Model\User\User', 'updated_by_user');
	}

	public function aclSubject()
	{
		return $this->hasMany('\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
	}
}

?>