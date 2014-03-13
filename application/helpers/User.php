<?php

class Mg_Common_Helper_User
{
    public static function getUserByIdentity($sIdentity) {
        $sIdentityType = Mg_Common_Helper_Identity::getIdentityType($sIdentity);
        if ( !empty($sIdentityType) ) {
            $aWhere = array(
                array($sIdentityType . ' = ?',$sIdentity),
            );
            $oUserMapper = new Mg_Model_Mapper_User();
            $oResult = $oUserMapper->getList($aWhere, null, 1, 1);
            return $oResult->getItem(0);
        } else {
            return new Mg_Model_User();
        }
    }
    
    public static function getUser($iUserId) {
        $oUserMapper = new Mg_Model_Mapper_User();
        $oUser = $oUserMapper->getItem($iUserId);
        return $oUser;
    }
    
    public static function isOwner($iUserId) {
        $oAcl = Zend_Registry::get('acl');
        $oUser = $oAuthSession->user;
        if ( $oUser->id_user != $iUserId ) {
            $oUser = self::getUser($iUserId);
        }
        return $oAcl->inheritsRole($oUser->getRoleId(), 'owner');
    }
    
    public static function isCurrentOwner() {
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oUser = $oAuthSession->user;
        return self::isOwner($oUser->id_user);
    }
    
    public static function getUsersOnline() {
        // Users online feature
        return 0;
        /*$oRedis = Zend_Registry::get('redis');
        // get last 10 mins users
        $iTimestamp = time();
        $iTimeAgo = time() - 60*10;
        return $oRedis->zCount('users_online', $iTimeAgo, $iTimestamp);*/
    }
}
