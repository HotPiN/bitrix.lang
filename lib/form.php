<?
namespace Hotpin\Lang;

class Form
{
	private $arLangsFiles = array();
	private $arRequestLang = array();
	private $arIncLang = array();
	private $arError = array();

	function __construct()
	{

		$this->arLangsFiles = $_REQUEST["arLangFiles"];
		$this->arRequestLang = $_REQUEST["arLang"];
	}

	function getIncMessages()
	{

		if ($this->arLangsFiles)
		{
			if ($_REQUEST["langForm"] == "Y")
			{
				$this->arLangsFiles = unserialize(stripcslashes($this->arLangsFiles));

			}
			if (count($this->arLangsFiles))
			{
				foreach ($this->arLangsFiles as $key => $fileLang)
				{
					$MESS = array();
					$admin = strpos($fileLang, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules") === 0
					         || strpos($fileLang, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components") === 0;
					if (!$admin && file_exists($fileLang))
					{
						require($fileLang);

						$arDirs = explode("/lang/" . LANGUAGE_ID, $fileLang);
						$textFile = file_get_contents($arDirs[0] . $arDirs[1]);

						$arr = array();
						preg_match_all('/GetMessage\((.+?)\)/', $textFile, $arr);
						if (is_array($arr[1]) && count($arr[1]) > 0)
						{
							foreach ($arr[1] as $langKey)
							{
								$langKey = explode(",", $langKey);
								$langKey = $langKey[0];
								$langKey = trim($langKey);
								$langKey = str_replace(array("'", '"'), '', $langKey);
								if (array_key_exists($langKey, $MESS))
								{
									$this->arIncLang[$fileLang][$langKey] = $MESS[$langKey];
								}
							}

						}
					}
				}
			}
		}

		return $this->arIncLang;
	}

	function setRequestLang($arRequestLang)
	{

		$this->arRequestLang = $arRequestLang;
	}

	function getError()
	{

		return $this->arError;
	}

	function getRequestLangFiles()
	{

		return $this->arLangsFiles;
	}

	function prepareRequestLang()
	{

		$arChangedLang = array();
		foreach ($this->arRequestLang as $file => $arLang)
		{
			foreach ($arLang as $keyLang => $langValue)
			{
				if ($this->arIncLang[$file][$keyLang] && $langValue != $this->arIncLang[$file][$keyLang])
				{
					$arChangedLang[$file][$keyLang] = $langValue;
				}
			}
		}
		if (count($arChangedLang))
		{
			foreach ($arChangedLang as $file => $arLang)
			{
				if (!file_exists($file))
				{
					$this->arError[] = GetMessage("H_LANG_ERROR_FILE_FIND", array("#FILE#" => $file));
				}
				else
				{
					$content = file_get_contents($file);
					foreach ($arLang as $keyLang => $langValue)
					{
						$textPos = strpos($content, $this->arIncLang[$file][$keyLang]);
						if ($textPos)
						{
							$contentReplace = substr(
								$content,
								$textPos - 1,
								strlen($this->arIncLang[$file][$keyLang]) + 2
							);
							$langValue = '"' . str_replace('"', "'", $langValue) . '"';
							$content = str_replace($contentReplace, $langValue, $content);
						}
					}
					$isSave = file_put_contents($file, $content);

					if (!$isSave)
					{
						$this->arError[] = GetMessage("H_LANG_ERROR_FILE_SAVE", array("#FILE#" => $file));;
					}
				}
			}

		}
	}
}