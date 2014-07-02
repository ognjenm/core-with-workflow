<?php

namespace Telenok\Core\Field\System\UpdatedBy;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\RelationOneToMany\Controller {

	protected $key = 'updated-by';
    protected $routeListTitle = "cmf.field.relation-one-to-many.list.title";

    public function getModelField($model, $field)
    {
		return ['updated_by', 'updated_by_user'];
    } 

	public function fill($field, $model, $input)
	{
		$model->setAttribute('updated_by_user', \Auth::check() ? \Auth::user()->id : 0);

		return parent::fill($field, $model, $input);
	}

	public function preProcess($model, $type, $input)
	{  
		$translationSeed = $this->translationSeed();

		$input->put('title', array_get($translationSeed, 'model.updated_by'));
		$input->put('title_list', array_get($translationSeed, 'model.updated_by'));
		$input->put('code', 'updated_by_user');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_delete', 0);
		$input->put('allow_create', 0);
		$input->put('allow_update', 1); 
		$input->put('relation_one_to_many_belong_to', \DB::table('object_type')->where('code', 'user')->pluck('id'));
		$input->put('field_order', 2);
		
		$table = \Telenok\Object\Type::find($input->get('field_object_type'))->code;
		$fieldName = 'updated_by_user';
		
		if (!\Schema::hasColumn($table, $fieldName) && !\Schema::hasColumn($table, "`{$fieldName}`"))
		{
			\Schema::table($table, function(Blueprint $table) use ($fieldName)
			{
				$table->integer($fieldName)->unsigned()->default(0);
			});
		}

		return parent::preProcess($model, $type, $input);
	}
	
	public function postProcess($model, $type, $input)
	{
		return $this;
	}

	public function translationSeed()
	{
		return [
			'model' => [
				'updated_by' => ['en' => 'Updated by', 'ru' => 'Обновлено'],
			],
		];
	}

}

?>