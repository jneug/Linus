<?php
/**
 * Linus skin template
 *
 * @file
 * @ingroup Skins
 * @author Jonas Neugebauer (jonasneug@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0
 */
if ( ! defined( 'MEDIAWIKI' ) ) {
  die( "This is an extension to the MediaWiki package and cannot be run standalone." );
}

class LinusTemplate extends BaseTemplate {

	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
    global $wgLinusUseSidebar, $wgLinusTOCInSidebar;

		$this->html( 'headelement' );
    ?>
    <!-- link to content for accessibility -->
    <a href="#wiki-body" class="sr-only">Skip to main content</a>
    <?php $this->renderNavbar(); ?>

		<main id="wiki-outer-body" class="container">
      <div id="wiki-body" class="row">
  			<?php if ( $wgLinusUseSidebar || $wgLinusTOCInSidebar ): ?>
				<aside class="col-md-3 hidden-print sidebar">
        <?php
        $sidebar = $this->getSidebar();
        foreach( $sidebar as $group=>$content) {
          echo '<ul class="nav nav-pills nav-stacked">';
          // echo '<li class="disabled"><a href="#">'.$content['header'].'</a></li>';
          echo '<li>'.$content['header'].'</li>';
          echo $this->nav($this->getNavigationFromData($group,'',$content['content']));
          echo '</ul>';
        }
        //$this->renderSidebar() ?>
        </aside>
				<section class="col-md-9 wiki-body-section">
        <?php else: ?>
        <section class="col-md-12 wiki-body-section">
  			<?php endif; ?>

  				<?php if( $this->data['sitenotice'] ): ?>
          <!-- siteNotice -->
  				<div id="siteNotice" class="alert alert-warning"><?php $this->html('sitenotice') ?></div>
          <!-- /siteNotice -->
  				<?php endif; ?>
  				<?php if ( $this->data['undelete'] ): ?>
  				<!-- undelete -->
  				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
  				<!-- /undelete -->
  				<?php endif; ?>
  				<?php if($this->data['newtalk'] ): ?>
  				<!-- newtalk -->
  				<div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
  				<!-- /newtalk -->
  				<?php endif; ?>

          <?php if ( !empty( $this->data['title'] ) ): ?>
  				<div class="pagetitle page-header">
  					<h1><?php $this->html( 'title' ) ?>
             <?php if( $this->data['subtitle'] ): ?><small><?php $this->html('subtitle') ?></small><?php endif; ?></h1>
  				</div>
          <?php endif; ?>

  				<article class="body">
  				<?php $this->renderBody() ?>
          </article>

  				<?php if ( $this->data['catlinks'] ): ?>
          <!-- catlinks -->
  				<div class="category-links"><?php $this->html( 'catlinks' ); ?></div>
          <!-- /catlinks -->
  				<?php endif; ?>
  				<?php if ( $this->data['dataAfterContent'] ): ?>
  				<!-- dataAfterContent -->
          <div class="data-after-content"><?php $this->html( 'dataAfterContent' ); ?></div>
  				<!-- /dataAfterContent -->
  				<?php endif; ?>

        </section> <!-- /.wiki-body-section -->
      </div> <!-- /.row -->
		</main>

