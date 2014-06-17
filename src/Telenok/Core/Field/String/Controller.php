<?php

namespace Telenok\Core\Field\String;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'string';

    protected $specialField = ['string_default', 'string_regex', 'string_password', 'string_max', 'string_min'];
    protected $ruleList = ['string_regex' => ['valid_regex']];

    public function getModelAttribute($model, $key, $value, $field)
    {
        if ($field->multilanguage)
        {
            $value = \Illuminate\Support\Collection::make(json_decode($value ?: '[]', true));
        }

        return $value;
    }

    public function setModelAttribute($model, $key, $value, $field)
    { 
        if ($field->multilanguage)
        { 
			$defaultLanguage = \Config::get('app.localeDefault', "en");

			if (is_string($value) )
			{
				$value = [$defaultLanguage => $value];
			}

			$default = json_decode($field->string_default ?: "[]", true);

            foreach ($default as $language => $v)
            {
                if (!isset($value[$language]))
                {
                    $value[$language] = $v;
                }
            }
            
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        else if (!strlen($value))
        {
            $value = $field->string_default ?: "";
        }

        $model->setAttribute($key, $value);
    }

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['string_default']) && $model->multilanguage)
			{ 
				return \Illuminate\Support\Collection::make(json_decode($value, true));
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
		if (in_array($key, ['string_default']) && $model->multilanguage)
		{
			$default = [];

			if ($value instanceof \Illuminate\Support\Collection) 
			{
				if ($value->count())
				{
					$value = $value->toArray();
				}
				else
				{
					$value = $default;
				}
			}
			else
			{
				$value = $value ? : $default;
			} 

			$model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
		}
		else
		{
			parent::setModelSpecialAttribute($model, $key, $value);
		}

        return true;
    }
	
    public function getListFieldContent($field, $item, $type = null)
    {  
        return \Str::limit($item->translate((string)$field->code), 20);
    } 
	
    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->text($fieldName);
			});
		}

        $fields = []; 
        
        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }
        
        if (trim($input->get('string_regex')))
        {
            $fields['rule'][] = "regex:".trim($input->get('string_regex'));
        }

        if (intval($input->get('string_max')))
        {
            $fields['rule'][] = "max:{$input->get('string_max')}";
        }

        if (intval($input->get('string_min')))
        {
            $fields['rule'][] = "min:{$input->get('string_min')}";
        }
        
        $model->fill($fields)->save();

        return parent::postProcess($model, $type, $input);
    }

}

?>