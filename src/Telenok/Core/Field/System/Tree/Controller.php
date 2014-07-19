<?php

namespace Telenok\Core\Field\System\Tree;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\RelationManyToMany\Controller {

	protected $key = 'tree';

    protected $viewModel = "core::field.relation-many-to-many.model";
    protected $viewField = "core::field.relation-many-to-many.field";

	public function getLinkedModelType($field)
	{
		return \Telenok\Object\Type::whereIn('code', 'object_sequence')->first();
	}
	
    public function saveModelField($field, $model, $input)
    { 
		if (!$model->sequence->treeable)
		{
			throw new \Exception('Model "' . get_class($model) . '" is not treeable');
		}

		$idsParentAdd = array_unique((array)$input->get("tree_parent_add", []));
        $idsParentDelete = array_unique((array)$input->get("tree_parent_delete", []));
        
		$idsChildAdd = array_unique((array)$input->get("tree_child_add", []));
        $idsChildelete = array_unique((array)$input->get("tree_child_delete", []));
		
        if (!empty($idsParentDelete))
        {
            if (in_array('*', $idsParentDelete))
            {
                $model->treeParent()->detach();
            }
            else if (!empty($idsParentDelete))
            {
                $model->treeParent()->detach($idsParentDelete);
            }
		}

        if (!empty($idsParentAdd))
        {
            foreach($idsParentAdd as $id)
            {
                try
                {
                    $model->makeLastChildOf($id);
                }
                catch(\Exception $e) {}
            }
		}
		
        if (!empty($idsChildelete))
        {
            if (in_array('*', $idsChildelete))
            {
                $model->treeChild()->detach();
            }
            else if (!empty($idsChildelete))
            {
                $model->treeChild()->detach($idsChildelete);
            }
		}

        if (!empty($idsChildAdd))
        {
            foreach($idsChildAdd as $id)
            {
                try
                {
					$child = \Telenok\Object\Sequence::findOrFail($id);

                    $child->makeLastChildOf($model);
                }
                catch(\Exception $e) {}
            }
		}

        return $model;
    }
	
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			$modelTable = $model->getTable();
			$pivotTable = 'pivot_relation_m2m_tree';

            if ($field->relation_many_to_many_has)
            {
                $fieldRelated = 'tree_id';
                $fieldSearchIn = 'tree_pid';
            }
            else
            {
                $fieldRelated = 'tree_pid';
                $fieldSearchIn = 'tree_id';
            }

			$query->join($pivotTable, function($join) use ($pivotTable, $modelTable, $fieldRelated)
			{
				$join->on($pivotTable . '.'.$fieldRelated, '=', $modelTable . '.id');
			});

