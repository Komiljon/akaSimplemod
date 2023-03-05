<?
AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerNext');
function OnBuildGlobalMenuHandlerNext(&$arGlobalMenu, &$arModuleMenu){
	if(!defined('AKA_MENU_INCLUDED')){
		define('AKA_MENU_INCLUDED', true);

		IncludeModuleLangFile(__FILE__);
		$moduleID = 'aka.simplemod';

		$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");

		if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){
			$arMenu = array(
				'menu_id' => 'global_menu_aka',
				'text' => GetMessage('AKA_GLOBAL_MENU_TEXT'),
				'title' => GetMessage('AKA_GLOBAL_MENU_TITLE'),
				'sort' => 1000,
				'items_id' => 'global_menu_aka_next_items',
				'icon' => 'imi_next',
				'items' => array(					
					array(
						'text' => GetMessage('AKA_MENU_TYPOGRAPHY_TEXT'),
						'title' => GetMessage('AKA_MENU_TYPOGRAPHY_TITLE'),
						'sort' => 20,
						'url' => '/bitrix/admin/'.$moduleID.'_options.php?mid=main',
						'icon' => 'imi_typography',
						'page_icon' => 'pi_typography',
						'items_id' => 'main',
					),					
				),
			);
			if(!isset($arGlobalMenu['global_menu_aka'])){
				$arGlobalMenu['global_menu_aka'] = array(
					'menu_id' => 'global_menu_aka',
					'text' => GetMessage('AKA_GLOBAL_ASPRO_MENU_TEXT'),
					'title' => GetMessage('AKA_GLOBAL_ASPRO_MENU_TITLE'),
					'sort' => 1000,
					'items_id' => 'global_menu_aka_items',
				);
			}

			$arGlobalMenu['global_menu_aka']['items'][$moduleID] = $arMenu;
		}
	}
}
?>