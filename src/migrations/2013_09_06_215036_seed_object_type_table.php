<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectTypeTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
		{
			$modelTypeId = DB::table('object_type')->where('code', 'object_type')->pluck('id');
			$modelFieldId = DB::table('object_type')->where('code', 'object_field')->pluck('id');

			$tabMainId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
						'code' => 'main',
						'active' => 1,
						'tab_object_type' => $modelTypeId,
						'tab_order' => 1
					]
			);

			$tabVisibleId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
						'code' => 'visibility',
						'active' => 1,
						'tab_object_type' => $modelTypeId,
						'tab_order' => 2
					]
			);

			$tabAdditionallyId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
						'code' => 'additionally',
						'active' => 1,
						'tab_object_type' => $modelTypeId,
						'tab_order' => 3
					]
			);
			
			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.id'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.id'), JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'id',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_list' => 1,
						'show_in_form' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 1,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.title'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.title'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'title',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 1,
						'show_in_list' => 1,
						'show_in_form' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'required' => 1,
						'field_order' => 2,
						'string_list_size' => 50,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.title_list'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.title_list'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'title_list',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 1,
						'show_in_list' => 0,
						'show_in_form' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'required' => 1,
						'field_order' => 3,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.treeable'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.treeable'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'treeable',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_list' => 0,
						'show_in_form' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 4,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.code'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.code'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'code',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 5,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.field'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.field'), JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'field',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => $modelFieldId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 0,
						'allow_choose' => 0,
						'allow_update' => 1,
						'field_order' => 6,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.class_model'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.class_model'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'class_model',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 0,
						'allow_delete' => 0,
						'field_order' => 7,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.class_controller'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectTypeTableTranslation::get('objects-type.field.class_controller'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'class_controller',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 8,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Active'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Active'], JSON_UNESCAPED_UNICODE),
						'key' => 'active',
						'code' => 'active',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabVisibleId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 9,
					]
			);


			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'tab',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_tab')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_choose' => 1,
						'allow_update' => 0,
						'field_order' => 10,
					]
			); 

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => 'Объекты', 'en' => 'Objects'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Объекты', 'en' => 'Objects'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'sequences',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 0,
						'allow_choose' => 0,
						'allow_update' => 1,
						'field_order' => 11,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
						'key' => 'created-by',
						'code' => 'created_by_user',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 12,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
						'key' => 'updated-by',
						'code' => 'updated_by_user',
						'active' => 1,
						'field_object_type' => $modelTypeId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 13,
					]
			);
		}
	}

}

class SeedObjectTypeTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'objects-type' => [
			'id' => [
				'ru' => '№',
				'en' => '№',
			],
			'title' => [
				'ru' => 'Тип объекта',
				'en' => 'Type of object',
			],
			'title_list' => [
				'ru' => 'Типы объектов',
				'en' => 'Types of objects',
			],
			'objects-field-title' => [
				'ru' => 'Поле объекта',
				'en' => 'Field of object',
			],
			'objects-field-title_list' => [
				'ru' => 'Поля объектов',
				'en' => 'Fields of objects',
			],
			'field' => [
				'id' => [
					'ru' => "№",
					'en' => "№",
				],
				'title' => [
					'ru' => "Заголовок",
					'en' => "Title",
				],
				'title_list' => [
					'ru' => "Заголовок списка",
					'en' => "Title of list",
				],
				'code' => [
					'ru' => "Код",
					'en' => "Code",
				],
				'field' => [
					'ru' => "Поле",
					'en' => "Field",
				],
				'treeable' => [
					'ru' => "Деревообразный",
					'en' => "Treeable",
				],
				'class_model' => [
					'ru' => "Класс модели",
					'en' => "Class of model",
				],
				'class_controller' => [
					'ru' => "Класс формы",
					'en' => "Class of form",
				],
			],
		]
	];

}
