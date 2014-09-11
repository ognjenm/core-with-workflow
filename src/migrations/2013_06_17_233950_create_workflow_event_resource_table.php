<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkflowEventResourceTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('workflow_event_resource'))
		{
			Schema::create('workflow_event_resource', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->string('title')->nullable();
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
