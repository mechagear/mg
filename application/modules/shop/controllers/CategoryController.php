<?php

class Shop_CategoryController extends Mg_Controller_Abstract 
{
    
    public function indexAction() {
        $sCategoryUrl = $this->_getParam('sCategoryUrl');
        
        $oCategory = Mg_Shop_Helper_Category::getCategoryByUrl($sCategoryUrl);
        $oCategories = Mg_Shop_Helper_Category::getShopChildCategories($oCategory->id_category);
        
    }
    
}
