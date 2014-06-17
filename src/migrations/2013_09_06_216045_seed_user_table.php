<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedUserTable extends Migration {

	public function up()
	{
		$modelUserId = DB::table('object_type')->where('code', 'user')->pluck('id');

		$tabMainId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Main', 'ru' => 'Основное'], JSON_UNESCAPED_UNICODE),
					'code' => 'main',
					'active' => 1,
					'tab_object_type' => $modelUserId,
					'tab_order' => 1
				]
		);

		$tabVisibleId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Visibility', 'ru' => 'Видимость'], JSON_UNESCAPED_UNICODE),
					'code' => 'visibility',
					'active' => 1,
					'tab_object_type' => $modelUserId,
					'tab_order' => 2
				]
		);

		$tabAdditionallyId = DB::table('object_tab')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Tab']),
					'title' => json_encode(['en' => 'Additionally', 'ru' => 'Дополнительно'], JSON_UNESCAPED_UNICODE),
					'code' => 'additionally',
					'active' => 1,
					'tab_object_type' => $modelUserId,
					'tab_order' => 3
				]
		);
		
		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.id'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.id'), JSON_UNESCAPED_UNICODE),
					'key' => 'integer-unsigned',
					'code' => 'id',
					'active' => 1,
					'field_object_type' => $modelUserId,
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
					'title' => json_encode(SeedUserTableTranslation::get('user.field.title'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.title'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'title',
					'active' => 1,
					'field_object_type' => $modelUserId,
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

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.username'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.username'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'username',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 3,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.usernick'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.usernick'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'usernick',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 4,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.email'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.email'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'email',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 5,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.password'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.password'), JSON_UNESCAPED_UNICODE),
					'key' => 'string',
					'code' => 'password',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 6,
					'string_password' => 1,
				]
		); 

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.configuration'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.configuration'), JSON_UNESCAPED_UNICODE),
					'key' => 'complex-array',
					'code' => 'configuration',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabMainId,
					'multilanguage' => 0,
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_update' => 1,
					'field_order' => 7,
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
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabVisibleId,
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
					'field_order' => 8,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(['ru' => "Автор", 'en' => "Author"], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => "Автор", 'en' => "Author"], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-one',
					'code' => 'author_user_message',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_one_to_one_belong_to' => DB::table('object_type')->where('code', 'user_message')->pluck('id'),
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'multilanguage' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 8,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(['ru' => "Получатель", 'en' => "Recepient"], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['ru' => "Получатель", 'en' => "Recepient"], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-many-to-many',
					'code' => 'recepient_user_message',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_many_to_many_belong_to' => DB::table('object_type')->where('code', 'user_message')->pluck('id'),
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'multilanguage' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 9,
				]
		);

		DB::table('object_field')->insertGetId(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(SeedUserTableTranslation::get('user.field.group'), JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(SeedUserTableTranslation::get('user.field.group'), JSON_UNESCAPED_UNICODE),
					'key' => 'relation-many-to-many',
					'code' => 'group',
					'active' => 1,
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_many_to_many_has' => DB::table('object_type')->where('code', 'group')->pluck('id'),
					'multilanguage' => 0,
					'show_in_form' => 1,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_delete' => 0,
					'allow_create' => 1,
					'allow_choose' => 1,
					'allow_update' => 1,
					'field_order' => 7,
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
					'field_object_type' => $modelUserId,
					'field_object_tab' => $tabAdditionallyId,
					'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
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
					'title' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'key' => 'updated-by',
					'code' => 'updated_by_user',
					'active' => 1,
					'field_object_type' => $modelUserId,
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
					'title' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Created by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'created_by',
					'field_object_type' => DB::table('object_type')->where('code', 'user')->pluck('id'),
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
				]
		);

		DB::table('object_field')->insert(
				[
					'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Field']),
					'title' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'title_list' => json_encode(['en' => 'Updated by'], JSON_UNESCAPED_UNICODE),
					'key' => 'relation-one-to-many',
					'code' => 'updated_by',
					'field_object_type' => DB::table('object_type')->where('code', 'user')->pluck('id'),
					'relation_one_to_many_has' => DB::table('object_type')->where('code', 'object_sequence')->pluck('id'),
					'field_object_tab' => $tabAdditionallyId,
					'active' => 1,
					'multilanguage' => 0,
					'show_in_list' => 0,
					'show_in_form' => 1,
					'allow_search' => 1,
					'allow_delete' => 0,
				]
		);


		if (!Schema::hasTable('pivot_relation_m2m_group_user'))
		{
			Schema::create('pivot_relation_m2m_group_user', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->integer('group')->unsigned()->default(0);
				$table->integer('group_user')->unsigned()->default(0);
			});
		}
	}

}

class CreateUserTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'user' => [
			'title' => [
				'ru' => 'Пользователь',
				'en' => 'User',
			],
			'title_list' => [
				'ru' => 'Пользователи',
				'en' => 'Users',
			],
		]
	];

}

class SeedUserTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'user' => [
			'field' => [
				'id' => [
					'ru' => "№",
					'en' => "№",
				],
				'title' => [
					'ru' => "Заголовок",
					'en' => "Title",
				],
				'username' => [
					'ru' => "Логин",
					'en' => "Login",
				],
				'usernick' => [
					'ru' => "Ник",
					'en' => "Nick",
				],
				'email' => [
					'ru' => "Email",
					'en' => "Email",
				],
				'password' => [
					'ru' => "Пароль",
					'en' => "Password",
				],
				'group' => [
					'ru' => "Группа",
					'en' => "Group",
				], 
				'configuration' => [
					'ru' => "Конфигурация",
					'en' => "Configuration",
				], 
			],
		]
	];

}
