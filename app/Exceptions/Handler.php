<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    //エラーハンドリング
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof CustomException) {
            return response()->json(['error' => 'カスタムエラーが発生しました'], 400);
        }

        return parent::render($request, $exception);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function report(Throwable $exception)
    {
        if ($exception instanceof SpecificException) {
            // 特定のアクションを実行
        }

        parent::report($exception);
    }

}
