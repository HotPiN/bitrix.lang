<?
namespace Hotpin\Lang;

use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

class Handler
{
	function addPanelButton()
	{

		global $APPLICATION;
		if ($_REQUEST["clear_cache"] == "Y")
		{
			$arLangsFiles = Loc::getIncludedFiles();

			$arMenu = array(
				array(
					"TEXT"   => GetMessage("H_LANG_MENU_ITEM"),
					"TITLE"  => GetMessage("H_LANG_MENU_TITLE"),
					"ICON"   => "panel-edit-text",
					"HK_ID"  => "top_panel_templ_templ_css",
					"ACTION" => $APPLICATION->GetPopupLink(
						array(
							"URL"    => "/bitrix/admin/public_lang_edit.php?lang=",
							"POST"   => array(
								"arLangFiles" => $arLangsFiles,
								"page"        => $APPLICATION->GetCurPage(true),
							),
							"PARAMS" => array(
								"width"       => 770,
								'height'      => 470,
								'resizable'   => true,
								'dialog_type' => 'ADMIN',
								"min_width"   => 700,
								"min_height"  => 200
							)
						)
					),
				)
			);

		}
		else
		{
			$arMenu = array(
				array(
					"ACTION" => 'javascript:BX.clearCache()',
					"ICON"   => "bx-panel-clear-cache-icon",
					"ALT"    => GetMessage("H_LANG_CLEAR_CACHE"),
					"TEXT"   => GetMessage("H_LANG_CLEAR_CACHE"),
				)
			);
		}
		$APPLICATION->AddPanelButton(
			array(
				"HREF"      => '',
				"ICON"      => "bx-panel-site-structure-icon",
				"ALT"       => GetMessage("H_LANG_MENU_ITEM"),
				"TEXT"      => GetMessage("H_LANG_MENU_NAME"),
				"MAIN_SORT" => 1000,
				"SORT"      => 100,
				"MENU"      => $arMenu,
			)
		);
	}
}
