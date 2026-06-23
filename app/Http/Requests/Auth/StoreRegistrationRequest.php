<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegistrationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom Vietnamese validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'       => 'Vui lòng nhập họ và tên.',
            'name.max'            => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required'      => 'Vui lòng nhập địa chỉ email.',
            'email.email'         => 'Địa chỉ email không hợp lệ.',
            'email.unique'        => 'Email này đã được sử dụng bởi tài khoản khác.',
            'email.lowercase'     => 'Email phải được nhập bằng chữ thường.',
            'password.required'   => 'Vui lòng nhập mật khẩu.',
            'password.confirmed'  => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
