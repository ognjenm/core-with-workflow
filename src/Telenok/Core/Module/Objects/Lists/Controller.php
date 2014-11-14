<?php

namespace Telenok\Core\Module\Objects\Lists;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller {

    protected $key = 'objects-lists';
    protected $parent = 'objects';
    protected $modelTree = '\Telenok\Object\Type';

    protected $presentation = 'tree-tab-object';
    protected $presentationContentView = 'core::module.objects-lists.content';
    protected $presentationModelView = 'core::module.objects-lists.model';
    protected $presentationTreeView = 'core::module.objects-lists.tree';
	
    protected $presentationFormModelView = 'core::presentation.tree-tab-object.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab-object.form-field-list';

	
	
    public function setPresentationModelView($view = '')
	{
		$this->presentationModelView = $view;

		return $this;
	}

	public function getPresentationModelView()
	{
		return $this->presentationModelView;
	}

	public function typeForm($type)
    {
        return \App::build($type->classController())
					->setTabKey($this->key)
					->setAdditionalViewParam($this->getAdditionalViewParam());
    }    

    public function getTreeListItemProcessed($item)
    {
        $typeObjectId = \Telenok\Object\Type::where('code', 'object_type')->pluck('id');

        $code = '';
		$module = null;
		
        if ($item->sequences_object_type == $typeObjectId)
        {
            $code = $item->model->code;
			
			if ($item->model->class_controller)
			{
				$module = \App::build($item->model->class_controller);
			}
        }

        return [
					'gridId' => $this->getGridId($code),
					'typeId' => $item->sequences_object_type, 
					'module' => ($module ? 1 : 0),
					'moduleKey' => ($module ? $module->getKey() : ""),
					'moduleRouterActionParam' => ($module ? $module->getRouterActionParam() : ""),
				];
    }

    public function getTreeContent()
    {
        return \View::make($this->getPresentationTreeView(), array(
                'controller' => $this, 
                'treeChoose' => $this->LL('header.tree.choose'),
                'id' => str_random()
            ))->render();
    }

    public function getContent()
    {  
        try 
        {
            $model = $this->modelByType(\Input::get('treePid', 0));
            $type = $this->getType(\Input::get('treePid', 0)); 

			if ($type->classController())
			{
				return $this->typeForm($type)->getContent();
			} 
            
            $fields = $model->getFieldList(); 
        }
        catch (\LogicException $e) 
        {
            return ['message' => $e->getMessage()];
        }
        catch (\Exception $e) 
        {
            return ['message' => 'Empty required'];
        } 
        
        return [
            'tabKey' => "{$this->getTabKey()}-{$model->getTable()}",
            'tabLabel' => $type->translate('title'),
            'tabContent' => \View::make($this->getPresentationContentView(), [
                'controller' => $this,  
                'model' => $model,
                'type' => $type,
                'fields' => $fields,
                'fieldsFilter' => $this->getModelFieldFilterExtended($model, $type),
                'gridId' => $this->getGridId($model->getTable()),
                'uniqueId' => str_random(),
            ])->render()
        ];
    }

    public function getFormContent($model, $type, $fields, $uniqueId)
    {
        return \View::make($this->getPresentationFormModelView(), array_merge([
					'controller' => $this,
					'model' => $model, 
					'type' => $type,
					'fields' => $fields,
					'uniqueId' => $uniqueId,
				], $this->getAdditionalViewParam()))->render();
	}

	public function getModelFieldFilterExtended($model, $type)
	{
		$fields = [];

		$model->getFieldForm()->each(function($item) use (&$fields)
		{
			if ($item->allow_search)
			{
				$fields[] = $item;
			}
		});

		return $fields;
	}

	public function getFilterSubQuery($input, $model, $query)
	{
		$fieldConfig = \App::make('telenok.config')->getObjectFieldController();

		if (!$input instanceof \Illuminate\Support\Collection)
		{
			$input = \Illuminate\Support\Collection::make($input);
		}

		$model->getFieldForm()->each(function($field) use ($input, $query, $fieldConfig, $model)
		{
			if ($field->allow_search)
			{
				if ($input->has($field->code))
				{
					$fieldConfig->get($field->key)->getFilterQuery($field, $model, $query, $field->code, $input->get($field->code));
				}
				else
				{
                    $fieldConfig->get($field->key)->getFilterQuery($field, $model, $query, $field->code, null);
				}
			}
        });
    }

