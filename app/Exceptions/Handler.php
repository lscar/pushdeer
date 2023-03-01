<?php

namespace App\Exceptions;

use App\Http\ReturnCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (BusinessException $e) {
            return Response::error($e->business->errors()->first(), ReturnCode::ARGS);
        });
    }

    public function invalid($request, ValidationException $exception)
    {
        return Response::error($exception->validator->errors()->first(), ReturnCode::ARGS);
    }

    public function unauthenticated($request, AuthenticationException $exception)
    {
        return Response::error('当前用户没有足够的权限访问此接口', ReturnCode::AUTH);
    }
}
