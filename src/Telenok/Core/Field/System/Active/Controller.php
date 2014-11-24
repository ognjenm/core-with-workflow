<?php

namespace Telenok\Core\Field\System\Active;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\Checkbox\Controller {

	protected $key = 'active';
	protected $specialField = ['active_default'];

	public function getModelAttribute($model, $key, $value, $field)
	{ 
		if ($key == 'active' && $value === null)
		{
			$value = $field->active_default;
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

		$model->setAttribute($key, $value);
    }

	public function preProcess($model, $type, $input)
	{
		$translationSeed = $this->translationSeed();

		$input->put('title', $translationSeed['active']);
		$input->put('title_list', $translationSeed['active']); 
		$input->put('code', 'active');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_create', 1);
		$input->put('allow_update', 1);
		$input->put('field_order', 1); 

		if (!$input->get('field_object_tab'))
		{
			$input->put('field_object_tab', 'visibility');
		}
		
		$tab = $this->getFieldTab($input->get('field_object_type'), $input->get('field_object_tab', 'visibility'));

		$input->put('field_object_tab', $tab->getKey());  
		
		return parent::preProcess($model, $type, $input);
	}

	public function translationSeed()
	{
		return [
            'active' => ['en' => 'Active', 'ru' => 'Активно'],
		];
	}

}

?>