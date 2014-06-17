<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTable extends Migration {

	public function up()
	{
		if (!Schema::hasTable('page'))
		{
			Schema::create('page', function(Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();
				$table->text('title')->nullable()->default(null);
				$table->text('title_ceo')->nullable()->default(null);
				$table->text('description_ceo')->nullable()->default(null);
				$table->string('template_view')->nullable()->default(null);
				$table->text('template_code')->nullable()->default(null);
				$table->string('url_pattern')->nullable()->default(null);
				$table->string('url_redirect')->nullable()->default(null);
				$table->integer('page_page_controller')->unsigned()->nullable()->default(null);
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
