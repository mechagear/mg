<?php

class Admin_PageController extends Mg_Controller_Abstract
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
    
    public function pagesAction() {
        $iPage = $this->_getParam('iPage',1); // paginator page number
        //Getting pages list
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        $aWhere = array();
        $oPages = $oPageMapper->getList($aWhere, array('name ASC'), $iPage, $this->oConfig['pagination']['list_items_on_page']);
        
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oPageMapper->getDbTable()->getTable());
        $this->view->oPages = $oPages;
        
    }
    
    /**
     * Page editing
     */
    public function pageeditAction() {
        $iPageId = $this->_getParam('iPageId');
        if ( $iPageId > 0 ) {
            $oPage = Mg_Base_Helper_Page::getPage($iPageId);
        } else {
            $oPage = new Mg_Base_Model_Page();
        }
        
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        // save
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( isset($aParams['pageName']) ) {
                $oPage->name = $aParams['pageName'];
            }
            if ( isset($aParams['pageUrl']) ) {
                $oPage->url = $aParams['pageUrl'];
            }
            if ( !empty($aParams['pageShortDescription']) ) {
                $oPage->short_text = $aParams['pageShortDescription'];
            }
            if ( !empty($aParams['pageText']) ) {
                $oPage->text = $aParams['pageText'];
            }
            if ( !empty($aParams['pageTitle']) ) {
                $oPage->title = $aParams['pageTitle'];
            }
            if ( !empty($aParams['pageKeywords']) ) {
                $oPage->keywords = $aParams['pageKeywords'];
            }
            if ( !empty($aParams['pageDescription']) ) {
                $oPage->description = $aParams['pageDescription'];
            }
            if ( !empty($aParams['pageRedirect']) ) {
                $oPage->redirect = $aParams['pageRedirect'];
            }
            if ( !empty($aParams['pageRedirectCode']) ) {
                $oPage->redirect_code = $aParams['pageRedirectCode'];
            }
            
            if ( !empty($aParams['pageIdStatus']) ) {
                $oPage->id_status = $aParams['pageIdStatus'];
            }
            
            if ( isset($aParams['pageDomainKey']) ) {
                $oPage->domain_key = $aParams['pageDomainKey'];
            }
            
            $oPage->date_change = date('Y-m-d H:i:s');
            
            if ( $oPage->id_page == 0 ) {
                $oPage->id_user = 1;
                $oPage->id_status = 1;
            }
            
            $bIsValid = $oPage->validateAll();
            if ( $bIsValid && $oPageMapper->save($oPage) ) {
                $this->redirect($this->view->url(array(),'pages-list'));
            } else {
                $this->view->aErrors = $oPage->getValidationErrors();
            }
            
        }
        
        $this->view->aDomainKeys = Mg_Common_Helper_Domain::getDomainKeys();
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oPageMapper->getDbTable()->getTable());
        $this->view->aRedirectCodes = Mg_Base_Helper_Page::getRedirectCodes();
        $this->view->oPage = $oPage;
    }
    
}
