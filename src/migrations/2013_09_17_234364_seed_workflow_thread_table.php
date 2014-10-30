<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWorkflowThreadTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('workflow_thread'))
		{
			$modelId = DB::table('object_type')->where('code', 'workflow_thread')->pluck('id');

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
						'multilanguage' => 1,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'required' => 1,
						'field_order' => 2,
						'string_list_size' => 50,
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
						'field_object_type' => $modelId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
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
						'field_object_type' => $modelId,
						'field_object_tab' => $tabAdditionallyId,
						'relation_one_to_many_belong_to' => DB::table('object_type')->where('code', 'user')->pluck('id'),
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'field_order' => 5,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['ru' => 'Оригинальный процесс', 'en' => 'Original process'],
						'title_list' => ['ru' => 'Оригинальный процесс', 'en' => 'Original process'],
						'key' => 'complex-array',
						'code' => 'original_process',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 0,
						'field_order' => 6,
					]
			); 

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['ru' => "Поток", 'en' => "Thread"],
						'title_list' => ['ru' => "Поток", 'en' => "Thread"],
						'key' => 'relation-one-to-many',
						'code' => 'thread',
						'active' => 1,
						'field_object_type' => 'workflow_process',
						'field_object_tab' => 'main',
						'show_in_form' => 1,
						'show_in_list' => 0,
						'allow_search' => 1,
						'multilanguage' => 0,
						'field_has' => 'workflow_thread',
						'allow_create' => 0,
						'allow_update' => 0,
						'field_order' => 8,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['ru' => "Этап процесса", 'en' => "Processing stage"],
						'title_list' => ['ru' => "Этап процесса", 'en' => "Processing stage"],
						'key' => 'string',
						'code' => 'processing_stage',
						'active' => 1,
						'field_object_type' => 'workflow_process',
						'field_object_tab' => 'main',
						'multilanguage' => 0,
						'show_in_form' => 1,
						'show_in_list' => 1,
						'allow_search' => 1,
						'required' => 0,
						'field_order' => 12,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['ru' => 'Активные элементы', 'en' => 'Active elements'],
						'title_list' => ['ru' => 'Активные элементы', 'en' => 'Active elements'],
						'key' => 'complex-array',
						'code' => 'processing_stencil',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'show_in_form' => 0,
						'show_in_list' => 0,
						'allow_search' => 0,
						'field_order' => 6,
					]
			);

			(new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
					[
						'title' => ['ru' => 'Состояние элементов', 'en' => 'State of elements'],
						'title_list' => ['ru' => 'Состояние элементов', 'en' => 'State of elements'],
						'key' => 'complex-array',
						'code' => 'processing_stencil_state',
						'active' => 1,
						'field_object_type' => $modelId,
						'field_object_tab' => $tabMainId,
						'show_in_form' => 0,
						'show_in_list' => 0,
						'allow_search' => 0,
						'field_order' => 6,
					]
			);
		}
	}
}

class SeedWorkflowThreadTableTranslation extends \Telenok\Core\Interfaces\Translation\Controller {

	public static $keys = [
		'workflow-thread' => [
			'title' => [
				'ru' => 'Выполняющийся бизнес-процесс',
				'en' => 'Launched business-process',
			],
			'title_list' => [
				'ru' => 'Выполняющийся бизнес-процесс',
				'en' => 'Launched business-process',
			],
			'field' => [
				'process' => [
					'ru' => "Процесс",
					'en' => "Process",
				],
			],
		]
	];

}
