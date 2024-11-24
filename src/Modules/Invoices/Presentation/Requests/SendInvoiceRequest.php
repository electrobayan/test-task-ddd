<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => 'string|max:255',
            'message' => 'string|max:255',
        ];
    }
}
