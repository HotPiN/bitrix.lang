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

$form = new \Hotpin\Lang\Form();
$arLangsFiles = $form->getRequestLangFiles();
$arIncLang = $form->getIncMessages();

$arRequestLang = $form->getRequestLang();
if (is_array($arRequestLang) && !empty($arRequestLang))
{
	$form->prepareRequestLang();
	$arError = $form->getError();

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
				<td style="text-align: center;"><?= $fileShow ?></td>
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
						       style="width: 97%;margin: 2px 6px;">
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