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
		$classes = 'nav';
		if( isset($args['class']) ) {
			$classes .= ' '.$args['class'];
		} else {
			$classes .= ' nav-pills';
		}

		$output = '<ul class="'.$classes.'" role="navigation">';
		$output .= LinusParser::nav(LinusParser::getNavigationFromString(trim($input)));
		$output .= '</ul>';
		return $output;
	}

	static function ButtonsSetup( Parser $parser ) {
		$parser->setHook( 'buttons', 'LinusHooks::buildButtons' );
		return true;
	}

	static function buildButtons( $input, array $args, Parser $parser, PPFrame $frame ) {
		$classes = '';
		if( isset($args['class']) ) {
			$classes .= $args['class'];
		} else {
			$classes .= 'btn-group';
		}

		$btnType = 'default';
		if( isset($args['type']) ) {
			$btnType = $args['type'];
		}

		$output = '<div class="'.$classes.'" role="menu">';
		$output .= LinusParser::buttons(LinusParser::getNavigationFromString(trim($input)), array('type'=>$btnType));
		$output .= '</div>';
		return $output;
	}

}