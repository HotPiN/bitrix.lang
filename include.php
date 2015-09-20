<?

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
	'hotpin.lang',
	array(
		'\Hotpin\Handler\Lang' => 'lib/handler.php',
	)
);