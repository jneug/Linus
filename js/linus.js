$(function() {
    // Some general styling of elements
    // Fix inverted footer color for html-tag
    $('body.inverted-navbar').parent().addClass('inverted-navbar');

    // Buttons
    $('input[type=submit],input[type=button],input[type=reset]').addClass('btn btn-default');
    $('input[type=submit]').addClass('btn-primary').removeClass('btn-default');
    $('button[type=submit],button[type=button],button[type=reset]').addClass('btn btn-default');
    $('button[type=submit]').addClass('btn-primary').removeClass('btn-default');

    // Form on edit pages
    if ($('#wpTextbox1')) {
        // Cancel link (buttons are styled by hook)
        // $('.editButtons').addClass('well'); // TODO: Move to LinusHooks or even remove?
        //$('span.cancelLink a').addClass('btn btn-warning');
        $('#mw-editform-cancel').addClass('btn btn-warning');
    }

    // Links in alerts
    $('.alert a').addClass('alert-link');

    // Preferences tabs
    $('#preftoc').addClass('nav nav-pills');

    // Move toc
    if( $('#sidebar') ) {
        if ($('#toc')) {
            $('#toc')
        			.prependTo($('#sidebar'))
        			.affix();

              // Activate Smooth scrolling
              if( $('body.smooth-scroll') ) {
                $('#toc a[href*=#]:not([href=#])').click(function() {
                    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                      var target = $(this.hash);
                      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                      if (target.length) {
                        $('html,body').animate({
                          scrollTop: target.offset().top - 50
                      }, 800);
                        return false;
                      }
                    }
                });
              }
        } else
        // remove sidebar if empty
        if( $('#sidebar').children().length == 0 ) {
            $('#sidebar').remove();
            $('#main-content')
                .removeClass('col-md-9')
                .addClass('col-md-12');
        }
    }

    // Some SMW styling
    $('.smw-editpage-help')
        .removeClass('smw-editpage-help')
        .addClass('well');
    if( $('.smwfact') ) {
        $('.smwfact')
            .removeClass('smwfact')
            .addClass('smw-factbox');
        $('.smwfacttable')
            .removeClass('smwfacttable')
            .addClass('table table-condensed table-bordered table-striped table-hover');
    }

    // Initialize BS components
    // Init tooltips and popovers
    $('.tip').tooltip();
    $('.pop').popover();
    // $('.dropdown-toggle').dropdown();

    // Add close functionality to close-buttons
    $('.close').click(function() {
        $(this).closest('.closeable').remove();
    });
});
