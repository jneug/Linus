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

	static function styleEditButtons( &$editpage, &$buttons, &$tabindex ) {
		$buttons['save'] = substr($buttons['save'],0,-1).' class="btn btn-success">';
		$buttons['preview'] = substr($buttons['preview'],0,-1).' class="btn btn-primary">';
		$buttons['diff'] = substr($buttons['diff'],0,-1).' class="btn btn-primary">';
	}

	static function NavSetup( Parser $parser ) {
		global $wgLinusEnableNavTag;
		if( $wgLinusEnableNavTag )
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
		global $wgLinusEnableButtonsTag;
		if( $wgLinusEnableButtonsTag )
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
