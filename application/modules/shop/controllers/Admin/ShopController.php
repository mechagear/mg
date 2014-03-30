<?php

class Shop_Admin_ShopController extends Mg_Controller_Admin
{
    public function init() {
        parent::init();
        $this->_helper->AjaxContext()->addActionContext('ajaxcategoryfind', 'json')->initContext('json');
    }
    
    protected function setNav($aParams) {
        $oNavigation = Zend_Registry::get('Zend_Navigation');
        $oCurPage = $oNavigation->findBy('route', 'shop-list');
        if ( !$oCurPage ) {
            return;
        }
        if ( !empty($aParams['shop']) ) {
            $oPage = new Zend_Navigation_Page_Mvc();
            $oPage->setLabel($aParams['shop']->name);
            $oPage->setRoute('shop-categories-list');
            $oPage->setParams(array(
                'iShopId' => $aParams['shop']->id_shop,
                'iCategoryId' => 0,
            ));
            $oPage->setActive(true);
            $oPage->setParent($oCurPage);
            
            if ( !empty($aParams['categories']) && is_array($aParams['categories']) ) {
                foreach ($aParams['categories'] as $oCategory) {
                    if ( ! $oCategory instanceof Mg_Shop_Model_Category ) {
                        continue;
                    }
                    $oPage->setActive(false);
                    $oCurPage = $oPage;
                    $oPage = new Zend_Navigation_Page_Mvc();
                    $oPage->setLabel($oCategory->name);
                    $oPage->setRoute('shop-categories-list');
                    $oPage->setParams(array(
                        'iShopId' => $oCategory->id_shop,
                        'iCategoryId' => $oCategory->id_category,
                    ));
                    $oPage->setActive(true);
                    $oPage->setParent($oCurPage);
                }
            }
            
            if ( !empty($aParams['category']) && $aParams['category']->id_category > 0 ) {
                $oPage->setActive(false);
                $oCurPage = $oPage;
                $oPage = new Zend_Navigation_Page_Mvc();
                $oPage->setLabel($aParams['category']->name);
                $oPage->setRoute('shop-categories-list');
                $oPage->setParams(array(
                    'iShopId' => $aParams['shop']->id_shop,
                    'iCategoryId' => $aParams['category']->id_category,
                ));
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
            }
            
            if ( !empty($aParams['items']) ) {
                $oPage->setActive(false);
                $oCurPage = $oPage;
                $oPage = new Zend_Navigation_Page_Mvc();
                $oPage->setLabel('Товары');
                $oPage->setRoute('shop-categories-items-list');
                $oPage->setParams(array(
                    'iShopId' => $aParams['shop']->id_shop,
                    'iCategoryId' => $aParams['category']->id_category,
                ));
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
            }
            
            if ( !empty($aParams['measures']) ) {
                $oPage->setActive(false);
                $oCurPage = $oPage;
                $oPage = new Zend_Navigation_Page_Mvc();
                $oPage->setLabel('Единицы измерения');
                $oPage->setRoute('shop-categories-measures-list');
                $oPage->setParams(array(
                    'iShopId' => $aParams['shop']->id_shop,
                ));
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
            }
            
            if ( !empty($aParams['properties']) ) {
                $oPage->setActive(false);
                $oCurPage = $oPage;
                $oPage = new Zend_Navigation_Page_Mvc();
                $oPage->setLabel('Характеристики');
                $oPage->setRoute('shop-categories-properties-list');
                $oPage->setParams(array(
                    'iShopId' => $aParams['shop']->id_shop,
                ));
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
            }
            
            if ( !empty($aParams['edit']) ) {
                $oPage->setActive(false);
                $oCurPage = $oPage;
                $oPage = new Zend_Navigation_Page_Uri();
                $oPage->setLabel('Редактор');
                $oPage->setActive(true);
                $oPage->setParent($oCurPage);
            }
        }
        
    }
    
