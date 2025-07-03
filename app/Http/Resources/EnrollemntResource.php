<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollemntResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->user->name,
            'date'=>date_create($this->created_at)->format('d-M-Y'),
            'paid'=>$this->paid,
            'courseType'=>$this->courseType,
            'transaction'=>  $this->courseType=='paid' ?[
                'CustomerName'  =>$this->transaction->CustomerName,
                'InvoiceId'  =>$this->transaction->InvoiceId,
                'InvoiceStatus'  =>$this->transaction->InvoiceStatus,
                'InvoiceValue'  =>$this->transaction->InvoiceValue,
                'PaidCurrency'  =>$this->transaction->PaidCurrency,
                'CustomerMobile'  =>$this->transaction->CustomerMobile,
                'PaymentGateway'  =>$this->transaction->PaymentGateway,
                'CardNumber'  =>$this->transaction->CardNumber,
            ] : '',
        ];
    }
}
