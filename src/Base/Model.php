<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Support\Facades\DB;

/**
 *
 * @author zxf
 * @date   2020年7月21日
 * @method static Model create(array $attributes = [])
 * @method static Model forceCreate(array $attributes)
 * @method static Model groupBy(array|string ...$groups)
 * @method static Model latest($column = null)
 * @method static Model oldest($column = null)
 * @method static Model orWhere($column, $operator = null, $value = null)
 * @method static Model orderBy(Closure|Builder|Expression|string $column, string $direction = 'asc')
 * @method static Model where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Model withCasts($casts)
 * @method static Model withOnly($relations)
 * @method static Model without($relations)
 * @method static Model|mixed unless($value, $callback, $default = null)
 * @method static Model|mixed when($value, $callback, $default = null)
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator cursorPaginate($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator simplePaginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Collection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null find($id, $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model|object|static|null first($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Model|object|static|null sole($columns = ['*'])
 * @method static \Illuminate\Support\Collection chunkMap(callable $callback, $count = 1000)
 * @method static \Illuminate\Support\Collection pluck($column, $key = null)
 * @method static boolean chunk($count, callable $callback)
 * @method static integer decrement($column, $amount = 1, array $extra = [])
 * @method static integer increment($column, $amount = 1, array $extra = [])
 * @method static integer update(array $values)
 * @method static integer upsert(array $values, $uniqueBy, $update = null)
 * @method static mixed delete()
 * @method static mixed value($column)
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

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
