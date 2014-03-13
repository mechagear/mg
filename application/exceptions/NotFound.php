<?php

class Mg_Common_Exception_NotFound extends Mg_Common_Exception_Abstract
{
    const EXCEPTION_NOT_FOUND = 'EXCEPTION_NOT_FOUND';
    
    public $type = self::EXCEPTION_NOT_FOUND;
}

