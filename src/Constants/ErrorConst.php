<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Constants;

use Seffeng\LaravelHelpers\Helpers\Arr;

/**
 * 错误常量
 * @author zxf
 */
class ErrorConst
{
    /**
     * 无错误，正常返回
     * @var integer
     */
    const NOT = 0;

    /**
     * 默认错误
     * @var integer
     */
    const DEFAULT = 1;

    /**
     * 未登录
     * @var integer
     */
    const UNAUTHORIZED = 401;

    /**
     * 无权限
     * @var integer
     */
    const PERMISSION_DENIED = 403;

    /**
     * 资源不存在
     * @var integer
     */
    const NOT_FOUND = 404;

    /**
     * 请求方式不支持
     * @var integer
     */
    const METHOD_NOT_SUPPORTED = 405;

    /**
     * CSRF-TOKEN不匹配
     * @var integer
     */
    const CSRF_MISMATCH = 419;

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param int $code
     * @return string
     */
    public static function getError(int $code = null)
    {
        is_null($code) && $code = static::DEFAULT;
        return Arr::get(static::fetchNameItems(), $code, '未定义错误代码：( '. $code .' )！');
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            static::NOT => '操作成功！',
            static::DEFAULT => '服务器异常错误！',
            static::UNAUTHORIZED => '用户未登录！',
            static::PERMISSION_DENIED => '权限错误！',
            static::NOT_FOUND => '资源不存在！',
            static::METHOD_NOT_SUPPORTED => '请求方式不支持！',
            static::CSRF_MISMATCH => 'CSRF-TOKEN不匹配！',
        ];
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(static::fetchNameItems());
    }

    /**
     *
     * @author zxf
     * @date    2019年10月28日
     * @param mixed $data
     * @param string $message
     * @return array
     */
    public static function responseSuccess($data = [], string $message = 'success')
    {
        return [
            'status' => 'success',
            'data' => (is_null($data) || (is_array($data) && count($data) === 0)) ? new \stdClass() : $data,
            'message' => $message,
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年10月28日
     * @param  string $message
     * @param  mixed $data
     * @param  int $code
     * @return array
     */
    public static function responseError(string $message, $data = [], int $code = null)
    {
        return [
            'status' => 'error',
            'code' => is_null($code) ? static::DEFAULT : $code,
            'data' => (is_null($data) || (is_array($data) && count($data) === 0)) ? new \stdClass() : $data,
            'message' => $message,
        ];
    }
}
