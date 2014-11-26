<?php

namespace Telenok\Core\Field\SelectMany;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration; 

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'select-many'; 
    protected $allowMultilanguage = false;
    protected $specialField = ['select_many_data'];
    protected $viewModel = "core::field.select-many.model-select-box";

    public function getModelAttribute($model, $key, $value, $field)
    { 
		$value = $value === null ? '[]' : $value;
		
		$v = json_decode($value, true);
		
		if (is_array($v))
		{
			return \Illuminate\Support\Collection::make($v);
		}
		else
		{
			return $v;
		}
    }

    public function setModelAttribute($model, $key, $value, $field)
    {
		if ($value instanceof \Illuminate\Support\Collection) 
		{
			$value_ = $value->toArray();
		}
		else if (is_array($value))
		{
			$value_ = $model->{$key};

			foreach($value as $k => $v)
			{
				$value_->put($k, $v);
			}
			
			$value_ = $value_->toArray();
		}
		else
		{
			$value_ = $value;
		}

		$model->setAttribute($key, is_null($value_) ? null : json_encode($value_, JSON_UNESCAPED_UNICODE));
    }
    
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['select_many_data']))
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
		if (in_array($key, ['select_many_data']))
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

            if ($key == 'select_many_data')
            {
                $localeDefault = \Config::get('app.localeDefault');

                $title = array_get($value, 'title.' . $localeDefault, []);
                
                foreach(array_get($value, 'title', []) as $k => $t)
                {
                    if ($k != $localeDefault)
                    {
                        foreach($t as $k_ => $t_)
                        {
                            if (!trim($t_))
                            {
                                $value['title'][$k][$k_] = $title[$k_];
                            }
                        }
                    }
                }
            }
            
			$model->setAttribute($key, json_encode($value, JSON_UNESCAPED_UNICODE));
		}
		else
		{
			parent::setModelSpecialAttribute($model, $key, $value);
		}

        return $this;
    }
    
    public function getListFieldContent($field, $item, $type = null)
    {  
        $value = $item->{$field->code}->toArray();

        if (!empty($value))
        {
            $config = $field->select_many_data->toArray();
            $locale = \Config::get('app.locale');

            $title = array_get($config, 'title.' . $locale, []);
            $key = array_get($config, 'key', []);

            $val = array_only(array_slice(array_combine($key, $title), 0, 10, true), $value);

            return \Str::limit(implode(', ', $val), 30);
        }
    }
    
    public function postProcess($model, $type, $input)
    {
		$table = $model->fieldObjectType()->first()->code;
        $fieldName = $model->code;

		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->string($fieldName)->nullable();
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