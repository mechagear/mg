<?php

class Mg_Base_Helper_IblockCategory
{
    public static function getIblockCategory($iIdIblockCategory) {
        $oIblockCategory = new Mg_Base_Model_IblockCategory();
        if ( !empty($iIdIblockCategory) ) {
            $oIblockCategoryMapper = new Mg_Base_Model_Mapper_IblockCategory();
            $oIblockCategory = $oIblockCategoryMapper->getItem($iIdIblockCategory);
        }
        return $oIblockCategory; 
    }
    
    public static function getCategoryByUrl($sUrl, $iIblockId) {
        $oCategory = new Mg_Base_Model_IblockCategory();
        if ( !empty($sUrl) ) {
            $oCategoryMapper = new Mg_Base_Model_Mapper_IblockCategory();
            $aWhere = array(
                    array('url = ?', $sUrl),
                );
            if ( !empty($iIblockId) ) {
                $aWhere[] = array('id_iblock = ?', $iIblockId);
            }
            $oResult = $oCategoryMapper->getList($aWhere, null, 1, 1);
            $oCategory = $oResult->getItem(0);
        }
        return $oCategory;
    }
    
    public static function getIblockCategories($iIblockId) {
        $oIblockCategoryMapper = new Mg_Base_Model_Mapper_IblockCategory();
        $aWhere = array(
            array('id_iblock = ?', $iIblockId),
        );
        $oResult = $oIblockCategoryMapper->getList($aWhere, array('name ASC'));
        return $oResult;
    }
    
    public static function getIblockChildCategories($iIdParent, $iIblockId = 0) {
        $oIblockCategoryMapper = new Mg_Base_Model_Mapper_IblockCategory();
        $aWhere = array(
            array('id_parent = ?', intval($iIdParent)),
        );
        if ( !empty($iIblockId) ) {
            $aWhere[] = array('id_iblock = ?', intval($iIblockId));
        }
        $oResult = $oIblockCategoryMapper->getList($aWhere, array('name ASC'));
        return $oResult;
    }
    
    public static function getIblockCategoriesFlatTree($iIblockId) {
        $oCategories = self::getIblockCategories($iIblockId);
        
        $aCategories = array();
        
        foreach ( $oCategories as $oCategory ) {
            $aCategories[] = array(
                'id_iblock' => $oCategory->id_iblock,
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
