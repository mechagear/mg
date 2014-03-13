<?php

class Mgcms_Controller_Abstract extends Zend_Controller_Action 
{
    protected $oDb;
    protected $oAcl;
    protected $oUser;
    protected $oConfig;
    
    public function init() {
        // setting up database
        $this->oDb = $this->_getParam('db');
        // setting up Acl
        $this->oAcl = Zend_Registry::get('acl');
        // setting up config
        $this->oConfig = new Zend_Config($this->getFrontController()->getParam('bootstrap')->getOptions());
        // getting current user info
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oUser = new Mg_Model_User();
        $oUser = ($oAuthSession->user) ? $oAuthSession->user : $oUser;
        $iIdUser = $oUser->id_user;
        if ( empty($oUser) || empty($iIdUser) ) {
            // Init user if empty
            $oAuth = Zend_Auth::getInstance();
            $sIdentity = $oAuth->getIdentity();
            $oUser = Mg_Common_Helper_User::getUserByIdentity($sIdentity);
            $oAuthSession->user = $oUser;
        }
        $this->oUser = $oAuthSession->user;
        $this->isAuthorized = Zend_Auth::getInstance()->hasIdentity();
        $oLayout = Zend_Layout::getMvcInstance();
        $oLayout->oUser = $this->oUser;
        $oLayout->isAuthorized = $this->isAuthorized;
        $oLayout->nickname = $oAuthSession->user->nickname;
        // Views dirty hack
        $aScriptPaths = $oLayout->getView()->getScriptPaths();
        $oLayout->getView()->setScriptPath(false);
        $oLayout->getView()->setScriptPath($aScriptPaths);
        // set user last visit timestamp
        /*$oRedis = Zend_Registry::get('redis');
        if ( $oUser->id_user > 0 ) {
            $iTimestamp = time();
            $oRedis->zAdd('users_online', $iTimestamp, $oUser->id_user);
        }*/ 
    }
}