  <?php
    $this->renderFooter();
    $this->printTrail();
  ?>
</body>
</html><?php
	} // execute()



  protected function renderNavbar() {
    global $wgSitename, $wgSitenameshort, $wgUser, $wgEnableUploads;
    global $wgLinusNavbarInverted, $wgLinusNavbarFixed,
              $wgLinusTitlebarPage, $wgLinusHideActionsForAnon;

    $navbarClasses = 'navbar';
    if( $wgLinusNavbarInverted ) {
      $navbarClasses .= ' navbar-inverse';
    } else {
      $navbarClasses .= ' navbar-default';
    }
    if( $wgLinusNavbarFixed ) {
      $navbarClasses .= ' navbar-fixed-top';
    }

    ?>
        <nav class="<?php echo $navbarClasses ?>">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#linus-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo isset( $wgLogo ) && $wgLogo ? "<img src='{$wgLogo}' alt='Logo'/> " : ''; echo isset($wgSitenameshort) ? $wgSitenameshort : $wgSitename; ?></a>
            </div>

            <div class="collapse navbar-collapse" id="linus-navbar-collapse">
              <ul class="nav navbar-nav navbar-right">
                <?php
                if ( $wgUser->isLoggedIn() ) {
                  echo $this->nav( $this->getNavigationFromData('personal_urls', $wgUser->getName()) );
                } else {
                ?>
                <li>
                  <?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Userlogin' ), $this->getIconFor('login').wfMessage( 'login' )->text() ); ?>
                </li>
                <?php
                } // end else

                if ( $wgUser->isLoggedIn() || !$wgLinusHideActionsForAnon ) {
                  if ( !empty($this->data['content_actions']) ) {
                    echo $this->nav( $this->getNavigationFromData('content_actions', wfMessage('article' /*'nstab-main' */)) );
                  }
                }

                ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $this->getIconFor('toolbox') ?><span class="sr-only"><?php echo wfMessage('toolbox') ?></span> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <?php
                    $toolbox = $this->getToolbox();
                    echo $this->nav( $this->getNavigationFromData('toolbox', '', $toolbox) );
                    ?>
                  </ul>
                </li>
              </ul>

              <form class="navbar-form navbar-right" action="<?php $this->text( 'wgScript' ) ?>" id="searchform" role="search">
                <div class="form-group">
                  <input class="form-control" type="search" name="search" placeholder="Search" title="Search <?php echo $wgSitename; ?> [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
                </div>
                <input class="btn" type="hidden" name="title" value="Special:Search">
              </form>

              <?php if( $this->pageExists($wgLinusTitlebarPage) ): ?>
              <ul class="nav navbar-nav">
                <?php echo $this->nav( $this->getNavigationFromPage($wgLinusTitlebarPage) ) ?>
              </ul>
              <?php endif; ?>
            </div><!-- /.collapse.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    <?php
  }

  protected function renderSidebar() {
    global $wgLinusUseSidebar;

    if( $wgLinusUseSidebar ) {
      foreach ( $this->getSidebar() as $boxName => $box ) { ?>
        <div id="<?php echo Sanitizer::escapeId( $box['id'] ) ?>"<?php echo Linker::tooltip( $box['id'] ) ?>>
          <h5><?php echo htmlspecialchars( $box['header'] ); ?></h5>
        <?php
          if ( is_array( $box['content'] ) ) { ?>
          <ul>
        <?php
            foreach ( $box['content'] as $key => $item ) {
              echo $this->makeListItem( $key, $item );
            }
        ?>
          </ul>
        <?php
          } else {
            echo $box['content'];
          }
        }
      }
  }

  protected function renderBody() {
    // echo "<pre>";
    // var_dump($this->getSidebar());
    // echo "\n\n";
    // var_dump($this->data['personal_urls']);
    // echo "\n\n";
    // var_dump($this->data['content_actions']);
    // foreach( $this->getToolbox() as $k=>$v ) {
    //   echo "'".$k."' => '',\n";
    // }
    // echo '</pre>';
    $this->html( 'bodytext' );
  }

  protected function renderFooter() {
    global $wgLinusNavbarInverted, $wgLinusShowFooterLinks,
      $wgLinusUseFooterIcons, $wgLinusFooterPage, $wgLinusCopyrightPage;

    $footerClasses = 'navbar-default';
    if( $wgLinusNavbarInverted ) {
      $footerClasses = 'navbar-inverse';
    }
    ?>
    <footer class="<?php echo $footerClasses ?>">
      <div class="container">
        <?php echo $this->getPageContent( $wgLinusFooterPage ); ?>

        <div class="pull-right">
          <?php if( $wgLinusShowFooterLinks ): ?>
          <ul>
            <?php foreach ( $this->getFooterLinks('flat') as $key ): ?>
            <li><?php $this->html( $key ) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>

          <?php
          $footericons = $this->getFooterIcons( "icononly" );
          if ( count( $footericons ) > 0  ) {
            foreach ( $footericons as $blockName => $footerIcons ) {
              foreach ( $footerIcons as $icon ) {
                if($wgLinusUseFooterIcons) {
                  echo $this->getSkin()->makeFooterIcon( $icon );
                } else {
                  echo '<span>' . $this->getSkin()->makeFooterIcon( $icon, 'withoutImage' ) . '</span>';
                }
              }
            }
          }
          ?>
        </div>

        <?php if( $this->pageExists( $wgLinusCopyrightPage ) ): ?>
        <p class="copyright"><?php echo $this->getPageContent( $wgLinusCopyrightPage ) ?></p>
        <?php endif; ?>
      </div><!-- container -->
    </footer><!-- footer -->
    <?php
  }

  function pageExists($title) {
    $pageTitle = Title::newFromText($title);
    return $pageTitle->exists();
  }

  function getPageContent($title, $default = '%2$s', $raw = false) {
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

  private function nav( $nav, $level = 0 ) {
    $output = '';
    foreach ( $nav as $navSlug => $navItem ) {
      if ( array_key_exists('items', $navItem) ) {
        $output .= '<li class="dropdown'.($level==0?:'-submenu').'">';
        $output .= '<a href="#"'.($level>0?:' class="dropdown-toggle" data-toggle="dropdown"').'>'
                      . $this->getIcon($navItem['icon'])
                      . $navItem['text']
                      . ($level>0?:' <b class="caret"></b>')
                      .'</a>';
        $output .= '<ul class="dropdown-menu">';
        $output .= $this->nav($navItem['items'], $level+1);
        $output .= '</ul>';
      } else {
        if( isset($navItem['divider']) && $navItem['divider'] ) {
          $output .= '<li class="divider"></li>';
        } else if( empty($navItem['href']) ) {
          $output .= '<li class="'.($level==0?'navbar-text':'dropdown-header').'">'.$this->getIcon($navItem['icon']).$navItem['text'].'</li>';
        } else {
          $output .= '<li><a href="'.$navItem['href'].'">'.$this->getIcon($navItem['icon']).$navItem['text'].'</a></li>';
        }
      }
    } // end foreach
    return $output;
  }

  /**
  * Render one or more navigations elements by name, automatically reveresed
  * when UI is in RTL mode
  */
  private function _nav( $nav ) {
    $output = '';
    foreach ( $nav as $topItem ) {
      // check mandatory properties
      if( !isset($topItem['title']) && !isset($topItem['link']) ) continue;
      $title = isset($topItem['title']) ? $topItem['title'] : $topItem['link'];
      $pageTitle = Title::newFromText( $title );

      $icon = $this->_buildIcon($topItem);

      if ( array_key_exists('sublinks', $topItem) ) {
        $output .= '<li class="dropdown">';
        $output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $icon . $title . ' <b class="caret"></b></a>';
        $output .= '<ul class="dropdown-menu">';

        foreach ( $topItem['sublinks'] as $subLink ) {
          $subtitle = isset($subLink['title']) ? $subLink['title'] : $subLink['link'];
          $subicon = $this->_buildIcon($subLink);

          $subLink['attributes'] = isset($subLink['attributes']) ? $subLink['attributes'] : '';
          $subLink['class'] = isset($subLink['class']) ? $subLink['class'] : '';

          if ( '----' == $subLink ) {
            $output .= "<li class='divider'></li>\n";
          } elseif ( isset($subLink['textonly']) && $subLink['textonly'] ) {
            $output .= "<li class=\"dropdown-header\">" . $subicon . "{$subtitle}</li>\n";
          } else {
            if( isset($subLink['local']) && $subLink['local'] && $subpageTitle = Title::newFromText($subtitle) ) {
              $href = $subpageTitle->getLocalURL();
            } else {
              $href = isset($subLink['link']) ? $subLink['link'] : '';
            } //end else

            $slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subtitle ) ) ) ) );
            $output .= "<li {$subLink['attributes']}><a href='{$href}' class='{$subLink['class']} {$slug}'>" . $icon . "{$subtitle}</a>";
          } //end else
        }
        $output .= '</ul>';
        $output .= '</li>';
      } else {
        if( $pageTitle ) {
          $output .= '<li' . ($this->data['title'] == $title ? ' class="active"' : '') . '><a href="' . ( $topItem['external'] ? $link : $pageTitle->getLocalURL() ) . '">' . $icon . $title . '</a></li>';
        }//end if
      }//end else
    }//end foreach
    return $output;
  }//end nav

  private function getNavigationFromData( $name, $text, &$data = null ) {
    if( !isset($data) ) {
      $data = isset($this->data[$name]) && $this->data[$name] ? $this->data[$name] : array();
    }

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
        $item['icon'] = $this->_getIconFor($navSlug);
      }

      $navArray[$navSlug] = $item;
    } // end foreach

    if( isset($text) && !empty($text) ) {
      $navArray = array($name => array(
        'text'    => $text,
        'icon'    => $this->_getIconFor($name),
        'primary' => true,
        'active'  => false,
        'items'   => $navArray
      ));
    }
    return $navArray;
  }

  private function getNavigationFromPage($title) {
    $content = $this->getPageContent($title, '', true);
    $content = explode("\n",$content);
    return $this->_parsePageContent($content);

    // $nav = array();
    // foreach(explode("\n", $content) as $line) {
    //   if(trim($line) == '') continue;
    //   if( preg_match('/^\*\*\s*----/', $line) ) {
    //     $nav[count($nav)-1]['items'][] = array('divider' => true);
    //     continue;
    //   } //end if

    //   $icon = '';
    //   if(preg_match('/^(.*)\s*\(icon:(fa-.+)\)\s*$/', $line, $iconMatch)) {
    //     $icon = $iconMatch[2];
    //     $line = $iconMatch[1];
    //   } else if(preg_match('/^(.*)\s*\(icon:(\S+)\)\s*$/', $line, $iconMatch)) {
    //     $icon = $iconMatch[2];
    //     $line = $iconMatch[1];
    //   }

    //   $sub = false;
    //   $link = false;
    //   $external = false;

    //   if(preg_match('/^\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
    //     $sub = false;
    //     $link = true;
    //   }elseif(preg_match('/^\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
    //     $sub = false;
    //     $link = true;
    //     $external = true;
    //   }elseif(preg_match('/^\*\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
    //     $sub = true;
    //     $link = true;
    //     $external = true;
    //   }elseif(preg_match('/\*\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
    //     $sub = true;
    //     $link = true;
    //   }elseif(preg_match('/\*\*\s*([^\* ]*)(.+)/', $line, $match)) {
    //     $sub = true;
    //     $link = false;
    //   }elseif(preg_match('/^\*\s*(.+)/', $line, $match)) {
    //     $sub = false;
    //     $link = false;
    //   }

    //   $match[1] = isset($match[1]) ? $match[1] : '';
    //   $match[2] = isset($match[2]) ? $match[2] : '';
    //   if( strpos( $match[2], '|' ) !== false ) {
    //     $item = explode( '|', $match[2] );
    //     $item = array(
    //       'text' => $match[1] . $item[1],
    //       'href' => $item[0],
    //       'local' => true,
    //     );
    //   } else {
    //     if( $external ) {
    //       $item = $match[2];
    //       $title = $match[1] . $match[3];
    //     } else {
    //       $item = $match[1] . $match[2];
    //       $title = $item;
    //     }//end else

    //     if( $link ) {
    //       $item = array('text'=> $title, 'href' => $item, 'local' => ! $external , 'external' => $external );
    //     } else {
    //       $item = array('text'=> $title, 'href' => $item, 'textonly' => true, 'external' => $external );
    //     }//end else
    //   }//end else

    //   $item['icon'] = $icon;

    //   if( $sub ) {
    //     $nav[count($nav)-1]['items'][] = $item;
    //   } else {
    //     $nav[] = $item;
    //   }//end else
    // }

    // return $nav;
  } //end get_page_links

  private function _parsePageContent( &$content ) {
    if(empty($content)) return array();

    $n = strlen($content[0]) - strlen(ltrim($content[0],'*'));
    $pre = str_repeat('*',$n);

    $items = array();
    while( !empty($content) && substr($content[0],0,$n) == $pre ) {
      if( trim($content[0]) == $pre ) {
        array_shift($content);
      } else if( strpos($content[0], '*', $n) === $n ) {
        $items[count($items)-1]['items'] = $this->_parsePageContent($content);
      } else {
        $items[] = $this->_parseNavLine($content[0]);
        array_shift($content);
      }
    }
    return $items;
  }

  private function _parseNavLine( $line ) {
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
      if( in_array($keyword, array('self')) ) {
        switch( $keyword ) {
          case 'self': $href = $this->getSkin()->getTitle()->getLocalURL(); break;
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

  private function getIcon( $icon ) {
    if( !empty($icon) ) {
      if( substr($icon, 0, 3) == 'fa-' ) {
        $icon = '<i class="fa fa-fw '.$icon.'"></i> ';
      } else {
        $icon = '<span class="glyphicon fa-fw glyphicon-'.$icon.'"></span> ';
      }
    }
    return $icon;
  }

  private function getIconFor( $slug ) {
    return $this->getIcon( $this->_getIconFor($slug) );
  }

  // TODO: Put this into i18n-files?
  private function _getIconFor( $slug ) {
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
