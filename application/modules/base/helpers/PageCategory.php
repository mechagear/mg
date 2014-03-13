<?php

class Mg_Base_Helper_PageCategory
{
    
    public static function getPageCategory($iIdPageCategory) {
        $oPageCategory = new Mg_Base_Model_PageCategory();
        if ( !empty($iIdPageCategory) ) {
            $oPageCategoryMapper = new Mg_Base_Model_Mapper_PageCategory();
            $oPageCategory = $oPageCategoryMapper->getItem($iIdPageCategory);
        }
        return $oPageCategory;
    }
    
    public static function getPageCategories() {
        $oPageCategoryMapper = new Mg_Base_Model_Mapper_PageCategory();
        $oResult = $oPageCategoryMapper->getList(null, array('name ASC'));
        return $oResult;
    }
    
    public static function getPageChildCategories($iIdParent) {
        $oPageCategoryMapper = new Mg_Base_Model_Mapper_PageCategory();
        $aWhere = array(
            array('id_parent = ?', intval($iIdParent)),
        );
        $oResult = $oPageCategoryMapper->getList($aWhere, array('name ASC'));
        return $oResult;
    }
    
    public static function getPageCategoriesFlatTree() {
        $oCategories = self::getPageCategories();
        
        $aCategories = array();
        
        foreach ( $oCategories as $oCategory ) {
            $aCategories[] = array(
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
