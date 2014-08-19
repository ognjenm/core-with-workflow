<?php

namespace Telenok\Core\Module\Objects\Sequence;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller { 

    protected $key = 'objects-sequence';
    protected $parent = 'objects';
    protected $typeList = 'object_sequence';
    protected $presentation = 'tree-tab-object';

    public function getActionParam()
    { 
        return json_encode([]);
    }
    
    public function getListItem($model)
    {
        $sequence = (new \Telenok\Object\Sequence());
        
        $query = $model::select($model->getTable().'.*')
            ->where(function($query) use ($sequence, $model)
            {
                if ($this->getModelList()->treeForming())
                {
                    $query->where($sequence->getTable().'.tree_pid', \Input::get('treePid', 0))->orWhere($sequence->getTable() . '.' . $sequence->getKeyName(), \Input::get('treePid', 0));
                }
            }); 
            
        $query->withPermission();

        $this->getFilterQuery($model, $query); 
        
        return $query->groupBy($model->getTable() . '.id')->orderBy($model->getTable() . '.updated_at', 'desc')->skip(\Input::get('iDisplayStart', 0))->take($this->displayLength + 1);
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

            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = ['tableCheckAll' => '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]" value="'.$item->getKey().'" /><span class="lbl"></span></label>'];

                foreach ($model->getFieldList() as $field)
                { 
		    $put[$field->code] = $config->get($field->key)->getListFieldContent($field, $item, $type); 
                }

                $put['tableManageItem'] = "";

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

    
}