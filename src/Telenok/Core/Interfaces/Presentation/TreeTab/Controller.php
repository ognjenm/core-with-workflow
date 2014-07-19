<?php 

namespace Telenok\Core\Interfaces\Presentation\TreeTab;

use \Telenok\Core\Interfaces\Exception\Validate as ValidateException;

abstract class Controller extends \Telenok\Core\Interfaces\Module\Controller implements \Telenok\Core\Interfaces\Eloquent\Controller {

    protected $tabKey = '';
    protected $presentation = 'tree-tab';
    protected $presentationView = 'core::presentation.tree-tab.presentation';
    protected $presentationTreeView = 'core::presentation.tree-tab.tree';
    protected $presentationContentView = 'core::presentation.tree-tab.content';
    protected $presentationModelView = 'core::presentation.tree-tab.model';
    protected $presentationFormModelView = 'core::presentation.tree-tab.form';
    protected $presentationFormFieldListView = 'core::presentation.tree-tab.form-field-list';

    protected $routerActionParam = '';
    protected $routerList = '';
    protected $routerContent = '';
    protected $routerCreate = '';
    protected $routerEdit = '';
    protected $routerDelete = '';
    protected $routerStore = '';
    protected $routerUpdate = '';
    protected $routerListEdit = '';
    protected $routerListDelete = '';
	
    protected $modelList = '';
    protected $modelTree = '';

    protected $displayLength = 15;
    protected $additionalViewParam = [];

    public function getPresentation()
    {
        return $this->presentation;
    }

    public function setPresentation($key)
    {
        $this->presentation = $key;
        
        return $this;
    }

    public function getPresentationView()
    {
        return $this->presentationView;
    }

    public function setPresentationView($key)
    {
        $this->presentationView = $key;
        
        return $this;
    }

    public function getPresentationTreeView()
    {
        return $this->presentationTreeView;
    }    

    public function setPresentationTreeView($key)
    {
        $this->presentationTreeView = $key;
        
        return $this;
    }    

    public function getPresentationContentView()
    {
        return $this->presentationContentView;
    }

    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;
        
