<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $response = [
            'status' => 'error',
            'message' => "Bad request",
            'data' => []
        ];
        $statusCode = JsonResponse::HTTP_BAD_REQUEST;

        if($exception instanceof AuthenticationException || $exception instanceof AuthorizationException) {
            $statusCode = JsonResponse::HTTP_UNAUTHORIZED;
            $response['message'] = 'Unauthorized';
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $response['message'] = 'Method not allowed';
            $statusCode = JsonResponse::HTTP_METHOD_NOT_ALLOWED;
        }

        if ($exception instanceof ValidationException && $request->isJson()) {
            $response['data'] = $exception->validator->getMessageBag()->toArray();
            $statusCode = JsonResponse::HTTP_PRECONDITION_FAILED;
        }

        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            $response['message'] = 'Resource not found';
            $statusCode = JsonResponse::HTTP_NOT_FOUND;
        }

        if(property_exists($exception,"getStatusCode")) {
            $statusCode = $exception->getStatusCode();
        }

        return response()->json($response, $statusCode);
    }
}
