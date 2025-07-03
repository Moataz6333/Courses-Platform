<?php

use App\Service\MyFatoorahPaymentService;

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('my fatoorah url test',function(){
    $uuid='8u8ui82ikj';
    $myfatoorahObj=new MyFatoorahPaymentService();
    expect($myfatoorahObj->pay($uuid))->toBe('http://127.0.0.1:8000/myfatoorah?oid='.$uuid);
});