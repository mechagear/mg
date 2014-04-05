<?php

class Mg_Controller_Abstract extends Zend_Controller_Action 
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
        
        // setting up session
        // TODO: Move to bootstrap
        // TODO: Limit session lifetime?
        /*$sCookie = $this->getRequest()->getCookie('mgsid');
        
        $oSession = new Mg_Model_Session();
        if ( $sCookie ) {
            $oSession = Mg_Common_Helper_Session::getSessionByKey($sCookie);
        }
        // If no session
        if ($oSession->id_session == 0) {
            $oSessionMapper = new Mg_Model_Mapper_Session();
            $oSession->sess_key = Zend_Session::getId();
            $oSession->id_user = 0;
            $oSession->ip_address = $this->getRequest()->getServer('REMOTE_ADDR');
            $iSessionId = $oSessionMapper->save($oSession);
            if ( $iSessionId > 0 ) {
                // TODO: Create something more secure
                setcookie('mgsid', $oSession->key, time()+60*60*24*14, '/', $_SERVER['HTTP_HOST'], false, true);
                $sCookie = $oSession->key;
            }
        }
        
        
        // getting current user info
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oAuth = Zend_Auth::getInstance();
        $oUser = new Mg_Model_User();
        // If lose auth identifier, then recreate new
        if ( !$oAuth->getIdentity() && $oSession->id_user > 0 ) {
            $oUser = Mg_Common_Helper_User::getUser($oSession->id_user);
            if ($oUser->id_user > 0) {
                $oAdapter = new Zend_Auth_Adapter_DbTable($this->oDb, 'tbl_user', 'email', 'password');
                $oAdapter->setIdentity($oUser->id_user);
                $oAdapter->setCredential($oUser->password);
                $oAuthResult = $oAuth->authenticate($oAdapter);
                if ($oAuthResult->isValid()) {
                    $oAuthSession->user = $oUser;
                }
            }
        }
        //----------------------------------
        $oUser = ($oAuthSession->user) ? $oAuthSession->user : $oUser;
        $iIdUser = $oUser->id_user;
        if ( !$oAuthSession->user ) {
            // Init user if empty
            if ( $oUser->id_user == 0 ) {
                $sIdentity = $oAuth->getIdentity();
                $oUser = Mg_Common_Helper_User::getUserByIdentity($sIdentity);
            }
            $oAuthSession->user = $oUser;
        }
         * 
         */
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oAuth = Zend_Auth::getInstance();
        
        $this->oUser = $oAuthSession->user;
        $this->isAuthorized = $oAuth->hasIdentity();
        $oLayout = Zend_Layout::getMvcInstance();
        $oLayout->oUser = $this->oUser;
        $oLayout->isAuthorized = $this->isAuthorized;
        $oLayout->nickname = $oAuthSession->user->nickname;
        // Views dirty hack
        $aScriptPaths = $oLayout->getView()->getScriptPaths();
        $oLayout->getView()->setScriptPath(false);
        $oLayout->getView()->setScriptPath($aScriptPaths);
    }
}
