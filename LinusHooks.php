<?php
/**
 * Linus skin hooks
 *
 * @file
 * @ingroup Skins
 * @author Jonas Neugebauer (jonasneug@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 */
if ( ! defined( 'MEDIAWIKI' ) ) {
  die( "This is an extension to the MediaWiki package and cannot be run standalone." );
}

class LinusHooks {

	static function NavSetup( Parser $parser ) {
		$parser->setHook( 'nav', 'LinusHooks::buildNavigation' );
		return true;
	}

	static function buildNavigation( $input, array $args, Parser $parser, PPFrame $frame ) {
		global $wgTemplate;
		return $input;
	}

}