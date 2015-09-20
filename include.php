<?

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
	'hotpin.lang',
	array(
		'\Hotpin\Lang\Handler' => 'lib/handler.php',
		'\Hotpin\Lang\Form' => 'lib/form.php',
	)
);