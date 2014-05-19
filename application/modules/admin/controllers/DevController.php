<?php

class Admin_DevController extends Mg_Controller_Admin
{
    
    public function init() {
        parent::init();
        $this->_helper->AjaxContext()->addActionContext('ajaxcategoryfind', 'json')->initContext('json');
    }
    
    public function yaAction() {
        
        $sBaseUrl = 'http://geocode-maps.yandex.ru/1.x/';
        $aParams = array(
            'geocode' => '',
            'format' => 'json',
            'results' => 10,
            'skip' => 0,
            'lang' => 'ru_RU',
            'key' => 'ADqiUVMBAAAAbnanRQMATaGBVIcIAHwkqpVppSsdM4JIy9wAAAAAAAAAAAAGtOUrl8H0v7w-3qPn9Qs2EV7qEQ==',
            'll' => '37.654750,55.633798',
            'spn' => '0.067806,0.019106',
            'rspn' => 1,
        );
        
        $sResult = '';
        if ($this->getRequest()->isPost()) {
            $aPost = $this->getRequest()->getPost();
            //$aParams['geocode'] = 'город Москва ' . $aPost['geocode'];
            $aParams['geocode'] = $aPost['geocode'];
            
            $sUrl = $sBaseUrl . '?';
            foreach ($aParams as $sKey => $sParam) {
                $sUrl .= $sKey . '=' . urlencode($sParam) . '&';
            }
            $sResult = json_decode(file_get_contents($sUrl), true);
            
        }
        
        $this->view->aParams = $aParams;
        $this->view->sResult = $sResult;
    }
    
    public function indexAction() {
        
        $oClient = new \Elasticsearch\Client();
        $aIndex = array(
            'index' => 'mg',
        );
        if (!$oClient->indices()->exists($aIndex)) {
            $aResult = $oClient->indices()->create($aIndex);
        } else {
            /*$oClient->indices()->putMapping(array(
                'type' => 'page',
                'body' => array(
                    'page' => array(
                        '_source' => array('enabled'=>true),
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                                'analyzer' => 'standard',
                            ),
                            'url' => array(
                                'type' => 'string',
                                'analyzer' => 'standard',
                            ),
                            'text_full' => array(
                                'type' => 'string',
                                'analyzer' => 'standard',
                            ),
                        ),
                    ),
                ),
            ));*/
            var_dump($oClient->indices()->getMapping($aIndex));
            
            $oPage = Mg_Base_Helper_Page::getPage(1);
            $aParams = $oPage->getParams();
            
            $aDoc = array(
                'index' => 'mg',
                'type' => 'page',
                'id' => $aParams['id_page'],
                'body' => array(
                    'name' => $aParams['name'],
                    'url' => $aParams['url'],
                    'text_full' => $aParams['text'],
                ),
            );
            //var_dump($oClient->index($aDoc));
            
            $results = $oClient->search(array(
                'index' => 'mg',
                'body' => array(
                    'query' => array(
                        'match' => array(
                            'text_full' => 'компании',
                        ),
                    ),
                ),
            ));
            var_dump($results);
            
        }
        
        if ( $this->getRequest()->isPost() ) {
            $aPost = $this->getRequest()->getPost();
            $iObjId = intval($aPost['iObjId']);
            $iObjType = intval($aPost['iObjType']);
            
            $oStaticFile = new Mg_Addon_StaticFileImage($iObjId,$iObjType,$this->oConfig['staticfile']);
            $oFileAdapter = new Zend_File_Transfer_Adapter_Http();
            $oStaticFile->upload($oFileAdapter->getFileInfo());
        }
        
        $oStaticFile = new Mg_Addon_StaticFileImage(1,1,$this->oConfig['staticfile']);
        
        $this->view->aOrigFiles = Mg_Common_Helper_Image::getImages(1, 1, 'origin', 0);
        $this->view->aFiles = Mg_Common_Helper_Image::getImages(1, 1, 'big', 0);
    }
}