        return $this;
    }

    public function getPresentationModelView()
    {
        return $this->presentationModelView;
    }

    public function setPresentationModelView($key)
    {
       $this->presentationModelView = $key;
        
        return $this;
    }

    public function getPresentationFormFieldListView()
    {
        return $this->presentationFormFieldListView;
    }

    public function setPresentationFormFieldListView($key)
    {
        $this->presentationFormFieldListView = $key;

        return $this;
    }

    public function getPresentationFormModelView()
    {
        return $this->presentationFormModelView;
    }
    
    public function setPresentationFormModelView($key)
    {
        $this->presentationFormModelView = $key;

        return $this;
    }
    
    public function getTabKey()
    {
        return $this->tabKey ?: $this->getKey();
    }

    public function setTabKey($key)
    {
        $this->tabKey = $key;
        
        return $this;
    }
	
    public function setRouterActionParam($param)
    {
		$this->routerActionParam = $param;
		
		return $this;
    } 

    public function getRouterActionParam($param = [])
    {
		return \URL::route($this->routerActionParam ?: "cmf.module.{$this->getKey()}.action.param", $param);
    } 
	
    public function setRouterList($param)
    {
		$this->routerList = $param;
		
		return $this;
    } 

    public function getRouterList($param = [])
    {
        return \URL::route($this->routerList ?: "cmf.module.{$this->getKey()}.list", $param);
    }	
	
    public function setRouterContent($param)
    {
		$this->routerContent = $param;
		
		return $this;
    }  
	
    public function getRouterContent($param = [])
    {
        return \URL::route($this->routerContent ?: "cmf.module.{$this->getKey()}", $param);
    }
	
    public function setRouterCreate($param)
    {
		$this->routerCreate = $param;
		
		return $this;
    }   
    
    public function getRouterCreate($param = [])
    {
        return \URL::route($this->routerCreate ?: "cmf.module.{$this->getKey()}.create", $param);
    }
	
    public function setRouterEdit($param)
    {
		$this->routerEdit = $param;
		
		return $this;
    }       

    public function getRouterEdit($param = [])
    {
        return \URL::route($this->routerEdit ?: "cmf.module.{$this->getKey()}.edit", $param);
    }
	
    public function setRouterDelete($param)
    {
		$this->routerDelete = $param;
		
		return $this;
    }       

    public function getRouterDelete($param = [])
    {
        return \URL::route($this->routerDelete ?: "cmf.module.{$this->getKey()}.delete", $param);
    }
	
    public function setRouterStore($param)
    {
		$this->routerStore = $param;
		
		return $this;
    }       

    public function getRouterStore($param = [])
    {
        return \URL::route($this->routerStore ?: "cmf.module.{$this->getKey()}.store", $param);
    }
	
    public function setRouterUpdate($param)
    {
		$this->routerUpdate = $param;
		
		return $this;
    }       

    public function getRouterUpdate($param = [])
    {
        return \URL::route($this->routerUpdate ?: "cmf.module.{$this->getKey()}.update", $param);
    }
	
    public function setRouterListEdit($param)
    {
		$this->routerListEdit = $param;
		
		return $this;
    }       

    public function getRouterListEdit($param = [])
    {
		return \URL::route($this->routerListEdit ?: "cmf.module.{$this->getKey()}.list.edit", $param);
    }
	
    public function setRouterListDelete($param)
    {
		$this->routerListDelete = $param;
		
		return $this;
    }       

    public function getRouterListDelete($param = [])
    {
		return \URL::route($this->routerListDelete ?: "cmf.module.{$this->getKey()}.list.delete", $param);
    }

    public function getModelList()
    {
        return \App::build($this->modelList);
    }    
    
    public function getModel($id)
    {
        return \Telenok\Object\Sequence::getModel($id);
    }
 
    public function getType($id)
    {
        return \Telenok\Object\Type::where('id', $id)->orWhere('code', $id)->firstOrFail();
    } 

    public function getTypeByModel($id)
    {
        return \Telenok\Object\Sequence::findOrFail($id)->sequencesObjectType;
    }
    
    public function modelByType($id)
    {
        return \App::build($this->getType($id)->class_model);
    }

    public function getModelTree()
    {
        return \App::build($this->modelTree);
    }    

    public function validator($model = null, $input = null, $message = [], $customAttribute = [])
    {
        return new \Telenok\Core\Interfaces\Validator\Model($model ?: $this->getModelList(), $input, $message, $customAttribute);
    }

    public function validateException()
    {
        return new ValidateException();
    }

    public function validate($model = null, $input = null, $message = [])
    { 
        return $this;
    }
    
    public function getActionParam()
    { 
        return json_encode(array(
            'presentation' => $this->getPresentation(),
            'presentationContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'treeContent' => $this->getTreeContent(),
            'contentUrl' => $this->getRouterContent(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
            'uniqueId' => uniqid(), 
        ));
    }
    
    public function getPresentationContent()
    {
        return \View::make($this->getPresentationView(), array(
            'presentation' => $this->getPresentation(),
            'controller' => $this,
            'uniqueId' => uniqid(),
            'iDisplayLength' => $this->displayLength
        ))->render();
    } 

    public function getContent()
    {
        $model = $this->getModelList();

        return array(
            'tabKey' => $this->getTabKey(),
            'tabLabel' => $this->LL('list.name'),
            'tabContent' => \View::make($this->getPresentationContentView(), array(
                'controller' => $this, 
                'fields' => $model->getFieldList(),
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(), 
                'uniqueId' => uniqid(),
            ))->render()
        );
    }
    
    public function getTreeContent()
    {
        return \View::make($this->getPresentationTreeView(), array(
                'controller' => $this, 
                'treeChoose' => $this->LL('header.tree.choose'),
                'id' => uniqid(),
            ))->render();
    }
    
    public function getFilterQuery($model, $query)
    {
        $translate = new \Telenok\Object\Translation();
        
        if ($title = trim(\Input::get('sSearch')))
        {
			$query->where(function($query) use ($title, $model)
			{
				\Illuminate\Support\Collection::make(explode(' ', $title))
						->reject(function($i) { return !trim($i); })
						->each(function($i) use ($query, $model)
				{
					$query->where($model->getTable().'.title', 'like', '%'.trim($i).'%');
				});
			});
			
            $query->leftJoin($translate->getTable(), function($join) use ($model, $translate)
            {
                $join   ->on($model->getTable().'.id', '=', $translate->getTable().'.translation_object_model_id')
                        ->on($translate->getTable().'.translation_object_field_code', '=', \DB::raw("'title'"))
                        ->on($translate->getTable().'.translation_object_language', '=', \DB::raw("'".\Config::get('app.locale')."'"));
            });
        } 

		if (\Input::get('filter_want_search', false))
		{
			$this->getFilterSubQuery(\Input::get('filter', []), $model, $query);
		}
        
        $orderByField = \Input::get('mDataProp_' . \Input::get('iSortCol_0'));
        
        if (\Input::get('iSortCol_0', 0))
        {
            if (in_array($orderByField, $model->getMultilanguage()))
            { 
                $query->leftJoin($translate->getTable(), function($join) use ($model, $translate, $orderByField)
                {
                    $join   ->on($model->getTable().'.id', '=', $translate->getTable().'.translation_object_model_id')
                            ->on($translate->getTable().'.translation_object_field_code', '=', \DB::raw("'{$orderByField}'"))
                            ->on($translate->getTable().'.translation_object_language', '=', \DB::raw("'".\Config::get('app.locale')."'"));
                });

                $query->orderBy($translate->getTable().'.translation_object_string', \Input::get('sSortDir_0'));
            }
            else
            {
                $query->orderBy($model->getTable().'.'.$orderByField, \Input::get('sSortDir_0'));
            }
        }
    }

    public function getFilterSubQuery($input, $model, $query)
    {
        foreach ($input as $name => $value)
        {
			$query->where(function($query) use ($value, $name, $model)
			{
				\Illuminate\Support\Collection::make(explode(' ', $value))
						->reject(function($i) { return !trim($i); })
						->each(function($i) use ($query, $name, $model)
				{
					$query->where($model->getTable().'.'.$name, 'like', '%'.trim($i).'%');
				});
			});

        } 
    }

    public function getListItem($model)
    {
        $sequence = (new \Telenok\Object\Sequence());
        
        $query = $model::select($model->getTable().'.*')
            ->join($sequence->getTable(), function($join) use ($sequence, $model)
            {
                $join->on($model->getTable() . '.' . $model->getKeyName(), '=', $sequence->getTable() . '.' . $sequence->getKeyName());
            })
            ->where(function($query) use ($sequence, $model)
            {
                if ($this->getModelList()->treeForming())
                {
                    $query->where($sequence->getTable().'.tree_pid', \Input::get('treePid', 0))->orWhere($sequence->getTable() . '.' . $sequence->getKeyName(), \Input::get('treePid', 0));
                }
            }); 
            
        $query->withPermission();

        $this->getFilterQuery($model, $query); 
        
        $query->orderBy('updated_at', 'desc')->skip(\Input::get('iDisplayStart', 0))->take($this->displayLength + 1);

        return $query->get();
    }

    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    public function getTreeList()
    {
        $tree = [];

        $id = \Input::get('id', -1);

        if ($id == -1)
        {
            $tree = [
                'data' => $this->LL('tree.root'),
                'attr' => ['id' => 0, 'rel' => 'folder'],
                "metadata" => ['id' => 0, 'gridId' => $this->getGridId()],
                'state' => 'closed'
            ];
        }
        else
        {
            try
            {
                $list = $this->getTreeListModel($id);

                $parents = $list->lists('id', 'tree_pid');

                $folderId = \Telenok\Object\Type::where('code', 'folder')->first()->getKey();

                foreach ($list as $key => $item)
                {
                    if ($item->getAttribute('tree_pid') == $id)
                    {
                        $tree[] = [
                            "data" => $item->translate('title'), 
                            'attr' => ['id' => $item->getKey(), 'rel' => ($folderId == $item->sequences_object_type ? 'folder' : '')],
                            "state" => (isset($parents[$item->getKey()]) ? 'closed' : ''),
                            "metadata" => array_merge( ['id' => $item->getKey(), 'gridId' => $this->getGridId() ], $this->getTreeListItemProcessed($item)),
                        ];
                    }
                }
            }
            catch (\Exception $e) { return $e->getMessage(); }
        }

        return $tree;
    } 

    public function getTreeListModel($treePid = 0)
    {
        $model = $this->getModelTree();
        
        $query = \Telenok\Object\Sequence::pivotTreeLinkedExtraAttr()->active();

        $sequences_object_type = [\Telenok\Object\Type::where('code', 'folder')->firstOrFail()->getKey()];

        if ($model !== null)
        {
            $sequences_object_type[] = \Telenok\Object\Type::where('code', $model->getTable())->first()->getKey();
        }
        
        if ($treePid==0)
        {
            $query->where('pivot_relation_m2m_tree.tree_depth', '<', 2);
        }
        else
        {
            $query->where('pivot_relation_m2m_tree.tree_pid', $treePid);
        } 
        
        $query->where('object_sequence.treeable', 1);
        $query->whereIn('object_sequence.sequences_object_type', $sequences_object_type);
		$query->withPermission();

        return $query->get();
    } 

    public function getTreeListItemProcessed($item)
    {
        return [];
    } 

    public function getListButton($item)
    {
        return '
                <div class="hidden-phone visible-lg btn-group">
                    <button class="btn btn-minier btn-info disable" title="'.$this->LL('list.btn.edit').'" 
                        onclick="telenok.getPresentationByKey(\''.$this->getPresentation().'\').addTabByURL({contentUrl : \'' 
                        . $this->getRouterEdit(['id' => $item->getKey()]) . '\'});">
                        <i class="fa fa-pencil"></i>
                    </button>
					
                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>

                    <button class="btn btn-minier btn-danger" title="'.$this->LL('list.btn.delete').'" 
                        onclick="if (confirm(\'' . $this->LL('notice.sure') . '\')) telenok.getPresentationByKey(\''.$this->getPresentation().'\').deleteByURL(this, \'' 
                        . $this->getRouterDelete(['id' => $item->getKey()]) . '\');">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>';
    }

    public function getAdditionalViewParam()
    {
        return $this->additionalViewParam;
    }    

    public function setAdditionalViewParam($param = [])
    {
		$this->additionalViewParam = $param;
		
		return $this;
    }    

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$key}";
    }
    
    public function getModelFieldFilter()
    {
        return [];
    }

    public function getList()
    {
        $content = [];
        
        $total = \Input::get('iDisplayLength', 10);
        $sEcho = \Input::get('sEcho');
        $iDisplayStart = \Input::get('iDisplayStart', 0);

        $model = $this->getModelList();
        $items = $this->getListItem($model);
        
        foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
        {
            $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="'.$item->getKey().'" /><span class="lbl"></span></label>'];

            foreach ($model->getFieldList() as $field)
            { 
                $put[$field->code] = $this->getListItemProcessed($field, $item);
            }
            
            $put['tableManageItem'] = $this->getListButton($item);

            $content[] = $put;
        }

        return [
            'gridId' => $this->getGridId(),
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    } 
    
    public function create($id = null)
    {  
        return [
            'tabKey' => $this->getTabKey().'-new-'.uniqid(),
            'tabLabel' => $this->LL('list.create'),
            'tabContent' => \View::make("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList(), 
                'route' => $this->getRouterStore(),
                'uniqueId' => uniqid(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function edit($id = null)
    { 
        return [
            'tabKey' => $this->getTabKey().'-edit-'.$id,
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => \View::make("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
                'route' => $this->getRouterUpdate(),
                'uniqueId' => uniqid(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function editList($id = null)
    {
        $content = [];

        $ids = (array)\Input::get('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        
        foreach ($ids as $id)
        {
            $content[] = \View::make("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
                'route' => $this->getRouterUpdate(),
                'uniqueId' => uniqid(),  
            ), $this->getAdditionalViewParam()))->render();
        }

        return [
            'tabKey' => $this->getTabKey().'-edit-'.uniqid(),
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function delete($id, $force = false)
    {
        $model = $this->getModelList();
        
        try
        {
            if ($force)
            {
                $model::findOrFail($id)->forceDelete();
            }
            else 
            {
                $model::findOrFail($id)->delete();
            }

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            return ['error' => 1];
        }
    }

    public function deleteList($id = null, $ids = [])
    {
        $ids = !empty($ids) ? $ids : (array)\Input::get('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
         
		$error = false;
		
		\DB::transaction(function() use ($ids, &$error)
		{ 
			try
			{
				$model = $this->getModelList();
				
				foreach ($ids as $id_)
				{
						$model::findOrFail($id_)->delete();
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

			$model = null;


            \DB::transaction(function() use (&$model, $input)
            { 
                $model = $this->save($input); 
            });
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        } 

        $return = [];
		
        $return['content'] = \View::make("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
                    'route' => $this->getRouterStore(),
                    'uniqueId' => uniqid(), 
                    'success' => true,
                    'warning' => \Session::get('warning'),
                ], $this->getAdditionalViewParam()))->render();

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

			$input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

            $model = null;

            \DB::transaction(function() use (&$model, $input)
            { 
                $model = $this->save($input); 
            });
        } 
        catch (\Exception $e) 
        {   
			throw $e;
        }
 
        $return = []; 
		
        $return['content'] = \View::make("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
                    'route' => $this->getRouterUpdate(),
                    'uniqueId' => uniqid(),                 
                    'success' => true,
                    'warning' => \Session::get('warning'), 
                ], $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function save($input = [], $type = null)
    {   
        $input = $input instanceof  \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);
        $model = $this->getModelList();

        $validator = $this->validator($model, $input, $this->LL('error'), ['table' => $model->getTable()]);

        if ($validator->fails()) 
        {
            throw $this->validateException()->setMessageError($validator->messages());
        } 
        
        $this->preProcess($model, $type, $input);

        $this->validate($model, $input);

        if ($model->exists && $model->getKey() == $input->get('id'))
        {
            $model->update($input->all()); 
        }
        else
        {
            $model->fill($input->all())->save();  
        } 
        
        if ($input->get('tree_pid') && $model->treeForming())
        {
            try
            {
                $model->sequence->makeLastChildOf(\Telenok\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
            }
            catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
            { 
                $model->sequence->makeRoot();  
            } 
        }
        
        $this->postProcess($model, $type, $input);

        return $model;
    }
    
    public function preProcess($model, $type, $input)
    { 
        return $this;
    }

    public function postProcess($model, $type, $input)
    {  
        return $this;
    }

    

}

?>