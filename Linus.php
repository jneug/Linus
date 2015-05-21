<?php
/**
 * Linus skin
 *
 * @file
 * @ingroup Skins
 * @author Jonas Neugebauer (jonasneug@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 */
if ( ! defined( 'MEDIAWIKI' ) ) {
  die( "This is an extension to the MediaWiki package and cannot be run standalone." );
}

$wgExtensionCredits['skin'][] = array(
	'path'        => __FILE__,
	'name'        => 'Linus',
  	'namemsg'     => 'skinname-linus',
  	'version'     => '0.0.1-alpha',
  	'descriptionmsg' => 'linus-desc',
  	'url' 			  => 'http://www.github.com/jneug/Linus',
	'author'      => '[http://jonas-neugebauer.de Jonas Neugebauer]',
	'license'     => 'GPL-2.0',
);

$wgValidSkinNames['linus'] = 'Linus';
$wgAutoloadClasses['SkinLinus'] = __DIR__ . '/SkinLinus.php';
$wgAutoloadClasses['LinusParser'] = __DIR__ . '/LinusParser.php';
$wgAutoloadClasses['LinusTemplate'] = __DIR__ . '/LinusTemplate.php';
$wgAutoloadClasses['LinusHooks'] = __DIR__ . '/LinusHooks.php';
$wgMessagesDirs['Linus'] = __DIR__ . '/i18n';

$wgHooks['ParserFirstCallInit'][] = 'LinusHooks::NavSetup';
$wgHooks['ParserFirstCallInit'][] = 'LinusHooks::ButtonsSetup';
$wgHooks['EditPageBeforeEditButtons'][] = 'LinusHooks::styleEditButtons';

// Setup resource modules
// To use Bootswatch, $wgLinusBwTheme has to be set before requireing Linus.php
// TODO: Remove Bootswatch from bundel, include instrcutions in readme.md
if( isset($wgLinusBootswatchTheme) ) {
  $bsTheme = $wgLinusBootswatchTheme;
} else {
  $bsTheme = 'bootstrap';
}

// TODO: Use .less files here?
$wgResourceModules['skins.linus.styles'] = array(
	'styles' => array(
	    'css/'.$bsTheme.'.min.css' => array( 'media' => 'all' ),
	    'less/linus.less'          => array( 'media' => 'all' ),
		'less/custom.less'         => array( 'media' => 'all' ),
		'less/print.less'          => array( 'media' => 'print' ),
	),
	'remoteSkinPath' => 'Linus',
	'localBasePath' => __DIR__,
);

$wgResourceModules['skins.linus.scripts'] = array(
	'scripts' => array(
	    'js/bootstrap.min.js',
	    'js/linus.js',
	    'js/custom.js',
	),
	'remoteSkinPath' => 'Linus',
	'localBasePath' => __DIR__,
);

$wgResourceModules['skins.linus.bootstrap-theme'] = array(
	'styles' => array(
		'css/bootstrap-theme.min.css' => array( 'media' => 'screen' ),
	),
	'remoteSkinPath' => 'Linus',
	'localBasePath' => __DIR__,
);

$wgResourceModules['skins.linus.fontawesome'] = array(
	'styles' => array(
    'css/font-awesome.min.css' => array( ),
	),
	'remoteSkinPath' => 'Linus',
	'localBasePath' => __DIR__,
);


$wgLinusUseSidebar = false;
$wgLinusTOCInSidebar = true;
$wgLinusNavbarInverted = false;
$wgLinusNavbarFixed = true;
$wgLinusUseFontAwesome = true;
$wgLinusUseBootstrapTheme = false; // TODO: Remove theme?
$wgLinusShowFooterLinks = false;
$wgLinusUseFooterIcons = true;
$wgLinusHideActionsForAnon = true;
$wgLinusEnableNavTag = true;
$wgLinusEnableButtonsTag = true;

// TODO: Add settings for footer/header/navbar/... classes
// $wgLinusClassFooter = 'navbar navbar-inverse';

$wgLinusTitlebarPage = 'MediaWiki:Linus/Titlebar';
$wgLinusFooterPage = 'MediaWiki:Linus/Footer';
$wgLinusCopyrightPage = 'MediaWiki:Linus/Copyright';

$wgLinusHideHeader = array('Mainpage','Hauptseite');
