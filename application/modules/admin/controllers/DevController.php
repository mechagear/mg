<?php

class Admin_DevController extends Mg_Controller_Abstract
{
    protected $oConfig;
    
    public function init() {
        parent::init();
        if (!$this->oAcl->isAllowed($this->oUser, 'cp', 'view') && !in_array($this->getRequest()->getActionName(), array('auth', 'unauth'))) {
            $this->redirect($this->view->url(array(),'auth'), array('exit' => true,));
            throw new Mg_Common_Exception_AccessDenied('No access');
            exit;
        }
        
        $this->_helper->AjaxContext()->addActionContext('ajaxcategoryfind', 'json')->initContext('json');
        
        $this->oConfig = Zend_Registry::get('config');
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
