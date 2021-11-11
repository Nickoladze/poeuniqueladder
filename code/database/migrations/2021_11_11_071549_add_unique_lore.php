<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueLore extends Migration
{
	public function up()
	{
		Schema::table("unique_items", function (Blueprint $table) {
			$table->text("flavor_text")->after("base_type");
		});
	}

	public function down()
	{
		Schema::table("unique_items", function (Blueprint $table) {
			$table->dropColumn("flavor_text");
		});
	}
}
