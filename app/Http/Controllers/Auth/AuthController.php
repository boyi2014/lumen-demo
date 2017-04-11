<?php
 
namespace App\Http\Controllers\Auth;
 
use App\Http\Controllers\Common\BaseController;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;

use Auth;
 
class AuthController extends BaseController {
 
    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        try
        {
            $this->validate($request, [
                'email' => 'required|email|max:255', 'password' => 'required',
            ]);
        }
        catch (HttpResponseException $e)
        {
            return response()->json([
                'error' => [
                    'message'     => 'Invalid auth',
                    'status_code' => IlluminateResponse::HTTP_BAD_REQUEST
                ]],
                IlluminateResponse::HTTP_BAD_REQUEST,
                $headers = []
            );
        }
 
        try
        {
            // attempt to verify the credentials and create a token for the user
	        $credentials = $this->getCredentials($request);
            if ( ! $token = Auth::attempt($credentials))
            {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch (JWTException $e)
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
 
        // all good so return the token
        return response()->json(compact('token'));
    }
 
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('email', 'password');
    }
}