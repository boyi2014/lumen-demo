<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    
    public function showProfile( Request $request ){
    	var_dump($request->input('c'));
    	var_dump($request->all());
    	//$request->flash();
    	
    	//return response('aaaa', 200)->header('Content-Type', 'application/json');
    	//redirect('form')->withInput( $request->all() );
    	//return view('user.profile', ['data'=>['id'=>1, 'name'=>'ceshi']]);
    	return redirect()->route('profile', ['id'=>1, 'name'=>'ceshi'] );
    }
}
