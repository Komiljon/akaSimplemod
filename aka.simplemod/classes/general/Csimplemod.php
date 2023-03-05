<?
/**
 * simplemod module
 * @copyright 2019 Akbarov Kamil
 */

if(!defined('SIMPLEKIT_MODULE_ID')){
	define('SIMPLEKIT_MODULE_ID', 'aka.simplemod');
} 

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection;

// initialize module parametrs list and default values
//include_once __DIR__.'/../../parametrs.php';

class Csimplemod{
	const MODULE_ID = SIMPLEKIT_MODULE_ID;
	const PARTNER_NAME = 'aka'; 
	const SOLUTION_NAME	= 'simplemod'; 	
	const devMode = 1; // set to false before release
	
	static $arParametrsList = array();	
	
	public function checkModuleRight($reqRight = 'R', $bShowError = false){
		global  $APPLICATION;
		
		if($APPLICATION->GetGroupRight(self::MODULE_ID) < $reqRight){
			if($bShowError){
				$APPLICATION->AuthForm(GetMessage('SIMPLEKIT_ACCESS_DENIED'));
			}
			return false;
		}
		
		return true;
	}
	
	static function IsMainPage(){
		static $result;

		if(!isset($result)){
			$result = CSite::InDir(SITE_DIR.'index.php');
		}

		return $result;
	}

	static function IsProjectsPage(){
		static $result;

		if(!isset($result)){
			$result = CSite::InDir(SITE_DIR.'projects/');
		}

		return $result;
	}

	static function IsCatalogPage(){
		static $result;

		if(!isset($result)){
			$result = CSite::InDir(SITE_DIR.'catalog/');
		}

		return $result;
	}

	static function IsPageFluid(){
		static $result;
		
		if( !isset($result) || $result===false ){
			$result = self::IsProjectsPage();
		}

		if( !isset($result) || $result===false ){
			$arLsmBackParametrsFluid = self::LsmGetBackParametrsValues($SITE_ID);
			$ignores = $arLsmBackParametrsFluid['IGNOR_PAGES_CODE'];

			$ignores = trim($ignores);
			$arIgnores = explode(',', $ignores);

			for($i=0; $i<count($arIgnores); $i++){
				$ignoreItem = SITE_DIR . trim($arIgnores[$i]) . '/';
				$result = CSite::InDir($ignoreItem);
				if($result){
					break;
				}
			}
		}

		return $result;
	}
	
	function start($siteID){	
		return true;
	}
		

	public function correctInstall(){
		if(CModule::IncludeModule('main')){
			if(COption::GetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED') == 'Y'){
				require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/wizard.php');
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.':'.self::SOLUTION_NAME)){
					if(!DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/')){
						self::removeDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/');
					}
				}
				
				UnRegisterModuleDependences('main', 'OnBeforeProlog', self::MODULE_ID, __CLASS__, 'correctInstall'); 
				COption::SetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED', 'N');
			}
		}  
	}

	
	protected function getBitrixEdition(){
		$edition = 'UNKNOWN';
		
		if(CModule::IncludeModule('main')){
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_client.php');
			$arUpdateList = CUpdateClient::GetUpdatesList(($errorMessage = ''), 'ru', 'Y');
			if(array_key_exists('CLIENT', $arUpdateList) && $arUpdateList['CLIENT'][0]['@']['LICENSE']){
				$edition = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
			}
		}
		
		return $edition;
	}
}	
?>