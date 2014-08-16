<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWidgetOnPageTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('widget_on_page'))
		{
			$modelId = DB::table('object_type')->where('code', 'widget_on_page')->pluck('id');

			$tabMainId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
						'code' => 'main',
						'active' => 1,
						'tab_object_type' => $modelId,
						'tab_order' => 1
					]
			);

			$tabVisibleId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
						'code' => 'visibility',
						'active' => 1,
						'tab_object_type' => $modelId,
						'tab_order' => 2
					]
			);

			$tabAdditionallyId = DB::table('object_tab')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
						'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
						'code' => 'additionally',
						'active' => 1,
						'tab_object_type' => $modelId,
						'tab_order' => 3
					]
			);
			
			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => "№", 'en' => "№"], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => "№", 'en' => "№"], JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'id',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 1,
					]
			);

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => "Заголовок", 'en' => "Title"], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => "Заголовок", 'en' => "Title"], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'title',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'required' => 1,
						'field_order' => 2,
						'string_list_size' => 50,
					]
			);

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => "Контейнер", 'en' => "Container"], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => "Контейнер", 'en' => "Container"], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'container',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'required' => 1,
						'field_order' => 3,
					]
			);

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => "Ключ виджета", 'en' => "Widget key"], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => "Ключ виджета", 'en' => "Widget key"], JSON_UNESCAPED_UNICODE),
						'key' => 'string',
						'code' => 'key',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'required' => 1,
						'field_order' => 4,
					]
			);

			DB::table('object_field')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['ru' => "Порядок", 'en' => "Order"], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => "Порядок", 'en' => "Order"], JSON_UNESCAPED_UNICODE),
						'key' => 'integer-unsigned',
						'code' => 'order',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabAdditionallyId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 5,
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
						'field_object_type' => $modelId,
						'field_object_tab' => $tabVisibleId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'field_order' => 6,
					]
			);

			DB::table('object_field')->insert(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
						'title' => json_encode(['en' => 'Structure', 'ru' => 'Структура'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['en' => 'Structure', 'ru' => 'Структура'], JSON_UNESCAPED_UNICODE),
						'key' => 'complex-array',
						'code' => 'structure',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'allow_create' => 1,
						'allow_update' => 1,
						'field_order' => 7,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['en' => 'Link to widget'],
						'title_list' => ['en' => 'Link to widget'],
						'key' => 'relation-one-to-many',
						'code' => 'widget_link',
						'active' => 1,
						'field_object_type' => 'widget_on_page',
						'field_object_tab' => 'main',
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'multilanguage' => 0,
						'relation_one_to_many_has' => 'widget_on_page',
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 10,
					]
			); 

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['en' => 'Widget'],
						'title_list' => ['en' => 'Widget'],
						'key' => 'relation-one-to-many',
						'code' => 'widget_language',
						'active' => 1,
						'field_object_type' => 'language',
						'field_object_tab' => 'main',
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'multilanguage' => 0,
						'relation_one_to_many_has' => 'widget_on_page',
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 13,
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
						'field_object_type' => $modelId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'field_order' => 14,
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
						'field_object_type' => $modelId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'field_order' => 15,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['en' => 'Widget'],
						'title_list' => ['en' => 'Widget'],
						'key' => 'relation-one-to-many',
						'code' => 'widget',
						'active' => 1,
						'field_object_type' => 'page',
						'field_object_tab' => 'additionally',
						'relation_one_to_many_has' => 'widget_on_page',
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'multilanguage' => 0,
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 8,
					]
			);

		}
	}
}
