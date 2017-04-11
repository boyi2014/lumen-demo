<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
   private $user;
   
   public function __construct(User $user)
   {
   		$this->user = $user;
   }
   
   public function store(  )
   {
//    		$objUser = $this->user->getUserById(1)->first();
//    		$objUser->nick_name = 'test-003';
//    		return $objUser->update();
   		return $this->user->query()->where('id', '=', '1')->update( ['nick_name'=>'博弈']);
   }
   
   public function getAgeLargerThan( $age )
   {
   		return $this->user->getAgerLargeThan($age)->get();
   }
   
   public function getUserById( $id )
   {
   		//return $this->user->findOrFail($id);
   		return $this->user->getUserById( $id )->get();
   }
   
   public function pagination( $params = [] )
   {
   		$page = empty($params['page']) ? config('paginate.page') : $params['page'];
	   	$size = empty($params['size']) ? config('paginate.size') : $params['size'];
	   	return $this->user->paginate($size, ['id', 'username', 'nick_name'], 
	   			'page', $page
	   	);
   }
}
