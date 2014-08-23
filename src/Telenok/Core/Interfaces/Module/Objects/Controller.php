<?php

namespace Telenok\Core\Interfaces\Module\Objects;
  

abstract class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller {
    
    protected $key = '';
    protected $parent = '';
    protected $typeList = '';
    protected $typeTree = '';
    
    protected $presentationTreeView = 'core::presentation.tree-tab-object.tree';
    protected $presentationContentView = 'core::presentation.tree-tab-object.content';
    protected $presentationModelView = 'core::presentation.tree-tab-object.model';
    protected $presentationFormModelView = 'core::presentation.tree-tab-object.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab-object.form-field-list';

    public function setTypeList($key)
    {
        $this->typeList = $key;
        
        return $this;
    }

    public function setTypeTree($key)
    {
        $this->typeTree = $key;
        
        return $this;
    }

    public function getModelList()
    {
        return \App::build(\Telenok\Object\Type::where('code', $this->typeList)->firstOrFail()->class_model);
    }

    public function getModelTree()
    {
        return \App::build(\Telenok\Object\Type::where('code', $this->typeTree)->firstOrFail()->class_model);
    }

    public function getTypeList()
    {
        return \Telenok\Object\Type::where('code', $this->typeList)->firstOrFail();
    } 

    public function getActionParam()
    { 
        try
        {
            return [
                'presentation' => $this->getPresentation(),
                'presentationContent' => $this->getPresentationContent(),
                'key' => $this->getKey(),
                'treeContent' => $this->getTreeContent(),
                'contentUrl' => $this->getRouterContent(['treePid' => $this->getTypeList()->getKey()]),
                'breadcrumbs' => $this->getBreadcrumbs(),
                'pageHeader' => $this->getPageHeader(),
            ];
        }
        catch (\Exception $e)
        {
            return [
                'error' => $e->getMessage(),
            ];
        } 
    }

    public function getContent()
    { 
        try 
        {
            $model = $this->getModelList();
            $type = $this->getTypeList(); 
            $fields = $model->getFieldList();
        } 
        catch (\Exception $e) 
        {  
            return ['message' => 'Empty required'];
        } 
        
        return [
            'tabKey' => "{$this->getTabKey()}-{$model->getTable()}",
            'tabLabel' => $type->translate('title'),
            'tabContent' => \View::make($this->getPresentationContentView(), array(
                'controller' => $this,  
                'model' => $model,
                'type' => $type,
                'fields' => $fields,
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(),
                'uniqueId' => str_random(),
            ))->render()
        ];
    }

    public function getTreeContent()
    {
        if (!$this->typeTree) 
		{
			return;
		}
		
        return \View::make($this->getPresentationTreeView(), array(
                'controller' => $this, 
                'treeChoose' => $this->LL('header.tree.choose'),
                'id' => str_random()
            ))->render();
    } 

    public function getFormContent($model, $type, $fields, $uniqueId)
    {
        return \View::make($this->getPresentationFormModelView(), array_merge(array( 
                'controller' => $this,
                'model' => $model, 
                'type' => $type, 
                'fields' => $fields, 
                'uniqueId' => $uniqueId, 
            ), $this->getAdditionalViewParam()))->render();
    }

