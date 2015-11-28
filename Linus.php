<?php
if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'Linus' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['Linus'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['LinusMagic'] = __DIR__ . '/LinusMagic.php';
	wfWarn(
		'Deprecated PHP entry point used for Linus skin. Please use wfLoadSkin instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
    die( 'This version of the Linus skin requires MediaWiki 1.25+' );
}
