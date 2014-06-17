<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectTabTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('object_tab'))
		{
			Schema::create('object_tab', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->string('code')->nullable()->default(null);
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('tab_order')->unsigned()->nullable()->default(null);
				$table->integer('tab_object_type')->unsigned()->nullable()->default(null);
				$table->string('icon_class')->nullable()->default(null);
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}
	}

}
