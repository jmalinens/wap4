<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Wurfl {

var $wurflManager;
var $device;
var $id;
var $fallBack;

public function Wurfl(){


require_once APPPATH.'/libraries/WURFL/Application.php';

$wurflConfigFile     = APPPATH.'/config/wurfl-config.xml';
$wurflConfig         = new WURFL_Configuration_XmlConfig($wurflConfigFile);

$wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
$wurflManager        = $wurflManagerFactory->create();	
$wurflInfo           = $wurflManager->getWURFLInfo();	

$this->device = $wurflManager->getDeviceForHttpRequest($_SERVER);	

}

function load(){
$this->id = $this->device->id;
$this->fallBack = $this->device->fallBack;
}

function getDevice(){
return $this->device;
}

function getCapability($capabilityName = ""){
return $this->device->getCapability($capabilityName);
}

function getAllCapabilities(){
return $this->device->getAllCapabilities();
}

function getId(){
return $this->id;
}

function getFallback(){
return $this->fallback;
}

}