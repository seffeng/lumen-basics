<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Exceptions;

use Throwable;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Seffeng\Basics\Constants\ErrorConst;
use Seffeng\Basics\Base\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     *
     * @var string
     */
    protected $asJson = false;

    /**
     *
     * @var ErrorConst
     */
    protected $errorClass = ErrorConst::class;

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
        //
    ];

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        return $this->renderException($request, $exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function renderException($request, $e)
    {
        if ($this->asJson || $request->expectsJson()) {
            $message = $e->getMessage();
            $exception = (config('app.debug') && $message) ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : [];

            if ($this->isHttpException($e)) {
                /**
                 * @var HttpException $e
                 */
                $errorCode = $e->getStatusCode() > 0 ? $e->getStatusCode() : $this->errorClass::DEFAULT;
            } else {
                $errorCode = $e->getCode() > 0 ? $e->getCode() : $this->errorClass::DEFAULT;
            }
            if ($e instanceof BaseException) {
                $data = $this->errorClass::responseError($message ? $message : $this->errorClass::getError($errorCode), $exception, $errorCode);
            } else {
                $data = $this->errorClass::responseError($this->errorClass::getError($errorCode), $exception, $errorCode);
            }

            $response = new Response();
            return $response->setContent($data)->setHeaders($this->errorClass::mergeHeaders())->send();
        } else {
            return parent::render($request, $e);
        }
    }
}
