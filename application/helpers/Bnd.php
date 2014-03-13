<?php

class Mg_Common_Helper_Bnd
{
    
    public static function getBnd($aBnd, $sParentTable) {
        $oBnd = new Mg_Model_Bnd();
        if ( !empty($aBnd) ) {
            $oBndMapper = new Mg_Model_Mapper_Bnd();
            $oBnd = $oBndMapper->getItem($aBnd);
        }
        return $oBnd;
    }
    
    public static function getChilds($iParentId, $sParentTable) {
        if ( $iParentId > 0 ) {
            $oBndMapper = new Mg_Model_Mapper_Bnd($sParentTable);
            $aWhere = array(
                array('id_parent = ?', $iParentId),
            );
            $oResult = $oBndMapper->getList($aWhere, null, 0, 0);
        } else {
            $oResult = null;
        }
        return $oResult;
    }
    
    public static function getParents($iChildId, $sParentTable) {
        if ( $iChildId > 0 ) {
            $oBndMapper = new Mg_Model_Mapper_Bnd($sParentTable);
            $aWhere = array(
                array('id_child = ?', $iChildId),
            );
            $oResult = $oBndMapper->getList($aWhere, null, 0, 0);
        } else {
            $oResult = null;
        }
        return $oResult;
    }
    
    public static function getChildsAsArray($iParentId, $sParentTable) {
        $oResult = self::getChilds($iParentId, $sParentTable);
        $aBnd = array();
        if ( $oResult && $oResult->getCurrentItemCount() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oItem) {
                $aBnd[] = $oItem->id_child;
            }
        }
        return $aBnd;
    }
    
    public static function getParentsAsArray($iChildId, $sParentTable) {
        $oResult = self::getParents($iChildId, $sParentTable);
        $aBnd = array();
        if ( $oResult && $oResult->getCurrentItemCount() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oItem) {
                $aBnd[] = $oItem->id_parent;
            }
        }
        return $aBnd;
    }
    
}
