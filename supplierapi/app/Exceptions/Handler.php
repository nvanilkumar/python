<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use App\Models\Error;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(\Exception $e)
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
    public function render($request, \Exception $e)
    {
      echo  $message = trim($e->getMessage());exit;
        $code = $e->getCode();
        $statusCode = 500;

        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();

            if ($code === 0) {
                $code = $statusCode;
            }

            if (!strlen($message)) {

                switch ($statusCode) {
                    case 404:
                        $message = 'Resource not found';
                        break;
                    case 405:
                        $message = 'Method not allowed';
                        break;
                    case 400:
                        $message = 'Malformed request';
                        break;
                    case 401:
                        $message = 'Unauthorized';
                        break;
                    case 403:
                        $message = 'Access denied';
                        break;
                    case 409:
                        $message = 'There was a conflict with the request';
                        break;
                    default:
                        $message = 'An unexpected error has occurred';
                        break;
                }
            }
        } else {
            $message = 'An unexpected error has occurred';
        }

        return response()->json(new Error($message, $code), $statusCode);
    }
}
