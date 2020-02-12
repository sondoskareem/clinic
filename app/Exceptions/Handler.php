<?php

namespace App\Exceptions;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
class Handler extends ExceptionHandler
{
    use ApiResponser;

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
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        if($exception instanceof ModelNotFoundException ){
            $modelName =strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exist any {$modelName} with the specified identifier" , 404);
        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage() , 403);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("The specified URL cannot be found" , 404);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse("Unvalide method name" , 405);
        }
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage() , $exception->getStatusCode());
        }
        if($exception instanceof QueryException){
            $errorCode = $exception->errorInfo[1];
            if($errorCode == 1451){
                return $this->errorResponse('Cannot remove this resource permanently , Its related with other resources' , 409);
            }
        }
        /** this exception if the application try to redirect unauthenticated user to login route
        *  or any other not existing route
        *   if the is no token , this will trigger
        */
        if($exception instanceof RouteNotFoundException){
            return $this->errorResponse('Unauthenticated' , 401);
        }
      
        //if none of the above exceptions occure 
        // return $this->errorResponse('Unexpected Exception. Try later' , 500);
        // return $this->errorResponse($exception , 500);
        return parent::render($request, $exception);

    }
    
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        
        return $this->errorResponse('Unauthenticated' , 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
       $errors = $e->validator->errors()->getMessages();
       return $this->errorResponse($errors , 422);
    }
}
