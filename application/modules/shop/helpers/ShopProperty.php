<?php

class Mg_Shop_Helper_ShopProperty 
{
    public static function getPropertyTypes() {
        $aTypes = array(
            'NUMBER'    => 'Число',
            'LIST'      => 'Список',
            'STRING'    => 'Строка',
        );
        return $aTypes;
    }
    
    public static function getPropertyViewTypes() {
        $aViewTypes = array(
            'INTEGER'   => 'Целое число',
            'FLOAT'     => 'Дробное число',
            'LIST'      => 'Список',
            'MULTILIST' => 'Мульти-список',
            'RANGE'     => 'Диапазон',
            'STRING'    => 'Строка',
        );
        return $aViewTypes;
    }
    
    public static function getProperty($iIdProperty) {
        $oProperty = new Mg_Shop_Model_ShopProperty();
        if ( !empty($iIdProperty) ) {
            $oShopPropertyMapper = new Mg_Shop_Model_Mapper_ShopProperty();
            $oProperty = $oShopPropertyMapper->getItem($iIdProperty);
        }
        return $oProperty; 
    }
    
}
