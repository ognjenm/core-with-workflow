<?php

namespace Telenok\Core\Field\System\Active;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\Checkbox\Controller {

	protected $key = 'active';
	protected $specialField = ['active_default'];

    public function getDateField($model, $field)
    { 
		return ['start_at', 'end_at'];
    } 

    public function getModelField($model, $field)
    { 
		return ['active', 'start_at', 'end_at'];
    } 

	public function fill($field, $model, $input)
    {
		if (!$model->exists)
		{
			if ($input->get('active', null) === null)
			{
				$input->put('active', $field->active_default);
			}
		}

        return parent::fill($field, $model, $input);
    }

	public function getModelAttribute($model, $key, $value, $field)
	{ 
		if ($key == 'active' && $value === null)
		{
			$value = $field->active_default;
		}
		else if (($key == 'start_at' || $key == 'end_at') && ( ($value instanceof \DateTime && $value->year < 0) || $value == null))
		{
			if ($key == 'start_at')
			{
				$value = \Carbon\Carbon::now()->second(0);
			}
			else if ($key == 'end_at')
			{
				$value = \Carbon\Carbon::now()->second(0)->modify('15 year');
			}
		}
		
		return $value;
	}

    public function setModelAttribute($model, $key, $value, $field)
    { 
		if ($key == 'active')
		{
			if ($value === null)
			{
				$value = $field->active_default;
			}
		}
		elseif (($key == 'start_at' || $key == 'end_at') && !($value instanceof \DateTime))
		{ 
			if ($value == null)
			{
				if ($key == 'start_at')
				{
					$value = \Carbon\Carbon::now();
				}
				else if ($key == 'end_at')
				{
					$value = \Carbon\Carbon::now()->modify('15 year');
				}
			}
			else
			{
				$value = \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $value);
			}
		}

		$model->setAttribute($key, $value);
    }

	public function preProcess($model, $type, $input)
	{
		if (!$input->has('field_object_tab'))
		{
			$input->put('field_object_tab', 'visibility');
		}
		
		$input->put('title', ['en' => 'Active']);
		$input->put('title_list', ['en' => 'Active']);
		$input->put('code', 'active');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_delete', 0);
		$input->put('allow_create', 1);
		$input->put('allow_update', 1);
		$input->put('field_order', 1);

		$table = \Telenok\Core\Model\Object\Type::find($input->get('field_object_type'))->code;
		$fieldName = 'start_at';
		
		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName);
			});
		}

		$fieldName = 'end_at';
		
		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->timestamp($fieldName);
			});
		}
		
		return parent::preProcess($model, $type, $input);
	}
}

?>