<?php

class Mg_Controller_Admin extends Mg_Controller_Abstract
{
    protected $oConfig;
    
    public function init() {
        parent::init();
        /*$sController = implode('/', explode('_', $this->getRequest()->getControllerName()));
        $sViewsPath = DOMAIN_PATH . '/views/admin/' . $this->getRequest()->getModuleName() . '/' . $sController;
        //var_dump($sViewsPath);
        $this->view->addBasePath($sViewsPath);
        */
        if (!$this->oAcl->isAllowed($this->oUser, 'cp', 'view') && !in_array($this->getRequest()->getActionName(), array('auth', 'unauth'))) {
            $this->redirect($this->view->url(array(),'auth'), array('exit' => true,));
            throw new Mg_Common_Exception_AccessDenied('No access');
            exit;
        }
        $this->oConfig = Zend_Registry::get('config');
    }
}