$(function() {
	// Some general styling of elements

	// Buttons
	$('input[type=submit],input[type=button],input[type=reset]').addClass('btn btn-default');
	$('input[type=submit]').addClass('btn-primary').removeClass('btn-default');
	$('button[type=submit],button[type=button],button[type=reset]').addClass('btn btn-default');
	$('button[type=submit]').addClass('btn-primary').removeClass('btn-default');

	// Check if the edit page form is present
	if( $('#wpTextbox1') ) {
		// Enable behave.js for wiki editor
		// (might collide with the new WikiEditor extension in the future)
		/*new Behave({
				textarea: document.getElementById('wpTextbox1')
		});*/

		// Add some styles to the editor elements
		$('.editButtons').addClass('well');
		$('input[name=wpSave]').removeClass('btn-primary').addClass('btn-success');
		$('span.cancelLink a').addClass('btn btn-warning');
	}

	$('#page-contents a').click(function(e){
		e.preventDefault();
		var $target = $(this).attr('href');
		$(document).scrollTop( $($target).offset().top-100 );
	});

	/*$('table')
		.not('#toc')
		.not('.mw-specialpages-table')
		.each(function() {
			var $el = $(this);

			if( $el.closest('form').length == 0 ) {
				if ( $el.hasClass('info-box') ) {
					$el.addClass('table')
						 .addClass('table-bordered');
				} else {
					$el.addClass('table')
						 .addClass('table-striped')
						 .addClass('table-bordered');
				}//end else
			}//end if
		});*/

	$('.alert a').each(function() {
		var $el = $(this);
		$el.addClass('alert-link');
	});

	// Add labels to checkboxes and radio buttons
	$('input[type=checkbox],input[type=radio]').each(function() {
		var $el = $(this);

		var id = $el.attr('id');
		$( 'label[for=' + id + ']' ).each(function() {
			var $label = $(this);
			if( $.trim( $label.text() ) != '' ) {
				$el.prependTo( $label );
			}//end if
		});
		$el.closest('label').addClass($el.attr('type'));
	});

	// Init tooltips and popovers
	$('.tip').tooltip();
	$('.pop').popover();
	$('.dropdown-toggle').dropdown();

	// Move and prepare table of contents
	if ( $('.toc-sidebar').length > 0 ) {
		if ( 0 === $('#toc').length ) {
			$('.toc-sidebar').remove();
			$('.wiki-body-section').removeClass('col-md-9').addClass('col-md-12');
		} else {
			$('.toc-sidebar').append('<h3>Inhalt</h3>');
			$('#toc').find('ul:first').appendTo('.toc-sidebar');
			$('#toc').remove();
			$('.toc-sidebar').attr('id', 'toc');
			/*$('#toc').affix({
			  offset: {
			    top: 0,
			    bottom: 10
			  }
			});*/

			//$('.toc-sidebar ul').addClass('list-group');
			//$('.toc-sidebar ul li').addClass('list-group-item');
		}//end else
	} else {
		$('#toc').each(function() {
			var $toc = $(this);
			var $title = $toc.find('#toctitle');
			var $links = $title.siblings('ul');

			//$('.page-header').prepend('<ul class="nav nav-pills pull-right"><li class="dropdown dropdown-menu-right" id="page-contents"><a class="dropdown-toggle" href="#"><i class="icon-list"></i> Contents <b class="caret"></b></a> <ul class="dropdown-menu"></ul></li></ul>');
			$('.page-header').prepend('<div class="dropdown pull-right toc-dropdown"><button class="btn dropdown-toggle" type="button" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-list"></span> Inhalt <span class="caret"></span></button><ul class="dropdown-menu" role="menu"></ul></div>');
			$('.page-header .toc-dropdown').find('.dropdown-menu').html( $links.html() );
			$('#toc').hide();
		});

		if( $('.page-header .toc-dropdown').length === 0 ) {
			$('.page-header').prepend('<ul class="pull-right toc-dropdown"><li></li></ul>');
		}//end if
	}//end if .. else

	$('#wiki-body .body a[title="Special:UserLogin"]').click();
});
