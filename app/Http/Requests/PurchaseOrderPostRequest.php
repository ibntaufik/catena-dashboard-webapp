<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderPostRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "account_vch_id"            => "required",
            "po_number"                 => "required|regex:/^[a-zA-Z0-9\/-]*$/",
            "po_date"                   => "required|date_format:Y-m-d",
            "expected_shipping_date"    => "required|date_format:Y-m-d",
            "item_type_id"              => "required|numeric",
            "item_description"          => "required|max:255",
            "item_quantity"             => "required|min:1|regex:/^\d+$/",
            "item_unit_id"              => "required|numeric",
            "item_unit_price"           => "required|regex:/^\d+$/",
            "item_max_quantity"         => "required|min:1|regex:/^\d+$/",
        ];
    }

    public function messages()
    {
        return [
            'account_vch_id.required'               => 'Please select VCH first',
            'po_number.required'                    => 'Please input PO number',
            'po_date.required'                      => 'Please input PO Date',
            'po_date.date_format'                   => 'Format date is invalid',
            'expected_shipping_date.required'       => 'Please input PO Date',
            'expected_shipping_date.date_format'    => 'Format date is invalid',
            'item_type_id.required'                 => 'Please select Item Type first',
            'item_description.required'             => 'Please describe item first',
        ];
    }
}
