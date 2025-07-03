<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'enrollment_id',
    'InvoiceId',
    'InvoiceStatus',
    'InvoiceValue',
    'CustomerName',
    'CustomerMobile',
    'DueDeposit',
    'DepositStatus',
    'PaymentGateway',
    'PaymentId',
    'PaidCurrency',
    'CardNumber',
];
public function enrollment()  {
    return $this->belongsTo(Enrollment::class);
}
}
