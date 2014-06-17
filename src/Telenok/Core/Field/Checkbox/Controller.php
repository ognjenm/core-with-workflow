<?php

namespace Telenok\Core\Field\Checkbox;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'checkbox'; 
    protected $specialField = ['checkbox_default'];
    protected $allowMultilanguage = false;

    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($value === null)
        {
            $value = $field->checkbox_default;
        }

        return $value;
    }

    public function setModelAttribute($model, $key, $value, $field)
    {
        $default = $field->checkbox_default; 
		
        if ($value === null)
        {
            $model->setAttribute($key, $default);
        }
        else
        {
            $model->setAttribute($key, $value);
        }
    }
	
    public function getFilterQuery($field = null, $model = null, $query = null, $name = null, $value = null) 
    {
        $query->where($model->getTable() . '.' . $name, '=', $value == 'on' ? 1 : 0);
    }

    public function getFilterContent($field = null)
    {
        return '<input type="checkbox" class="ace ace-switch" name="filter[' . $field->code . ']"><span class="lbl"></span>';
    }

    public function getListFieldContent($field, $item, $type = null)
    { 
		return '<input type="checkbox" class="ace ace-switch" disabled="disabled" ' . ($item->{$field->code} ? ' checked="checked" ' : '') . ' name="form-field-checkbox" onclick="return false;"><span class="lbl"></span>';
    }
	
    public function preProcess($model, $type, $input)
    {
		$input->put('multilanguage', 0);

        return parent::preProcess($model, $type, $input);
    } 

    public function postProcess($model, $type, $input)
    {
        $table = $model->fieldObjectType()->first()->getAttribute('code');
        $fieldName = $model->getAttribute('code');

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->default(0);
			});
		}

        $field = [];

        if ($input->get('required'))
        {
            $field['rule'][] = 'required';
        }  

        $model->fill($field)->save();

        return parent::postProcess($model, $type, $input);
    }
}

?>