<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Seffeng\Basics\Constants\TypeConst;
use Seffeng\LaravelHelpers\Helpers\Str;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Seffeng\LaravelHelpers\Helpers\Json;

/**
 *
 * @author zxf
 * @date    2019年11月6日
 * @property array $with
 * @property array $fillItems
 * @example
 *  $form = new FormRequest();
 *  $validator = Validator::make($form->load($request->all()), $form->rules(), $form->messages(), $form->attributes());
 *  $errors = $form->getErrorItems($validator);
 *  $form->getIsPass();
 *  $form->getFillItems();
 */
class FormRequest extends \Illuminate\Http\Request
{
    /**
     * fillable 参数格式
     * true-驼峰，false-下划线
     * 驼峰参数格式时$fillItems将同时存在驼峰和下划线两种值
     * @var boolean
     */
    protected $isCamel = false;

    /**
     *  过滤前后空格
     * @var boolean
     */
    protected $filter = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
    *
    * @var array
    */
    protected $fillItems = [];

    /**
     *
     * @var array
     */
    protected $with = [];

    /**
     *
     * @var string|array
     */
    protected $orderBy;

    /**
     * 排序请求参数，如：$_GET['orderBy']
     * @var string
     */
    protected $orderByField = 'orderBy';

    /**
     * 数据表主键，非主键排序时追加主键排序
     * @var string
     */
    protected $orderByPrimaryKey = 'id';

    /**
     *
     * @var string|array
     */
    protected $groupBy;

    /**
     *
     * @var array
     */
    protected $messageBag;

    /**
     *
     * @var string
     */
    protected $isPass = false;

    /**
     * 分页参数
     * @var string
     */
    protected $pageName = 'page';

    /**
     * 分页参数
     * @var string
     */
    protected $perPageName = 'perPage';

    /**
     * 每页默认数量
     * @var integer
     */
    protected $perPage = 10;

    /**
     *
     * @var integer
     */
    protected $page = 1;

    /**
     *
     * @var array
     */
    public $variables = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ':attribute不能为空！',
            //'min' => ':attribute至少:min位字符！',
            //'max' => ':attribute最多:max位字符！',
            //'between' => ':attribute必须:min~:max位字符！',
            'unique' => ':attribute已存在！',
            'integer' => ':attribute只能是数字！',
            'exists' => ':attribute不存在！',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     *  配置验证器实例。
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (false) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  array $with
     * @return static
     */
    public function setWith(array $with)
    {
        $this->with = $with;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  array|string $orderBy
     * @return static
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @return string|array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     *
     * @author zxf
     * @date   2020年7月22日
     * @param  array|string $orderBy
     * @return static
     */
    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年7月22日
     * @return string|array
     */
    public function getGroupBy()
    {
        return $this->groupBy;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @return array
     */
    public function getErrors($validator)
    {
        if (is_null($this->messageBag)) {
            if ($validator->passes()) {
                $this->isPass = true;
                $this->messageBag = [];
            } else {
                $this->messageBag = $validator->getMessageBag()->getMessages();
            }
        }
        return $this->messageBag;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @param  boolean $isOne 仅返回一条错误
     * @param  boolean $cover $isOne为true时无效，$isOne为false时，存在相同错误是否只显示为一条
     * @return string|null
     */
    public function getErrorsToString($validator, bool $isOne = false, bool $cover = false)
    {
        $messages = $this->getErrors($validator);
        if ($messages) {
            if ($isOne) {
                foreach ($messages as $message) {
                    return Arr::get($message, '0', '');
                }
            } else {
                $errors = [];
                if ($cover) {
                    $tmpItems = [];
                    foreach ($messages as $message) {
                        $tmpItems = Arr::merge($tmpItems, $message);
                    }
                    $tmpItems = array_unique($tmpItems);
                    foreach ($tmpItems as $message) {
                        $errors[] = $message;
                    }
                } else {
                    foreach ($messages as $message) {
                        $errors[] = implode(' ', $message);
                    }
                }
                return implode(' ', $errors);
            }
        }
        return null;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @param  boolean $isOne 仅返回一条错误
     * @param  boolean $cover $isOne为true时无效，$isOne为false时，存在相同错误是否只显示为一条
     * @return array
     */
    public function getErrorItems($validator, bool $isOne = false, bool $cover = false)
    {
        $messageItems = $this->getErrors($validator);
        if ($messageItems) {
            return [
                'data' => $messageItems,
                'message' => $this->getErrorsToString($validator, $isOne, $cover)
            ];
        }
        return [];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  array $params
     * @return object
     */
    public function load(array $params)
    {
        if ($this->fillable) foreach ($this->fillable as $key) {
            $value = Arr::get($params, $key);
            $this->filter && is_string($value) && $value = trim($value);
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = $value;
            }
            $this->fillItems[$key] = $value;
        }
        return $this->fillItems;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string|int $key
     * @param  mixed $defalut
     * @return mixed
     */
    public function getFillItems($key = null, $defalut = null)
    {
        if (is_null($key)) {
            return $this->fillItems;
        }
        return Arr::get($this->fillItems, $key, $defalut);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @param  string|integer $key
     * @param  mixed $value
     * @return boolean
     */
    public function setFillItem($key, $value)
    {
        if (array_search($key, $this->fillable) !== false) {
            $this->fillItems[$key] = $value;
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = $value;
            }
            return true;
        }
        return false;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @return boolean
     */
    public function getIsPass()
    {
        return $this->isPass;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @return boolean
     */
    public function getIsExport()
    {
        return $this->getFillItems('export') == 1;
    }

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return string
     */
    public function getPerPageName()
    {
        return $this->perPageName;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  int $perPage
     * @return static
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return number
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  int $page
     * @return static
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @return number
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     *
     * @author zxf
     * @date   2020年7月22日
     * @return static
     */
    public function loadVariables()
    {
        $this->variables = [
            'fillItems' => $this->getFillItems(),
            'groupBy'   => $this->getGroupBy(),
            'orderBy'   => $this->getOrderBy(),
            'page'      => $this->getPage(),
            'perPage'   => $this->getPerPage(),
            'with'      => $this->getWith()
        ];
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @return static
     */
    public function sortable()
    {
        $orderBy = $this->getFillItems($this->orderByField);
        $sort = (is_string($orderBy) && $orderBy !== '') ? Json::decode($orderBy) : [];
        if ($sort) {
            $key = $this->replaceSortKey(Arr::get($sort, 'key'));
            $value = $this->replaceSortValue(strtoupper(Arr::get($sort, 'value')));
            if ($key) {
                $orderBy = [$key => $value];
                $key !== $this->orderByPrimaryKey && $orderBy[$this->orderByPrimaryKey] = TypeConst::ORDERBY_DESC;
                $this->setOrderBy($orderBy);
            }
        }
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @param string $key
     * @return boolean
     */
    protected function replaceSortKey(string $key = null)
    {
        return (!is_null($key) && array_key_exists($key, $this->fetchSortKeyItems())) ? Arr::get($this->fetchSortKeyItems(), $key) : false;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @param string $sort
     * @return string
     */
    protected function replaceSortValue(string $sort = null)
    {
        return (!is_null($sort) && in_array($sort, [TypeConst::ORDERBY_ASC, TypeConst::ORDERBY_DESC])) ? $sort : TypeConst::ORDERBY_DESC;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @return array
     */
    protected function fetchSortKeyItems()
    {
        return [
            // '接收字段' => '数据库字段',
            // 'userId' => 'id',
            // 'createDate' => 'created_at'
        ];
    }
}
