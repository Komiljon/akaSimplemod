<?
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));

IncludeModuleLangFile($PathInstall."/index.php");

Class aka_simplemod extends CModule
{
	const solutionName	= 'simplemod'; 	
	const partnerName = 'aka'; 
	const moduleClass = 'Csimplemod';

	var $MODULE_ID = "aka.simplemod";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = 'R';

	function aka_simplemod()
	{
		$arModuleVersion = array();

		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("AKA_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("AKA_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{        
		global $DB, $DBType, $APPLICATION;

		RegisterModule($this->MODULE_ID);
		COption::SetOptionString($this->MODULE_ID, 'GROUP_DEFAULT_RIGHT', $this->MODULE_GROUP_RIGHTS);
		
		RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, $this->moduleClass, 'showPanel');
		if(preg_match('/.bitrixlabs.ru/', $_SERVER['HTTP_HOST'])){
			RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, $this->moduleClass, 'correctInstall');
		}		
		
		if(CModule::IncludeModule($this->MODULE_ID)){
			$moduleClass = self::moduleClass;
			$instance = new $moduleClass();
		}
		
		return true;
	}

	function UnInstallDB($arParams = Array())
	{
        global $DB, $DBType, $APPLICATION;
		
		if(CModule::IncludeModule($this->MODULE_ID)){
			$moduleClass = self::moduleClass;
			$instance = new $moduleClass();			
		}
		UnRegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, $this->moduleClass, 'showPanel');		
		
		COption::RemoveOption($this->MODULE_ID, 'GROUP_DEFAULT_RIGHT');
		UnRegisterModule($this->MODULE_ID);

		return true;
	}

	
	
	function InstallPublic(){
	}
	
	function InstallFiles(){
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);		
		return true;
	}

	function UnInstallFiles(){
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
		
		return true;
	}

	function DoInstall(){
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		$APPLICATION->IncludeAdminFile(GetMessage('AKA_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/step.php');
		
	}

	function DoUninstall(){
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$APPLICATION->IncludeAdminFile(GetMessage('AKA_UNINSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/unstep.php');		
	}
}
?>