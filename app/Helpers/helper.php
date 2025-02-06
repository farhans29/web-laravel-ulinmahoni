<?php
namespace App\Helpers;
class Helper{
    public static function formatCurrency($value, $beforeValue = "IDR ", $afterValue = "")
    {
        $output = $beforeValue . number_format($value, 0, ',', '.') . $afterValue;
        return $output;
    }
    
}