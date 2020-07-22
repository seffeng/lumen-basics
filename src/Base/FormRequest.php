<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Seffeng\LaravelHelpers\Helpers\Str;
use Seffeng\LaravelHelpers\Helpers\Arr;

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
     * @return \Seffeng\Basics\Base\FormRequest
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
     * @return \Seffeng\Basics\Base\FormRequest
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
     * @return \Seffeng\Basics\Base\FormRequest
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
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = Arr::get($params, $key);
            }
            $this->fillItems[$key] = Arr::get($params, $key);
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
     * @return \Seffeng\Basics\Base\FormRequest
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
     * @return \Seffeng\Basics\Base\FormRequest
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
     * @return \Seffeng\Basics\Base\FormRequest
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
}
