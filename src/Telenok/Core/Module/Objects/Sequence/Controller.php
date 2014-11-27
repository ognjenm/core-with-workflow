<?php

namespace Telenok\Core\Module\Objects\Sequence;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller { 

    protected $key = 'objects-sequence';
    protected $parent = 'objects';
    protected $typeList = 'object_sequence';
    protected $presentation = 'tree-tab-object';

    public function getActionParam()
    { 
        return json_encode([]);
    }
    
    public function getListItem(\Illuminate\Database\Eloquent\Model $model)
    {
        $sequence = app('\App\Model\Telenok\Object\Sequence');
        
        $query = $model::select($model->getTable().'.*')
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
}