<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ];
    }
}
