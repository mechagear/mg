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
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( $oUser->id_user == 0 && empty($aParams['userPassword']) ) {
                // TODO: password gen
                $aParams['userPassword'] = '123456';
            }
            
            if ( !empty($aParams['userEmail']) ) {
                $oUser->email = $aParams['userEmail'];
            }
            if ( !empty($aParams['userPhone']) ) {
                $oUser->phone = $aParams['userPhone'];
            }
            if ( !empty($aParams['userNickname']) ) {
                $oUser->nickname = $aParams['userNickname'];
            }
            if ( !empty($aParams['userRole']) ) {
                $oUser->role = $aParams['userRole'];
            } else {
                $oUser->role = 'member';
            }
            if ( !empty($aParams['userIdStatus']) ) {
                $oUser->id_status = $aParams['userIdStatus'];
            }
            if ( !empty($aParams['userPassword']) ) {
                $oUser->password = md5($aParams['userPassword']);
            }
            
            $oUser->date_change = date('Y-m-d H:i:s');
            
            if ( $iId = $oUserMapper->save($oUser) ) {
                $this->redirect($this->view->url(array(),'users-list'));
            }
        }
        
        $this->view->aRoles = Mg_Common_Helper_Role::getRoles();
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oUserMapper->getDbTable()->getTable());
        $this->view->oUser = $oUser;
    }
    
}
