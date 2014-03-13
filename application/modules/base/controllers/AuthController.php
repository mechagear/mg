<?php

class AuthController extends Sacms_Controller_Abstract 
{
    public function authAction() {
        if ( $this->getRequest()->isPost() ) {
            $sEmail = $this->getRequest()->getPost('authEmail');
            $sPhone = $this->getRequest()->getPost('authPhone');
            $sPassword = $this->getRequest()->getPost('authPassword');
            $bRemember = ($this->getRequest()->getPost('authRemember')) ? true : false;
            $sUrl = $this->getRequest()->getPost('backUrl');
            $sUrl = empty($sUrl) ? '/' : $sUrl;
            
            if ( (empty($sEmail) && empty($sPhone)) || empty($sPassword) ) {
                // Simply do nothing
            } else {
                $sIdentity = 'email';
                $sIdentityValue = $sEmail;
                if ( !empty($sPhone) ) {
                    $sIdentity = 'phone';
                    $sIdentityValue = Mg_Common_Helper_Identity::formatPhone($sPhone);
                }

                $oAdapter = new Zend_Auth_Adapter_DbTable($this->oDb, 'tbl_user', $sIdentity, 'password', 'MD5(?)');
                $oAdapter->setIdentity($sIdentityValue);
                $oAdapter->setCredential($sPassword);

                $oAuth = Zend_Auth::getInstance();
                $oResult = $oAuth->authenticate($oAdapter);

                if ( $oResult->isValid() ) {
                    $oAuthSession = new Zend_Session_Namespace('auth');
                    $sIdentity = $oResult->getIdentity();
                    $oUser = Mg_Common_Helper_User::getUserByIdentity($sIdentity);
                    $oAuthSession->user = $oUser;
                    if ( $bRemember ) {
                        Zend_Session::rememberMe(604800); // 1 week
                    }
                    $this->redirect(urldecode($sUrl));
                }
            }
        }
    }
    
    public function unauthAction() {
        $oAuth = Zend_Auth::getInstance();
        $oAuth->clearIdentity();
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oAuthSession->user = new Mg_Model_User();
        $this->redirect('/');
    }
    
}
