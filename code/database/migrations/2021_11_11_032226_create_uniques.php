<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniques extends Migration
{
	public function up()
	{
		Schema::create("unique_items", function (Blueprint $table)
		{
			$table->id();
			$table->string("name");
			$table->string("icon");
			$table->string("base_type");
			$table->boolean("is_retired")->default(0);
			$table->timestamps();

			$table->index("name");
			$table->index("base_type");
		});
	}

	public function down()
	{
		Schema::dropIfExists("unique_items");
	}
}
