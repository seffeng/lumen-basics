<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Seffeng\LaravelHelpers\Helpers\Xml;
use Illuminate\Support\Facades\Request;

class Response
{
    /**
     *
     * @var string
     */
    const FORMAT_JSON = 'json';
    /**
     *
     * @var string
     */
    const FORMAT_XML = 'xml';

    /**
     * 默认接口返回格式[json|xml]
     * @var string
     */
    protected $format = 'json';
    /**
     * 接口返回支持格式
     * @var array
     */
    protected $allowFormat = ['xml', 'json'];

    /**
     *
     * @var mixed|array
     */
    protected $content = [];
    /**
     *
     * @var array
     */
    protected $headers = [];
    /**
     *
     * @var integer
     */
    protected $status = 200;

    /**
     *
     * @author zxf
     * @date   2020年6月22日
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse
     */
    public function send()
    {
        if ($this->getIsResponseXml()) {
            return response(Xml::toXml($this->getContent()), $this->getStatus(), $this->getHeaders())->withHeaders(['Content-Type' => 'text/xml']);
        } else {
            return response()->json($this->getContent(), $this->getStatus(), $this->getHeaders());
        }
    }

    /**
     *
     * @author zxf
     * @date    2020年6月22日
     * @param  array $headers
     * @return \Seffeng\Basics\Base\Response
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年6月22日
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月22日
     * @param  mixed|array $content
     * @return \Seffeng\Basics\Base\Response
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月22日
     * @return mixed|array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月22日
     * @param  int $status
     * @return \Seffeng\Basics\Base\Response
     */
    public function setStatus(int $status)
    {
        $this->status;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年6月22日
     * @return number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月22日
     * @return string
     */
    public function getFormat()
    {
        $format = strtolower(Request::header('Format'));
        if (in_array($format, $this->allowFormat)) {
            $this->format = $format;
        }
        return $this->format;
    }

    /**
     *
     * @author zxf
     * @date   2020年6月22日
     * @return boolean
     */
    public function getIsResponseXml()
    {
        return $this->getFormat() == self::FORMAT_XML;
    }
}
