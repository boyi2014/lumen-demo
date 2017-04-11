<?php

namespace App\Repositories;

use App\Models\UserScore;

class UserScoreRepository
{
   private $userScore;
   
   public function __construct(UserScore $userScore)
   {
   		$this->userScore = $userScore;
   }
   
   public function getScoreByUserId( $userID )
   {
   		return $this->userScore->getScoreByUserID( $userID )->get();
   }
   
}
