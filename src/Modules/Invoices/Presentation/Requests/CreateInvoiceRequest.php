<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'productLines' => 'nullable|array',
            'productLines.*.productName' => 'required|string|max:255',
            'productLines.*.quantity' => 'required|integer|min:1',
            'productLines.*.price' => 'required|integer|min:1',
        ];
    }
}