    public function getModelFieldFilter()
    {
		$model = $this->getModelList();
        $fields = [];

        $model->getFieldForm()->each(function($field) use (&$fields)
		{
			if ($field->allow_search)
            {
                $fields[] = $field;
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

    public function getList()
    {
        $content = [];

        $total = \Input::get('iDisplayLength', 10);
        $sEcho = \Input::get('sEcho');
        $iDisplayStart = \Input::get('iDisplayStart', 0);

        try
        {
            $model = $this->getModelList();
            $type = $this->getTypeList();

            $items = $this->getListItem($model)->get();

            $config = \App::make('telenok.config')->getObjectFieldController();

			$fields = $model->getFieldList();
			
            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="'.$item->getKey().'" /><span class="lbl"></span></label>'];

                foreach ($fields as $field)
                {
					$put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $type);
                }

                $put['tableManageItem'] = $this->getListButton($item);

                $content[] = $put;
            }
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return [
                'gridId' => $this->getGridId(), 
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => []
            ];
        }

        return [
            'gridId' => $this->getGridId(), 
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    }

    public function getListItem($model)
    {  
        $query = $model::select($model->getTable() . '.*')->withPermission();

        $this->getFilterQuery($model, $query); 

        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip(\Input::get('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function create()
    { 
		$id = (int)\Input::get('id');

		$model = $this->getModelList();
        $type = $this->getTypeList();
        $fields = $model->getFieldForm();

        $params = ['model' => $model, 'type' => $type, 'fields' => $fields];
        
        \Event::fire('form.create.object', [$params]);

        return [
            'tabKey' => $this->getTabKey().'-new-'.str_random(),
            'tabLabel' => $type->translate('title'),
            'tabContent' => \View::make($this->getPresentationModelView(), array_merge([
                'controller' => $this,
                'model' => $params['model'], 
                'type' => $params['type'], 
                'fields' => $params['fields'], 
                'uniqueId' => str_random(), 
				'routerParam' => $this->getRouterParam('create', $type, $model),
				'canCreate' => \Auth::can('create', $params['model']), 
            ], $this->getAdditionalViewParam()))->render()
        ];
    }

    public function edit($id = NULL)
    { 
        $model = $this->getModelList()->findOrFail($id);
        $type = $this->getTypeList();
        $fields = $model->getFieldForm();

        $params = ['model' => $model, 'type' => $type, 'fields' => $fields];

        \Event::fire('form.edit.object', [$params]);
			
        return [
            'tabKey' => $this->getTabKey().'-edit-'.str_random(),
            'tabLabel' => $type->translate('title'),
            'tabContent' => \View::make($this->getPresentationModelView(), array_merge(array( 
				'controller' => $this,
				'model' => $params['model'], 
				'type' => $params['type'], 
				'fields' => $params['fields'], 
				'uniqueId' => str_random(), 
				'routerParam' => $this->getRouterParam('edit', $type, $model),
				'canUpdate' => \Auth::can('update', $params['model']),
				'canDelete' => \Auth::can('delete', $params['model']),
            ), $this->getAdditionalViewParam()))->render()
        ];
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

            $type = $this->getTypeList();

			$model = $this->save($input, $type); 
        } 
        catch (\Exception $e) 
        {
			throw $e;
        }

		$fields = $model->getFieldForm();
		
        $params = ['model' => $model, 'type' => $type, 'fields' => $fields];

        \Event::fire('form.edit.object', [$params]);

        $return = [];

        $return['tabContent'] = \View::make($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $params['model'], 
                    'type' => $params['type'], 
                    'fields' => $params['fields'], 
                    'uniqueId' => str_random(), 
                    'success' => true,
                    'warning' => \Session::get('warning'),
					'routerParam' => $this->getRouterParam('store', $type, $model),
					'canUpdate' => \Auth::can('update', $params['model']),
					'canDelete' => \Auth::can('delete', $params['model']),
                ), $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function update($id = NULL, $input = [])
    {
        try 
        {
			if (empty($input))
			{
				$input = \Input::all();
			}

			$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

            $type = $this->getTypeList();
			
			$model = $this->save($input, $type); 
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        }  
	
		$fields = $model->getFieldForm();
		
        $params = ['model' => $model, 'type' => $type, 'fields' => $fields];

        \Event::fire('form.edit.object', [$params]);

        $return = [];

        $return['tabContent'] = \View::make($this->getPresentationModelView(), array_merge(array(
                    'controller' => $this,
                    'model' => $model,
                    'type' => $type, 
                    'fields' => $params['fields'], 
                    'uniqueId' => str_random(), 
                    'success' => TRUE,
                    'warning' => \Session::get('warning'),
					'routerParam' => $this->getRouterParam('update', $type, $model),
					'canUpdate' => \Auth::can('update', $params['model']),
					'canDelete' => \Auth::can('delete', $params['model']),
                ), $this->getAdditionalViewParam()))->render();

        return $return;
    }
	
    public function save($input = [], $type = null)
    {
		if ($input === null)
		{
			$input = \Input::all();
		}

		$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array) $input);

        $model = $this->getModelList();
        
        if (!($type instanceof \Telenok\Core\Model\Object\Type))
        {
            $type = $this->getTypeList();
        }

		return $model->storeOrUpdate($input, true);
    }

    public function editList($id = null)
    {
        $content = [];

        $ids = (array)\Input::get('tableCheckAll');
        
        if (empty($ids))
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		}
        
        $type = $this->getTypeList();
        $model = $this->getModelList();
        $fields = $type->field()->get();

        foreach ($ids as $id)
        {
            $params = ['model' => $model::find($id), 'type' => $type, 'fields' => $fields];

            \Event::fire('form.edit.object', [$params]);
            
            $content[] = \View::make($this->getPresentationModelView(), array_merge(array( 
                'controller' => $this,
                'model' => $params['model'], 
                'type' => $params['type'], 
                'fields' => $params['fields'], 
				'routerParam' => $this->getRouterParam('edit', $type, $model),
                'uniqueId' => str_random(), 
				'canUpdate' => \Auth::can('update', $params['model']),
				'canDelete' => \Auth::can('delete', $params['model']),
            ), $this->getAdditionalViewParam()))->render();
        }

        return [
            'tabKey' => $this->getTabKey().'-edit-'.str_random(),
            'tabLabel' => $type->translate('title'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

	public function getRouterParam($action = '', $type = null, $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['id' => $type->getKey(), 'files' => true,  'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', false)]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true)]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true)]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $type->getKey(), 'files' => true, 'saveBtn' => \Input::get('saveBtn', true), 'chooseBtn' => \Input::get('chooseBtn', true)]) ];
				break;

			default:
				return [];
				break;
		}
	} 
	
    public function getRouterActionParam($param = [])
    {
		try
		{
			return \URL::route($this->routerActionParam ?: "cmf.module.{$this->getKey()}.action.param", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.action.param", $param);
		}
    } 
    
    public function getRouterList($param = [])
    {
		try
		{
			return \URL::route($this->routerList ?: "cmf.module.{$this->getKey()}.list", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.list", $param);
		}
    }	

    public function getRouterContent($param = [])
    {
		try
		{
			return \URL::route($this->routerContent ?:"cmf.module.{$this->getKey()}", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists", $param);
		}
    }
    
    public function getRouterCreate($param = [])
    {
		try
		{
			return \URL::route($this->routerCreate ?: "cmf.module.{$this->getKey()}.create", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.create", $param);
		} 
    }
	
    public function getRouterEdit($param = [])
    {
		try
		{
			return \URL::route($this->routerEdit ?:"cmf.module.{$this->getKey()}.edit", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.edit", $param);
		} 
    }
    
    public function getRouterDelete($param = [])
    {
		try
		{
			return \URL::route($this->routerDelete ?: "cmf.module.{$this->getKey()}.delete", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.delete", $param);
		} 
    }
    
    public function getRouterStore($param = [])
    {		
		try
		{
			return \URL::route($this->routerStore ?: "cmf.module.{$this->getKey()}.store", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.store", $param);
		} 
    }
    
    public function getRouterUpdate($param = [])
    {
		try
		{
			return \URL::route($this->routerUpdate ?: "cmf.module.{$this->getKey()}.update", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.update", $param);
		} 
    }
	
    public function getRouterListEdit($param = [])
    {
		try
		{
			return \URL::route($this->routerListEdit ?: "cmf.module.{$this->getKey()}.list.edit", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.list.edit", $param);
		} 
    }
	
    public function getRouterListDelete($param = [])
    {
		try
		{
			return \URL::route($this->routerListDelete ?: "cmf.module.{$this->getKey()}.list.delete", $param);
		} 
		catch (\InvalidArgumentException $ex) 
		{
			return \URL::route("cmf.module.objects-lists.list.delete", $param);
		} 
    }


}