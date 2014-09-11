<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWidgetOnPageTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('widget_on_page'))
		{
			Schema::create('widget_on_page', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->text('container')->nullable();
				$table->text('structure')->nullable();
				$table->string('key')->nullable();
				$table->integer('order')->unsigned()->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null);
				$table->timestamp('locked_at');
			});
		}
	}

}