    public function getListItem($model)
    {  
        $query = $model::select($model->getTable() . '.*')->withPermission();

        $this->getFilterQuery($model, $query); 

        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip(\Input::get('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function getListJson()
    {
        $content = [];
        
        $fields = \Input::get('fields', ['id', 'title']);
        $type = $this->getType(\Input::get('treePid', 0));
        $model = $this->modelByType(\Input::get('treePid', 0));
        
        $items = $this->getListItem($model)->get();

        $config = \App::make('telenok.config')->getObjectFieldController();

        $fieldsIterate = $type->field()->active()->get()->filter(function($item) use ($fields)
				{
					return in_array($item->code, $fields);
				});
        
        foreach ($items as $item)
        {
            foreach ($fieldsIterate as $field)
            { 
                $put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $type);
            }

            $content[] = $put;
        }
        
        return json_encode($content);
    }
    
    public function getList()
    {
        $content = [];

        $iDisplayStart = intval(\Input::get('iDisplayStart', 10));
        $sEcho = \Input::get('sEcho');

        try
        {
            $type = $this->getType(\Input::get('treePid', 0));
            $model = $this->modelByType(\Input::get('treePid', 0)); 
			
			if ($type->classController())
			{
				return $this->typeForm($type)->getList();
			}

            $items = $this->getListItem($model)->get();

			$config = \App::make('telenok.config')->getObjectFieldController();

            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="'.$item->getKey().'" /><span class="lbl"></span></label>'];

                foreach ($model->getFieldList() as $field)
                { 
					$put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $type);
                }

				$canDelete = \Auth::can('delete', $item);

                $put['tableManageItem'] = $this->getListButtonExtended($item, $type, $canDelete);

                $content[] = $put;
            }
        }
        catch (\Exception $e) 
        {
			return [
                'gridId' => $this->getGridId(), 
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => [],
                'exception' => $e->getMessage(),
            ];
        } 

        return [
            'gridId' => $this->getGridId($model->getTable()), 
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    }

    public function getListButtonExtended($item, $type, $canDelete)
    {
        return '<div class="hidden-phone visible-lg btn-group">
                    <button class="btn btn-minier btn-info" title="'.$this->LL('list.btn.edit').'" 
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                        . $this->getRouterEdit(['id' => $item->getKey()]) . '\'});return false;">
                        <i class="fa fa-pencil"></i>
                    </button>

                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>

                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->locked() ? 'locked' : 'unlocked')) . '">
                        <i class="fa fa-' . ($item->locked() ? 'lock ' . (\Auth::user()->id == $item->locked_by_user ? 'green' : 'red') : 'unlock green'). '"></i>
                    </button>

