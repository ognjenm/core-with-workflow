<?php 

namespace Telenok\Core\Field\MorphOneToMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'morph-one-to-many'; 
    protected $specialField = ['morph_one_to_many_has', 'morph_one_to_many_belong_to'];
    protected $allowMultilanguage = false;

	public function getLinkedModelType($field)
	{
		return \Telenok\Core\Model\Object\Type::whereIn('id', [$field->morph_one_to_many_has, $field->morph_one_to_many_belong_to])->first();
	}

    public function getModelField($model, $field)
    {
		return $field->morph_one_to_many_belong_to ? [$field->code . '_type', $field->code . '_id'] : [];
    }

    public function getTitleList($id = null) 
    {
        $term = trim(\Input::get('term'));
        $return = [];

        try 
        {
            $class = \Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;

            $class::where('title', 'like', "%{$term}%")->take(20)->get()->each(function($item) use (&$return)
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
                        onclick="editO2MHas'.$uniqueId.'(this, \''.\URL::route('cmf.module.objects-lists.wizard.edit', ['id' => $item->getKey() ]).'\'); return false;">
                        <i class="fa fa-pencil"></i>
                    </button>
                    
                    <button class="btn btn-minier btn-light disabled" title="'.$this->LL('list.btn.edit').'">
                        <i class="fa fa-check ' . ($item->active ? 'green' : 'white'). '"></i>
                    </button>
                    ' .
                    ($field->allow_delete ? '
                    <button class="btn btn-minier btn-danger trash-it" title="'.$this->LL('list.btn.delete').'" 
                        onclick="deleteO2MHas'.$uniqueId.'(this); return false;">
                        <i class="fa fa-trash-o"></i>
                    </button>' : ''
                    ). '
                </div>';
    } 

    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
		if (!empty($value))
		{
			if ($field->morph_one_to_many_has)
			{
				$modelTable = $model->getTable();

				$linkedTable = \Telenok\Core\Model\Object\Sequence::getModel($field->morph_one_to_many_has)->code;

				$query->join($linkedTable, function($join) use ($modelTable, $linkedTable, $name, $field)
				{
					$join->on($modelTable . '.id', '=', $linkedTable . '.' . $field->code . '_' . $modelTable . '_id');
				});

				$query->whereIn($linkedTable.'.id', (array)$value);
			}
			else if ($field->morph_one_to_many_belong_to)
			{
				$modelTable = $model->getTable();

				$linkedTable = \Telenok\Core\Model\Object\Sequence::getModel($field->morph_one_to_many_belong_to)->code;

				$query->join($linkedTable, function($join) use ($modelTable, $linkedTable, $name, $field)
				{
					$join->on($modelTable . '.' . $field->code . '_id', '=', $linkedTable . '.id');
				});

				$query->whereIn($linkedTable.'.id', (array)$value);
			}
		}
    }

    public function getFilterContent($field = null)
    {
        $uniqueId = uniqid();
        $option = [];
        
        $id = $field->morph_one_to_many_has ?: $field->morph_one_to_many_belong_to;
        
        $class = \Telenok\Core\Model\Object\Sequence::getModel($id)->class_model;
        
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
		if ($field->morph_one_to_many_belong_to)
		{
			$id = $input->get("{$field->code}", 0);

			if ($id)
			{
				$objectModel = \Telenok\Core\Model\Object\Sequence::find($id)->model()->first();

				if ($objectModel->type()->getKey() == $field->morph_one_to_many_belong_to)
				{
					$model->fill([$field->code . '_type' => get_class($objectModel), $field->code . '_id' => $objectModel->getKey()])->save();
				}
			}
			else
			{
				$model->fill([$field->code . '_type' => null, $field->code . '_id' => null])->save();
			}
		}
		else if ($field->morph_one_to_many_has)
		{ 
			$idsAdd = array_unique((array)$input->get("{$field->code}_add", []));
			$idsDelete = array_unique((array)$input->get("{$field->code}_delete", []));

			if (!empty($idsAdd) || !empty($idsDelete))
			{ 
				$method = camel_case($field->code);

				$relatedField = $field->code . '_' . $model->sequence->sequencesObjectType->code;

				if (in_array('*', $idsDelete))
				{
					$model->$method()->get()->each(function($item) use ($relatedField) 
					{
						$item->fill([$relatedField . '_id' => 0, $relatedField . '_type' => null])->save();
					});
				}
				else if (!empty($idsDelete))
				{
					$model->$method()->whereIn('id', $idsDelete)->get()->each(function($item) use ($relatedField) 
					{
						$item->fill([$relatedField . '_id' => 0, $relatedField . '_type' => null])->save();
					});
				}
				
				$relatedModel = \App::build(\Telenok\Core\Model\Object\Type::findOrFail($field->morph_one_to_many_has)->class_model);

				\Illuminate\Support\Collection::make($idsAdd)->each(function($id) use ($model, $method, $relatedModel) 
				{
					if (intval($id)) 
					{
						try
						{
							$linked = $relatedModel::findOrFail($id);
							$model->$method()->save( $linked );
						} 
						catch(\Exception $e) {}
					}
				});
			}
		}
		
        return $model;
    }
	
    public function preProcess($model, $type, $input)
    {
		$input->put('morph_one_to_many_has', intval(\Telenok\Core\Model\Object\Type::where('code', $input->get('morph_one_to_many_has'))->orWhere('id', $input->get('morph_one_to_many_has'))->pluck('id')));
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0);

        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
        try 
        {
			if (!$model->morph_one_to_many_has)
			{
				return parent::postProcess($model, $type, $input);
			} 

            $relatedTypeOfModelField = $model->fieldObjectType()->first();   // eg object \Telenok\Core\Model\Object\Type which DB-field "code" is "author"

            $classModelHasMany = $relatedTypeOfModelField->class_model;
            $codeFieldHasMany = $model->code; 
            $codeTypeHasMany = $relatedTypeOfModelField->code; 

            $typeBelongTo = \Telenok\Core\Model\Object\Type::findOrFail($model->morph_one_to_many_has); 
            $tableBelongTo = $typeBelongTo->code;
            $classBelongTo = $typeBelongTo->class_model;

            $relatedSQLField = $codeFieldHasMany . '_' . $codeTypeHasMany;

            $hasMany = [
                    'method' => camel_case($codeFieldHasMany),
					'name' => $codeFieldHasMany,
                    'class' => $classBelongTo,
                    'type' => $relatedSQLField . '_type',
                    'foreignKey' => $relatedSQLField . '_id',
                    'otherKey' => 'id',
                ];

			$belongTo = [
                    'method' => camel_case($relatedSQLField),
					'name' => $codeFieldHasMany,
                    'type' => $relatedSQLField . '_type',
                    'id' => $relatedSQLField . '_id',
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

				if (!($tabTo = \Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeBelongTo->getKey())->where('code', \Telenok\Core\Model\Object\Tab::find($input->get('field_object_tab'))->code)->first()))
				{
					if (!($tabTo = \Telenok\Core\Model\Object\Tab::where('tab_object_type', $typeBelongTo->getKey())->where('code', 'main')->first()))
					{
						throw new \Exception($this->LL('error.tab.field.key'));
					}
				}

				$toSave = [
					'title' => $title,
					'title_list' => $title_list,
					'key' => $this->getKey(),
					'code' => $relatedSQLField,
					'field_object_type' => $typeBelongTo->getKey(),
					'field_object_tab' => $tabTo->getKey(),
					'morph_one_to_many_belong_to' => $relatedTypeOfModelField->getKey(),
					'show_in_form' => $input->get('show_in_form_belong', $model->show_in_form),
					'show_in_list' => $input->get('show_in_list_belong', $model->show_in_list),
					'allow_search' => $input->get('allow_search_belong', $model->allow_search),
					'allow_delete' => $input->get('allow_delete_belong', $model->allow_delete),
					'multilanguage' => 0,
					'active' => $input->get('active_belong', $model->active),
					'allow_choose' => $input->get('allow_choose_belong', $model->allow_choose),
					'allow_create' => $input->get('allow_create_belong', $model->allow_create),
					'allow_update' => $input->get('allow_update_belong', $model->allow_update),
					'field_order' => $input->get('field_order_belong', $model->field_order),
				];

				$validator = $this->validator(new \Telenok\Core\Model\Object\Field(), $toSave, []);

				if ($validator->passes()) 
				{
					\Telenok\Core\Model\Object\Field::create($toSave);
				}
			
				if (!\Schema::hasColumn($tableBelongTo, $relatedSQLField . '_type') && !\Schema::hasColumn($tableBelongTo, "`" . $relatedSQLField . "_type`"))
				{
					\Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
					{
						$table->string($relatedSQLField . '_type');
					});
				}

				if (!\Schema::hasColumn($tableBelongTo, $relatedSQLField . '_id') && !\Schema::hasColumn($tableBelongTo, "`" . $relatedSQLField . "_id`"))
				{
					\Schema::table($tableBelongTo, function(Blueprint $table) use ($relatedSQLField)
					{
						$table->unsignedInteger($relatedSQLField . '_id');
					});
				}

				if (!$this->validateMethodExists($belongToObject, $belongTo['method']))
				{
					$this->updateModelFile($belongToObject, $belongTo, 'morphTo', __DIR__);
				}
				else
				{
					\Session::flash('warning.hasManyBelongTo', $this->LL('error.method.defined', ['method'=>$belongTo['method'], 'class'=>$classBelongTo]));
				} 
			}

            if (!$this->validateMethodExists($hasManyObject, $hasMany['method']))
            {
                $this->updateModelFile($hasManyObject, $hasMany, 'morphMany', __DIR__);
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