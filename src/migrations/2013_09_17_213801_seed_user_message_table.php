<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedUserMessageTable extends Migration {

	public function up()
	{
		$modelUserId = DB::table('object_type')->where('code', 'user')->pluck('id');
		$modelUserMessageId = DB::table('object_type')->where('code', 'user_message')->pluck('id');

		$tabMainId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
					'code' => 'main',
					'active' => 1,
					'tab_object_type' => $modelUserMessageId,
					'tab_order' => 1
				]
		);

		$tabVisibleId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
					'code' => 'visibility',
					'active' => 1,
					'tab_object_type' => $modelUserMessageId,
					'tab_order' => 2
				]
		);

		$tabAdditionallyId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
					'code' => 'additionally',
					'active' => 1,
					'tab_object_type' => $modelUserMessageId,
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
					'field_object_type' => $modelUserMessageId,
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

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(['ru' => "Заголовок", 'en' => "Title"], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => "Заголовок", 'en' => "Title"], JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'title',
					'active' => 1,
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'required' => 1,
					'field_order' => 2,
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
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabVisibleId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'field_order' => 3,
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
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'field_order' => 4,
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
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'field_order' => 5,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserMessageTableTranslation::get('message.field.content'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserMessageTableTranslation::get('message.field.content'), JSON_UNESCAPED_UNICODE),
					'key' => 'text',
					'code' => 'content',
					'active' => 1,
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabMainId,
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

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserMessageTableTranslation::get('message.field.author'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserMessageTableTranslation::get('message.field.author'), JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-one',
					'code' => 'author',
					'active' => 1,
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabMainId,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'multilanguage' => 0,
					'relation_one_to_one_has' => $modelUserId,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 7,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserMessageTableTranslation::get('message.field.recepient'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserMessageTableTranslation::get('message.field.recepient'), JSON_UNESCAPED_UNICODE),
					'key' => 'relation-many-to-many',
					'code' => 'recepient',
					'active' => 1,
					'field_object_type' => $modelUserMessageId,
					'field_object_tab' => $tabMainId,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'multilanguage' => 0,
					'relation_many_to_many_has' => $modelUserId,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 8,
				]
		);

		if (Schema::hasTable('recepient_user_message'))
		{
			Schema::table('recepient_user_message', function(Blueprint $table)
			{
				$table->integer('viewed')->unsigned()->default(0);
			});
		}
	}

}

class SeedUserMessageTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'message' => [
			'title' => [
				'ru' => 'Сообщение',
				'en' => 'Message',
			],
			'title_list' => [
				'ru' => 'Сообщения',
				'en' => 'Messages',
			],
			'field' => [
				'title' => [
					'ru' => "Заголовок",
					'en' => "Title",
				],
				'content' => [
					'ru' => "Содержание",
					'en' => "Content",
				], 
				'author' => [
					'ru' => "Автор",
					'en' => "Author",
				], 
				'recepient' => [
					'ru' => "Получатель",
					'en' => "Recepient",
				], 
			],
		]
	];

}