			$query->whereIn($pivotTable.'.'.$fieldSearchIn, (array)$value);
		}
    }

    public function getFormModelTableColumn($field, $model, $jsUnique)
    {
		$fields = [];
		
		$objectField = \Telenok\Object\Type::where('code', 'object_sequence')->first()->field()->where('show_in_list', 1)->get();
		
		foreach($objectField as $key => $field)
		{
			$fields[$field->code] = [
				"mData" => $field->code, 
				"sTitle" => e($field->translate('title_list')), 
				"mDataProp" => null, 
				"bSortable" => $field->allow_sort ? true : false
			];
			
			if ( ($key==1 && $objectField->count() > 1) || ($key == 0 && $objectField->count() == 1))
			{
				$fields['tableManageItem'] = [
					"mData" => 'tableManageItem', 
					"sTitle" => e($this->LL('action')), 
					"mDataProp" => null, 
					"bSortable" => false
				];
			}
		}
		
		return $fields;
    }

    public function getTableList1111($id = null, $fieldId = null, $uniqueId = null) 
    {
        $term = trim(\Input::get('sSearch'));
        $iDisplayStart = intval(\Input::get('iDisplayStart', 0));
        $iDisplayLength = intval(\Input::get('iDisplayLength', 10));
        $sEcho = \Input::get('sEcho');
        $content = [];

        try 
        {
            $model = \Telenok\Object\Sequence::find($id);
            $type = $model->sequencesObjectType;
            $field = \Telenok\Object\Sequence::getModel($fieldId);

            if ($field->relation_many_to_many_has)
            {
                $fieldRelated = 'tree_child';
            }
            else
            {
                $fieldRelated = 'tree_parent';
            }

			$query = $model->{camel_case($fieldRelated)}();

            if ($term)
            { 
				$query->where(function ($query) use ($term)
				{
					\Illuminate\Support\Collection::make(explode(' ', $term))
							->reject(function($i) { return !trim($i); })
							->each(function($i) use ($query)
					{
						$query->where('title', 'like', "%{$i}%");
					});
				}); 
            }

            $query->skip($iDisplayStart)->take($this->displayLength + 1);

            $items = $query->get(); 

			$objectField = $model->type()->field()->where('show_in_list', 1)->get();

			$config = \App::make('telenok.config')->getObjectFieldController();
			
            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
				$c = [];
				
				foreach($objectField as $f)
				{
					$c[$f->code] = $config->get($f->key)->getListFieldContent($f, $item, $type);
				}

				$c['tableManageItem'] = $this->getListButtonExtended($item, $field, $type, $uniqueId);
						
                $content[] = $c;
            }

            return [
                'sEcho' => $sEcho,
                'iTotalRecords' => ($iDisplayStart + $items->count()),
                'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
                'aaData' => $content
            ]; 
        }
        catch (\Exception $e) 
        {
			return [
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => [],
                'exception' => $e->getMessage(),
            ];
        } 
    } 
	
    public function preProcess($model, $type, $input)
    {
		$sequenceTypeId = \DB::table('object_type')->where('code', 'object_sequence')->pluck('id');
		
		$translationSeed = $this->translationSeed();

		$input->put('title', array_get($translationSeed, 'model.parent'));
		$input->put('title_list', array_get($translationSeed, 'model.parent')); 
		$input->put('key', 'tree');
		$input->put('code', 'tree_parent');
		$input->put('relation_many_to_many_has', $sequenceTypeId);
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_delete', 1);
		$input->put('allow_create', 0);
		$input->put('allow_choose', 1);
		$input->put('allow_update', 1);
		$input->put('field_order', 5);
		 
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab'));

		$input->put('field_object_tab', $tab->getKey()); 
		
		$tabTo = $this->getFieldTabBelongTo($sequenceTypeId, $input->get('field_object_tab')); 
		
		$toSave = [
			'title' => array_get($translationSeed, 'model.children'),
			'title_list' => array_get($translationSeed, 'model.children'),
			'key' => 'tree',
			'code' => 'tree_child',
			'field_object_type' => $input->get('field_object_type'),
			'field_object_tab' => $input->get('field_object_tab'),
			'relation_many_to_many_belong_to' => $sequenceTypeId,
			'show_in_list' => $input->get('show_in_list'),
			'show_in_form' => $input->get('show_in_form'),
			'allow_search' => $input->get('allow_search'),
			'allow_delete' => $input->get('allow_delete'),
			'multilanguage' => 0,
			'active' => $input->get('active'),
			'allow_create' => $input->get('allow_create'),
			'allow_choose' => $input->get('allow_choose'),
			'allow_update' => $input->get('allow_update'),
			'field_order' => $input->get('field_order'),
			'field_object_tab' => $tabTo->getKey(),
		];  
 
		$validator = $this->validator(new \Telenok\Object\Field(), $toSave, []);

		if ($input->get('create_belong') !== false && $validator->passes()) 
		{
			\Telenok\Object\Field::create($toSave);
		}
		
        return $this;
    }

    public function postProcess($model, $type, $input) 
	{ 
		
		return $this;
	}

	public function translationSeed()
	{
		return [
			'model' => [
				'parent' => ['en' => 'Parent', 'ru' => 'Родитель'],
				'children' => ['en' => 'Children', 'ru' => 'Потомок'],
			],
		];
	}
}

?>