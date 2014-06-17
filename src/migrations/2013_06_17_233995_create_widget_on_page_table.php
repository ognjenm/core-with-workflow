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
				$table->text('title')->nullable()->default(null);
				$table->text('container')->nullable()->default(null);
				$table->text('structure')->nullable()->default(null);
				$table->string('key')->nullable()->default(null);
				$table->integer('order')->unsigned()->nullable()->default(null);
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
