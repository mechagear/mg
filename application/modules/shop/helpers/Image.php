<?php

class Mg_Shop_Helper_Image extends Mg_Common_Helper_Image
{   
    const OBJ_SHOP_ITEM_IMAGE = 101;
    
    protected static $aBounds = array(101, 200);
    protected static $aPreferences = array(
        'shop_item_preview' => array(
            'width' => 150,
            'height' => 100,
            'w_eq_h' => false,
            'crop' => true,
            'quality' => 80,
            'blur' => 1,
            'alpha' => false,
            'type' => 'jpg',
            'exact' => true,
            'watermark' => false,
            'thumbnail' => true,
            'version' => 2,
        ),
    );
    
    public static function updateOrderByOriginName($iObjId, $iObjType, $sImageName, $iOrder) {
        $aConfig = Zend_Registry::get('config');
        $sClass = get_called_class();
        $aPrefs = $sClass::$aPreferences;
        $aConfig['staticfile']['image']['preferences'] = array_merge($aConfig['staticfile']['image']['preferences'], $aPrefs);
        $oStaticFileImage = new Mg_Addon_StaticFileImage($iObjId, $iObjType, $aConfig['staticfile']);
        foreach ($oStaticFileImage->getFiles() as $iSequence => $aFiles) {
            if ( $aFiles['origin']['name'] == $sImageName ) {
                $oStaticFileImage->updateItem(array('order' => $iOrder), $iSequence, 'origin');
                return;
            }
        }
    }
}