<?php
/**
 * Linus skin parser
 *
 * @file
 * @ingroup Skins
 * @author Jonas Neugebauer (jonasneug@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 */
if ( ! defined( 'MEDIAWIKI' ) ) {
  die( "This is an extension to the MediaWiki package and cannot be run standalone." );
}

class LinusParser {

  static public function pageExists($title) {
    $pageTitle = Title::newFromText($title);
    return $pageTitle->exists();
  }

  static public function getPageContent($title, $default = '%2$s', $raw = false) {
    global $wgParser, $wgUser;
    $pageTitle = Title::newFromText($title);
    if(!$pageTitle->exists()) {
      return sprintf($default, $pageTitle->getFullText(), Linker::link($pageTitle));
    } else {
      $article = new Article($pageTitle);
      if( $raw ) {
        return $article->getRawText();
      } else {
        $wgParserOptions = new ParserOptions($wgUser);
        $parserOutput = $wgParser->parse($article->getRawText(), $pageTitle, $wgParserOptions);
        return $parserOutput->getText();
      }
    }
  }

  static public function buttons( $nav, $options = array(), $level = 0 ) {
	$btnClasses = 'btn btn-'.(isset($options['type']) ? $options['type'] : 'default');

	$output = '';
    foreach ( $nav as $navSlug => $navItem ) {
      if ( array_key_exists('items', $navItem) ) {
        $output .= '<div class="btn-group">';
        $output .= '<button type="button"'.($level>0?' class="'.$btnClasses.'"':' class="'.$btnClasses.' dropdown-toggle" data-toggle="dropdown"').'>'
                      . LinusParser::getIcon($navItem['icon'])
                      . $navItem['text']
                      . ' <span class="caret"></span>'
                      . '</button>';
        $output .= '<ul class="dropdown-menu" role="menu">';
        $output .= LinusParser::nav($navItem['items'], $level+1);
        $output .= '</ul>';
        $output .= '</div>';
      } else {
        if( isset($navItem['divider']) && $navItem['divider'] ) {
          $output .= '<button type="button" class="btn disabled btn-divider">&nbsp;</button>';
        } else if( empty($navItem['href']) ) {
          $output .= '<button class="'.$btnClasses.($level==0?' disabled':' dropdown-header').'">'.LinusParser::getIcon($navItem['icon']).$navItem['text'].'</button>';
        } else {
	      	$attributes = array('href="'.$navItem['href'].'"', 'class="'.$btnClasses.'"');
	      	if( isset($navItem['rel']) )
				$attributes[] = 'rel="'.$navItem['rel'].'"';

        	$output .= '<a '.implode(' ',$attributes).'>'.LinusParser::getIcon($navItem['icon']).$navItem['text'].'</a>';
        }
      }
    } // end foreach
    return $output;
  }

  static public function nav( $nav, $level = 0 ) {
    $output = '';
    foreach ( $nav as $navSlug => $navItem ) {
      if ( array_key_exists('items', $navItem) ) {
        $output .= '<li class="dropdown'.($level==0?'':'-submenu').'">';
        $output .= '<a href="#"'.($level>0?'':' class="dropdown-toggle" data-toggle="dropdown"').'>'
                      . LinusParser::getIcon($navItem['icon'])
                      . $navItem['text']
                      . ' <span class="caret"></span>'
                      .'</a>';
        $output .= '<ul class="dropdown-menu">';
        $output .= LinusParser::nav($navItem['items'], $level+1);
        $output .= '</ul>';
        $output .= '</li>';
      } else {
        if( isset($navItem['divider']) && $navItem['divider'] ) {
          $output .= '<li class="divider"></li>';
        } else if( empty($navItem['href']) ) {
          $output .= '<li class="'.($level==0?'navbar-text':'dropdown-header').'">'.LinusParser::getIcon($navItem['icon']).$navItem['text'].'</li>';
        } else {
	      	$attributes = array('href="'.$navItem['href'].'"');
            if( isset($navItem['id']) )
                $attributes[] = 'id="'.$navItem['id'].'"';
	      	if( isset($navItem['rel']) )
				$attributes[] = 'rel="'.$navItem['rel'].'"';

			$output .= '<li><a '.implode(' ',$attributes).'>'.LinusParser::getIcon($navItem['icon']).$navItem['text'].'</a></li>';
        }
      }
    } // end foreach
    return $output;
  }