                    ' . ($canDelete ? '
                    <button class="btn btn-minier btn-danger" title="'.$this->LL('list.btn.delete').'" 
                        onclick="if (confirm(\'' . $this->LL('notice.sure') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \'' 
                        . $this->getRouterDelete(['id' => $item->getKey()]) . '\');return false;">
                        <i class="fa fa-trash-o"></i>
                    </button>' : '') . '
                </div>';
    } 

    public function create($id = null)
    {   
		$id = $id ?: \Input::get('id');
		
        $model = $this->modelByType($id);
        $type = $this->getType($id);
        $fields = $model->getFieldForm();

        if (!\Auth::can('create', "object_type.{$type->code}"))
        {
            throw new \LogicException($this->LL('error.access'));
        } 

        if ($type->classController())
        {
            return $this->typeForm($type)->create();
        }

        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);
        $eventInput = \Illuminate\Support\Collection::make([]);

        \Event::fire('workflow.form.create', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($eventInput));

		try
		{
			return [
				'tabKey' => $this->getTabKey() . '-new-' . str_random(),
				'tabLabel' => $type->translate('title'),
				'tabContent' => \View::make($this->getPresentationModelView(), array_merge(array( 
					'controller' => $this,
					'model' => $eventResource->get('model'), 
					'type' => $eventResource->get('type'), 
					'fields' => $eventResource->get('fields'), 
					'uniqueId' => str_random(), 
					'routerParam' => $this->getRouterParam('create', $eventResource->get('type'), $eventResource->get('model')),
					'canCreate' => \Auth::can('create', "object_type.{$eventResource->get('type')->code}"),
				), $this->getAdditionalViewParam()))->render()
			];
		} 
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
    }

    public function edit($id = null)
    {  
		$id = $id ?: \Input::get('id');
		
        $model = $this->getModel($id);
        $type = $this->getTypeByModel($id);
        $fields = $model->getFieldForm();

        if (!\Auth::can('read', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        if ($type->classController())
        {
            return $this->typeForm($type)->edit($id);
        } 

        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);
        $eventInput = \Illuminate\Support\Collection::make([]);

        \Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($eventInput));

        $model->lock();

		try
		{
			return [
				'tabKey' => $this->getTabKey() . '-edit-' . $id,
				'tabLabel' => $type->translate('title'),
				'tabContent' => \View::make($this->getPresentationModelView(), array_merge(array( 
					'controller' => $this,
					'model' => $eventResource->get('model'), 
					'type' => $eventResource->get('type'), 
					'fields' => $eventResource->get('fields'), 
					'uniqueId' => str_random(), 
					'routerParam' => $this->getRouterParam('edit', $eventResource->get('type'), $eventResource->get('model')),
					'canUpdate' => \Auth::can('update', $eventResource->get('model')),
					'canDelete' => \Auth::can('delete', $eventResource->get('model')),
				), $this->getAdditionalViewParam()))->render()
			];
		} 
		catch (\Exception $ex) 
		{
			return [
				'exception' => $ex->getMessage(),
			];
		}
    }

    public function delete($id = 0, $force = false)
    { 
        $model = $this->getModel($id);
        $type = $this->getTypeByModel($id);

        if (!\Auth::can('delete', $id))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        try
        {
			\DB::transaction(function() use ($model, $type, $force)
			{
				\Event::fire('workflow.delete.before', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}"));

				if ($force)
				{
					$model->forceDelete();
				}
				else 
				{
					$model->delete();
				}

				\Event::fire('workflow.delete.after', (new \Telenok\Core\Workflow\Event())->setResourceCode("object_type.{$type->code}")->setResource($model));
			});

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            throw new \LogicException($this->LL('error.access'));
        }
    }

    public function editList($id = null)
    { 
        $ids = (array)\Input::get('tableCheckAll');
        
        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        
        $content = [];
        
        $model = $this->modelByType($id);
        $type = $this->getType($id);
        $fields = $model->getFieldForm();

        foreach ($ids as $id_)
        {
			if (!\Auth::can('read', $id_))
			{
				continue;
			}
			
            if ($type->classController())
            {
                $content[] = with(new \Illuminate\Support\Collection($this->typeForm($type)->edit($id_)))->get('tabContent');
            }
            else
            {
                $eventResource = \Illuminate\Support\Collection::make(['model' => $model::find($id_), 'type' => $type, 'fields' => $fields]);
                $eventInput = \Illuminate\Support\Collection::make([]);

                \Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($eventInput));
                
                $content[] = \View::make($this->getPresentationModelView(), array_merge(array( 
                    'controller' => $this,
                    'model' => $eventResource->get('model'), 
                    'type' => $eventResource->get('type'), 
                    'fields' => $eventResource->get('fields'), 
					'routerParam' => $this->getRouterParam('edit', $eventResource->get('type'), $eventResource->get('model')),
                    'uniqueId' => str_random(), 
					'canUpdate' => \Auth::can('update', $eventResource->get('model')),
					'canDelete' => \Auth::can('delete', $eventResource->get('model')),
                ), $this->getAdditionalViewParam()))->render();
            }
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $type->translate('title'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function deleteList($id = null, $ids = [])
    {
        $ids = empty($ids) ? (array)\Input::get('tableCheckAll') : $ids;

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }

        $type = $this->getTypeByModel($id);

        if (!\Auth::can('delete', "object_type.{$type->code}"))
        {
            throw new \LogicException($this->LL('error.access'));
        }

        $error = false;

		\DB::transaction(function() use ($ids, &$error)
		{ 
			try
			{
				$model = $this->getModelList();

				foreach ($ids as $id_)
				{
			        if (!\Auth::can('delete', $id_))
					{
						$model::findOrFail($id_)->delete();
					}
				}
			}
			catch (\Exception $e)
			{
			   $error = true;
			}
		});

        if ($error)
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        else
        {
            return \Response::json(['success' => 1]);
        }
    }

    public function store($id = null, $input = [])
    {
        try 
        {
			if (empty($input))
			{
				$input = \Input::all();
			}

			$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

			$type = $this->getType($id);

			if ($type->classController())
			{
				return $this->typeForm($type)->store($id, $input);
			}

			$model = $this->save($input, $type); 
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        } 
        
		$fields = $model->getFieldForm();

        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);
        $eventInput = \Illuminate\Support\Collection::make([]);

        \Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($eventInput));

        $return = [];
        
        $return['tabContent'] = \View::make($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $eventResource->get('model'), 
                    'type' => $eventResource->get('type'), 
                    'fields' => $eventResource->get('fields'), 
                    'uniqueId' => str_random(), 
                    'success' => true,
                    'warning' => \Session::get('warning'),
					'routerParam' => $this->getRouterParam('store', $eventResource->get('type'), $eventResource->get('model')),
					'canUpdate' => \Auth::can('update', $eventResource->get('model')),
					'canDelete' => \Auth::can('delete', $eventResource->get('model')),
               ), $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function update($id = null, $input = [])
    {
        try 
        {
			if (empty($input))
			{
				$input = \Input::all();
			}

            $type = $this->getType($id);            
			$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

			if ($type->classController())
			{
				return $this->typeForm($type)->update($id, $input);
			}

			$model = $this->save($input, $type); 
        }
        catch (\Exception $e) 
        {   
			throw $e;
        } 
        
		$fields = $model->getFieldForm();

        $eventResource = \Illuminate\Support\Collection::make(['model' => $model, 'type' => $type, 'fields' => $fields]);
        $eventInput = \Illuminate\Support\Collection::make([]);

        \Event::fire('workflow.form.edit', (new \Telenok\Core\Workflow\Event())->setResource($eventResource)->setInput($eventInput));
        
        $return = [];
        
        $return['tabContent'] = \View::make($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $eventResource->get('model'), 
                    'type' => $eventResource->get('type'), 
                    'fields' => $eventResource->get('fields'), 
                    'uniqueId' => str_random(),
                    'success' => true,
                    'warning' => \Session::get('warning'),
					'routerParam' => $this->getRouterParam('update', $eventResource->get('type'), $eventResource->get('model')),
					'canUpdate' => \Auth::can('update', $eventResource->get('model')),
					'canDelete' => \Auth::can('delete', $eventResource->get('model')),
                ), $this->getAdditionalViewParam()))->render();

        return $return;
    }

	public function getRouterParam($action = '', $type = null, $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['id' => $type->getKey(), 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', false), 'chooseSequence' => \Input::get('chooseSequence', false)]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true), 'chooseSequence' => \Input::get('chooseSequence', false)]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true), 'chooseSequence' => \Input::get('chooseSequence', false)]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true), 'chooseSequence' => \Input::get('chooseSequence', false)]) ];
				break;

			default:
				return [];
				break;
		}
	} 

	public function save($input = [], $type = null)
	{
		if ($input === null)
		{
			$input = \Input::all();
		}

        $input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array) $input);

        if (!($type instanceof \Telenok\Core\Model\Object\Type))
        {
            try
            {
                $type = $this->getType($type);
            }
            catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
            {
                try
                {
                    $type = \Telenok\Object\Sequence::findOrFail($input->get('id'))->sequencesObjectType()->firstOrFail();
                }
                catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e)
                {
                    throw new \Exception("Telenok\Core\Module\Objects\Lists\Controller::save() - Error: 'type of object not found, please, define it'");
                }
            }
        }

        $model = $this->modelByType($type->getKey());

        $this->preProcess($model, $type, $input);
		
        $this->validate($model, $input); 

		$model_ = $model->storeOrUpdate($input, true);
		
        $this->postProcess($model_, $type, $input);
		
		return $model_;
	} 
}