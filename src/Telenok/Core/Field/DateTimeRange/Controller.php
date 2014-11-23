<?php

namespace Telenok\Core\Field\DateTimeRange;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'datetime-range'; 
    protected $allowMultilanguage = false;
	protected $specialDateField = ['datetime_range_default_start', 'datetime_range_default_end'];

    public function getDateField($model, $field)
    { 
		return [$field->code . '_start', $field->code . '_end'];
    } 

    public function getModelField($model, $field)
    {
		return [];
    } 

    public function getListFieldContent($field, $item, $type = null)
    {  
        $value = [];
        $value[] = $item->{$field->code . '_start'};
        $value[] = $item->{$field->code . '_end'};
        
        return count($value) ? implode(' ... ', $value) : '';
    } 

    public function setModelAttribute($model, $key, $value, $field)
    {   
        if (in_array($key, [$field->code . '_start', $field->code . '_end']))
        {
            if ($value === null)
            {
                if ($key == $field->code . '_start')
                {
                    $value = $field->datetime_range_default_start ?: null;
                }
                else if ($key == $field->code . '_end')
                {
                    $value = $field->datetime_range_default_end ?: null;
                }
            }
            else if (is_scalar($value) && $value)
            {
                $value = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value);
            } 
        }

        parent::setModelAttribute($model, $key, $value, $field);
    }
    
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['datetime_range_default_start', 'datetime_range_default_end']) && $value === null)
			{ 
                return \Carbon\Carbon::now();
            }
			else
			{
				return parent::getModelSpecialAttribute($model, $key, $value);
			}
        }
        catch (\Exception $e)
        {
            return null;
        }
    }
    
    public function setModelSpecialAttribute($model, $key, $value)
    {
        if (in_array($key, ['datetime_range_default_start', 'datetime_range_default_end']))
		{
            if ($value === null)
            {
                $value = \Carbon\Carbon::now();
            }
            else if (is_scalar($value) && $value)
            {
                $value = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value);
            }
		}

        parent::setModelSpecialAttribute($model, $key, $value);

        return true;
    }
    
    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName . '_start') && !\Schema::hasColumn($table, "`{$fieldName}_start`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName . '_start')->nullable();
			});
		}

		if (!\Schema::hasColumn($table, $fieldName . '_end') && !\Schema::hasColumn($table, "`{$fieldName}_end`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName . '_end')->nullable();
			});
		}

        $fields = []; 
        
        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }

        $model->fill($fields)->save();

        return parent::postProcess($model, $type, $input);
    }
}

?>