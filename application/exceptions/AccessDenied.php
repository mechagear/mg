<?php

class Mg_Common_Exception_AccessDenied extends Mg_Common_Exception_Abstract
{
    const EXCEPTION_ACCESS_DENIED = 'EXCEPTION_ACCESS_DENIED';
    
    public $type = self::EXCEPTION_ACCESS_DENIED;
}
