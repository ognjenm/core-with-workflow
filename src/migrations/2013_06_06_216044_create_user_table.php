<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('user'))
		{
			Schema::create('user', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->string('title')->nullable()->default(null);
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->string('username')->nullable()->default(null);
				$table->string('usernick')->nullable()->default(null);
				$table->string('email')->nullable()->default(null);
				$table->string('remember_token')->nullable()->default(null);
				$table->string('password', 60)->nullable()->default(null);
				$table->longText('configuration');
				$table->integer('author_user_message')->unsigned()->nullable()->default(null);
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}
	}

}
