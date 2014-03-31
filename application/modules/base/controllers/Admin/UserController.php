<?php

class Base_Admin_UserController extends Mg_Controller_Admin
{   
    public function init() {
        parent::init();
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
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'users-list', 'label' => 'Пользователи', 'params' => array()),
        ));
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
        
        Mg_Common_Helper_Breadcrumbs::setBreadcrumbs(array(
            array('is_mvc' => true, 'route' => 'users-list', 'label' => 'Пользователи', 'params' => array()),
            array('is_mvc' => true, 'route' => 'users-edit', 'label' => ($oUser->id_user > 0) ? $oUser->email : 'Новый пользователь', 'params' => array('iUserId' => $oUser->id_user)),
        ));
    }
    
}
