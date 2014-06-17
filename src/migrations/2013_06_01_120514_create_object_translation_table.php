<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectTranslationTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_translation'))
		{
			Schema::create('object_translation', function(Blueprint $table)
			{
				$table->integer('translation_object_model_id')->unsigned()->default(0);
				$table->string('translation_object_field_code')->nullable()->default(null);
				$table->string('translation_object_language', 4)->nullable()->default(null);
				$table->text('translation_object_string')->nullable();
			});
		}
	}

}
