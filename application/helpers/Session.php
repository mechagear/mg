<?php

class Mg_Common_Helper_Session
{
    public static function getSessionByKey($sKey) {
        $aWhere = array(
            array('sess_key = ?',$sKey),
        );
        $oSessionMapper = new Mg_Model_Mapper_Session();
        $oResult = $oSessionMapper->getList($aWhere, null, 1, 1);
        return $oResult->getItem(0);
    }
    
    public static function getSession($iSessionId) {
        $oSessionMapper = new Mg_Model_Mapper_Session();
        $oSession = $oSessionMapper->getItem($iSessionId);
        return $oSession;
    }
}
