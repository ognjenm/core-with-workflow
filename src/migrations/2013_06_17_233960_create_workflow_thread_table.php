<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkflowThreadTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('workflow_thread'))
		{
			Schema::create('workflow_thread', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->text('title')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('thread_workflow_process')->unsigned()->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null);
				$table->timestamp('locked_at');
			});
		}
	}

}
