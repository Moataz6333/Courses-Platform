<?php 
namespace App\Interfaces;

interface AiInterface{
    public function correct($answer,$model);
}