  static public function getNavigationFromData( $name, $text, &$data ) {
    $navArray = array();
    foreach( $data as $navSlug => $navItem ) {
      $item = array_merge(array(
        'text'    => '',
        'href'    => '#',
        'icon'    => '',
        'id'      => '',
        'active'  => false,
        'class'   => '',
        'primary' => true,
        'rel'     => ''
      ), $navItem);

      // Set missing values
      if( empty($item['id']) ) {
        if( !is_int($navSlug) ) {
          $item['id'] = 'n-'.strtolower($navSlug);
        } else {
          $item['id'] = 'n-'.$name.'-'.$navSlug;
        }
      }

      if( is_int($navSlug) ) {

        if( strpos($item['id'],'-') !== false ) {
          $navSlug = substr($item['id'], strpos($item['id'],'-')+1);
        } else {
          $navSlug = $item['id'];
        }
      }
      if( empty($item['text']) ) {
        $item['text'] = wfMessage($navSlug);
      }

      if( empty($item['icon']) ) {
        $item['icon'] = LinusParser::_getIconFor($navSlug);
      }

      $navArray[$navSlug] = $item;
    } // end foreach

    if( isset($text) && !empty($text) ) {
      $navArray = array($name => array(
        'text'    => $text,
        'icon'    => LinusParser::_getIconFor($name),
        'primary' => true,
        'active'  => false,
        'items'   => $navArray
      ));
    }
    return $navArray;
  }

  static public function getNavigationFromPage($title) {
    $content = LinusParser::getPageContent($title, '', true);
    return LinusParser::getNavigationFromString($content);
  }

  static public function getNavigationFromString($content) {
	$content = explode("\n", $content);
    return LinusParser::_parseString($content);
  }

  static private function _parseString( &$content ) {
    if(empty($content)) return array();

    $n = strlen($content[0]) - strlen(ltrim($content[0],'*'));
    $pre = str_repeat('*',$n);

    $items = array();
    while( !empty($content) && substr($content[0],0,$n) == $pre ) {
      if( trim($content[0]) == $pre ) {
        array_shift($content);
      } else if( strpos($content[0], '*', $n) === $n ) {
        $items[count($items)-1]['items'] = LinusParser::_parseString($content);
      } else {
        $items[] = LinusParser::_parseLine($content[0]);
        array_shift($content);
      }
    }
    return $items;
  }

  static private function _parseLine( $line ) {
    // Special case: dividers
    if( preg_match('/^\*+\s*----/', $line) ) {
      return array('divider' => true);
    }

    // Check if an icon is set and strip from line
    $icon = '';
    if(preg_match('/^(.*)\s*\(icon:((fa-)?.+)\)\s*$/', $line, $iconMatch)) {
      $icon = $iconMatch[2];
      $line = $iconMatch[1];
    }

    // Strip '*'
    $line = ltrim(ltrim($line, '*'));

    // Split text from href
    $href = '';
    $text = $line;
    if( strpos($line, '|') !== false ) {
      $parts = explode('|', $line);
      $href = $parts[0];
      $text = $parts[1];


      // Deal with some special keywords for dynamic urls (current page, homepage, ...)
      $keyword = $href;
      $query = '';
      if( strpos($href, '?') !== false ) {
        $keyword = substr($href, 0, strpos($href, '?'));
        $query = substr($href, strpos($href, '?'));
      }
      if( in_array($keyword, array('self','mainpage')) ) {
        switch( $keyword ) {
          case 'self': global $wgTitle; $href = $wgTitle->getLocalURL(); break;
          case 'mainpage': $href = Title::newMainPage()->getLocalURL(); break;
        }
        $href .= $query;
      }
      // Copied from Skin.php:1306
      else if ( preg_match( '/^(?i:' . wfUrlProtocols() . ')/', $href ) ) {
        global $wgNoFollowLinks, $wgNoFollowDomainExceptions;
        if ( $wgNoFollowLinks && !wfMatchesDomainList( $href, $wgNoFollowDomainExceptions ) ) {
          $extraAttribs['rel'] = 'nofollow';
        }

        global $wgExternalLinkTarget;
        if ( $wgExternalLinkTarget ) {
          $extraAttribs['target'] = $wgExternalLinkTarget;
        }
      } else {
        $title = Title::newFromText( $href );

        if ( $title ) {
          $title = $title->fixSpecialName();
          $href = $title->getLinkURL();
        } else {
          $href = '';
        }
      }
    }

    $item = array(
      'text'    => $text,
      'href'    => $href,
      'icon'    => $icon
    );
    return $item;
  }

