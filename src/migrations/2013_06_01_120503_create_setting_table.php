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
				$table->string('code')->nullable()->default(null)->unique('code');
				$table->mediumText('value');
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
