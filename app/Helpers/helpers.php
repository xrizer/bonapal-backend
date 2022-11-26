<?php
if(! function_exists(function:'moneyFormat')){
    function moneyFormat($str){
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}