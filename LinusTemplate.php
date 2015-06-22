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
    global $wgParser, $wgLinusHeadingCount;
    global $wgLinusUseSidebar, $wgLinusTOCInSidebar, $wgLinusHideHeader;

    // Decide if to show sidebar
    $showSidebar = $wgLinusUseSidebar;
    if( $wgLinusTOCInSidebar && $wgLinusHeadingCount > 3 ) {
      $showSidebar = true;
    }

		$this->html( 'headelement' );
    ?>
    <!-- link to content for accessibility -->
    <a href="#wiki-body" class="sr-only">Skip to main content</a>
    <?php $this->renderNavbar(); ?>

	<main id="wiki-outer-body" class="container">
      <div id="wiki-body" class="row">
  		<?php if ( $showSidebar ): ?>
        <aside class="col-md-3 hidden-print" id="sidebar">
        <?php $this->renderSidebar() ?>
        </aside>
		    <section class="col-md-9 wiki-body-section" id="main-content">
        <?php else: ?>
        <section class="col-md-12 wiki-body-section" id="main-content">
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

          <?php if ( !empty( $this->data['title'] ) && !in_array($this->data['title'],$wgLinusHideHeader) ): ?>
  				<div class="pagetitle page-header">
  					<h1><?php $this->html( 'title' ) ?></h1>

            <?php if( $this->data['subtitle'] ): ?>
            <div class="pagesubtitle text-muted"><?php $this->html('subtitle') ?></div>
            <?php endif; ?>
  				</div>
          <?php endif; ?>

  				<article class="body">
  				<?php $this->renderBody() ?>
          </article>

  				<?php if ( $this->data['catlinks'] ): ?>
                <!-- catlinks -->
  				<?php $this->html( 'catlinks' ); ?>
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
    global $wgLogo, $wgSitename, $wgSitenameshort, $wgUser, $wgEnableUploads;
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
        <nav class="<?php echo $navbarClasses ?>" id="main-nav">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#linus-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo $this->data['nav_urls']['mainpage']['href'] ?>" title="<?php echo $wgSitename ?>"><?php echo isset( $wgLogo ) && $wgLogo ? '<img src="'.$wgLogo.'" alt="Logo"/> ' : (isset($wgSitenameshort) ? $wgSitenameshort : $wgSitename); ?></a>
            </div>

            <div class="collapse navbar-collapse" id="linus-navbar-collapse">
                <?php if( LinusParser::pageExists($wgLinusTitlebarPage) ): ?>
                <ul class="nav navbar-nav">
                  <?php echo LinusParser::nav( LinusParser::getNavigationFromPage($wgLinusTitlebarPage) ) ?>
                </ul>
                <?php endif; ?>

              <ul class="nav navbar-nav navbar-right">
                <?php
                if ( $wgUser->isLoggedIn() ) {
                  echo LinusParser::nav( LinusParser::getNavigationFromData('personal_urls', $wgUser->getName(), $this->data['personal_urls']) );
                } else {
                ?>
                <li>
                  <?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Userlogin' ), LinusParser::getIconFor('login').wfMessage( 'login' )->text() ); ?>
                </li>
                <?php
                } // end else

                if ( $wgUser->isLoggedIn() || !$wgLinusHideActionsForAnon ) {
                  if ( !empty($this->data['content_actions']) ) {
                    echo LinusParser::nav( LinusParser::getNavigationFromData('content_actions', wfMessage('article' /*'nstab-main' */), $this->data['content_actions']) );
                  }
                }

                ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo LinusParser::getIconFor('toolbox') ?><span class="sr-only"><?php echo wfMessage('toolbox') ?></span> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <?php
                    $toolbox = $this->getToolbox();
                    echo LinusParser::nav( LinusParser::getNavigationFromData('toolbox', '', $toolbox) );
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
            </div><!-- /.collapse.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    <?php
  }

  protected function renderSidebar() {
    global $wgLinusUseSidebar, $wgLinusTOCInSidebar;

    if( $wgLinusTOCInSidebar ) {
      echo '<div id="toc-container"></div>';
    }

    if( $wgLinusUseSidebar ) {
      $sidebar = $this->getSidebar();
        foreach( $sidebar as $boxName => $box) {
          echo '<div class="hidden-sm hidden-xs"id="'.Sanitizer::escapeId($box['id']).'">'."\n";
          echo '<h2>'.htmlspecialchars($box['header']).'</h2>'."\n";
          echo '<ul class="nav nav-pills nav-stacked">';
          // echo '<li class="disabled"><a href="#">'.$content['header'].'</a></li>';
          // echo '<li class="navbar-text"><a href="#">'.$content['header'].'</a></li>';
          echo LinusParser::nav(LinusParser::getNavigationFromData($boxName,'',$box['content']));
          echo '</ul>'."\n";
          echo '</div>'."\n";
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

    $footerClasses = 'footer footer-default';
    if( $wgLinusNavbarInverted ) {
      $footerClasses = 'footer footer-inverse';
    }
    ?>
    <footer class="<?php echo $footerClasses ?> container-fluid" id="main-footer">
    <div class="row">
        <?php echo LinusParser::getPageContent( $wgLinusFooterPage ); ?>
    </div>
    <div class="row">
        <div class="col-md-8">
        <?php if( LinusParser::pageExists( $wgLinusCopyrightPage ) ): ?>
        <p class="copyright"><?php echo LinusParser::getPageContent( $wgLinusCopyrightPage ) ?></p>
        <?php endif; ?>
    </div>
      <div class="col-md-4" id="footer-links">
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
                  echo '<span class="footericon">'.$this->getSkin()->makeFooterIcon( $icon ).'</span>';
                } else {
                  echo '<span class="footerlink">' . $this->getSkin()->makeFooterIcon( $icon, 'withoutImage' ) . '</span>';
                }
              }
            }
          }
          ?>
        </div>
      </div><!-- container -->
    </footer><!-- footer -->
    <?php
  }

}
