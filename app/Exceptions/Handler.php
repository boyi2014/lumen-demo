<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Helpers;
use Lsxiao\JWT\Exception\TokenExpiredException;

class Handler extends ExceptionHandler
{
	use Helpers;
	
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
    	//自定义404页面
    	if( $e instanceof NotFoundHttpException )
    	{
    		return response(view('errors.404'), 404);
    	}
    	
    	if( $e instanceof BusinessException )
    	{
    		return $this->errorStr($e->getMessage(), $e->getCode()); 
    	}
    	
    	if( $e instanceof TokenExpiredException )
    	{
    		return $this->errorStr($e->getMessage(), $e->getCode());
    	}
    	
        return parent::render($request, $e);
    }
}
