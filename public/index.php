<?php
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('CACHE_PATH') || define('CACHE_PATH', realpath(dirname(__FILE__) . '/../cache'));
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
// Define domains path
define('DOMAINS_PATH', realpath(dirname(__FILE__) . '/../domains'));
define('PUBLIC_PATH', realpath(dirname(__FILE__)));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH . '/../library'),)));
include_once realpath(APPLICATION_PATH . '/../library') . '/Pimple/Pimple.php';

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Config/Xml.php';

/* ============================== APP INIT ================================= */
$sDomain = $_SERVER['HTTP_HOST'];
$aDomainParts = explode('.', $_SERVER['HTTP_HOST']);

$sDomainsXmlPath = DOMAINS_PATH . '/domains.xml';
if ( !file_exists($sDomainsXmlPath) ) {
    die('Corrupted domain config.');
}
$oDomainsConfig = new Zend_Config_Xml($sDomainsXmlPath);
// Trying to find current domain
$sCurrentDomainKey = '';
$bIsPrimary = false;
foreach ( $oDomainsConfig as $sDomainKey => $oDomain ) {
    foreach ($oDomain->hosts as $sType => $oHost) {
        $aHost = explode('.', (!empty($oHost->host))?$oHost->host:'');
        if ($aHost == $aDomainParts) { // exact
            $sCurrentDomainKey = $sDomainKey;
            $bIsPrimary = ('primary' == $sType) ? true : false;
        } elseif ( empty($sCurrentDomainKey) && $aHost[0] == '*' && array_slice($aHost, 1) == array_slice($aDomainParts, 1)) { // multi
            // Redefine only if nothing was found earlier (for multi). Can't be primary
            $sCurrentDomainKey = ( empty($sCurrentDomainKey) ) ? $sDomainKey : $sCurrentDomainKey;
        }
    }
}

// Define path by domain key
if ( !empty($sCurrentDomainKey) ) {
    if ( !$bIsPrimary && $oDomainsConfig->$sCurrentDomainKey->options->redirect_secondary ) {
        header('Location: http://' . $oDomainsConfig->$sCurrentDomainKey->hosts->primary->host, true, 301);
        exit;
    }
    $sDomainDir = ( $oDomainsConfig->$sCurrentDomainKey->dir ) ? $oDomainsConfig->$sCurrentDomainKey->dir : $sDomain;
    if ( file_exists(DOMAINS_PATH . '/' . $sDomainDir) ) {
        define('DOMAIN_KEY', strtoupper($sCurrentDomainKey));
        define('DOMAIN_PATH', DOMAINS_PATH . '/' . $sDomainDir);
        define('PUBLIC_FILES_PATH', '/domains/' . $sDomainDir);
    }   
}

// Or define paths if not found domain config or no domain key
defined('DOMAIN_KEY') || define('DOMAIN_KEY', 'DEFAULT');
defined('DOMAIN_PATH') || define('DOMAIN_PATH', DOMAINS_PATH . '/default');
defined('PUBLIC_FILES_PATH') || define('PUBLIC_FILES_PATH', '/domains/default');
define('ROUTES_PATH', DOMAIN_PATH . '/routes.xml');

$oAppConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/mechagear.ini', APPLICATION_ENV, array('allowModifications' => true,));
$oAppExtendConfig = new Zend_Config_Ini(DOMAIN_PATH . '/config.ini', APPLICATION_ENV);
$oAppConfig = $oAppConfig->merge($oAppExtendConfig);
// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV, $oAppConfig);
$application->bootstrap()->run();