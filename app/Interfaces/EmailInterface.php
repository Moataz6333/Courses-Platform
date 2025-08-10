<?php
namespace App\Interfaces;

interface EmailInterface{
    
    public function send($from, $to,$subject,$message);
}