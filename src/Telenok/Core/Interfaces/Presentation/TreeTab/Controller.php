<?php 

namespace Telenok\Core\Interfaces\Presentation\TreeTab;

use \Telenok\Core\Interfaces\Module\Controller as Module;
use \Telenok\Core\Interfaces\Presentation\IPresentation;

abstract class Controller extends Module implements IPresentation {

    protected $tabKey = '';
    protected $presentation = 'tree-tab';
    protected $presentationModuleKey = '';
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
    protected $routerLock = '';
    protected $routerListLock = '';
    protected $routerListUnlock = '';

    protected $modelList = '';
    protected $modelTree = '';

    protected $displayLength = 15;
    protected $additionalViewParam = [];
	
    protected $lockInListPeriod = 3600;
    protected $lockInFormPeriod = 300;

	
	public function getLockInListPeriod()
    {
        return $this->lockInListPeriod;
    }
	
	public function setLockInListPeriod($param = 3600)
    {
        $this->lockInListPeriod = $param;
		
		return $this;
    }
	
	public function getLockInFormPeriod()
    {
        return $this->lockInFormPeriod;
    }
	
	public function setLockInFormPeriod($param = 300)
    {
        $this->lockInFormPeriod = $param;

		return $this;
    }
	
	public function getPresentation()
    {
        return $this->presentation;
    }

    public function setPresentation($key)
    {
        $this->presentation = $key;
        
        return $this;
    }
	
	public function getPresentationModuleKey()
    {
        return $this->presentationModuleKey ?: $this->presentation . '-' . $this->getKey();
    }

