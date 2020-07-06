<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Constants;

/**
 * 状态常量
 * @author zxf
 */
class StatusConst
{
    /**
     * 状态[正常]
     * status_id
     * @var integer
     */
    const NORMAL = 1;
    /**
     * 状态[锁定]
     * status_id
     * @var integer
     */
    const LOCK = 2;

    /**
     * 状态[启用]
     * status_id
     * @var integer
     */
    const ON = 3;
    /**
     * 状态[停用]
     * status_id
     * @var integer
     */
    const OFF = 4;

    /**
     * 状态[成功]
     * status_id
     * @var integer
     */
    const SUCCESS = 5;
    /**
     * 状态[失败]
     * status_id
     * @var integer
     */
    const FAILD = 6;
}
