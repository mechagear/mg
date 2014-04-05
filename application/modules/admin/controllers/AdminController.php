<?php

class Admin_AdminController extends Mg_Controller_Abstract
{
    protected $oConfig;
    
    public function init() {
        parent::init();
        if (!$this->oAcl->isAllowed($this->oUser, 'cp', 'view') && !in_array($this->getRequest()->getActionName(), array('auth', 'unauth'))) {
            $this->redirect($this->view->url(array(),'auth'), array('exit' => true,));
            throw new Mg_Common_Exception_AccessDenied('No access');
            exit;
        }
        $this->_helper->AjaxContext()
                ->addActionContext('kladrimportblock', 'json')
                ->initContext('json');
        $this->oConfig = Zend_Registry::get('config');
    }
    
    public function authAction() {
        $this->_helper->layout->setLayout('auth');
        if ( $this->getRequest()->isPost() ) {
            $sEmail = $this->getRequest()->getPost('authEmail');
            $sPhone = $this->getRequest()->getPost('authPhone');
            $sPassword = $this->getRequest()->getPost('authPassword'); 
            $bRemember = ($this->getRequest()->getPost('authRemember')) ? true : false;
            $sUrl = $this->getRequest()->getPost('backUrl');
            $sUrl = ( empty($sUrl) || '/cp/auth' == $sUrl ) ? '/cp' : $sUrl;
            
            if ( (empty($sEmail) && empty($sPhone)) || empty($sPassword) ) {
                // Simply do nothing
            } else {
                $sIdentity = 'email';
                $sIdentityValue = $sEmail;
                if ( !empty($sPhone) ) {
                    $sIdentity = 'phone';
                    $sIdentityValue = Mg_Helper_Identity::formatPhone($sPhone);
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
                    
                    // add user id to Session
                    $oSession = $oAuthSession->session;
                    $oSession->id_user = $oUser->id_user;
                    $oSessionMapper = new Mg_Model_Mapper_Session();
                    $oSessionMapper->save($oSession);
                    
                    if ( $bRemember ) {
                        Zend_Session::rememberMe(604800); // 1 week
                    }
                    $this->redirect(urldecode($sUrl));
                }
            }
        }
        $this->view->disableNav = true;
    }
    
    public function unauthAction() {
        $this->_helper->layout->setLayout('auth');
        $oAuth = Zend_Auth::getInstance();
        $oAuth->clearIdentity();
        $oAuthSession = new Zend_Session_Namespace('auth');
        $oAuthSession->user = new Mg_Model_User();
        // Clear user id from Session
        $oSession = $oAuthSession->session;
        $oSession->id_user = 0;
        $oSessionMapper = new Mg_Model_Mapper_Session();
        $oSessionMapper->save($oSession);
        
        $this->redirect('/');
    }
    

    public function indexAction() {
        
    }
    
