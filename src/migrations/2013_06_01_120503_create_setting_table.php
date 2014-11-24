<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('setting'))
		{
			Schema::create('setting', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->string('code')->nullable()->unique('code');
				$table->mediumText('value')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start');
				$table->timestamp('active_at_end');
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null);
				$table->timestamp('locked_at');
				$table->timestamp('locked_at');
			});
		}
	}

}
