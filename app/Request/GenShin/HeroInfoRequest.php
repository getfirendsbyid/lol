<?php

declare(strict_types=1);

namespace App\Request\GenShin;

use Hyperf\Validation\Request\FormRequest;

class HeroInfoRequest extends FormRequest
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
            'id' => 'required|integer',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'id.required' => 'id不能为空',
            'id.integer' => 'id必须是整数',
        ];
    }
}
