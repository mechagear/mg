<?php

class Mg_Shop_Helper_Category 
{
    const MAX_RECURSION_LEVEL = 100;
    
    public static function getCategory($iIdCategory) {
        $oCategory = new Mg_Shop_Model_Category();
        if ( !empty($iIdCategory) ) {
            $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
            $oCategory = $oCategoryMapper->getItem($iIdCategory);
        }
        return $oCategory; 
    }
    
    public static function getCategoryByUrl($sUrl) {
        $oCategory = new Mg_Shop_Model_Category();
        if ( !empty($sUrl) ) {
            $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
            $aWhere = array(array('url = ?', $sUrl));
            $oResult = $oCategoryMapper->getList($aWhere, null, 1, 1);
            $oCategory = $oResult->getItem(0);
        }
        return $oCategory;
    }
    
    public static function getShopCategories($iShopId) {
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        $aWhere = array(
            array('id_shop = ?', $iShopId),
        );
        $oResult = $oCategoryMapper->getList($aWhere, array('name ASC'));
        return $oResult;
    }
    
    public static function getShopParentCategories($iCategoryId, $iLevel = 0) {
        $aResult = array();
        $oCategory = self::getCategory($iCategoryId);
        if ( $oCategory->id_category <= 0 ) {
            return $aResult;
        }
        $aResult[] = $oCategory;
        if ( $oCategory->id_parent > 0 && $iLevel < self::MAX_RECURSION_LEVEL ) {
            $aResult = array_merge($aResult, self::getShopParentCategories($oCategory->id_parent, $iLevel+1));
        }
        return $aResult;
    }
    
    public static function getShopChildCategories($iIdParent) {
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        $aWhere = array(
            array('id_parent = ?', intval($iIdParent)),
        );
        $oResult = $oCategoryMapper->getList($aWhere, array('name ASC'));
        return $oResult;
    }
    
    public static function getShopCategoriesFlatTree($iShopId) {
        $oCategories = self::getShopCategories($iShopId);
        
        $aCategories = array();
        
        foreach ( $oCategories as $oCategory ) {
            $aCategories[] = array(
                'id_shop' => $oCategory->id_shop,
                'id_category' => $oCategory->id_category,
                'id_parent' => $oCategory->id_parent,
                'name' => $oCategory->name,
            );
        }
        
        $aCategories = self::_flattenize($aCategories);
        return $aCategories;
    }
    
    private static function _flattenize($aCategories, $iParentId = 0, $iLevel = 0) {
        $aResult = array();
        foreach ( $aCategories as $aCategory ) {
            if ( $aCategory['id_parent'] != $iParentId ) {
                continue;
            }
            $aCategory['level'] = $iLevel;
            $aResult[] = $aCategory;
            
            $aChilds = self::_flattenize($aCategories, $aCategory['id_category'], $iLevel+1);
            $aResult = array_merge($aResult, $aChilds);
        }
        return $aResult;
    }
}