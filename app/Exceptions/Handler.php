<?php

// Define the namespace
namespace App\Exceptions;

// Include any other namespaces
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Support\Exceptions\AppException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Contracts\Validation\ValidationException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        ValidationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(\Exception $e)
    {
        if ($this->shouldntReport($e) || ($e instanceof AppException && !$e->shouldReport())) {
            return;
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @throws AppException
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
           return parent::render($request, $e);

           if($e instanceof AppException){
              return $this->returnAppException($e);
           }
           if ($e instanceof HttpException && $e->getStatusCode() == 503) {
               throw new AppException(AppExceptionType::$MAINTENANCE_MODE);
           }

           if($e instanceof NotFoundHttpException || $e instanceof MethodNotAllowedHttpException){
               throw new AppException(AppExceptionType::$INVALID_ENDPOINT);
           }
           if($e instanceof QueryException){
            if (App::environment('production')) {
                throw new AppException(AppExceptionType::$INVALID_PARAMETERS);
            } else {
                throw new AppException(AppExceptionType::$INVALID_PARAMETERS, array(
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings()
                ));
            }
           }
           if($e instanceof ModelNotFoundException){
               throw new AppException(AppExceptionType::$NOT_FOUND);
           }
           if($e instanceof TokenExpiredException){
               throw new AppException(AppExceptionType::$TOKEN_EXPIRED);
           }
           if($e instanceof TokenBlacklistedException){
               throw new AppException(AppExceptionType::$TOKEN_BLACKLISTED);
           }
           if($e instanceof TokenInvalidException){
               throw new AppException(AppExceptionType::$INVALID_TOKEN);
           }
           if($e instanceof PayloadException){
               throw new AppException(AppExceptionType::$PAYLOAD_EXCEPTION);
           }
           if($e instanceof JWTException){
               throw new AppException(AppExceptionType::$INVALID_TOKEN);
           }

    }

    /**
     * Return the App Exception.
     *
     * @param  AppException $exception
     * @return \Illuminate\Http\Response
     */
    private function returnAppException(AppException $exception)
    {
        $response = [
            'error' => [
                'message'   => $exception->getMessage(),
                'code'      => $exception->getCustomCode()
            ]
        ];

        if ($exception->getInfo()) {
            $response['error']['info'] = $exception->getInfo();
        }

        return response($response, $exception->getCode());
    }
}
