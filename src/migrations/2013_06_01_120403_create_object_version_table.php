<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectVersionTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_version'))
		{
			Schema::create('object_version', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->integer('object_id');
				$table->integer('object_type_id');
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->mediumText('object_data')->nullable();
			});
		}
	}

}
