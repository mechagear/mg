<?php

class Shop_ShopController extends Mg_Controller_Abstract 
{
    
    public function indexAction() {
        $oCategories = Mg_Shop_Helper_Category::getShopChildCategories(0);
        $this->view->oCategories = $oCategories;
    }
    
}
