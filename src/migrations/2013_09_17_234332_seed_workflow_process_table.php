<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWorkflowProcessTable extends Migration {

    public function up()
    {
        $modelTypeId = DB::table('object_type')->where('code', 'workflow_process')->pluck('id');

        $tabMainId = \SeedCommonFields::createTabMain($modelTypeId);
        $tabVisibleId = \SeedCommonFields::createTabVisible($modelTypeId);
        $tabAdditionallyId = \SeedCommonFields::createTabAdditionally($modelTypeId);

        \SeedCommonFields::alterId($modelTypeId, $tabMainId);
        \SeedCommonFields::alterTitle($modelTypeId, $tabMainId);
        \SeedCommonFields::alterActive($modelTypeId, $tabVisibleId);
        \SeedCommonFields::alterCreateUpdateBy($modelTypeId, $tabAdditionallyId);

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Процесс', 'en' => 'Process'],
                    'title_list' => ['ru' => 'Процесс', 'en' => 'Process'],
                    'key' => 'complex-array',
                    'code' => 'process',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 6,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => 'Событие-Oбъект', 'en' => 'Event-Object'],
                    'title_list' => ['ru' => 'Событие-Oбъект', 'en' => 'Event-Object'],
                    'key' => 'complex-array',
                    'code' => 'event_object',
                    'active' => 1,
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 0,
                    'show_in_list' => 0,
                    'allow_search' => 0,
                    'field_order' => 6,
                ]
        );

        (new \Telenok\Core\Model\Object\Field())->storeOrUpdate(
                [
                    'title' => ['ru' => "Схема без ошибок?", 'en' => "Scheme without errors?"],
                    'title_list' => ['ru' => "Схема без ошибок?", 'en' => "Scheme without errors?"],
                    'key' => 'select-one',
                    'code' => 'is_valid',
                    'select_one_data' => [
                        'title' => [
                            'en' => ['No', 'Yes'],
                            'ru' => ['Нет', 'Да'],
                        ],
                        'key' => [
                            0,
                            1,
                        ],
                        'default' => 0
                    ],
                    'active' => 1,
                    'field_view' => 'core::field.select-one.model-toggle-button',
                    'field_object_type' => $modelTypeId,
                    'field_object_tab' => $tabMainId,
                    'show_in_form' => 1,
                    'show_in_list' => 1,
                    'allow_search' => 1,
                    'allow_create' => 0,
                    'allow_update' => 0,
                    'field_order' => 7,
                ]
        );
    }

}
