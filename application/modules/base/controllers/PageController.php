<?php

class Base_PageController extends Mg_Controller_Abstract
{
    
    public function pageAction() {
        $sPageUrl = $this->_getParam('sPageUrl');
        
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        $aWhere = array(
            array('domain_key = ?', DOMAIN_KEY),
            array('url = ?', $sPageUrl),
        );
        $oResult = $oPageMapper->getList($aWhere, null, 1, 1);
        if ( $oResult->getCurrentItemCount() > 0 ) {
            $oPage = $oResult->getItem(1);
        } else {
            $oPage = false;
        }
        
        $aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oPageMapper->getDbTable()->getTable());
        
        if ( !$oPage || $aStatuses[$oPage->id_status]->code != 'ACTIVE') {
            $this->getResponse()->clearBody();
            $this->getResponse()->clearHeaders();
            $this->getResponse()->setHttpResponseCode(404);
            throw new Zend_Controller_Action_Exception('', 404);
            return;
        }
        
        if ( $oPage->redirect_code > 0 ) {
            $this->redirect($oPage->redirect, array('exit'=>true, 'code' => $oPage->redirect_code,));
        }
        
        $this->view->oPage = $oPage;
    }
    
    public function pagewidgetAction() {
        $this->pageAction();
    }
    
}