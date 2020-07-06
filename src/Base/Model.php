<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';


    /**
     *
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::performInsert()
     */
    protected function performInsert(Builder $query)
    {
        if (!$this->usesTimestamps()) {
            $time = time();
            if (!$this->exists && !is_null(static::CREATED_AT) && !$this->isDirty(static::CREATED_AT)) {
                $this->{static::CREATED_AT} = $time;
            }

            if (!is_null(static::UPDATED_AT) && !$this->isDirty(static::UPDATED_AT)) {
                $this->{static::UPDATED_AT} = $time;
            }
        }
        return parent::performInsert($query);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::performUpdate()
     */
    protected function performUpdate(Builder $query)
    {
        if (!$this->usesTimestamps()) {
            if (!is_null(static::UPDATED_AT) && !$this->isDirty(static::UPDATED_AT)) {
                $this->{static::UPDATED_AT} = time();
            }
        }
        return parent::performUpdate($query);
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @param  array $diffAttribute
     * @return array
     */
    public function diffChanges(array $diffAttribute = null)
    {
        $changes = [];
        is_null($diffAttribute) && $diffAttribute = array_keys($this->getAttributes());
        if ($diffAttribute) foreach ($diffAttribute as $attribute) {
            $value    = $this->getAttribute($attribute);
            $oldValue = $this->getOriginal($attribute);
            if ($oldValue != $value) {
                $changes[$attribute][] = $oldValue;
                $changes[$attribute][] = $value;
            }
        }
        return $changes;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     */
    public function loadDefaultValue()
    {
        //
    }

    /**
     * 开启查询日志
     * @author zxf
     * @date    2019年11月15日
     * @param string $name
     */
    public static function openQueryLog(string $name = null)
    {
        DB::connection($name)->enableQueryLog();
    }

    /**
     * 获取日志
     * @author zxf
     * @date    2019年11月15日
     * @return mixed
     */
    public static function getQueryLog()
    {
        return DB::getQueryLog();
    }

    /**
     *
     * @author zxf
     * @date    2019年12月17日
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->getConnection()->getTablePrefix();
    }
}
