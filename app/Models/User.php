<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use MaiMeng\EloquentCache\Common\BaseModel;

use Lsxiao\JWT\Contracts\IClaimProvider;
use Illuminate\Support\Facades\Hash;

class User extends BaseModel implements  AuthenticatableContract, AuthorizableContract, IClaimProvider
{
	use Authenticatable, Authorizable;
    
    protected $needCache = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'username', 'email', 'nickName'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'deleted_at'
    ];
    
    /* 
    public function getTable()
    {
    	//echo 111;
    	$tableName = parent::getTable();
    	//var_dump('初始', $tableName, $this->id, '========' );
    	if( $this->id  == 20 )
    	{
    		$tableName .= '_20170410';
    		//var_dump('修改后', $tableName);
    	}
    	return $tableName;
    }
    */
    
    //Token中身份标识,一般设置为user id 即可
    public function getIdentifier()
    {
    	return $this->id;
    }
    
    //自定义的claims,无法覆盖预定义的claims
    public function getCustomClaims()
    {
    	//['name'=>'value','author'=>'lsxiao'] 必须是键值对形式
    	//return ['id'=>$this->id, 'nick_name'=>$this->nick_name];
    	return [ ];
    }
    
    public function getAuthPassword()
    {
    	return Hash::make( $this->password );
    }
    
    public function scopeGetAgerLargeThan($query, $age=0){
    	return $query->where('age', '>', $age);
    }
    
    public function scopeGetUserById($query, $id=0 )
    {
//     	return $query;
    	return $query->where('id', '=', $id);
    }
}
