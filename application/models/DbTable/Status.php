<?php

class Mg_Model_DbTable_Status extends Mg_Model_DbTable_Abstract
{
    protected $_name = '';
    protected $_primary = 'id_status';
    
    public function setName($sName) {
        $this->_name = $sName;
    }
}