  static public function getIcon( $icon ) {
    if( !empty($icon) ) {
      if( substr($icon, 0, 3) == 'fa-' ) {
        $icon = '<i class="fa fa-fw '.$icon.'"></i> ';
      } else {
        $icon = '<span class="glyphicon fa-fw glyphicon-'.$icon.'"></span> ';
      }
    }
    return $icon;
  }

  static public function getIconFor( $slug ) {
    return LinusParser::getIcon( LinusParser::_getIconFor($slug) );
  }

  // TODO: Put this into i18n-files?
  static private function _getIconFor( $slug ) {
    global $wgLinusUseFontAwesome;

    if( $wgLinusUseFontAwesome ) {
      $icons = array(
        'personal_urls' => 'user',
        'content_actions' => 'file',
        'toolbox' => 'cog',
        'unwatch' => 'eye-slash',
        'watch' => 'eye',
        'userpage' => 'user',
        'mytalk' => 'comment',
        'preferences' => 'cog',
        'watchlist' => 'eye',
        'mycontris' => 'list-alt',
        'logout' => 'sign-out',
        'login' => 'sign-in',
        'nstab-main' => 'file',
        'nstab-mediawiki' => 'file',
        'nstab-template' => 'file',
        'talk' => 'comment',
        'edit' => 'edit',
        'history' => 'clock-o',
        'delete' => 'remove',
        'move' => 'arrows',
        'protect' => 'lock',
        'whatlinkshere' => 'angle-double-down',
        'recentchangeslinked' => 'pencil-square-o',
        'specialpages' => 'star-o',
        'print' => 'print',
        'permalink' => 'link',
        'info' => 'info',
        'upload' => 'upload',
        'help' => 'book',
        'randompage' => 'random',
        'recentchanges' => 'pencil-square-o',
        'purge' => 'refresh',
        'smw-browse' => 'search',
      );
      if( array_key_exists($slug, $icons) ) {
        return 'fa-'.$icons[$slug];
      }
    } else {
      $icons = array(
        'personal_urls' => 'user',
        'content_actions' => 'file',
        'toolbox' => 'cog',
        'unwatch' => 'eye-close',
        'watch' => 'eye-open',
        'userpage' => 'user',
        'mytalk' => 'comment',
        'preferences' => 'cog',
        'watchlist' => 'eye-open',
        'mycontris' => 'list-alt',
        'logout' => 'log-out',
        'login' => 'log-in',
        'nstab-main' => 'file',
        'nstab-mediawiki' => 'file',
        'nstab-template' => 'file',
        'talk' => 'comment',
        'edit' => 'edit',
        'history' => 'time',
        'delete' => 'remove',
        'move' => 'move',
        'protect' => 'lock',
        'whatlinkshere' => 'menu-down',
        'recentchangeslinked' => 'edit',
        'specialpages' => 'star-empty',
        'print' => 'print',
        'permalink' => 'link',
        'info' => 'info',
        'upload' => 'circle-arrow-up',
      );
      if( array_key_exists($slug, $icons) ) {
        return $icons[$slug];
      }
    }
    return '';
  }

}
