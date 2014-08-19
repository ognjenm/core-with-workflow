<?php

namespace Telenok\Core\Interfaces\Field\Relation;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    public function getTitleList($id = null) 
    { 
        $term = trim(\Input::get('term'));
        $return = [];
        
		$class = \Telenok\Object\Sequence::getModel($id)->class_model;

		$model = new $class;

		$model::withPermission()
		->join('object_translation', function($join) use ($model)
		{
			$join->on($model->getTable() . '.id', '=', 'object_translation.translation_object_model_id');
		})	
		->where(function($query) use ($term)
		{
			\Illuminate\Support\Collection::make(explode(' ', $term))
					->reject(function($i) { return !trim($i); })
					->each(function($i) use ($query)
			{
				$query->orWhere('object_translation.translation_object_string', 'like', "%{$i}%");
			});
		})
		->take(20)->groupBy($model->getTable() . '.id')->get()->each(function($item) use (&$return)
		{
			$return[] = ['value' => $item->id, 'text' => $item->translate('title')];
		});

        return $return;
    }
	
    public function getListButtonExtended($item, $field, $type, $uniqueId, $canUpdate)
    {
        return '<div class="hidden-phone visible-lg btn-group">
                    <button class="btn btn-minier btn-info" title="'.$this->LL('list.btn.edit').'" 
                        onclick="editM2M'.$uniqueId.'(this, \''.\URL::route($this->getRouteWizardEdit(), ['id' => $item->getKey(), 'saveBtn' => 1, 'chooseBtn' => 0]).'\'); return false;">
                        <i class="fa fa-pencil"></i>
                    </button>
                    
                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>
                    ' .
                    ($canUpdate ? '
                    <button class="btn btn-minier btn-danger trash-it" title="'.$this->LL('list.btn.delete').'" 
                        onclick="deleteM2M'.$uniqueId.'(this); return false;">
                        <i class="fa fa-trash-o"></i>
                    </button>' : ''
                    ). '
                </div>';
    } 

    public function getListFieldContent($field, $item, $type = null)
    {
        $method = camel_case($field->code);

        $items = [];
        $rows = \Illuminate\Support\Collection::make($item->$method()->take(8)->getResults());
        
        if ($rows->count())
        {
            foreach($rows->slice(0, 7, TRUE) as $row)
            {
                $items[] = $row->translate('title');
            }

            return '"'.implode('", "', $items).'"'.(count($rows)>7 ? ', ...' : '');
        }
    }
}

?>