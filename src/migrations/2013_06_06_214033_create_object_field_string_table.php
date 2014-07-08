<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectFieldStringTable extends Migration {

	public function up()
	{
		if (Schema::hasTable('object_field'))
		{
			Schema::table('object_field', function($table)
			{
				if (!\Schema::hasColumn('object_field', 'string_default'))
				{
					$table->text('string_default')->nullable();
				}

				if (!\Schema::hasColumn('object_field', 'string_regex'))
				{
					$table->string('string_regex')->nullable()->default(null);
				}

				if (!\Schema::hasColumn('object_field', 'string_max'))
				{
					$table->integer('string_max')->unsigned()->default(255);
				}

				if (!\Schema::hasColumn('object_field', 'string_min'))
				{
					$table->integer('string_min')->unsigned();
				}

				if (!\Schema::hasColumn('object_field', 'string_password'))
				{
					$table->integer('string_password')->unsigned();
				}

				if (!\Schema::hasColumn('object_field', 'string_list_size'))
				{
					$table->integer('string_list_size')->unsigned();
				}
			});
		}
	}

}
