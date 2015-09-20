<?
IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Class hotpin_lang extends CModule
{
	const MODULE_ID = 'hotpin.lang';
	var $MODULE_ID = 'hotpin.lang';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{

		$arModuleVersion = array();
		include(dirname(__FILE__) . "/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("hotpin.lang_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("hotpin.lang_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("hotpin.lang_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("hotpin.lang_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{

		RegisterModuleDependences('main', 'OnPanelCreate', self::MODULE_ID, '\Hotpin\Lang\Handler', 'addPanelButton');

		return true;
	}

	function UnInstallDB($arParams = array())
	{

		UnRegisterModuleDependences('main', 'OnPanelCreate', self::MODULE_ID, '\Hotpin\Lang\Handler', 'addPanelButton');

		return true;
	}

	function InstallEvents()
	{

		return true;
	}

	function UnInstallEvents()
	{

		return true;
	}

	function InstallFiles($arParams = array())
	{

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . "/local/modules/" . self::MODULE_ID . "/install/admin",
			$_SERVER['DOCUMENT_ROOT'] . "/bitrix/admin"
		);
		return true;
	}

	function UnInstallFiles()
	{

		unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/public_lang_edit.php");
	}


	function DoInstall()
	{

		ModuleManager::registerModule(self::MODULE_ID);
		$this->InstallFiles();
		$this->InstallDB();
	}

	function DoUninstall()
	{

		ModuleManager::unregisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}

?>
