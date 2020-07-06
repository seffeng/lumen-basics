<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Constants;

class DeleteConst
{
    /**
     * 是否删除[否]
     * delete_id
     * @var integer
     */
    const NOT = 1;
    /**
     * 是否删除[是]
     * delete_id
     * @var integer
     */
    const YES = 2;
    /**
     * 是否删除[系统删除]
     * delete_id
     * @var integer
     */
    const SYS = 3;
    /**
     * 是否删除[自主注销]
     * delete_id
     * @var integer
     */
    const SELF = 4;
}
