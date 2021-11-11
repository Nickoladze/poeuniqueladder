<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UniqueItem extends Model
{
	public function accounts()
	{
		return $this->belongsToMany("App\Account");
	}

    public function league()
	{
		return $this->belongsTo("App\League");
	}
}
