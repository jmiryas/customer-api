<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        return $user != null && $user->tokenCan("create");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "*.customerId" => ["required", "integer"],
            "*.amount" => ["required", "numeric"],
            "*.status" => ["required", Rule::in(["billed", "paid", "void", "BILLED", "PAID", "VOID"])],
            "*.billedDate" => ["required", "date_format:Y-m-d H:i:s"],
            "*.paidDate" => ["date_format:Y-m-d H:i:s", "nullable"],
        ];
    }

    // Sebelum melakukan validasi, 
    // ubah data yang dikirimkan user terlebih dahulu
    // jika user mengirimkan data tersebut
    // Contoh: customerId menjadi customer_id

    protected function prepareForValidation()
    {
        $data = [];

        foreach ($this->toArray() as $item) {
            $item["customer_id"] = $item["customerId"] ?? null;
            $item["billed_date"] = $item["billedDate"] ?? null;
            $item["paid_date"] = $item["paidDate"] ?? null;

            $data[] = $item;
        }

        $this->merge($data);
    }
}
