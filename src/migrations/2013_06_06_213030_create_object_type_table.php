<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectTypeTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_type'))
		{
			Schema::create('object_type', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->text('title_list')->nullable();
				$table->string('code')->unique()->nullable()->default(null);
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->string('class_model')->nullable()->default(null);
				$table->string('class_controller')->nullable()->default(null);
				$table->integer('treeable')->unsigned()->default(0);
				$table->integer('multilanguage')->unsigned()->default(0);
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}
	}

}
