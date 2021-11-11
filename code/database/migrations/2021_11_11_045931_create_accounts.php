<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccounts extends Migration
{
	public function up()
	{
		Schema::create("accounts", function (Blueprint $table)
		{
			$table->id();
			$table->string("name");
			$table->timestamps();
		});

		Schema::create("account_unique_item", function (Blueprint $table)
		{
			$table->integer("account_id")->unsigned();
			$table->integer("unique_item_id")->unsigned();
		});
	}

	public function down()
	{
		Schema::dropIfExists("accounts");
		Schema::dropIfExists("account_unique_item");
	}
}
