<?php

namespace Telenok\Core\Field\System\DeletedBy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

	protected $key = 'deleted-by';
    protected $routeListTitle = "cmf.field.relation-one-to-many.list.title";

	public function fill($field, $model, $input)
	{
		if ($model->exists)
		{
			$model->deleted_by_user = \Auth::check() ? \Auth::user()->id : 0;
		}

		return parent::fill($field, $model, $input);
	}

	public function preProcess($model, $type, $input)
	{
		$modelField = \Telenok\Object\Field::where(function($query)
				{
					$query->where('code', 'deleted_by');
					$query->where('field_object_type', \Telenok\Object\Type::where('code', 'user')->first()->getKey());
				})->first();

		$field_object_type = $input->get('field_object_type');

		$input->put('id', $modelField ? $modelField->getKey() : null);
		$input->put('title', ['en' => 'Deleted by']);
		$input->put('title_list', ['en' => 'Deleted by']);
		$input->put('code', 'deleted_by');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_delete', 0);
		$input->put('allow_create', 0);
		$input->put('allow_update', 1);
		$input->put('field_object_type', \Telenok\Object\Type::where('code', 'user')->first()->getKey());
		$input->put('relation_one_to_many_has', \Telenok\Object\Type::where('code', 'object_sequence')->first()->getKey());

		$toSave = [
			'title' => $input->get('title'),
			'title_list' => $input->get('title_list'),
			'key' => $this->getKey(),
			'code' => 'deleted_by_user',
			'active' => 1,
			'field_object_type' => $field_object_type,
			'relation_one_to_many_belong_to' => \Telenok\Object\Type::where('code', 'user')->first()->getKey(),
			'show_in_list' => $input->get('show_in_list'),
			'show_in_form' => $input->get('show_in_form'),
			'allow_search' => $input->get('allow_search'),
			'allow_delete' => $input->get('allow_delete'),
			'multilanguage' => $input->get('multilanguage'),
			'allow_create' => $input->get('allow_create'),
			'allow_update' => $input->get('allow_update'),
		];

		$validator = $this->validator(new \Telenok\Object\Field(), $toSave, []);

		if ($input->get('create_belong') !== false && $validator->passes())
		{
			\Telenok\Object\Field::create($toSave);
		}

		$table = \Telenok\Object\Type::find($input->get('field_object_type'))->code;
		$fieldName = 'deleted_by_user';
		
		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->default(0);
			});
		}

		return parent::preProcess($model, $type, $input);
	}

}

?>