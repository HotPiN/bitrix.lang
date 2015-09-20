<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_js.php");

use Bitrix\Main\Loader;

Loader::includeModule("hotpin.lang");
IncludeModuleLangFile(__FILE__);

CUtil::JSPostUnescape();

$obJSPopup = new CJSPopup(
	'', array(
		  'TITLE' => GetMessage("H_LANG_PAGE_NAME"),
	  )
);

$obJSPopup->ShowTitlebar();
$obJSPopup->StartDescription('bx-core-edit-menu');
$obJSPopup->StartContent();

$arLangsFiles = $_REQUEST["arLangFiles"];
if ($arLangsFiles)
{
	if ($_REQUEST["langForm"] == "Y")
	{
		$arLangsFiles = unserialize(stripcslashes($arLangsFiles));

	}
	if (count($arLangsFiles))
	{
		$arIncLang = array();
		foreach ($arLangsFiles as $key => $fileLang)
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
						$langKey = str_replace(array("'", '"'), '', $langKey);
						if (array_key_exists($langKey, $MESS))
						{
							$arIncLang[$fileLang][$langKey] = $MESS[$langKey];
						}
					}

				}
			}
		}
	}
}

$arRequestLang = $_REQUEST["arLang"];
if (is_array($arRequestLang) && !empty($arRequestLang))
{
	$arError = array();
	$arChangedLang = array();
	foreach ($arRequestLang as $file => $arLang)
	{
		foreach ($arLang as $keyLang => $langValue)
		{
			if ($arIncLang[$file][$keyLang] && $langValue != $arIncLang[$file][$keyLang])
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
				$arError[] = GetMessage("H_LANG_ERROR_FILE_FIND" , array("#FILE#" => $file));
			}
			else
			{
				$content = file_get_contents($file);
				foreach ($arLang as $keyLang => $langValue)
				{
					$textPos = strpos($content, $arIncLang[$file][$langKey]);
					if ($textPos)
					{
						$contentReplace = substr($content, $textPos - 1, strlen($arIncLang[$file][$langKey]) + 2);
						$langValue = '"' . str_replace('"', "'", $langValue) . '"';
						$content = str_replace($contentReplace, $langValue, $content);
					}
				}
				$isSave = file_put_contents($file, $content);

				if (!$isSave)
				{
					$arError[] = GetMessage("H_LANG_ERROR_FILE_SAVE" , array("#FILE#" => $file));;
				}
			}
		}

	}

	if (!count($arError))
	{
		$obJSPopup->Close(true, "");
		die();
	}
	else
	{
		$obJSPopup->ShowValidationError(implode("<br>", $arError));
	}
}

if (count($arIncLang))
{
	?>
	<table class="bx-width100" id="bx_folder_properties">
		<input type="hidden" name="arLangFiles" value='<?= addslashes(serialize($arLangsFiles)); ?>'>
		<input type="hidden" name="langForm" value="Y">
		<?
		foreach ($arIncLang as $file => $arFileLang)
		{
			$fileShow = str_replace($_SERVER["DOCUMENT_ROOT"], "", $file);
			?>
			<tr class="section">
				<td><?= $fileShow ?></td>
			</tr>
			<?

			foreach ($arFileLang as $keyLang => $langValue)
			{
				$value = $arRequestLang[$file][$keyLang] ? $arRequestLang[$file][$keyLang] : $langValue;
				$value = str_replace('"', "'", $value);
				?>
				<tr>
					<td>
						<input type="text" name="arLang[<?= $file ?>][<?= $keyLang ?>]" value="<?= $value ?>"
						       style="width: 95%;margin: 2px 5px;">
					</td>
				</tr>
				<?
			}

		}

		?>
	</table>
	<?
}
else
{
	$obJSPopup->ShowValidationError(GetMessage("H_LANG_ERROR_EMPTY"));
}
$obJSPopup->ShowStandardButtons();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_js.php");