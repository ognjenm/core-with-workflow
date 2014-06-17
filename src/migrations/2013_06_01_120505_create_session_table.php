<?php

use Illuminate\Database\Migrations\Migration;

class CreateSessionTable extends Migration {

	public function up()
	{
		Schema::create('session', function($t)
		{
			$t->string('id')->unique();
			$t->text('payload');
			$t->integer('last_activity')->unsigned()->default(0);
		});
	}

}
