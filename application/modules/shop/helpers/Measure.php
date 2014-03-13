<?php

class Mg_Shop_Helper_Measure 
{
    public static function getMeasure($iIdMeasure) {
        $oMeasure = new Mg_Shop_Model_Measure();
        if ( !empty($iIdMeasure) ) {
            $oMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
            $oMeasure = $oMeasureMapper->getItem($iIdMeasure);
        }
        return $oMeasure; 
    }
    
    public static function getMeasureByShop($iIdShop) {
        $oMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
        $aWhere = array(
            array('id_shop = ?', $iIdShop),
        );
        $oResult = $oMeasureMapper->getList($aWhere, array('name_full ASC'));
        return $oResult;
    }
}