    public function setPresentationModuleKey($key)
    {
        $this->presentationModuleKey = $key;
        
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
	
    public function setRouterListLock($param)
    {
		$this->routerListLock = $param;
		
		return $this;
    }       

    public function getRouterLock($param = [])
    {
		return \URL::route($this->routerLock ?: "cmf.module.{$this->getKey()}.lock", $param);
    }

    public function getRouterListLock($param = [])
    {
		return \URL::route($this->routerListLock ?: "cmf.module.{$this->getKey()}.list.lock", $param);
    }
	
    public function setRouterListUnlock($param)
    {
		$this->routerListUnlock = $param;
		
		return $this;
    }       

    public function getRouterListUnlock($param = [])
    {
		return \URL::route($this->routerListUnlock ?: "cmf.module.{$this->getKey()}.list.unlock", $param);
    }

    public function getModelList()
    {
        return app($this->modelList);
    }    
    
    public function getModel($id)
    {
        return \App\Model\Telenok\Object\Sequence::getModel($id);
    }
 
    public function getType($id)
    {
        return \App\Model\Telenok\Object\Type::where('id', $id)->orWhere('code', $id)->firstOrFail();
    } 

    public function getTypeByModel($id)
    {
        return \App\Model\Telenok\Object\Sequence::findOrFail($id)->sequencesObjectType;
    }
    
    public function modelByType($id)
    {
        return app($this->getType($id)->class_model);
    }

    public function getModelTree()
    {
        return app($this->modelTree);
    }    

    public function validator($model = null, array $input = [], array $message = [], array $customAttribute = [])
    {
        return app('\Telenok\Core\Interfaces\Validator\Model')
                    ->setModel($model ?: $this->getModelList())
                    ->setInput($input)
                    ->setMessage($message)
                    ->setCustomAttributes($customAttribute);
    }

    public function validateException()
    {
        return app('\Telenok\Core\Interfaces\Exception\Validate');
    }

    public function validator(\Illuminate\Database\Eloquent\Model $model = null, array $input = [], array $message = [], array $customAttribute = [])
    { 
        return $this;
    }
    
    public function getActionParam()
    { 
        return json_encode([
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'treeContent' => $this->getTreeContent(),
            'url' => $this->getRouterContent(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
            'uniqueId' => str_random(), 
        ]);
    }
    
    public function getPresentationContent()
    {
        return view($this->getPresentationView(), [
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'controller' => $this,
            'uniqueId' => str_random(),
            'iDisplayLength' => $this->displayLength
        ])->render();
    } 

    public function getContent()
    {
        $model = $this->getModelList();

        return [
            'tabKey' => $this->getTabKey(),
            'tabLabel' => $this->LL('list.name'),
            'tabContent' => view($this->getPresentationContentView(), array(
                'controller' => $this, 
                'fields' => $model->getFieldList(),
                'fieldsFilter' => $this->getModelFieldFilter(),
                'gridId' => $this->getGridId(), 
                'uniqueId' => str_random(),
            ))->render()
        ];
    }
    
    public function getTreeContent()
    {
        return view($this->getPresentationTreeView(), [
                'controller' => $this, 
                'treeChoose' => $this->LL('header.tree.choose'),
                'id' => str_random(),
            ])->render();
    }
    
    public function getFilterQueryLike($value, $query, $model, $input)
    {     
        $query->where(function($query) use ($value, $model, $field)
        {
            \Illuminate\Support\Collection::make(explode(' ', $value))
                ->reject(function($i) 
                { 
                    return !trim($i);
                }) 
                ->each(function($i) use ($query, $model, $field)
                {
                    $query->orWhere($model->getTable() . '.' . $field, 'like', '%' . trim($i) . '%');
                });

            $query->orWhere($model->getTable().'.id', intval($value));
        }); 
    }
    
    public function getFilterQuery($model, $query)
    {
        if ($title = trim($this->getRequest()->input('sSearch')))
        {
            $this->getFilterQueryLike($title, $query, $model, 'title');
        } 

		if ($this->getRequest()->input('multifield_search', false))
		{
			$this->getFilterSubQuery($this->getRequest()->input('filter', []), $model, $query);
		}
        
        $orderByField = $this->getRequest()->input('mDataProp_' . $this->getRequest()->input('iSortCol_0'));
        
        if ($this->getRequest()->input('iSortCol_0', 0))
        {
            $query->orderBy($model->getTable() . '.' . $orderByField, $this->getRequest()->input('sSortDir_0'));
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
        $sequence = (new \App\Model\Telenok\Object\Sequence());
        
        $query = $model::select($model->getTable() . '.*')
            ->join($sequence->getTable(), function($join) use ($sequence, $model)
            {
                $join->on($model->getTable() . '.' . $model->getKeyName(), '=', $sequence->getTable() . '.' . $sequence->getKeyName());
            })
            ->where(function($query) use ($sequence, $model)
            {
                if ($this->getModelList()->treeForming())
                {
                    $query->where($sequence->getTable().'.tree_pid', $this->getRequest()->input('treePid', 0))->orWhere($sequence->getTable() . '.' . $sequence->getKeyName(), $this->getRequest()->input('treePid', 0));
                }
            }); 
            
        $query->withPermission();

        $this->getFilterQuery($model, $query); 
        
        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip($this->getRequest()->input('iDisplayStart', 0))->take($this->displayLength + 1);
    }

    public function getListItemProcessed($field, $item)
    {
        return $item->translate($field->code);
    }

    public function getTreeList()
    {
        $tree = [];
        $input = $this->getRequest()->input();

        $id = $input->get('id', -1);
        $searchStr = trim($input->get('search_string'));

        if ($id == -1)
        {
            $tree = [
                'data' => $this->LL('tree.root'),
                'attr' => ['id' => 0, 'rel' => 'folder'],
                'metadata' => ['id' => 0, 'gridId' => $this->getGridId()],
                'state' => 'closed'
            ];
        }
        else
        {
            try
            {
                $list = $this->getTreeListModel($id, $searchStr);

                $parents = $list->lists('id', 'tree_pid');

                $folderId = \App\Model\Telenok\Object\Type::where('code', 'folder')->first()->getKey();

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

    public function getTreeListModel($treePid = 0, $str = '', $input = [])
    {
        $model = $this->getModelTree();
        
        $query = \App\Model\Telenok\Object\Sequence::pivotTreeLinkedExtraAttr()->active();

        $sequences_object_type = [];
        
        $sequences_object_type[] = \App\Model\Telenok\Object\Type::where('code', 'folder')->firstOrFail()->getKey();

        if ($model !== null)
        {
            $sequences_object_type[] = \App\Model\Telenok\Object\Type::where('code', $model->getTable())->first()->getKey();
        }
        
        if ($str)
        {
            $this->getFilterQueryLike($str, $query, $model, $input);
        }
        else
        {
            if ($treePid == 0)
            {
                $query->where('pivot_relation_m2m_tree.tree_depth', '<', 2);
            }
            else
            {
                $query->where('pivot_relation_m2m_tree.tree_pid', $treePid);
            }
            
            $query->whereIn('object_sequence.sequences_object_type', $sequences_object_type);
        }

        $query->where('object_sequence.treeable', 1);
		$query->withPermission('read', null, ['direct-right']);

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
                        onclick="telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').addTabByURL({url : \'' 
                        . $this->getRouterEdit(['id' => $item->getKey()]) . '\'});">
                        <i class="fa fa-pencil"></i>
                    </button>
					
                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>

                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->locked() ? 'locked' : 'unlocked')) . '">
                        <i class="fa fa-' . ($item->locked() ? 'lock ' . (\Auth::user()->id == $item->locked_by_user ? 'green' : 'red') : 'unlock green'). '"></i>
                    </button>

                    <button class="btn btn-minier btn-danger" title="'.$this->LL('list.btn.delete').'" 
                        onclick="if (confirm(\'' . $this->LL('notice.sure') . '\')) telenok.getPresentation(\''.$this->getPresentationModuleKey().'\').deleteByURL(this, \'' 
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
        
        $input = $input ?: \Input::all();
        
        dd($request->input());
        
        $total = $this->getRequest()->input('iDisplayLength', 10);
        $sEcho = $this->getRequest()->input('sEcho');
        $iDisplayStart = $this->getRequest()->input('iDisplayStart', 0);

        $model = $this->getModelList();
        $items = $this->getListItem($model)->get();
        
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

	public function getRouterParam($action = '', $model = null)
	{
		switch ($action)
		{
			case 'create':
				return [ $this->getRouterStore(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', false), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'edit':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'store':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			case 'update':
				return [ $this->getRouterUpdate(['id' => $model->getKey(), 'saveBtn' => $this->getRequest()->input('saveBtn', true), 'chooseBtn' => $this->getRequest()->input('chooseBtn', true), 'chooseSequence' => $this->getRequest()->input('chooseSequence', false)]) ];
				break;

			default:
				return [];
				break;
		}
	} 

    public function create($id = null)
    {  
		$id = $id ?: $this->getRequest()->input('id');
		
        return [
            'tabKey' => $this->getTabKey().'-new-'.str_random(),
            'tabLabel' => $this->LL('list.create'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList(), 
				'routerParam' => $this->getRouterParam('create'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function edit($id = null)
    { 
		$id = $id ?: $this->getRequest()->input('id');
		
        return [
            'tabKey' => $this->getTabKey() . '-edit-' . $id,
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
				'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render()
        ];
    }

    public function editList($id = null)
    {
        $content = [];

        $ids = (array)$this->getRequest()->input('tableCheckAll');

        if (empty($ids)) 
        {
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
        }
        
        foreach ($ids as $id)
        {
            $content[] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge(array( 
                'controller' => $this,
                'model' => $this->getModelList()->find($id), 
				'routerParam' => $this->getRouterParam('edit'),
                'uniqueId' => str_random(),  
            ), $this->getAdditionalViewParam()))->render();
        }

        return [
            'tabKey' => $this->getTabKey() . '-edit-' . implode('', $ids),
            'tabLabel' => $this->LL('list.edit'),
            'tabContent' => implode('<div class="hr hr-double hr-dotted hr18"></div>', $content)
        ];
    }

    public function delete($id = 0, $force = false)
    { 
        try
        {
	        $model = $this->getModelList()->findOrFail($id);
			
			\DB::transaction(function() use ($model, $force)
			{
				if ($force)
				{
					$model->forceDelete();
				}
				else 
				{
					$model->delete();
				}
			});

            return ['success' => 1];
        }
        catch (\Exception $e)
        {
            return ['exception' => 1];
        }
    }

    public function deleteList($id = null, $ids = [])
    {
        $ids = !empty($ids) ? $ids : (array)$this->getRequest()->input('tableCheckAll');

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

    public function lock()
    {
		$id = $this->getRequest()->input('id');

		try
		{
			$model = \App\Model\Telenok\Object\Sequence::find($id)->model;

			if (!$model->locked())
			{
				$model->lock($this->getLockInFormPeriod());
			}
		}
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}

    public function lockList()
    {
		$tableCheckAll = $this->getRequest()->input('tableCheckAll', []);
		
		try
		{
			foreach($tableCheckAll as $id)
			{
				$model = \App\Model\Telenok\Object\Sequence::find($id)->model;
				
				if (!$model->locked())
				{
					$model->lock($this->getLockInListPeriod());
				}
			}
		} 
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}

	public function unlockList()
    {
		$tableCheckAll = $this->getRequest()->input('tableCheckAll', []);
		
		try
		{
			$userId = \Auth::user()->id;
			
			foreach($tableCheckAll as $id)
			{
				$model = \App\Model\Telenok\Object\Sequence::find($id)->model;

				if ($model && $model->locked_by_user == $userId)
				{
					$model->unLock();
				}
			}
		} 
		catch (\Exception $ex) 
		{
            return \Response::json(['message' => 'Expectation Failed'], 417 /* Expectation Failed */);
		} 
		
		return \Response::json(['success' => 1]);
	}


    public function store($id = null)
    {   
        try 
		{
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input()); 

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
		
        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
					'routerParam' => $this->getRouterParam('store'),
                    'uniqueId' => str_random(), 
                    'success' => true,
                    'warning' => \Session::get('warning'),
                ], $this->getAdditionalViewParam()))->render();

        return $return;
    }
    
    public function update()
    { 
        try 
        {
            $input = \Illuminate\Support\Collection::make($this->getRequest()->input());  

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
		
        $return['content'] = view("{$this->getPackage()}::module.{$this->getKey()}.model", array_merge([
                    'controller' => $this,
                    'model' => $model,
					'routerParam' => $this->getRouterParam('update'),
                    'uniqueId' => str_random(),                 
                    'success' => true,
                    'warning' => \Session::get('warning'), 
                ], $this->getAdditionalViewParam()))->render();

        return $return;
    }

    public function save($input = [], $type = null)
    {   
        $input = $input instanceof  \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make((array)$input);
        $model = $this->getModelList();

        $validator = $this->validator($model, $input->all(), $this->LL('error'), ['table' => $model->getTable()]);

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
                $model->sequence->makeLastChildOf(\App\Model\Telenok\System\Folder::findOrFail($input->get('tree_pid'))->sequence);
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