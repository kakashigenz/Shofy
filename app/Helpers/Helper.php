<?php

namespace App\Helpers;

use Illuminate\Support\Str;

function generateSKU($category,$name, $variation)
{
    
    $splited_category = explode(' ',data_get($category,'name'));
    $sku = Str::slug(mb_substr($splited_category[0],0,2)) . '-';
    $sku .=  Str::slug(mb_substr($name, 0, 3,'UTF-8')) . '-';
    
    foreach ($variation as $value) {
        $sku .= Str::slug(data_get($value,'value')) . '-';
    }
    
    return substr($sku,0,strlen($sku) - 1);
}

function callApiGoship(string $endpoint,string $method,array $data = null){
    $curl = curl_init();
    $option = [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_URL => $endpoint,
        CURLOPT_HTTPHEADER => [
            sprintf("Authorization: Bearer %s",env('GOSHIP_TOKEN')),
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ];
    curl_setopt_array($curl,$option);
    $json = curl_exec($curl);
    curl_close($curl);
    $res =  json_decode($json,true);
    return $res;
}