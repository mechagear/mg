<?php

class Admin_UserController extends Mg_Controller_Abstract
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
    
    public function listAction() {
        $sFilters = $this->_getParam('sFilters', '');
        $iPage = intval($this->_getParam('iPage', 1));
        
        $oUserMapper = new Mg_Model_Mapper_User();
        
        $aColumns = array();
        $oSelect = new Zend_Db_Select($oUserMapper->getDbTable()->getAdapter());
        $oSelect->from($oUserMapper->getDbTable()->getTable());
        $oUsers = $oUserMapper->getListExt($oSelect, $iPage, 20);
        
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oUserMapper->getDbTable()->getTable());
        $this->view->oUsers = $oUsers;
    }
    
    public function editAction() {
        $iUserId = $this->_getParam('iUserId', 0);
        
        $oUserMapper = new Mg_Model_Mapper_User();
        if ( $iUserId > 0 ) {
            $oUser = Mg_Common_Helper_User::getUser($iUserId);
        } else {
            $oUser = new Mg_Model_User();
        }
        
        $this->view->aRoles = Mg_Common_Helper_Role::getRoles();
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oUserMapper->getDbTable()->getTable());
        $this->view->oUser = $oUser;
    }
    
}
