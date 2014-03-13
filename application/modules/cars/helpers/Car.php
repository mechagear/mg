<?php

class Mg_Cars_Helper_Car 
{
    public static function getMakerById($iIdMaker) {
	$oCarMakerMapper = new Mg_Cars_Model_Mapper_CarMaker();
	$oMaker = $oCarMakerMapper->getItem($iIdMaker);
	return $oMaker;
    }
    
    public static function getModelById($iIdModel) {
	$oCarModelMapper = new Mg_Cars_Model_Mapper_CarModel();
	$oModel = $oCarModelMapper->getItem($iIdModel);
	return $oModel;
    }
    
    public static function getCarName($id_maker, $id_model = null, $id_submodel = null) {
        if ( empty($id_maker) || (empty($id_model) && empty($id_submodel)) ) {
            return false;
        }
        $aCarName = array();
        
        $oCarMakerMapper = new Mg_Cars_Model_Mapper_CarMaker();
        $oResult = $oCarMakerMapper->getItem($id_maker);
        $aMaker = $oResult->current()->toArray();
        $aCarName[] = $aMaker['name'];
        
        if ( !empty($id_submodel) ) {
            $oCarSubmodelMapper = new Mg_Cars_Model_Mapper_CarSubmodel();
            $oResult = $oCarSubmodelMapper->getItem($id_submodel);
        } else {
            $oCarModelMapper = new Mg_Cars_Model_Mapper_CarModel();
            $oResult = $oCarModelMapper->getItem($id_model);
        }
        $aModel = $oResult->current()->toArray();
        $aCarName[] = $aModel['name'];
        
        return implode(' ', $aCarName);
    }
    
    public function getUserCar($iIdUserCar) {
        $oUserCar = new Mg_Cars_Model_UserCar();
        if ( !empty($iIdUserCar) ) {
            $oUserCarMapper = new Mg_Cars_Model_Mapper_UserCar();
            $oResult = $oUserCarMapper->getItem($iIdUserCar);
            $oUserCar->setParams($oResult->current()->toArray());
        }
        return $oUserCar;
    }
    
    public static function getMakers() {
        $aMakers = array();
        $oCarMakerMapper = new Mg_Cars_Model_Mapper_CarMaker();
        $oResult = $oCarMakerMapper->getList(null, array('name ASC'));
        if ( $oResult->count() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oRow) {
                $aMakers[] = new Mg_Cars_Model_CarMaker($oRow->toArray());
            }
        }
        return $aMakers;
    }
    
    public static function getModelsByMaker($iMakerId) {
        $aMakers = array();
        $oCarModelMapper = new Mg_Cars_Model_Mapper_CarModel();
        $oResult = $oCarModelMapper->getList(array(array('id_maker = ?', $iMakerId)), array('name ASC'));
        if ( $oResult->count() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oRow) {
                $aMakers[] = $oRow;
            }
        }
        return $aMakers;
    }
    
    public static function getSubmodelsByModel($iModelId) {
        $aSubmodels = array();
        $oCarSubmodelMapper = new Mg_Cars_Model_Mapper_CarSubmodel();
        $oResult = $oCarSubmodelMapper->getList(array(array('id_model = ?', $iModelId)), array('name ASC'));
        if ( $oResult->count() > 0 ) {
            foreach ($oResult->getCurrentItems() as $oRow) {
                $aSubmodels[] = new Mg_Cars_Model_CarSubmodel($oRow->toArray());
            }
        }
        return $aSubmodels;
    }
    
}
