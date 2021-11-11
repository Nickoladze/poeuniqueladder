<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
	public function uniqueItems()
	{
		return $this->hasMany("App\UniqueItem");
	}
}
