<?php

namespace Telenok\Core\Field\System\LockedBy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\Checkbox\Controller {

	protected $key = 'locked-by';

	public function preProcess1111111($model, $type, $input)
	{
		$modelField = \Telenok\Object\Field::where(function($query) use ($input)
				{
					$query->where('code', 'locked_by');
					$query->where('field_object_type', $input->get('field_object_type'));
				})->first();

		$input->get('id', $modelField ? $modelField->getKey() : null);
		$input->get('title', ['en' => 'Locked']);
		$input->get('title_list', ['en' => 'Locked']);
		$input->get('code', 'locked_by');
		$input->get('active', 1);
		$input->get('multilanguage', 0);
		$input->get('show_in_list', 0);
		$input->get('show_in_form', 1);
		$input->get('allow_search', 1);
		$input->get('allow_delete', 0);
		$input->get('allow_create', 0);
		$input->get('allow_update', 1);

		return parent::preProcess($model, $type, $input);
	}

}

?>