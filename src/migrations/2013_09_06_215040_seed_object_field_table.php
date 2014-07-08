<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedObjectFieldTable extends Migration {

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
						'tab_object_type' => $modelFieldId,
						'tab_order' => 1
					]
			);

			$tabVisibleId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
						'code' => 'visibility',
						'active' => 1,
						'tab_object_type' => $modelFieldId,
						'tab_order' => 2
					]
			);

			$tabAdditionallyId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
						'code' => 'additionally',
						'active' => 1,
						'tab_object_type' => $modelFieldId,
						'tab_order' => 3
					]
			);
			
			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.id'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.id'), JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'id',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
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
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.title'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.title'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'title',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 1,
						'show_in_form' => 1,
						'show_in_list' => 1,
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
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.title_list'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.title_list'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'title_list',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 1,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'required' => 1,
						'field_order' => 3,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.required'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.required'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'required',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 0,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'required' => 0,
						'field_order' => 3,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.code'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.code'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'code',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 4,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.field_object_type'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.field_object_type'), JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'field_object_type',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'relation_one_to_many_belong_to' => $modelTypeId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_choose' => 1,
						'allow_update' => 0,
						'field_order' => 5,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'key' => 'relation-one-to-many',
						'code' => 'field_object_tab',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'object_tab')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_choose' => 1,
						'allow_update' => 0,
					]
			); 
			
			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Order in field list'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Order in field list'], JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'field_order',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 6,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.multilanguage'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.multilanguage'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'multilanguage',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 7,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.rule'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.rule'), JSON_UNESCAPED_UNICODE),
						'key' => 'complex-array',
						'code' => 'rule',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 0,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
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
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabVisibleId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 9,
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
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 10,
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
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 11,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_create'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_create'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_create',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 12,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_choose'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_choose'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_choose',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 12,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_update'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_update'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_update',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 13,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_delete'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_delete'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_delete',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 14,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_sort'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_sort'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_sort',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 15,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_search'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.allow_search'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'allow_search',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 16,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.show_in_list'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.show_in_list'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'show_in_list',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 17,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.show_in_form'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.show_in_form'), JSON_UNESCAPED_UNICODE),
						'key' => 'checkbox',
						'code' => 'show_in_form',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 1,
						'checkbox_default' => 1,
						'field_order' => 18,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.key'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.key'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'key',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 0,
						'allow_delete' => 0,
						'allow_create' => 1,
						'allow_update' => 0,
						'field_order' => 19,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.description'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.description'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'description',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 1,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 20,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.css_class'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.css_class'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'css_class',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 21,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.icon_class'), JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(SeedObjectFieldTableTranslation::get('objects-field.field.icon_class'), JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'icon_class',
						'active' => 1,
						'field_object_type' => $modelFieldId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_delete' => 0,
						'field_order' => 22,
					]
			);
		}
	}

}

class SeedObjectFieldTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'objects-field' => [
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
				'key' => [
					'ru' => "Тип поля",
					'en' => "Type of field",
				],
				'field_object_type' => [
					'ru' => "Принадлежит типу",
					'en' => "Belong to type",
				],
				'multilanguage' => [
					'ru' => "Мультиязычное",
					'en' => "Multilanguage",
				],
				
				'rule' => [
					'ru' => "Правила проверки",
					'en' => "Validation rules",
				],
				
				'show_in_list' => [
					'ru' => "Показывать в списке",
					'en' => "Show in list",
				],
				'show_in_form' => [
					'ru' => "Показывать в форме",
					'en' => "Show in form",
				],
				'allow_create' => [
					'ru' => "Доступно при создании объекта",
					'en' => "Available at object creation",
				],
				'allow_choose' => [
					'ru' => "Доступно при выборе объекта",
					'en' => "Available at choosing object",
				],
				'allow_search' => [
					'ru' => "Разрешить искать по полю",
					'en' => "Available search by field",
				],
				'allow_update' => [
					'ru' => "Доступно при редактировании объекта",
					'en' => "Available at object editing",
				],
				'allow_delete' => [
					'ru' => "Можно удалить",
					'en' => "Сan delete",
				],
				'allow_sort' => [
					'ru' => "Cортировка в списке",
					'en' => "Sorting",
				],
				'description' => [
					'ru' => "Описание",
					'en' => "Description",
				],
				'css_class' => [
					'ru' => "CSS класс",
					'en' => "CSS class",
				],
				'icon_class' => [
					'ru' => "ICON класс",
					'en' => "ICON class",
				], 
				'required' => [
					'ru' => "Обязательно заполняется",
					'en' => "Required",
				], 
			],
		]
	];

}
