<?php

namespace Telenok\Core\Field\RelationOneToOne;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'relation-one-to-one'; 
    protected $specialField = ['relation_one_to_one_has', 'relation_one_to_one_belong_to'];
    protected $allowMultilanguage = false;

	public function getLinkedModelType($field)
	{
		return \Telenok\Object\Type::whereIn('id', [$field->relation_one_to_one_has, $field->relation_one_to_one_belong_to])->first();
	}
	
    public function getModelField($model, $field)
    {
		return $field->relation_one_to_one_belong_to ? [$field->code] : [];
    } 
	
    public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
    { 		
		if ($field->relation_one_to_one_has || $field->relation_one_to_one_belong_to)
		{
			return parent::getFormModelContent($controller, $model, $field, $uniqueId);
		}
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
    
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			if ($field->relation_one_to_one_belong_to)
			{
				$query->whereIn($name, (array)$value);
			}
			else
			{
				$modelTable = $model->getTable();

				$linkedTable = \Telenok\Object\Sequence::getModel($field->relation_one_to_one_has)->code;

				$query->join($linkedTable, function($join) use ($modelTable, $linkedTable, $name, $field)
				{
					$join->on($linkedTable . '.' . $field->code . '_' . $modelTable, '=', $modelTable . '.id');
				});

				$query->whereIn($linkedTable.'.id', (array)$value);
			}
		}
    }

    public function getFilterContent($field = null)
    {
        $uniqueId = str_random();
        $option = [];
        
        $id = $field->relation_one_to_one_has ?: $field->relation_one_to_one_belong_to;
        
        $class = \Telenok\Object\Sequence::getModel($id)->class_model;
        
        $class::take(200)->get()->each(function($item) use (&$option)
        {
            $option[] = "<option value='{$item->id}'>[{$item->id}] {$item->translate('title')}</option>";
        });
        
        $option[] = "<option value='0' disabled='disabled'>...</option>";
        
        return '
            <select class="chosen-select" multiple data-placeholder="'.$this->LL('notice.choose').'" id="input'.$uniqueId.'" name="filter['.$field->code.'][]">
            ' . implode('', $option) . ' 
            </select>
            <script>
                jQuery("#input'.$uniqueId.'").ajaxChosen({ 
                    keepTypingMsg: "'.$this->LL('notice.typing').'",
                    lookingForMsg: "'.$this->LL('notice.looking-for').'",
                    type: "GET",
                    url: "'.\URL::route($this->getRouteListTitle(), ['id' => $id]).'", 
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
        $rows = \Illuminate\Support\Collection::make($item->$method()->getResults());
        
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
		// if created field
		if ($model instanceof \Telenok\Core\Model\Object\Field && !$input->get('id'))
		{
			return $model;
		}
		
        if ($field->relation_one_to_one_has)
        { 
	        $id = (int)$input->get($field->code, 0); 

            $method = camel_case($field->code);
            
            $currentRelatedModel = $model->$method()->getResults();
            
            $field = $field->code . '_' . \Telenok\Object\Type::find($field->field_object_type)->code;
            
            if ($currentRelatedModel)
            {
                $currentRelatedModel->fill([ $field => 0 ])->save();
            }

			try
			{
				$relatedModel = \App::build(\Telenok\Object\Type::findOrFail($field->relation_one_to_one_has)->class_model)->findOrFail($id);
				$model->$method()->save($relatedModel);
			}
			catch(\Exception $e) {}
        }

        return $model;
    }   

    public function preProcess($model, $type, $input)
	{		
		$input->put('relation_one_to_one_has', intval(\Telenok\Object\Type::where('code', $input->get('relation_one_to_one_has'))->orWhere('id', $input->get('relation_one_to_one_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);
		
        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    { 
        try 
        {
			$model->fill(['relation_one_to_one_has' => $input->get('relation_one_to_one_has')])->save();
			
			if (!$input->get('relation_one_to_one_has'))
			{
				return parent::postProcess($model, $type, $input);
			} 

            $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \Telenok\Object\Type which DB-field "code" is "author"

            $classModelHasOne = $relatedTypeOfModelField->class_model;
            $codeFieldHasOne = $model->code; 
            $codeTypeHasOne = $relatedTypeOfModelField->code; 

            $typeBelongTo = \Telenok\Object\Type::findOrFail($input->get('relation_one_to_one_has')); 
            $tableBelongTo = $typeBelongTo->code;
            $classBelongTo = $typeBelongTo->class_model;

            $relatedSQLField = $codeFieldHasOne . '_' . $codeTypeHasOne;

            $hasOne = [
                    'method' => camel_case($codeFieldHasOne),
                    'class' => $classBelongTo,
                    'field' => $relatedSQLField,
                ];

            $belongTo = [
                    'method' => camel_case($relatedSQLField),
                    'class' => $classModelHasOne,
                    'field' => $relatedSQLField,
                ];

            $hasOneObject = \App::build($classModelHasOne);
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
					'code' => $relatedSQLField,
					'field_object_type' => $typeBelongTo->getKey(),
					'field_object_tab' => $tabTo->getKey(),
					'relation_one_to_one_belong_to' => $relatedTypeOfModelField->getKey(),
					'show_in_form' => $input->get('show_in_form_belong', $model->show_in_form),
					'show_in_list' => $input->get('show_in_list_belong', $model->show_in_list),
					'allow_search' => $input->get('allow_search_belong', $model->allow_search),
					'multilanguage' => 0,
					'active' => $input->get('active_belong', $model->active),
					'start_at' => $input->get('start_at_belong', $model->start_at),
					'end_at' => $input->get('end_at_belong', $model->end_at),
					'allow_create' => $input->get('allow_create_belong', $model->allow_create),
					'allow_update' => $input->get('allow_update_belong', $model->allow_update),
					'field_order' => $input->get('field_order_belong', $model->field_order),
				]; 

				$validator = $this->validator(new \Telenok\Object\Field(), $toSave, []);

				if ($validator->passes()) 
				{
					\Telenok\Object\Field::create($toSave);
				}
				
				if (!\Schema::hasColumn($tableBelongTo, $relatedSQLField) && !\Schema::hasColumn($tableBelongTo, "`{$relatedSQLField}`"))
				{
					\Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
					{
						$table->integer($relatedSQLField)->unsigned()->nullable();
					});
				}
				
				if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
				{
					$this->updateModelFile($belongToObject, $belongTo, 'belongsTo', __DIR__);
				}
				else
				{
					\Session::flash('warning.hasOneBelongTo', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
				} 
			}

            if (!$this->validateMethodExists($hasOneObject, $hasOne['method']))
            {
                $this->updateModelFile($hasOneObject, $hasOne, 'hasOne', __DIR__);
            } 
            else
            {
                \Session::flash('warning.hasOne', $this->LL('error.method.defined', ['method'=>$hasOne['method'], 'class'=>$classModelHasOne]));
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