    // STATIC PAGES
    /**
     * Pages & categories list
     */
    public function pagesAction() {
        $iPage = $this->_getParam('iPage',1); // paginator page number
        $iIdCategory = $this->_getParam('iCategoryId',0); // category id
        //Getting pages list
        $oPageMapper = new Mg_Base_Model_Mapper_Page();
        $aWhere = array(
            array('id_category = ?', $iIdCategory),
        );
        if ( empty($iIdCategory) ) {
            $aWhere[] = array('id_category IS NULL', null, 'OR');
        }
        
        $oPages = $oPageMapper->getList($aWhere, array('name ASC'), $iPage, $this->oConfig['pagination']['cp_items_on_page']);
        
        $this->view->aTopButtons = array(
            //array('href'=>$this->view->url(array('iPageId' => 0,), 'cp-pages-edit'), 'icon' => 'icon-plus', 'title' => 'Добавить страницу'),
            //array('href'=>$this->view->url(array('iCategoryId' => 0,), 'cp-pages-categoryedit'), 'icon' => 'icon-folder-close', 'title' => 'Добавить раздел'),
        );
        
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oPageMapper->getDbTable()->getTable());
        $this->view->oCategory = ($iIdCategory > 0) ? Mg_Base_Helper_PageCategory::getPageCategory($iIdCategory) : new Mg_Base_Model_PageCategory();
        $this->view->oCategories = Mg_Base_Helper_PageCategory::getPageChildCategories($iIdCategory);
        $this->view->oPages = $oPages;
    }
    /**
     * Category editing
     */
    public function categoryeditAction() {
        $iIdCategory = $this->_getParam('iCategoryId', 0);
        if ( $iIdCategory > 0 ) {
            $oCategory = Mg_Base_Helper_PageCategory::getPageCategory($iIdCategory);
        } else {
            $oCategory = new Mg_Base_Model_PageCategory();
        }
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            if ( isset($aParams['pageCategoryIdCategory']) ) {
                $oCategory->id_parent = $aParams['pageCategoryIdCategory'];
            }
            if ( !empty($aParams['pageCategoryName']) ) {
                $oCategory->name = $aParams['pageCategoryName'];
            }
            $oPageCategoryMapper = new Mg_Base_Model_Mapper_PageCategory();
            if ( $oPageCategoryMapper->save($oCategory) ) {
                $this->redirect($this->view->url(array(),'index-cp-pages-default'));
            }
        }
        
        $aCategories = Mg_Base_Helper_PageCategory::getPageCategoriesFlatTree();
        
        $this->view->aCategories = $aCategories;
        $this->view->oCategory = $oCategory;
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
            if ( isset($aParams['pageIdCategory']) && $aParams['pageIdCategory'] > 0 ) {
                $oPage->id_category = $aParams['pageIdCategory'];
            }
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
            
            $oPage->date_change = date('Y-m-d H:i:s');
            
            if ( $oPage->id_page == 0 ) {
                $oPage->id_user = 1;
                $oPage->id_status = 1;
            }
            $bIsValid = $oPage->validateAll();
            if ( $bIsValid && $oPageMapper->save($oPage) ) {
                $this->redirect($this->view->url(array(),'index-cp-pages-default'));
            } else {
                $this->view->aErrors = $oPage->getValidationErrors();
            }
            
        }
        
        $aCategories = Mg_Base_Helper_PageCategory::getPageCategoriesFlatTree();
        
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oPageMapper->getDbTable()->getTable());
        $this->view->aCategories = $aCategories;
        $this->view->aRedirectCodes = Mg_Base_Helper_Page::getRedirectCodes();
        $this->view->oPage = $oPage;
    }
    
    
    // -------------------------------------------------------
    // IBLOCK
    /**
     * List of iblocks
     */
    public function iblockAction() {
        //var_dump($this->view);die();
        $iPage = $this->_getParam('iPage',1);
        $oIblockMapper = new Mg_Model_Mapper_Iblock();
        $oIblocks = $oIblockMapper->getList(null, array('name ASC'), $iPage, 20);
        
        $this->view->aTopButtons = array(
            array('href'=>$this->view->url(), 'icon' => 'icon-plus', 'title' => 'Добавить инфоблок'),
        );
        $this->view->oIblocks = $oIblocks;
    }
    /**
     * Iblock category & elements
     */
    public function iblockcategoryAction() {
        $iPage = $this->_getParam('iPage',1);
        $iIblockId = $this->_getParam('iIblockId',0);
        $iIdCategory = $this->_getParam('iCategoryId',0);
        
        $oIblockElementMapper = new Mg_Model_Mapper_IblockElement();
        $aWhere = array(
            array('id_category = ?', $iIdCategory),
        );
        $oElements = $oIblockElementMapper->getList($aWhere, array('name ASC'), $iPage, 20);
        
        $this->view->aTopButtons = array(
            array('href'=>$this->view->url(array('iElementId' => 0,), 'index-cp-iblock-editelement'), 'icon' => 'icon-plus', 'title' => 'Добавить элемент'),
            array('href'=>$this->view->url(), 'icon' => 'icon-folder-close', 'title' => 'Добавить раздел'),
        );
        
        $this->view->iIblockId = $iIblockId;
        $this->view->oCategory = ($iIdCategory > 0) ? Mg_Helper_IblockCategory::getIblockCategory($iIdCategory) : new Mg_Model_IblockCategory();
        $this->view->oCategories = Mg_Helper_IblockCategory::getIblockChildCategories($iIdCategory);
        $this->view->oElements = $oElements;
    }
    /**
     * Iblock element editing
     */
    public function iblockelementeditAction() {
        $iElementId = $this->_getParam('iElementId',0);
        
        if ( $iElementId > 0 ) {
            $oElement = Mg_Helper_IblockElement::getElement($iElementId);
        } else {
            $oElement = new Mg_Model_IblockElement();
        }
        
        // save
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            if ( isset($aParams['ibelementIdCategory']) ) {
                $oElement->id_category = $aParams['ibelementIdCategory'];
            }
            if ( !empty($aParams['ibelementName']) ) {
                $oElement->name = $aParams['ibelementName'];
            }
            if ( !empty($aParams['ibelementUrl']) ) {
                $oElement->url = $aParams['ibelementUrl'];
            }
            if ( !empty($aParams['ibelementShortDescription']) ) {
                $oElement->short_text = $aParams['ibelementShortDescription'];
            }
            if ( !empty($aParams['ibelementText']) ) {
                $oElement->text = $aParams['ibelementText'];
            }
            if ( !empty($aParams['ibelementTitle']) ) {
                $oElement->title = $aParams['ibelementTitle'];
            }
            if ( !empty($aParams['ibelementKeywords']) ) {
                $oElement->keywords = $aParams['ibelementKeywords'];
            }
            if ( !empty($aParams['ibelementDescription']) ) {
                $oElement->description = $aParams['ibelementDescription'];
            }
            
            $oElement->date_change = date('Y-m-d H:i:s');
            
            if ( $oElement->id_element == 0 ) {
                $oElement->id_user = 1;
                $oElement->id_status = 1;
            }
            
            $oElementMapper = new Mg_Model_Mapper_IblockElement();
            if ( $oElementMapper->save($oElement) ) {
                $this->redirect($this->view->url(array(),'index-cp-iblock-default'));
            }
            
        }
        
        $this->view->oCategory = Mg_Helper_IblockCategory::getIblockCategory($oElement->id_category);
        $this->view->aCategories = Mg_Helper_IblockCategory::getIblockCategoriesFlatTree($this->view->oCategory->id_iblock);
        $this->view->oElement = $oElement;
    }
    
    public function citiesAction() {
	$iPage = $this->_getParam('iPage',1);
	$oKladrMapper = new Mg_Model_Mapper_KladrDb();
	$oCities = $oKladrMapper->getList(null, array('name ASC'), $iPage, 20);
	
	$this->view->oCities = $oCities;
    }
    
    
    
    public function carsAction() {
	$iPage = $this->_getParam('iPage',1);
	$oCarMakersMapper = new Mg_Model_Mapper_CarMaker();
	$oCarMakers = $oCarMakersMapper->getList(null, array('name ASC'), $iPage, 20);
	
	$this->view->aTopButtons = array(
            array('href'=>'#', 'icon' => 'icon-plus', 'title' => 'Добавить марку'),
        );
	
        $this->view->aStatuses = Mg_Helper_Status::getStatusesAsArray($oCarMakersMapper->getDbTable()->getTable());
	$this->view->oCarMakers = $oCarMakers;
    }
    
    public function carmodelsAction() {
	$iPage = $this->_getParam('iPage',1);
	$iIdMaker = $this->_getParam('iMakerId',0);
	$oCarModelsMapper = new Mg_Model_Mapper_CarModel();
	$oCarMaker = Mg_Helper_Car::getMakerById($iIdMaker);
	
	$aWhere = array(
	    array('id_maker = ?', $iIdMaker),
	);
	$oCarModels = $oCarModelsMapper->getList($aWhere, array('name ASC'), $iPage, 20);
	// init navigation
	$oNavigation = Zend_Registry::get('Zend_Navigation');
	$oNavigation->findOneBy('route', 'index-cp-cars-models')->setParams(array('iMakerId' => $oCarMaker->id_maker, 'iPage' => $iPage,))->setLabel($oCarMaker->name);
	
        $this->view->aTopButtons = array(
            array('href'=>'#', 'icon' => 'icon-plus', 'title' => 'Добавить модель'),
        );
        
        $this->view->aStatuses = Mg_Helper_Status::getStatusesAsArray($oCarModelsMapper->getDbTable()->getTable());
	$this->view->oCarModels = $oCarModels;
    }
    
    public function carsubmodelsAction() {
	$iPage = $this->_getParam('iPage',1);
	$iIdModel = $this->_getParam('iModelId',0);
	$oCarSubmodelsMapper = new Mg_Model_Mapper_CarSubmodel();
	
	$oCarModel = Mg_Helper_Car::getModelById($iIdModel);
	$oCarMaker = Mg_Helper_Car::getMakerById($oCarModel->id_maker);
	
	$aWhere = array(
	    array('id_model = ?', $iIdModel),
	);
	$oCarSubmodels = $oCarSubmodelsMapper->getList($aWhere, array('name ASC'), $iPage, 20);
        
	// init navigation
	$oNavigation = Zend_Registry::get('Zend_Navigation');
	$oNavigation->findOneBy('route', 'index-cp-cars-models')->setParams(array('iMakerId' => $oCarMaker->id_maker, 'iPage' => $iPage,))->setLabel($oCarMaker->name);
	$oNavigation->findOneBy('route', 'index-cp-cars-submodels')->setParams(array('iModelId' => $oCarModel->id_model, 'iPage' => $iPage,))->setLabel($oCarModel->name);
	
        $this->view->aTopButtons = array(
            array('href'=>'#', 'icon' => 'icon-plus', 'title' => 'Добавить модификацию'),
        );
        
        $this->view->aStatuses = Mg_Helper_Status::getStatusesAsArray($oCarSubmodelsMapper->getDbTable()->getTable());
	$this->view->oCarModel = $oCarModel;
	$this->view->oCarSubmodels = $oCarSubmodels;
    }
    // -------------------------------------------------------
    public function toolsAction() {
        
    }
    
    public function kladrimportAction() {
        // KLADR
        $iNumRecords = Mg_Helper_KladrDb::getCountFromKladr();
        $this->view->iNumRecords = $iNumRecords;
    }
    
    public function kladrimportblockAction() {
        $this->_helper->layout->disableLayout();
        $iFromRecord = intval($this->getRequest()->getPost('from_record'));
        $iNumRecords = intval($this->getRequest()->getPost('num_records'));
        
        $iTotalRecords = Mg_Helper_KladrDb::getCountFromKladr();
        $aRecords = Mg_Helper_KladrDb::getFromKladr($iFromRecord, $iNumRecords);
        
        $iNumProcessed = 0;
        
        $oKladrMapper = new Mg_Model_Mapper_KladrDb();
        foreach ($aRecords as $aRecord) {
            $oKladrDbItem = new Mg_Model_KladrDb();
            $oKladrDbItem->id_region = $aRecord['region'];
            $oKladrDbItem->id_area = $aRecord['area'];
            $oKladrDbItem->id_city = $aRecord['city'];
            $oKladrDbItem->id_locality = $aRecord['locality'];
            $oKladrDbItem->name = $aRecord['name'];
            $oKladrDbItem->type = $aRecord['type'];
            $oKladrDbItem->status = $aRecord['status'];
            $oKladrDbItem->id_status = 1;
            
            $aResult = $oKladrMapper->save($oKladrDbItem);
            if ( !empty($aResult) ) {
                ++$iNumProcessed;
            }
        }
        
        $this->view->iRecordsProcessed = $iNumProcessed;
        $this->view->iPoolSize = count($aRecords);
        $this->view->iRecordsTotal = $iTotalRecords;
    }
}
