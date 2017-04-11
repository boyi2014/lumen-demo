<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\BusinessException as BusinessException;
use App\Http\Controllers\Common\BaseController;
use App\Models\User as User;
use App\Repositories\UserRepository as UserRepository;
use App\Repositories\UserScoreRepository;
use Illuminate\Http\Request as Request;
use Cache;
use DB;
use Illuminate\Support\Facades\Log;
// use Log;

use Lsxiao\JWT\Util\Parser;
use function Qiniu\base64_urlSafeDecode;

class UserController extends BaseController
{
	protected $user;
	protected $userScore;
	
	public function __construct(UserRepository $user, UserScoreRepository $userScore)
	{
		$this->user = $user;
		$this->userScore = $userScore;
	}
	
	public function showProfile(Request $request, $id){
		var_dump($id);
		var_dump($request->all());
		var_dump( $request->url() );
		
		return view('user.profile', ['data'=>['id'=>222, 'name'=>'张三']]);
	}
	
	public function show($id)
	{
		echo  md5(uniqid());
// 		$insertFlag = DB::insert("insert into users(username, password, nick_name, email, createTime ) values(?, ?, ?, ?, ?)", 
// 				['18861181266', '123456', '张三', 'zhangshan@163.com', date('Y-m-d H:i:s')]);
// 		var_dump($insertFlag);

// 		$insertFlag = DB::table('users')->insert([
// 				['username'=>'13112345678', 'password'=>'111111', 'nick_name'=>'李斯', 'email'=>'lishi@qq.com', 'createTime'=>date('Y-m-d H:i:s')],
// 				['username'=>'15212345678', 'password'=>'222222', 'nick_name'=>'王武', 'email'=>'wangwu@163.com', 'createTime'=>date('Y-m-d H:i:s')]
// 			]
// 		);
// 		var_dump('批量插入', $insertFlag);

// 		$id = DB::table('users')->insertGetId(
// 				['username'=>'18712345678', 'password'=>'333333', 'nick_name'=>'圣骑', 'email'=>'john@example.com', 'createTime'=>date('Y-m-d H:i:s')]
// 		);
// 		var_dump('插入', $id);

// 		$updateFlag = DB::table('users')->where('id', 6)->update(['nick_name'=>'冬瓜']);
// 		var_dump('更新', $updateFlag);
		
		/* $objDetailInfo = DB::select('select * from users where id = :id', [':id'=>6]);
		var_dump($objDetailInfo);
		
		$objDetailInfo = DB::table('users')->where('nick_name', '博弈')->first();
		var_dump($objDetailInfo);
		
		$email = DB::table('users')->where('nick_name', '博弈')->value('email');
		var_dump("电子邮箱", $email);
		
		$names = DB::table('users')->pluck('username', 'nick_name');
		var_dump($names);
		var_dump("总计", DB::table('users')->count());
		
		echo '<br/>';
		DB::table('users')->orderBy('id')->chunk(100, function ($users) {
			foreach ($users as $user) {
				var_dump($user);
			}
		});
		echo '<br/>';
		
		var_dump('查询部分字段', DB::table('users')->select('username', 'nick_name as name')->get());
		
		$users = DB::table('users')
						->leftjoin('device', 'users.id', '=', 'device.userID')
						->where('users.id', '<>', '1')
						->select('users.id', 'users.nick_name', 'device.id as deviceID')
						->get();
		var_dump("连表查询1", $users);
		
		$role = 'admin';
		$users = DB::table('users')
				->leftjoin('device', function($join){
						$join->on('users.id', '=', 'device.userID')
							->where('users.nick_name', 'like', '张%')
							->whereNotNull('device.deviceToken');
				})
				->where('users.id', '<>', '1')
				->whereNotIn('users.id', [1, 2, 3])
				->whereMonth('users.created_at', '3')
				->whereColumn('users.username', 'users.nick_name')
				->orWhere( function( $query ){
						$query->where('age', '<', 12)
						  	->where('sex', '=', 1);
				})
				->whereExists(function ($query) {
						$query->select(DB::raw(1))
							->from('device')
							->whereRaw('device.userID = users.id');
				})
				->when($role, function($query) use ($role){
					return $query->where('users.nick_name', '=', $role);
				}, function($query){
					return $query->whereNotNull('users.nick_name');
				})
				->select('users.id', 'users.nick_name', 'device.id as deviceID')
				->orderBy('id', 'asc')
				->offset(10)
				->limit(5)
				->get();
		var_dump("连表查询2", $users); */
		
		
// 		$insertFlag = DB::table('users_20170410')->insert([
// 						['username'=>'13112345678', 'password'=>'111111', 'nick_name'=>'李斯', 'email'=>'lishi@qq.com'],
// 					]
// 				);
		$id = 30;
		
// 		$user = User::find(20);
		$user = $this->user->getUserById($id);
		var_dump('查询结果：',$user);
		
// 		$objDetailInfo = User::find($id);
// 		$objDetailInfo = ['id'=>222, 'name'=>'张三'];
// 		//var_dump("注入", $this->user);
// 		return response(json_encode($objDetailInfo, JSON_UNESCAPED_UNICODE))
// 					->header('X-Header-One', 'Header Value-001')
// 					->header('X-Header-Two', 'Header Value-002')
					//->header('Content-type', 'application/json; charset=utf-8')
// 					;
		//return  view('user.profile', ['data'=>$objDetailInfo]);
	}
	
	
	public function getUsers( Request $request )
	{
		Log::info("日志测试");
// 		$users = $this->user->getUserById(1);
// 		if( $users->isEmpty() )
// 		{
// 			throw new BusinessException('用户不存在', 404);
// 		}
		//var_dump('更新前', $users);
// 		$updateFlag = $this->user->store();
// 		var_dump($updateFlag);
		
		$users = $this->user->getUserById(1);
		//var_dump('更新后', $users);
		
		$this->userScore->getScoreByUserId(43424);
		//var_dump($users);
		
// 		$user->nick_name = '博弈_002';
// 		$user->age = 20;
// 		$user->save();
// 		echo '用户[id=1]：' . json_encode($users, JSON_UNESCAPED_UNICODE);

		//echo crc32('b91445e081397c5942e1ae9c35eb855bdfc41a3a');
		
		$key = 'maimeng-key';
// 		$aaa = Cache::store('redis')->put($key, '测试', 10);
		$aaa = Cache::put($key, '打得过打的费当', 5000);
// 		var_dump('flag:', $aaa);
		$value = Cache::get($key);
		//echo "缓存：{$value}";
		
		
		$age = $request->input('age');
		$results = $this->user->getAgeLargerThan($age);
		$this->validate($request, [
				'age' => 'required|min:0|max:100'
			]
			//,['age 必须大于等于12']
		);
		
// 		$results = $this->user->pagination( $request->all() );
		//var_dump($results);
		
		
		//var_dump( $results->modelKeys() );
		$data = 'eyJpc3MiOiJodHRwOi8vbWFpbWVuZy1ibG9nLmFwcHpkLm5ldC9hcGkvbG9naW4iLCJpYXQiOjE0OTE1NjEyNDMsImV4cCI6MTQ5MTU2NDg0MywicmV4cCI6MTQ5Mjc3MDg0MywibmJmIjoxNDkxNTYxMTgzLCJibGd0IjozMDAsInN1YiI6NCwianRpIjoiNThlNzZiMWI5Yjc0MCJ9';
		var_dump('base64:', base64_urlSafeDecode($data));
		echo '=====================';
		$token = Parser::parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbWFpbWVuZy1ibG9nLmFwcHpkLm5ldC9hcGkvbG9naW4iLCJpYXQiOjE0OTE1NjEyNDMsImV4cCI6MTQ5MTU2NDg0MywicmV4cCI6MTQ5Mjc3MDg0MywibmJmIjoxNDkxNTYxMTgzLCJibGd0IjozMDAsInN1YiI6NCwianRpIjoiNThlNzZiMWI5Yjc0MCJ9.ZjVkMDI4Mjk3MGYzMWQ1NjA4MDIxMzAzNGU0Y2RmMjU2M2RhMGY3YmYyY2E1YzNkODI0M2QxNTJlNDYyZDk0Mg');
		var_dump('subject：', $token->getClaim('sub')->getValue());
		var_dump($token);
		//var_dump($token->getPayload());
		return $this->succeed( $results );
	}
	
}