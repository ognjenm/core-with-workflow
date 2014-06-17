<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWidgetTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('widget'))
		{
			Schema::create('widget', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->text('title')->nullable()->default(null);
				$table->string('controller_class')->nullable()->default(null);
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}
	}

}
