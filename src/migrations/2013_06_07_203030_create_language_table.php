<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration 
{
    public function up()
    {
        if (!Schema::hasTable('language')) 
        {
            Schema::create('language', function(Blueprint $table)
            {
                $table->increments('id');
                $table->timestamps();
                $table->softDeletes();
                $table->text('title')->nullable();
                $table->string('locale')->unique('locale');
				$table->integer('active')->unsigned()->nullable();
				$table->timestamp('active_at_start');
				$table->timestamp('active_at_end');
                $table->integer('created_by_user')->unsigned()->nullable();
                $table->integer('updated_by_user')->unsigned()->nullable(); 
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
				$table->integer('locked_by_user')->unsigned()->nullable()->default(null);
				$table->timestamp('locked_at');
            }); 
        }
    }
}