<?php
namespace App\Service;

use App\Interfaces\PaymentInterface;

class MyFatoorahPaymentService implements PaymentInterface
{
    public function pay($uuid) {
        return url('/myfatoorah?oid='.$uuid);
    }
}

?>