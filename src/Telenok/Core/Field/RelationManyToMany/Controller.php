<?php

namespace Telenok\Core\Field\RelationManyToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'relation-many-to-many'; 
    protected $specialField = array('relation_many_to_many_has', 'relation_many_to_many_belong_to');
    protected $allowMultilanguage = false;

    public function getModelField($model, $field)
    {
		return [];
    } 

	public function getLinkedModelType($field)
	{
		return \Telenok\Object\Type::whereIn('id', [$field->relation_many_to_many_has, $field->relation_many_to_many_belong_to])->first();
	}
	
    public function getTitleList($id = null) 
    {
        $term = trim(\Input::get('term'));
        $return = [];

        try 
        {
            $class = \Telenok\Object\Sequence::getModel($id)->class_model;

            $class::where(function($query) use ($term)
			{
				\Illuminate\Support\Collection::make(explode(' ', $term))
						->reject(function($i) { return !trim($i); })
						->each(function($i) use ($query)
				{
					$query->where('title', 'like', "%{$i}%");
				});
			})
			->take(20)->get()->each(function($item) use (&$return)
            {
                $return[] = ['value' => $item->id, 'text' => $item->translate('title')];
            });
        }
        catch (\Exception $e) {}

        return $return;
    } 
	
    public function getListButtonExtended($item, $field, $type, $uniqueId)
    {
        return '<div class="hidden-phone visible-lg btn-group">
                    <button class="btn btn-minier btn-info" title="'.$this->LL('list.btn.edit').'" 
                        onclick="editM2M'.$uniqueId.'(this, \''.\URL::route('cmf.module.objects-lists.wizard.edit', ['id' => $item->getKey() ]).'\'); return false;">
                        <i class="fa fa-pencil"></i>
                    </button>
                    
                    <button class="btn btn-minier btn-light" onclick="return false;" title="' . $this->LL('list.btn.' . ($item->active ? 'active' : 'inactive')) . '">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>
                    ' .
                    ($field->allow_delete ? '
                    <button class="btn btn-minier btn-danger trash-it" title="'.$this->LL('list.btn.delete').'" 
                        onclick="deleteM2M'.$uniqueId.'(this); return false;">
                        <i class="fa fa-trash-o"></i>
                    </button>' : ''
                    ). '
                </div>';
    } 

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			$modelTable = $model->getTable();

			if ($field->relation_many_to_many_has)
			{
				$pivotTable = 'pivot_relation_m2m_' . $field->code . '_' . $field->fieldObjectType()->first()->code;
				$relatedField = $field->code . '_' . $field->fieldObjectType()->first()->code;
			}
			else
			{
				$pivotTable = 'pivot_relation_m2m_' . $field->code;
				$codeTypeHasMany = \Telenok\Object\Sequence::getModel($field->relation_many_to_many_belong_to)->code;
				$relatedField = preg_replace('/_'.$codeTypeHasMany.'$/', '', $pivotTable);
			}

			$query->join($pivotTable, function($join) use ($pivotTable, $modelTable, $relatedField)
			{
				$join->on($pivotTable . '.'.$relatedField, '=', $modelTable . '.id');
			});

			$query->whereIn($pivotTable.'.'.$name, (array)$value);
		}
    }
    
    public function getFilterContent($field = null)
    {
        $uniqueId = uniqid();
        $option = [];
         
        $id = $field->relation_many_to_many_has ?: $field->relation_many_to_many_belong_to;
        
        $class = \Telenok\Object\Sequence::getModel($id)->class_model;
        
        $class::take(200)->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });
        
        $option[] = "<option value='0' disabled='disabled'>...</option>";
        
        return '
            <select class="chosen" multiple data-placeholder="'.$this->LL('notice.choose').'" id="input'.$uniqueId.'" name="filter['.$field->code.'][]">
            ' . implode('', $option) . ' 
            </select>
            <script>
                jQuery("#input'.$uniqueId.'").ajaxChosen({ 
                    keepTypingMsg: "'.$this->LL('notice.typing').'",
                    lookingForMsg: "'.$this->LL('notice.looking-for').'",
                    type: "GET",
                    url: "'.\URL::route($this->getRouteListTitle(), ['id' => (int)$id]).'", 
                    dataType: "json",
                    minTermLength: 1
                }, 
                function (data) 
                {
                    var results = [];

                    jQuery.each(data, function (i, val) {
                        results.push({ value: val.value, text: val.text });
                    });

                    return results;
                },
                {
                    width: "200px",
                    no_results_text: "'.$this->LL('notice.not-found').'" 
                    
                });
            </script>';
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

    public function saveModelField($field, $model, $input)
    {
        $idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
        $idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));
         
        if ( (!empty($idsAdd) || !empty($idsDelete)))
        { 
            $method = camel_case($field->code);
             
            if (in_array('*', $idsDelete))
            {
                $model->$method()->detach();
            }
            else if (!empty($idsDelete))
            {
                $model->$method()->detach($idsDelete);
            }

            foreach($idsAdd as $id)
            {
                try
                {
                    $model->$method()->attach($id);
                }
                catch(\Exception $e) {}
            }

        }

        return $model;
    }
	
    public function preProcess($model, $type, $input)
    {
		$input->put('relation_many_to_many_has', intval(\Telenok\Object\Type::where('code', $input->get('relation_many_to_many_has'))->orWhere('id', $input->get('relation_many_to_many_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);
		
        return parent::preProcess($model, $type, $input);
    } 
 
    public function postProcess($model, $type, $input)
    {
        try 
        {
			$model->fill(['relation_many_to_many_has' => $input->get('relation_many_to_many_has')])->save();
			
			if (!$input->get('relation_many_to_many_has'))
			{
				return parent::postProcess($model, $type, $input);
			}

            $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \Telenok\Object\Type which DB-field "code" is "author"

            $classModelHasMany = $relatedTypeOfModelField->class_model;
            $tableHasMany = $relatedTypeOfModelField->code;
            $codeFieldHasMany = $model->code; 
            $codeTypeHasMany = $relatedTypeOfModelField->code; 

            $typeBelongTo = \Telenok\Object\Type::findOrFail($input->get('relation_many_to_many_has')); 
            $tableBelongTo = $typeBelongTo->code;
            $classBelongTo = $typeBelongTo->class_model;

            $pivotTable = 'pivot_relation_m2m_' . $codeFieldHasMany . '_' . $codeTypeHasMany;
            $pivotField = $codeFieldHasMany . '_' . $codeTypeHasMany;

            $hasMany = [
                    'method' => camel_case($codeFieldHasMany),
                    'class' => $classBelongTo,
                    'table' => $pivotTable,
                    'field_1' => $pivotField,
                    'field_2' => $codeFieldHasMany,
                ];

            $belongTo = [
                    'method' => camel_case($codeFieldHasMany . '_' . $codeTypeHasMany),
                    'class' => $classModelHasMany,
                    'table' => $pivotTable,
                    'field_1' => $codeFieldHasMany,
                    'field_2' => $pivotField,
                ];

            $hasManyObject = \App::build($classModelHasMany);
            $belongToObject = \App::build($classBelongTo);

            if ($input->get('create_belong') !== false) 
            {
				$title = $input->get('title_belong', []);
				$title_list = $input->get('title_list_belong', []);

				foreach($relatedTypeOfModelField->title->toArray() as $language => $val)
				{
					$title[$language] = array_get($title, $language, $val . '/' . $model->translate('title', $language));
				}

				foreach($relatedTypeOfModelField->title_list->toArray() as $language => $val)
				{
					$title_list[$language] = array_get($title_list, $language, $val . '/' . $model->translate('title_list', $language));
				}

				$tabTo = $this->getFieldTabBelongTo($typeBelongTo->getKey(), $input->get('field_object_tab')); 

				$toSave = [
					'title' => $title,
					'title_list' => $title_list,
					'key' => $this->getKey(),
					'code' => $pivotField,
					'field_object_type' => $typeBelongTo->getKey(),
					'field_object_tab' => $tabTo->getKey(),
					'relation_many_to_many_belong_to' => $relatedTypeOfModelField->getKey(),
					'show_in_list' => $input->get('show_in_list_belong', $model->show_in_list),
					'show_in_form' => $input->get('show_in_form_belong', $model->show_in_form),
					'allow_search' => $input->get('allow_search_belong', $model->allow_search),
					'allow_delete' => $input->get('allow_delete_belong', $model->allow_delete),
					'multilanguage' => 0,
					'active' => $input->get('active_belong', $model->active),
					'allow_choose' => $input->get('allow_choose_belong', $model->allow_choose),
					'allow_create' => $input->get('allow_create_belong', $model->allow_create),
					'allow_choose' => $input->get('allow_choose_belong', $model->allow_choose),
					'allow_update' => $input->get('allow_update_belong', $model->allow_update),
					'field_order' => $input->get('field_order_belong', $model->field_order),
				];

				$validator = $this->validator(new \Telenok\Object\Field(), $toSave, []);

				if ($validator->passes()) 
				{
					\Telenok\Object\Field::create($toSave);
				}

				if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
				{
					$this->updateModelFile($belongToObject, $belongTo, 'hasManyToMany', __DIR__);
				}
				else
				{
					\Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
				} 
			}

            if (!\Schema::hasTable($pivotTable)) 
			{
                \Schema::create($pivotTable, function(Blueprint $table) use ($codeFieldHasMany, $pivotTable, $pivotField, $tableHasMany, $tableBelongTo)
                {
                    $table->increments('id');
                    $table->timestamps();
                    $table->integer($codeFieldHasMany)->unsigned()->nullable();
                    $table->integer($pivotField)->unsigned()->nullable();

                    $table->unique([$pivotField, $codeFieldHasMany], 'uniq_key');
					
					$table->foreign($codeFieldHasMany)->references('id')->on($tableBelongTo)->onDelete('cascade');
					$table->foreign($pivotField)->references('id')->on($tableHasMany)->onDelete('cascade');
                });
            }

            if (!$this->validateMethodExists($hasManyObject, $hasMany['method']))
            {
                $this->updateModelFile($hasManyObject, $hasMany, 'hasManyToMany', __DIR__);
            } 
            else
            {
                \Session::flash('warning.hasMany', $this->LL('error.method.defined', ['method'=>$hasMany['method'], 'class'=>$classModelHasMany]));
            }
        }
        catch (\Exception $e) 
        {
            throw $e;
        }

        return parent::postProcess($model, $type, $input);
    }  
}

?>