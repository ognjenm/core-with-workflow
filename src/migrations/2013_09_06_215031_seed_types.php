<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTypes extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_type') && Schema::hasTable('object_field'))
		{
			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Тип объекта', 'en' => 'Type object'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Тип объекта', 'en' => 'Type object'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_type',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Object\Type',
						'class_controller' => '\Telenok\Core\Module\Objects\Type\Controller',
						'treeable' => 1,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Поле объекта', 'en' => 'Field object'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Поле объекта', 'en' => 'Field object'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_field',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Object\Field',
						'class_controller' => '\Telenok\Core\Module\Objects\Field\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Хранилище', 'en' => 'Repository'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Хранилище', 'en' => 'Repository'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_sequence',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Object\Sequence',
						'class_controller' => '\Telenok\Core\Module\Objects\Sequence\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Вкладка', 'en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Вкладка', 'en' => 'Tab'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_tab',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Object\Tab'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Пользователь', 'en' => 'User'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Пользователь', 'en' => 'User'], JSON_UNESCAPED_UNICODE),
						'code' => 'user',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\User\User',
						'multilanguage' => 0,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Версия', 'en' => 'Version'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Версия', 'en' => 'Version'], JSON_UNESCAPED_UNICODE),
						'code' => 'object_version',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Object\Version',
						'class_controller' => '\Telenok\Core\Module\Objects\Version\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Ресурс', 'en' => 'Resource'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Ресурс', 'en' => 'Resource'], JSON_UNESCAPED_UNICODE),
						'code' => 'resource',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Security\Resource',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Настройки', 'en' => 'Setting'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Настройки', 'en' => 'Setting'], JSON_UNESCAPED_UNICODE),
						'code' => 'setting',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\System\Setting',
						'class_controller' => '\Telenok\Core\Module\System\Setting\Controller',
						'multilanguage' => 1,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Роль', 'en' => 'Role'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Роли пользователей', 'en' => 'Role of user'], JSON_UNESCAPED_UNICODE),
						'code' => 'role',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Security\Role',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Группа', 'en' => 'Group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа пользователей', 'en' => 'Group of user'], JSON_UNESCAPED_UNICODE),
						'code' => 'group',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\User\Group',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Разрешение', 'en' => 'Permission'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Разрешение', 'en' => 'Permission'], JSON_UNESCAPED_UNICODE),
						'code' => 'permission',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Security\Permission',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Объект-разрешение-ресурс', 'en' => 'Object-permission-resource'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Объект-разрешение-ресурс', 'en' => 'Object-permission-resource'], JSON_UNESCAPED_UNICODE),
						'code' => 'subject_permission_resource',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Security\SubjectPermissionResource',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Сообщение', 'en' => 'Message'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Сообщение', 'en' => 'Message'], JSON_UNESCAPED_UNICODE),
						'code' => 'user_message',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\User\UserMessage',
						'multilanguage' => 0,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Язык', 'en' => 'Language'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Список языков', 'en' => 'List of languages'], JSON_UNESCAPED_UNICODE),
						'code' => 'language',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\System\Language',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Папка', 'en' => 'Folder'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Папка', 'en' => 'Folder'], JSON_UNESCAPED_UNICODE),
						'code' => 'folder',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\System\Folder',
						'treeable' => 1
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Бизнес-процесс: статус', 'en' => 'Business-process: status'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Бизнес-процесс: статус', 'en' => 'Business-process: status'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_status',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Workflow\Status',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Бизнес-процесс', 'en' => 'Business-process'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Бизнес-процесс', 'en' => 'Business-process'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_process',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Workflow\Process',
						'class_controller' => '\Telenok\Core\Module\Workflow\Process\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Событие бизнес-процесса', 'en' => 'Event of business-process'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Событие бизнес-процесса', 'en' => 'Event of business-process'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_event',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Workflow\Event',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Бизнес-процесс: события и ресурсы', 'en' => 'Business-process: events and resources'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Бизнес-процесс: события и ресурсы', 'en' => 'Business-process: events and resources'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_event_resource',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Workflow\EventResource',
						'multilanguage' => 1,
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Выполняющийся бизнес-процесс', 'en' => 'Launched business-process'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Выполняющийся бизнес-процесс', 'en' => 'Launched business-process'], JSON_UNESCAPED_UNICODE),
						'code' => 'workflow_thread',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Workflow\Thread'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Страница', 'en' => 'Page'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Страница', 'en' => 'Page'], JSON_UNESCAPED_UNICODE),
						'code' => 'page',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\Page'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Группа модулей', 'en' => 'Module group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа модулей', 'en' => 'Module group'], JSON_UNESCAPED_UNICODE),
						'code' => 'module_group',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\ModuleGroup'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Модуль', 'en' => 'Module'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Модуль', 'en' => 'Module'], JSON_UNESCAPED_UNICODE),
						'code' => 'module',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\Module'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Группа виджетов', 'en' => 'Widget group'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Группа виджетов', 'en' => 'Widget group'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget_group',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\WidgetGroup'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Виджет', 'en' => 'Widget'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Виджет', 'en' => 'Widget'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\Widget'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Контроллер страницы', 'en' => 'Page controller'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Контроллер страницы', 'en' => 'Page controller'], JSON_UNESCAPED_UNICODE),
						'code' => 'page_controller',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\PageController'
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Виджет на странице', 'en' => 'Widget on page'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Виджет на странице', 'en' => 'Widget on page'], JSON_UNESCAPED_UNICODE),
						'code' => 'widget_on_page',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\Web\WidgetOnPage',
						'class_controller' => '\Telenok\Core\Module\Web\WidgetOnPage\Controller',
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Расширение файла', 'en' => 'File extension'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Расширение файла', 'en' => 'File extension'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_extension',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\File\FileExtension', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Mime type файла', 'en' => 'File mime type'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Mime type файла', 'en' => 'File mime type'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_mime_type',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\File\FileMimeType', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Файл', 'en' => 'File'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Файл', 'en' => 'File'], JSON_UNESCAPED_UNICODE),
						'code' => 'file',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\File\File', 
					]
			);

			DB::table('object_type')->insertGetId(
					[
						'id' => DB::table('object_sequence')->insertGetId(['id' => null, 'class_model' => '\Telenok\Core\Model\Object\Type']),
						'title' => json_encode(['ru' => 'Категория файлов', 'en' => 'File category'], JSON_UNESCAPED_UNICODE),
						'title_list' => json_encode(['ru' => 'Категория файлов', 'en' => 'File category'], JSON_UNESCAPED_UNICODE),
						'code' => 'file_category',
						'active' => 1,
						'class_model' => '\Telenok\Core\Model\File\FileCategory', 
					]
			);
		}
	}

}