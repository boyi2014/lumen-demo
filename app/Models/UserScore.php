<?php

namespace App\Models;

use App\Models\Common\BaseModel;

class UserScore extends BaseModel 
{
	protected $needCache = true;
	
	public function scopeGetScoreByUserId($query, $userID=0 )
	{
		return $query->where('userID', '=', $userID);
	}
}
