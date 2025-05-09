<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
        public function authorize()
    {
        return true;
    }

        public function rules(): array
    {
        return [
            'payment_method' => 'required|in:credit_card,convenience_store',

        ];
    }
}
