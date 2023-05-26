<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public static function index ($number, $precicition = 1) {
        $nominal = (float)$number;
        if ($nominal < 900) {
            $numberFormat = number_format($nominal, $precicition);
            $symbol = '';
        } else if ($nominal < 900000) {
            $numberFormat = number_format($nominal / 1000, $precicition);
            $symbol = 'rb';
        } else if ($nominal < 900000000) {
            $numberFormat = number_format($nominal / 1000000, $precicition);
            $symbol = 'jt';
        } else if ($nominal < 900000000000) {
            $numberFormat = number_format($nominal / 1000000000, $precicition);
            $symbol = 'M';
        } else {
            $numberFormat = number_format($nominal / 1000000000000, $precicition);
            $symbol = 'T';
        }
    
        if ( $precicition > 0 ) {
            $split = '.' . str_repeat( '0', $precicition );
            $numberFormat = str_replace( $split, '', $numberFormat );
        }
        $text =  $numberFormat.$symbol;
        return $text;
    }
}
