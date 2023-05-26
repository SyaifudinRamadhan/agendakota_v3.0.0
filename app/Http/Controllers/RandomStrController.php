<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RandomStrController extends Controller
{
    private $randomStrings;
    public function __construct($length = 10)
    {   
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $this->randomStrings = $randomString.time();
    }
    public function get()
    {
        return $this->randomStrings;
    }
}
