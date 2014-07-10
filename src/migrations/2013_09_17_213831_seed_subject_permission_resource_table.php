<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedSubjectPermissionResourceTable extends Migration {

	public function up()
	{
		$subjectPermissionResourceId = DB::table('object_type')->where('code', 'subject_permission_resource')->pluck('id');
 
		$tabMainId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
					'code' => 'main',
					'active' => 1,
					'tab_object_type' => $subjectPermissionResourceId,
					'tab_order' => 1
				]
		);

		$tabVisibleId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
					'code' => 'visibility',
					'active' => 1,
					'tab_object_type' => $subjectPermissionResourceId,
					'tab_order' => 2
				]
		);

		$tabAdditionallyId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
					'code' => 'additionally',
					'active' => 1,
					'tab_object_type' => $subjectPermissionResourceId,
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
					'field_object_type' => $subjectPermissionResourceId,
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
					'field_object_type' => $subjectPermissionResourceId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'required' => 1,
					'field_order' => 2,
					'string_list_size' => 150,
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
					'field_object_type' => $subjectPermissionResourceId,
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
					'field_object_type' => $subjectPermissionResourceId,
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
					'field_object_type' => $subjectPermissionResourceId,
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

		(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('acl.field.code'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('acl.field.code'),
					'key' => 'string',
					'code' => 'code',
					'active' => 1,
					'field_object_type' => $subjectPermissionResourceId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 0,
					'field_order' => 6,
				]
		);

		(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('acl.field.resource'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('acl.field.resource'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_resource',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $subjectPermissionResourceId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 8,
				]
		);
		
		(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('acl.field.subject'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('acl.field.subject'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_subject',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $subjectPermissionResourceId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);
		
		(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => SeedObjectPermissionResourceTableTranslation::get('acl.field.permission'),
					'title_list' => SeedObjectPermissionResourceTableTranslation::get('acl.field.permission'),
					'key' => 'relation-one-to-many',
					'code' => 'acl_permission',
					'active' => 1,
					'field_object_type' => 'object_sequence',
					'relation_one_to_many_has' => $subjectPermissionResourceId,
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);
	}

}

class SeedObjectPermissionResourceTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'acl' => [
			'title' => ['ru' => 'Объект-разрешение-ресурс', 'en' => 'Object-permission-resource'],
			'field' => [
				'code' => ['ru' => 'Код', 'en' => 'Code'],
				'permission' => ['ru' => 'Разрешение', 'en' => 'Permission'],
				'resource' => ['ru' => 'Ресурс', 'en' => 'Resource'],
				'subject' => ['ru' => 'Владелец', 'en' => 'Owner'],
			],
		]
	];

}
