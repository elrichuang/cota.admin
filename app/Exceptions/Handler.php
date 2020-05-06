<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedHttpException && $request->expectsJson())
        {
            return responseFail('请先登录',50008);
        }

        if ($exception instanceof TokenExpiredException && $request->expectsJson())
        {
            return responseFail('登录已过期',50014);
        }

        if ($exception instanceof ValidationException && $request->expectsJson())
        {
            $errorsArray = $exception->errors();
            $errorsStrArray = [];
            foreach ($errorsArray as $errorKey => $errorItemArray) {
                $itemStr = $errorKey.'：'.implode('',$errorItemArray);
                $errorsStrArray[] = $itemStr;
            }
            return responseFail(implode('',$errorsStrArray));
        }

        if ($exception instanceof AuthenticationException && $request->expectsJson())
        {
            return responseFail($exception->getMessage(), $exception->getCode());
        }

        if ($exception instanceof NotFoundHttpException && $request->expectsJson())
        {
            return responseFail('找不到相关信息', $exception->getCode());
        }

        if ($exception instanceof ModelNotFoundException && $request->expectsJson())
        {
            return responseFail('找不到相关记录', $exception->getCode());
        }

        if ($exception instanceof HttpException && $request->expectsJson())
        {
            return responseFail($exception->getMessage());
        }

        if (app()->environment('production') && $request->expectsJson()) {
            return responseFail($exception->getMessage());
        }

        return parent::render($request, $exception);
    }
}
