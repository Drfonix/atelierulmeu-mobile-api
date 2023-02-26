<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

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

    public function render($request, Throwable $exception)
    {
        if($exception instanceof AuthenticationException || $request->expectsJson()) {
            return response()->json([
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method not allowed'
            ], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof ValidationException && $request->isJson()) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'errors' => $exception->getMessage(),
                    'fields' => $exception->validator->getMessageBag()->toArray()
                ]
            ], JsonResponse::HTTP_PRECONDITION_FAILED);
        }

        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $statusCode = $exception->getStatusCode() ?? JsonResponse::HTTP_NOT_FOUND;

        return response()->json(["error" => $exception->getMessage()], $statusCode);
//        return parent::render($request, $exception);
    }
}
