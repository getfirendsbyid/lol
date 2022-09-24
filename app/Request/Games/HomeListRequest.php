<?php

declare(strict_types=1);

namespace App\Request\Games;

use Hyperf\Validation\Request\FormRequest;

class HomeListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'limit' => 'required|integer',

        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [

            'limit.required' => 'limit长度不能超过10',
            'limit.integer' => 'limit必须是整数',
        ];
    }
}
