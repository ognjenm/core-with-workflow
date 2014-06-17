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
                $table->string('title')->nullable()->default(null);
                $table->string('locale')->unique('locale');
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