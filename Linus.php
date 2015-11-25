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
$wgAutoloadClasses['ResponsiveCategory'] = __DIR__ . '/LinusSMW.php';
$wgMessagesDirs['Linus'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['LinusMagic'] = __DIR__ . '/LinusMagic.php';

LinusHooks::setupHooks();

$wgExtensionFunctions[] = 'setupSMWextensions';
function setupSMWextensions() {
  if( defined( 'SMW_VERSION' ) ) {
    global $smwgResultFormats, $smwgResultAliases;
    $smwgResultFormats['responsive_category'] = 'ResponsiveCategory';
    $smwgResultAliases['responsive_category'] = array('responsive category','rcategory');

    // $smwgResultFormats['buttons'] = 'LinusSMW_Buttons';
    // $smwgResultFormats['nav'] = 'LinusSMW_Nav';

    // echo '<pre>'; var_dump($smwgResultFormats); die('</pre>');
  }
}

// Setup resource modules
$wgResourceModules['skins.linus.styles'] = array(
	'styles' => array(
	    'css/bootstrap.min.css'    => array( 'media' => 'all' ),
	    'less/linus.less'          => array( 'media' => 'all' ),
		'less/custom.less'         => array( 'media' => 'all' )
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

$wgResourceModules['skins.linus.fontawesome'] = array(
	'styles' => array(
        'css/font-awesome.min.css' => array( ),
	),
	'remoteSkinPath' => 'Linus',
	'localBasePath' => __DIR__,
);


$wgLinusUseSidebar = false;
$wgLinusTOCInSidebar = false;
$wgLinusEnableSmoothScroll = true;
$wgLinusNavbarInverted = false;
$wgLinusNavbarFixed = true;
$wgSitenameshort = null;
$wgLinusUseFontAwesome = false;
$wgLinusShowFooterLinks = false;
$wgLinusUseFooterIcons = true;
$wgLinusHideActionsForAnon = true;

$wgLinusEnableNavTag = true;
$wgLinusEnableButtonsTag = true;
$wgLinusResponsiveCategories = true;

// TODO: Add settings for footer/header/navbar/... classes
// $wgLinusClassFooter = 'navbar navbar-inverse';

$wgLinusTitlebarPage = 'MediaWiki:Linus/Titlebar';
$wgLinusFooterPage = 'MediaWiki:Linus/Footer';
$wgLinusSidebarPage = 'MediaWiki:Linus/Sidebar';
$wgLinusCopyrightPage = 'MediaWiki:Linus/Copyright';

$wgLinusHideHeader = array('Mainpage','Hauptseite');
