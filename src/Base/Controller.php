<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;
use Seffeng\Basics\Constants\ErrorConst;
use Seffeng\Basics\Exceptions\BaseException;
use Seffeng\LaravelHelpers\Helpers\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Controller extends BaseController
{
    /**
     *
     * @var ErrorConst
     */
    protected $errorClass = ErrorConst::class;

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  array $data
     * @param  string $message
     * @param  array $headers
     * @param  integer $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = [], string $message = 'success', array $headers = [], int $code = null)
    {
        $response = new Response();
        $data = $this->errorClass::responseSuccess($data, $message, $code);
        return $response->setContent($data)->setHeaders($headers)->send();
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string $message
     * @param  array $data
     * @param  integer $code
     * @param  array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(string $message, $data = [], int $code = null, array $headers = [])
    {
        $response = new Response();
        $data = $this->errorClass::responseError($message, $data, $code);
        return $response->setContent($data)->setHeaders($headers)->send();
    }

    /**
     * 下载
     * @author zxf
     * @date    2019年11月06日
     * @param  mixed $data
     * @param  string $fileName
     * @param  array $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function responseDownload($data, string $fileName, array $headers = [])
    {
        return $this->streamDownload(function() use ($data) { echo $data; }, $fileName, $headers);
    }

    /**
     * Create a new streamed response instance as a file download.
     *
     * @param  \Closure  $callback
     * @param  string|null  $name
     * @param  array  $headers
     * @param  string|null  $disposition
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new StreamedResponse($callback, 200, $headers);

        if (! is_null($name)) {
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                $disposition,
                $name,
                $this->fallbackName($name)
            ));
        }

        return $response;
    }

    /**
     * Convert the string to ASCII characters that are equivalent to the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function fallbackName($name)
    {
        return str_replace('%', '', Str::ascii($name));
    }

    /**
     *
     * @author zxf
     * @date    2019年11月07日
     * @param  \Exception $e
     * @param  array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseException($e, array $headers = [])
    {
        $response = new Response();
        $message = ($e instanceof BaseException) ? $e->getMessage() : '';
        $data = $this->errorClass::responseError($message ? $message : $this->errorClass::getError(),
            config('app.debug') ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : []);

        Log::error($e->getMessage(), ['code' => $e->getCode(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        return $response->setContent($data)->setHeaders($headers)->send();
    }
}
