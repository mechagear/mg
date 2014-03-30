<?php
class Admin_UserinfoController extends Mg_Controller_Abstract
{
    protected $oConfig;
    
    public function init() {
        parent::init();
        if (!$this->oAcl->isAllowed($this->oUser, 'cp', 'view') && !in_array($this->getRequest()->getActionName(), array('auth', 'unauth'))) {
            $this->redirect($this->view->url(array(),'auth'), array('exit' => true,));
            throw new Mg_Common_Exception_AccessDenied('No access');
            exit;
        }
        $this->oConfig = Zend_Registry::get('config');
    }
    
    public function userinfoAction() {
        $this->view->oUser = $this->oUser;
    }
    
}