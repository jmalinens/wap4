<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
    define('WURFL_DIR', 'F:/xampp/htdocs/bwap.localhost/global/classes/ScientiaMobile/WURFL/');
    define('WURFL_CACHE_DIR', 'F:/xampp/htdocs/bwap.localhost/global/cache/WURFL/');
} else {
    define('WURFL_DIR', '/home/bwap/global/classes/ScientiaMobile/WURFL/');
    define('WURFL_CACHE_DIR', '/home/bwap/global/cache/WURFL/');
}
require_once WURFL_DIR.'Application.php';
//require_once APPPATH . '/third_party/WURFL/Application.php';

class Wurfl {

    private $device;
    public  $id;
    public $fallBack;

    function __construct() {
        
        $sWURFLDir = WURFL_DIR;
        require_once $sWURFLDir.'/Application.php';
        $persistenceDir = WURFL_CACHE_DIR.'persistence';
        $cacheDir = WURFL_CACHE_DIR.'cache';
        $this->wurflConfig = new WURFL_Configuration_InMemoryConfig();
        $this->wurflConfig->wurflFile($sWURFLDir.'/wurfl.zip');
        $this->wurflConfig->matchMode('performance');
        $this->wurflConfig->allowReload(true);
        $this->wurflConfig->persistence('file', array('dir' => $persistenceDir));
        $this->wurflConfig->cache('file', array('dir' => $cacheDir, 'expiration' => 36000));
        $wurflManagerFactory = new WURFL_WURFLManagerFactory($this->wurflConfig);
        $wurflManager = $wurflManagerFactory->create();
        $wurflInfo = $wurflManager->getWURFLInfo();
        $this->device = $wurflManager->getDeviceForHttpRequest($_SERVER);

    }
    
    function build_persistence() {
        
        $persistenceStorage = WURFL_Storage_Factory::create($this->wurflConfig->persistence);
        $context = new WURFL_Context ($persistenceStorage);
        $userAgentHandlerChain = WURFL_UserAgentHandlerChainFactory::createFrom($context);

        $devicePatcher = new WURFL_Xml_DevicePatcher ();
        $deviceRepositoryBuilder = new WURFL_DeviceRepositoryBuilder ($persistenceStorage, $userAgentHandlerChain, $devicePatcher);

        return $deviceRepositoryBuilder->build($this->wurflConfig->wurflFile, $this->wurflConfig->wurflPatches);
        
    }

    function load() {
        $this->id = $this->device->id;
        $this->fallBack = $this->device->fallBack;
    }

    function getDevice() {
        return $this->device;
    }

    function getCapability($capabilityName = "") {
        return $this->device->getCapability($capabilityName);
    }

    function getAllCapabilities() {
        return $this->device->getAllCapabilities();
    }

    function getId() {
        return $this->id;
    }

    function getFallback() {
        return $this->fallback;
    }

}