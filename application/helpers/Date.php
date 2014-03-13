<?php

class Mg_Common_Helper_Date
{
    public static function toDefault($sDate) {
        $sDate = strtotime($sDate);
        return date('Y-m-d H:i:s', $sDate);
    }
    
    public static function toView($sDate) {
        $sDate = strtotime($sDate);
        return date('d.m.Y', $sDate);
    }
    
    public static function getHour($sDate) {
        $sDate = strtotime($sDate);
        return date('H', $sDate);
    }
    
    public static function getMinute($sDate) {
        $sDate = strtotime($sDate);
        return date('i', $sDate);
    }
}