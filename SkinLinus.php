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

class SkinLinus extends SkinTemplate {
	var $skinname = 'linus', $stylename = 'Linus',
		$template = 'LinusTemplate', $useHeadElement = true;

    /**
  	 * Add JavaScript via ResourceLoader
     *
  	 * @param OutputPage $out
  	 */
     public function initPage( OutputPage $out ) {
        parent::initPage( $out );

  		$out->addMeta( 'X-UA-Compatible', 'IE=edge' );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );

        $out->addScript( '
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->' );

      $out->addModuleScripts( 'skins.linus.scripts' );
  	}

  	/**
  	 * Add CSS via ResourceLoader
  	 *
  	 * @param $out OutputPage
  	 */
  	function setupSkinUserCss( OutputPage $out ) {
      global $wgLinusUseFontAwesome, $wgLinusUseBootstrapTheme;

  		parent::setupSkinUserCss( $out );

      $styles = array('skins.linus.styles');
      if( $wgLinusUseBootstrapTheme ) {
        $styles[] = 'skins.linus.bootstrap-theme';
      }
      if( $wgLinusUseFontAwesome ) {
        $styles[] = 'skins.linus.fontawesome';
      }
  	   $out->addModuleStyles($styles);
  	}

    /**
     * Adds classes to the body element.
     *
     * @param $out OutputPage object
     * @param &$bodyAttrs Array of attributes that will be set on the body element
     */
    function addToBodyAttributes( $out, &$bodyAttrs ) {
      global $wgLinusNavbarFixed, $wgLinusNavbarInverted, $wgLinusEnableSmoothScroll, $wgLinusUseFontAwesome;

      $bodyClasses = array();
      if( $wgLinusNavbarFixed ) {
        $bodyClasses[] = 'fixed-navbar';
      }
      if( $wgLinusNavbarInverted ) {
        $bodyClasses[] = 'inverted-navbar';
      }
      if( $wgLinusEnableSmoothScroll ) {
          $bodyClasses[] = 'smooth-scroll';
      }
      if( $wgLinusUseFontAwesome ) {
          $bodyClasses[] = 'fa-enabled';
      }

      if ( isset( $bodyAttrs['class'] ) && strlen( $bodyAttrs['class'] ) > 0 ) {
        $bodyAttrs['class'] .= ' ' . implode( ' ', $bodyClasses );
      } else {
        $bodyAttrs['class'] = implode( ' ', $bodyClasses );
      }
    }
}
