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
				$table->string('title')->nullable();
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->string('username')->nullable();
				$table->string('usernick')->nullable();
				$table->string('email')->nullable();
				$table->string('remember_token')->nullable();
				$table->string('password', 60)->nullable();
				$table->longText('configuration')->nullable();
				$table->integer('author_user_message')->unsigned()->nullable();
				$table->integer('created_by_user')->unsigned()->nullable();
				$table->integer('updated_by_user')->unsigned()->nullable();
				$table->integer('deleted_by_user')->unsigned()->nullable();
			});
		}
	}

}
