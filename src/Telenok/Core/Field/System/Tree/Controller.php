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
	
    public function preProcess($model, $type, $input)
    {
		if (!$input->get('relation_many_to_many_has'))
		{
			return parent::preProcess($model, $type, $input);
		} 
		
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
		$input->put('allow_create', 0);
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
			'multilanguage' => 0,
			'active' => $input->get('active'),
			'allow_create' => $input->get('allow_create'),
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