    /**
     * Shops list
     */
    public function listAction() {
        $oShopMapper = new Mg_Shop_Model_Mapper_Shop();
        $oShops = $oShopMapper->getList(null, array('name ASC'), 0, 0);
        
        $this->view->oShops = $oShops;
    }
    /**
     * Shop edit
     */
    public function editAction() {
        $iShopId = intval($this->_getParam('iShopId',0));
        
        if ($iShopId > 0) {
            $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        } else {
            $oShop = new Mg_Shop_Model_Shop();
        }
        
        $oShopMapper = new Mg_Shop_Model_Mapper_Shop();
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['shopName']) ) {
                $oShop->name = $aParams['shopName'];
            }
            if ( !empty($aParams['shopCode']) ) {
                $oShop->code = $aParams['shopCode'];
            }
            if ( !empty($aParams['shopIdStatus']) ) {
                $oShop->id_status = $aParams['shopIdStatus'];
            }
            
            if ( $oShop->id_shop == 0 ) {
                $oShop->id_user = 1;
            }
            
            if ( $oShopMapper->save($oShop) ) {
                $this->redirect($this->view->url(array(),'shop-list'));
            }
        }
        
        $this->setNav(array('shop' => $oShop, 'edit'=>true,));
        
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oShopMapper->getDbTable()->getTable());
        $this->view->oShop = $oShop;
    }
    
    /**
     * Shop categories list
     */
    public function categoryAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iCategoryId = intval($this->_getParam('iCategoryId', 0));
        $iPage = intval($this->_getParam('iPage', 1));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( 0 >= $oShop->id_shop ) {
            throw new Mg_Common_Exception_NotFound();
            exit;
        }
        
        // current category
        if ($iCategoryId > 0) {
            $oCategory = Mg_Shop_Helper_Category::getCategory($iCategoryId);
        } else {
            $oCategory = new Mg_Shop_Model_Category();
        }
        
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        
        // Get binded categories
        $aBndCategories = Mg_Common_Helper_Bnd::getChildsAsArray($oCategory->id_category, $oCategoryMapper->getDbTable()->getTable());
        
        // Get categories
        $aColumns = array();
        $oSelect = new Zend_Db_Select($oCategoryMapper->getDbTable()->getAdapter());
        $oSelect->from($oCategoryMapper->getDbTable()->getTable());
        $oSelect = $oSelect->where('id_shop = ' . $iShopId . ' AND id_parent ' . (($iCategoryId > 0) ? '= ?' : 'IS NULL'), $iCategoryId);
        if ( !empty($aBndCategories) ) {
            $oSelect = $oSelect->orWhere('id_category IN (' . implode(',', $aBndCategories) . ')');
            $aColumns[] = '(CASE WHEN `id_category` IN (' . implode(',', $aBndCategories) . ') THEN 1 ELSE 0 END) AS cross_flag';
        }
        $oSelect = $oSelect->columns($aColumns);
        $oCategories = $oCategoryMapper->getListExt($oSelect, $iPage, 20);
        // ---
        
        $this->view->aParentCategories = array_reverse(Mg_Shop_Helper_Category::getShopParentCategories($oCategory->id_parent));
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oCategoryMapper->getDbTable()->getTable());
        $this->view->oShop = $oShop;
        $this->view->oCategory = $oCategory;
        $this->view->oCategories = $oCategories;
        
        $this->setNav(array('shop' => $oShop, 'categories' => $this->view->aParentCategories, 'category'=>$oCategory,));
    }
    /**
     * 
     * @throws Mg_Common_Exception_NotFound
     */
    public function categoryeditAction() {
        $iCategoryId = intval($this->_getParam('iCategoryId', 0));
        $iParentId = intval($this->_getParam('iParentId', 0));
        $iShopId = intval($this->_getParam('iShopId', 0));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( 0 >= $oShop->id_shop ) {
            throw new Mg_Common_Exception_NotFound();
            exit;
        }
        
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        
        if ( $iCategoryId > 0 ) {
            $oCategory = Mg_Shop_Helper_Category::getCategory($iCategoryId);
        } else {
            $oCategory = new Mg_Shop_Model_Category();
        }
        
        if ( $iParentId > 0 ) {
            $oParentCategory = Mg_Shop_Helper_Category::getCategory($iParentId);
        } else {
            $oParentCategory = new Mg_Shop_Model_Category();
        }
        
        // Get binded categories
        $aBndCategories = Mg_Common_Helper_Bnd::getChildsAsArray($oCategory->id_category, $oCategoryMapper->getDbTable()->getTable());
        
        if ( !empty($aBndCategories) ) {
            $oBndCategories = $oCategoryMapper->getList(array('id_category IN (' . implode(',', $aBndCategories) . ')'), 'name ASC');
        } else {
            $oBndCategories = null;
        }
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['categoryIdParent']) ) {
                $oCategory->id_parent = $aParams['categoryIdParent'];
            }
            
            if ( !empty($aParams['categoryName']) ) {
                $oCategory->name = $aParams['categoryName'];
            }
            if ( !empty($aParams['categoryUrl']) ) {
                $oCategory->url = $aParams['categoryUrl'];
            }
            if ( !empty($aParams['categoryIdStatus']) ) {
                $oCategory->id_status = $aParams['categoryIdStatus'];
            }
            
            if ( $oCategory->id_category == 0 ) {
                $oCategory->id_user = 1;
                $oCategory->id_shop = $iShopId;
            }
            
            if ( $iId = $oCategoryMapper->save($oCategory) || ($oCategory->id_category > 0 && !empty($aParams['categoryCrosses'])) ) {
                // Update bindings
                if ( 0 == $oCategory->id_category ) {
                    $oCategory->id_category = $iId;
                }
                
                $aBndToRemove = array();
                $aBndToAdd = array();
                if ( !empty($aParams['categoryCrosses']) && is_array($aParams['categoryCrosses']) ) {
                    $oBndMapper = new Mg_Model_Mapper_Bnd($oCategoryMapper->getDbTable()->getTable());
                    foreach ($aParams['categoryCrosses'] as $iCrossId) {
                        $iCrossId = intval($iCrossId);
                        if ( $iCrossId <= 0 ) {
                            continue;
                        }
                        // check if binding exists
                        $oBnd = $oBndMapper->getItem(array($oCategory->id_category, $iCrossId));
                        if ( false !== ($iKey = array_search($oBnd->id_child, $aBndCategories)) ) {
                            // if exists - remove it from array and do nothing
                            unset($aBndCategories[$iKey]);
                        } else {
                            // else add new binding
                            $oBnd = new Mg_Model_Bnd(array('id_parent'=>$oCategory->id_category,'id_child'=>$iCrossId));
                            $oBndMapper->save($oBnd);
                        }
                    }
                    // remove deleted bindings
                    if ( !empty($aBndCategories) ) {
                        foreach ($aBndCategories as $iCrossId) {
                            $oBndMapper->delete(array($oCategory->id_category, $iCrossId));
                        }
                    }
                }
                $this->redirect($this->view->url(array('iShopId'=>$oCategory->id_shop ,'iCategoryId' => $oCategory->id_parent, 'iPage' => 1,),'shop-categories-list'));
            }
        }
        
        $this->view->aParentCategories = array_reverse(Mg_Shop_Helper_Category::getShopParentCategories($oCategory->id_parent));
        $this->view->aCategories = Mg_Shop_Helper_Category::getShopCategoriesFlatTree($iShopId);
        $this->view->oBndCategories = $oBndCategories;
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oCategoryMapper->getDbTable()->getTable());
        $this->view->oParent = $oParentCategory;
        $this->view->oCategory = $oCategory;
        $this->view->oShop = $oShop;
        
        $this->setNav(array('shop' => $oShop, 'categories' =>$this->view->aParentCategories, 'category' => $oCategory, 'edit' => true));
    }
    
    
    public function itemsAction() {
        $iCategoryId = intval($this->_getParam('iCategoryId', 0));
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iPage = intval($this->_getParam('iPage', 1));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        $oCategory = Mg_Shop_Helper_Category::getCategory($iCategoryId);
        if ( 0 >= $oShop->id_shop || 0 >= $oCategory->id_category ) {
            throw new Mg_Common_Exception_NotFound();
            exit;
        }
        
        $oItemMapper = new Mg_Shop_Model_Mapper_Item();
        
        // Get binded categories
        $aBndItems = Mg_Common_Helper_Bnd::getChildsAsArray($oCategory->id_category, $oItemMapper->getDbTable()->getTable());
        
        // Get items
        $aColumns = array();
        $oSelect = new Zend_Db_Select($oItemMapper->getDbTable()->getAdapter());
        $oSelect->from($oItemMapper->getDbTable()->getTable());
        $oSelect = $oSelect->where('id_shop = ' . $iShopId . ' AND id_category ' . (($iCategoryId > 0) ? '= ?' : 'IS NULL'), $iCategoryId);
        if ( !empty($aBndItems) ) {
            $oSelect = $oSelect->orWhere('id_item IN (' . implode(',', $aBndItems) . ')');
            $aColumns[] = '(CASE WHEN `id_item` IN (' . implode(',', $aBndItems) . ') THEN 1 ELSE 0 END) AS cross_flag';
        }
        $oSelect = $oSelect->columns($aColumns);
        $oItems = $oItemMapper->getListExt($oSelect, $iPage, 20);
        // ---
        
        $this->view->aParentCategories = array_reverse(Mg_Shop_Helper_Category::getShopParentCategories($oCategory->id_parent));
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oItemMapper->getDbTable()->getTable());
        $this->view->oItems = $oItems;
        $this->view->oCategory = $oCategory;
        $this->view->oShop = $oShop;
        
        $this->setNav(array('shop' => $oShop, 'categories' =>$this->view->aParentCategories, 'category' => $oCategory, 'items' => true,));
    }
    
    /**
     * Edit item
     * @throws Mg_Common_Exception_NotFound
     */
    public function itemeditAction() {
        $iCategoryId = intval($this->_getParam('iCategoryId', 0));
        $iItemId = intval($this->_getParam('iItemId', 0));
        
        $oCategory = Mg_Shop_Helper_Category::getCategory($iCategoryId);
        $oShop = Mg_Shop_Helper_Shop::getShop($oCategory->id_shop);
        if ( 0 >= $oCategory->id_category ) {
            throw new Mg_Common_Exception_NotFound();
            exit;
        }
        
        $oItemMapper = new Mg_Shop_Model_Mapper_Item();
        
        if ( $iItemId > 0 ) {
            $oItem = Mg_Shop_Helper_Item::getItem($iItemId);
        } else {
            $oItem = new Mg_Shop_Model_Item();
        }
        
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        
        // Get binded categories
        $aBndCategories = Mg_Common_Helper_Bnd::getParentsAsArray($oItem->id_item, $oItemMapper->getDbTable()->getTable());
        
        if ( !empty($aBndCategories) ) {
            $oBndCategories = $oCategoryMapper->getList(array('id_category IN (' . implode(',', $aBndCategories) . ')'), 'name ASC');
        } else {
            $oBndCategories = null;
        }
        
        // Get Descriptions
        $oShortText = Mg_Shop_Helper_ItemDescription::getDescriptionByCode('SHORT');
        $oFullText = Mg_Shop_Helper_ItemDescription::getDescriptionByCode('FULL');
        
        // Get images
        $aImages = ($oItem->id_item) ? Mg_Shop_Helper_Image::getImages($oItem->id_item, Mg_Shop_Helper_Image::OBJ_SHOP_ITEM_IMAGE, 'shop_item_preview', 1) : array();
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['itemIdCategory']) ) {
                $oItem->id_category = $aParams['itemIdCategory'];
            }
            if ( !empty($aParams['itemName']) ) {
                $oItem->name = $aParams['itemName'];
            }
            if ( !empty($aParams['itemMarking']) ) {
                $oItem->marking = $aParams['itemMarking'];
            }
            if ( !empty($aParams['itemUrl']) ) {
                $oItem->url = $aParams['itemUrl'];
            }
            if ( !empty($aParams['itemTitle']) ) {
                $oItem->title = $aParams['itemTitle'];
            }
            if ( !empty($aParams['itemDescription']) ) {
                $oItem->description = $aParams['itemDescription'];
            }
            if ( !empty($aParams['itemKeywords']) ) {
                $oItem->keywords = $aParams['itemKeywords'];
            }
            if ( !empty($aParams['itemIdStatus']) ) {
                $oItem->id_status = $aParams['itemIdStatus'];
            }
            
            if ( $oItem->id_item == 0 ) {
                // reload category if changed
                if ( $oItem->id_category != $oCategory->id_category ) {
                    $oCategory = Mg_Shop_Helper_Category::getCategory($oItem->id_category);
                }
                $oItem->id_shop = $oCategory->id_shop;
                $oItem->id_user = 1;
            }
            
            $oFileAdapter = new Zend_File_Transfer_Adapter_Http();
            $aUploadFiles = $oFileAdapter->getFileInfo();
            
            if ( $iId = $oItemMapper->save($oItem) || ($oItem->id_item > 0 && !empty($aParams['categoryCrosses'])) || !empty($aUploadFiles) || !empty($aParams['itemImages']) || $oItem == Mg_Shop_Helper_Item::getItem($oItem->id_item) ) {
                // Update bindings
                if ( 0 == $oItem->id_item ) {
                    $oItem->id_item = $iId;
                }
                
                $oItemDescriptionMapper = new Mg_Shop_Model_Mapper_ItemDescription();
                if ( !empty($aParams['itemShortText']) ) {
                    if ( $oShortText->id_description <= 0 ) {
                        $oShortText->id_item = $oItem->id_item;
                        $oShortText->code = 'SHORT';
                    }
                    $oShortText->text = $aParams['itemShortText'];
                    $oItemDescriptionMapper->save($oShortText);
                }
                if ( !empty($aParams['itemFullText']) ) {
                    if ( $oFullText->id_description <= 0 ) {
                        $oFullText->id_item = $oItem->id_item;
                        $oFullText->code = 'FULL';
                    }
                    $oFullText->text = $aParams['itemFullText'];
                    $oItemDescriptionMapper->save($oFullText);
                }
                
                $aBndToRemove = array();
                $aBndToAdd = array();
                if ( !empty($aParams['categoryCrosses']) && is_array($aParams['categoryCrosses']) ) {
                    $oBndMapper = new Mg_Model_Mapper_Bnd($oItemMapper->getDbTable()->getTable());
                    foreach ($aParams['categoryCrosses'] as $iCrossId) {
                        $iCrossId = intval($iCrossId);
                        if ( $iCrossId <= 0 ) {
                            continue;
                        }
                        // check if binding exists
                        $oBnd = $oBndMapper->getItem(array($iCrossId, $oItem->id_item));
                        if ( false !== ($iKey = array_search($oBnd->id_parent, $aBndCategories)) ) {
                            // if exists - remove it from array and do nothing
                            unset($aBndCategories[$iKey]);
                        } else {
                            // else add new binding
                            $oBnd = new Mg_Model_Bnd(array('id_parent'=>$iCrossId,'id_child'=>$oItem->id_item));
                            $oBndMapper->save($oBnd);
                        }
                    }
                    // remove deleted bindings
                    if ( !empty($aBndCategories) ) {
                        foreach ($aBndCategories as $iCrossId) {
                            $oBndMapper->delete(array($iCrossId, $oItem->id_item));
                        }
                    }
                }
                
                if ( !empty($aUploadFiles) ) {
                    Mg_Shop_Helper_Image::uploadImage($oItem->id_item, Mg_Shop_Helper_Image::OBJ_SHOP_ITEM_IMAGE, $aUploadFiles);
                }
                
                if ( !empty($aParams['itemImages']) ) {
                    $iOrder = 0;
                    foreach ($aParams['itemImages'] as $sImageName) {
                        Mg_Shop_Helper_Image::updateOrderByOriginName($oItem->id_item, Mg_Shop_Helper_Image::OBJ_SHOP_ITEM_IMAGE, $sImageName, $iOrder);
                        ++$iOrder;
                    }
                    // reload images
                    $aImages = Mg_Shop_Helper_Image::getImages($oItem->id_item, Mg_Shop_Helper_Image::OBJ_SHOP_ITEM_IMAGE, 'shop_item_preview', 1);
                }
                
                if ( !empty($aParams['return']) ) {
                    $this->redirect($this->view->url(array('iShopId'=>$oItem->id_shop ,'iCategoryId' => $oItem->id_category, 'iPage' => 1,),'shop-categories-items-list'));
                } else {
                    $this->redirect($this->view->url(array('iShopId'=>$oItem->id_shop, 'iCategoryId' => $oItem->id_category, 'iItemId' => $oItem->id_item), 'shop-categories-items-edit'));
                }
            }
        }
        
        $this->view->aCategories = Mg_Shop_Helper_Category::getShopCategoriesFlatTree($oCategory->id_shop);
        $this->view->oBndCategories = $oBndCategories;
        $this->view->aStatuses = Mg_Common_Helper_Status::getStatusesAsArray($oItemMapper->getDbTable()->getTable());
        $this->view->oItem = $oItem;
        $this->view->oShortText = $oShortText;
        $this->view->oFullText = $oFullText;
        $this->view->aImages = $aImages;
        $this->view->oCategory = $oCategory;
        $this->view->oShop = $oShop;
        
        $this->setNav(array('shop' => $oShop, 'categories' =>$this->view->aParentCategories, 'category' => $oCategory, 'items' => true, 'edit' => true,));
    }
    
    /**
     * Properties list
     */
    public function propertiesAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iPage = $this->_getParam('iPage', 1);
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        
        $oShopPropertyMapper = new Mg_Shop_Model_Mapper_ShopProperty();
        $aWhere = array(
            array('id_shop = ?', $iShopId),
        );
        $oShopProperties = $oShopPropertyMapper->getList($aWhere, null, $iPage, 20);
        $aShopPropertyTypes = Mg_Shop_Helper_ShopProperty::getPropertyTypes();
        $aShopPropertyViewTypes = Mg_Shop_Helper_ShopProperty::getPropertyViewTypes();
        
        $this->view->aShopPropertyTypes = $aShopPropertyTypes;
        $this->view->aShopPropertyViewTypes = $aShopPropertyViewTypes;
        $this->view->oShopProperties = $oShopProperties;
        $this->view->oShop = $oShop;
        
        $this->setNav(array('shop' => $oShop, 'properties' => true,));
    }
    
    public function propertyeditAction() {
        $iShopId = $this->_getParam('iShopId', 0);
        $iPropertyId = intval($this->_getParam('iPropertyId', 0));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( $iPropertyId > 0 ) {
            $oProperty = Mg_Shop_Helper_ShopProperty::getProperty($iPropertyId);
        } else {
            $oProperty = new Mg_Shop_Model_ShopProperty();
        }
        
        $aShopPropertyTypes = Mg_Shop_Helper_ShopProperty::getPropertyTypes();
        $aShopPropertyViewTypes = Mg_Shop_Helper_ShopProperty::getPropertyViewTypes();
        $oMeasures = Mg_Shop_Helper_Measure::getMeasureByShop($iShopId);
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['propertyType']) ) {
                $oProperty->type = $aParams['propertyType'];
            }
            if ( !empty($aParams['propertyViewType']) ) {
                $oProperty->view_type = $aParams['propertyViewType'];
            }
            if ( !empty($aParams['propertyName']) ) {
                $oProperty->name = $aParams['propertyName'];
            }
            if ( !empty($aParams['propertyType']) ) {
                $oProperty->id_measure = $aParams['propertyMeasure'];
            }
            
            if ( $oProperty->id_property <= 0 ) {
                $oProperty->id_shop = $oShop->id_shop;
            }
            
            $oPropertyMapper = new Mg_Shop_Model_Mapper_ShopProperty();
            
            if ( $iId = $oPropertyMapper->save($oProperty) ) {
                $this->redirect($this->view->url(array('iShopId'=>$oShop->id_shop, 'iPage' => 1,),'shop-categories-properties-list'));
                exit;
            }
            
        }
        
        $this->view->oMeasures = $oMeasures;
        $this->view->aShopPropertyTypes = $aShopPropertyTypes;
        $this->view->aShopPropertyViewTypes = $aShopPropertyViewTypes;
        $this->view->oShopProperty = $oProperty;
        
    }
    
    /**
     * Measures list
     */
    public function measuresAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iPage = $this->_getParam('iPage', 1);
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        
        
        $oShopMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
        $aWhere = array(
            array('id_shop = ?', $iShopId),
        );
        $oShopMeasures = $oShopMeasureMapper->getList($aWhere, null, $iPage, 20);
        
        $this->view->oShopMeasures = $oShopMeasures;
        $this->view->oShop = $oShop;
        
        $this->setNav(array('shop' => $oShop, 'measures' => true,));
    }
    
    public function measureeditAction() {
        $iShopId = intval($this->_getParam('iShopId', 0));
        $iMeasureId = intval($this->_getParam('iMeasureId', 0));
        
        $oShop = Mg_Shop_Helper_Shop::getShop($iShopId);
        if ( $iMeasureId > 0 ) {
            $oMeasure = Mg_Shop_Helper_Measure::getMeasure($iMeasureId);
        } else {
            $oMeasure = new Mg_Shop_Model_Measure();
        }
        
        if ( $this->getRequest()->isPost() ) {
            $aParams = $this->getRequest()->getPost();
            
            if ( !empty($aParams['measureNameFull']) ) {
                $oMeasure->name_full = $aParams['measureNameFull'];
            }
            if ( !empty($aParams['measureNameShort']) ) {
                $oMeasure->name_short = $aParams['measureNameShort'];
            }
            if ( !empty($aParams['measureForms']) ) {
                $oMeasure->forms = array_slice($aParams['measureForms'], 0, 3);
            }
            
            if ( $oMeasure->id_measure <= 0 ) {
                $oMeasure->id_shop = $oShop->id_shop;
            }
            
            $oMeasureMapper = new Mg_Shop_Model_Mapper_Measure();
            
            if ( $iId = $oMeasureMapper->save($oMeasure) ) {
                $this->redirect($this->view->url(array('iShopId'=>$oShop->id_shop, 'iPage' => 1,),'shop-categories-measures-list'));
                exit;
            }
        }
        
        $this->view->oShop = $oShop;
        $this->view->oMeasure = $oMeasure;
        
        $this->setNav(array('shop' => $oShop, 'measures' => true, 'measure' => $oMeasure, 'edit' => true,));
    }
    
    
    // ------------------ AJAX ----------------
    public function ajaxcategoryfindAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $aResult = array();
        
        $iShopId = $this->getRequest()->getPost('id_shop');
        $sSearchString = $this->getRequest()->getPost('search');
        
        $oCategoryMapper = new Mg_Shop_Model_Mapper_Category();
        // Search by ID
        $iCategoryId = intval($sSearchString);
        if ( $iCategoryId > 0 ) {
            $oCategory = Mg_Shop_Helper_Category::getCategory($iCategoryId);
            if ( $oCategory ) {
                $aResult[] = $oCategory->getParams();
            }
        }
        
        $sSearchString = preg_replace('/[^-_ 0-9a-zа-яё]/iu', '', $sSearchString);
        if ( strlen($sSearchString) > 2 ) {
            // Search by other params
            $aWhere = array(
                new Zend_Db_Expr('`name` LIKE \'%' . $sSearchString . '%\' OR `url` LIKE \'%' . $sSearchString . '%\'')
            );
            $oResult = $oCategoryMapper->getList($aWhere, NULL);
            if ( $oResult->getCurrentItemCount() ) {
                foreach ( $oResult->getCurrentItems() as $oItem ) {
                    $aResult[] = $oItem->getParams();
                }
            }
        }
        
        $this->view->result = $aResult;
        $this->view->search = $sSearchString;
    }
}
