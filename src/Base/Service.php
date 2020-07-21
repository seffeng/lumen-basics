<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Seffeng\LaravelHelpers\Helpers\Str;

class Service
{
    /**
     *
     * @var array
     */
    protected $fillable = [];

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  LengthAwarePaginator $paginator
     * @return array
     */
    public function getPaginate(LengthAwarePaginator $paginator)
    {
        return [
            'totalCount' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'pageCount' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
        ];
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param int $totalCount
     * @param int $pageSize
     * @return array ['totalCount' => '剩余总数(含当前页)', 'currentPage' => '当前页(无意义)', 'pageCount' => '剩余页数', 'perPage' => '每次加载数']
     */
    public function calculatePaginate(int $totalCount, int $pageSize)
    {
        return [
            'totalCount' => $totalCount,
            'currentPage' => 0,
            'pageCount' => $totalCount > $pageSize ? floor($totalCount / $pageSize) : 0,
            'perPage' => $pageSize,
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @param string $name
     */
    public function openQueryLog(string $name = null)
    {
        Model::openQueryLog($name);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @return mixed
     */
    public function getQueryLog()
    {
        return Model::getQueryLog();
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateChatCode(int $length, bool $diff = false)
    {
        return Str::generateChatCode($length, $diff);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @return string
     */
    public function generateNumberCode(int $length)
    {
        return Str::generateNumberCode($length);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateStringCode(int $length, bool $diff = false)
    {
        return Str::generateStringCode($length, $diff);
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @param  array $fillable
     * @return static
     */
    public function setFillable(array $fillable = [])
    {
        $this->fillable = $fillable;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @param  array $item
     * @return array
     */
    public function filterByFillable(array $item)
    {
        if ($this->getFillable()) foreach ($item as $key => $val) {
            if (!in_array($key, $this->getFillable())) {
                unset($item[$key], $val);
            }
        }
        return $item;
    }
}
