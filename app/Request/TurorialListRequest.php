<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class TurorialListRequest extends FormRequest
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
            'page' => 'required|integer',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'limit.required' => 'limit不能为空',
            'limit.integer' => 'limit必须是整型',

            'page.required' => 'page不能为空',
            'page.integer' => 'page必须是整型',
        ];
    }
}
