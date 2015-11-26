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

    static function setupHooks() {
        global $wgHooks;

        $wgHooks['ParserFirstCallInit'][] = 'LinusHooks::NavSetup';
        $wgHooks['ParserFirstCallInit'][] = 'LinusHooks::ButtonsSetup';

        $wgHooks['ParserBeforeTidy'][] = 'LinusHooks::parseMagicWords';

        // TODO Fails in some cases, where headings without edit section links are generated (eg. category result format for SMW)
        $GLOBALS['wgLinusHeadingCount'] = 0;
        $wgHooks['OutputPageParserOutput'][] = 'LinusHooks::countSections';
        $wgHooks['EditPageBeforeEditButtons'][] = 'LinusHooks::styleEditButtons';
        $wgHooks['ArticleFromTitle'][] = 'LinusHooks::onArticleFromTitle';
    }

    static function onExtensionLoad() {
        
    }

    // Used to count headings to see if a toc will be generated
    // static function countSections() {
    // static function countSections($parser, $section, &$sectionContent, $showEditLinks) {
    static function countSections(OutputPage &$out, ParserOutput $parseroutput) {
      global $wgLinusHeadingCount;

      // if( $section != 0 )
      //   $wgLinusHeadingCount++;

      $toc = $parseroutput->getTOCHTML();
      if( !empty($toc) ) {
        $wgLinusHeadingCount = 5;
      }

      return true;
    }

    // static function setupSMWHooks() {
    //     global $wgHooks;
    //
    //     // TODO: What to check here to verify SMW is installed?
    //     if( function_exists('enableSemantics') ) {
    //         //$wgHooks[''][] = 'LinusHooks::smw';
    //     }
    // }

  static function onArticleFromTitle( Title &$title, &$article, $context ) {
    global $wgLinusResponsiveCategories;
    if ( $wgLinusResponsiveCategories && $title->getNamespace() == NS_CATEGORY ) {
       $article = new LinusCategoryPage( $title );
    }
    return true;
  }

  static function styleEditButtons( &$editpage, &$buttons, &$tabindex ) {
		$buttons['save'] = substr($buttons['save'],0,-1).' class="btn btn-success">';
		$buttons['preview'] = substr($buttons['preview'],0,-1).' class="btn btn-primary">';
		$buttons['diff'] = substr($buttons['diff'],0,-1).' class="btn btn-primary">';
	}

    static function parseMagicWords( Parser &$parser, &$text ) {
        global $wgLinusUseSidebar,$wgLinusTOCInSidebar,$wgLinusHideHeader;

        // Save sidebar setting for restoring if NOSIDEBAR and FORCESIDEBAR both were used
        $sidebar = $wgLinusTOCInSidebar;

        try {
            if( MagicWord::get( 'LINUS_NOSIDEBAR' )->matchAndRemove( $text ) ) {
              $wgLinusUseSidebar = false;
              $wgLinusTOCInSidebar = false;
            }
            if( MagicWord::get( 'LINUS_FORCESIDEBAR' )->matchAndRemove( $text ) ) {
              $wgLinusUseSidebar = true;
              $wgLinusTOCInSidebar = $sidebar;
            }
            if( MagicWord::get( 'LINUS_NOTITLE' )->matchAndRemove( $text ) ) {
              $wgLinusHideHeader[] = $parser->getTitle()->getText();
            }

            // TODO: Is this necessary? Seems overkill to disable Cache every time.
            $parser->disableCache();

            return true;
        } catch( MWException $ex ) {
            // Magic word definitions not loaded from LinusMagic.php
            // Probably due to loading from mw-config ?
            return false;
        }
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
		$classes = 'btn-group';
		if( isset($args['class']) ) {
			$classes .= ' '.$args['class'];
		} else {
			$classes .= '';
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

class LinusCategoryPage extends CategoryPage {
  protected $mCategoryViewerClass = 'LinusCategoryViewer';
}

class LinusCategoryViewer extends CategoryViewer {
  public static $mCategoryLayout = 'rowLayout';

  function formatList( $articles, $articles_start_char, $cutoff = 6 ) {
    // $list = $this->columnLayout( $articles, $articles_start_char );
    // $list = call_user_func_array(array($this, self::$mCategoryLayout), array($articles, $articles_start_char));
    $list = call_user_func_array(array('LinusCategoryViewer', self::$mCategoryLayout), array($articles, $articles_start_char));

    $pageLang = $this->title->getPageLanguage();
    $attribs = array( 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir(),
      'class' => 'mw-content-' . $pageLang->getDir() );
    $list = Html::rawElement( 'div', $attribs, $list );

    return $list;
  }

  /**
   * Render category items in three grid columns.
   */
  public static function gridLayout( $articles, $articles_start_char ) {
    $columns = array_combine( $articles, $articles_start_char );
		# Split into three columns
		$columns = array_chunk( $columns, ceil( count( $columns ) / 3 ), true /* preserve keys */ );

		$ret = '<div class="row">'."\n";
		$prevchar = null;

		foreach ( $columns as $column ) {
      $ret .= '<div class="col-md-4 col-sm-6">';
			$colContents = array();

			# Kind of like array_flip() here, but we keep duplicates in an
			# array instead of dropping them.
			foreach ( $column as $article => $char ) {
				if ( !isset( $colContents[$char] ) ) {
					$colContents[$char] = array();
				}
				$colContents[$char][] = $article;
			}

			$first = true;
			foreach ( $colContents as $char => $articles ) {
				# Change space to non-breaking space to keep headers aligned
				$h3char = $char === ' ' ? '&#160;' : htmlspecialchars( $char );

				if ( $first && $char === $prevchar ) {
          $ret .= '<h3 class="hidden-xs">' . $h3char;
					# We're continuing a previous chunk at the top of a new
					# column, so add " cont." after the letter.
					$ret .= ' ' . wfMessage( 'listingcontinuesabbrev' )->escaped();
				} else {
          $ret .= '<h3>' . $h3char;
        }
				$ret .= "</h3>\n";

				$ret .= '<ul><li>';
				$ret .= implode( "</li>\n<li>", $articles );
				$ret .= '</li></ul>';

				$first = false;
				$prevchar = $char;
			}

			$ret .= "</div>\n";
		}

		$ret .= '</div>';
		return $ret;
  }

  /**
   * render category items in a .col-md-12 with the columns css property applied
   */
   public static function columnLayout( $articles, $articles_start_char ) {
    $size = count($articles);
    $half = ceil( $size / 2 );
    $third = ceil( $size / 3 );

    $ret = '<div class="row"><div class="col-md-12"><div class="catcolumns">'."\n";
    $prevchar = null;

    foreach( $articles as $i=>$article) {
      $char = $articles_start_char[$i];
      # Change space to non-breaking space to keep headers aligned
      $h3char = $char === ' ' ? '&#160;' : htmlspecialchars( $char );

      if( $i === 0 || $char !== $prevchar ) {
        $ret .= "<h3>".$h3char."</h3>\n";
      } else {
        if( $i%$half === 0 ) {
          $ret .= "<h3 class=\"visible-sm-block\">"
                    .$h3char
                    .' ' . wfMessage( 'listingcontinuesabbrev' )->escaped()
                    ."</h3>\n";
        }
        else if( $i%$third === 0 ) {
          $ret .= "<h3 class=\"visible-md-block visible-lg-block\">"
                    .$h3char
                    .' ' . wfMessage( 'listingcontinuesabbrev' )->escaped()
                    ."</h3>\n";
        }
      }

      $ret .= $article."<br/>\n";

      $prevchar = $char;
    }

    $ret .= '</div></div></div>';
    return $ret;
  }

  /**
   * Render category items in rows after their respective header
   */
   public static function rowLayout( $articles, $articles_start_char ) {
    $ret = "";
    $prevchar = null;

    $first = true;
    foreach( $articles as $i=>$article) {
      $char = $articles_start_char[$i];
      # Change space to non-breaking space to keep headers aligned
      $h3char = $char === ' ' ? '&#160;' : htmlspecialchars( $char );

      if( $i === 0 || $char !== $prevchar ) {
        if( !$first ) {
          $ret .= '</div>';
        }
        $ret .= '<div class="row catrow">'."\n";
        $ret .= "<div class=\"col-lg-12\"><h3>".$h3char."</h3></div>\n";
      }

      $ret .= '<div class="col-lg-3 col-md-4 col-sm-6">'.$article."</div>\n";

      $first = false;
      $prevchar = $char;
    }

    $ret .= "</div>";
    return $ret;
  }
}
