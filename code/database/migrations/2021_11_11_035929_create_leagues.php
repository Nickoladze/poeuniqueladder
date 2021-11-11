<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagues extends Migration
{
	public function up()
	{
		Schema::create("leagues", function (Blueprint $table)
		{
			$table->id();
			$table->string("name");
			$table->timestamps();
		});

		Schema::table("unique_items", function (Blueprint $table) {
			$table->integer("league_id")->unsigned()->nullable()->after("name");
			$table->index("league_id");
		});
	}

	public function down()
	{
		Schema::dropIfExists("leagues");

		Schema::table("unique_items", function (Blueprint $table) {
			$table->dropColumn("league_id");
		});
	}
}
