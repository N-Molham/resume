/**
 * Resume
 */
( function( window ) {
	jQuery( function( $ ) {
		// inits
		var $nav = $( '#header .sections-nav ul' ),
			$body = $( 'body' ),
			$go_top = $( '#go-top' ),
			scroll_speed = 500;

		// loop nav sections
		$( '.section[data-nav=true]' ).each( function( index, element ) {
			// add nav menu item
			$nav.append( '<li class="item"><a href="#'+ element.id +'" class="link">'+ $( element ).find( '.section-title:eq(0)' ).text() +'</a></li>' );
		} );

		// nav menu items click
		var $nav_links = $nav.find( '.link' ).on( 'click', function( e ) {
			// prevent default
			e.preventDefault();

			
			// add selected class
			$( this ).addClass( 'link-selected' );

			// set URL hash
			set_hash_no_scroll( e.currentTarget.hash );
		} );

		// if there are no hash trigger click the first nav item
		if ( !document.location.hash.length )
			$nav_links.filter( ':first' ).trigger( 'click' );

		// check init hash
		$( window ).on( 'hashchange', function( e ) {
			// prevent default
			e.preventDefault();

			// if hash is set
			if ( document.location.hash.length ) {
				if ( document.getElementById( document.location.hash.replace( '#', '' ) ) ) {
					// scroll to section
					$body.animate( { scrollTop: $( document.location.hash ).offset().top }, scroll_speed );

					// remove selected class from all nav links > add selected class to target link
					$nav_links.removeClass( 'link-selected' ).filter( '[href='+ document.location.hash +']' ).addClass( 'link-selected' );
				}
			} 
		} )
		// trigger the event on load
		.trigger( 'hashchange' )
		// scrolling event
		.on( 'scroll', function() {
			// check vertical scrolling
			if ( window.scrollY > 124 ) {
				// it passed the header

				// show button
				if ( !$go_top.hasClass( 'visible' ) )
					$go_top.addClass( 'visible' );
			} else {
				// still on header

				// hide button
				if ( $go_top.hasClass( 'visible' ) )
					$go_top.removeClass( 'visible' );
			}
		} );

		// scroll to top 
		$go_top.on( 'click', function( e ) {
			// prevent default
			e.preventDefault();

			// trigger click the first nav item
			$nav_links.filter( ':first' ).trigger( 'click' );
		} );
	} );

	// remove hash from location
	window.remove_hash = function () { 
		var scrollV, scrollH, loc = window.location;
		if ( 'pushState' in history ) {
			// html5
			history.pushState( '', document.title, loc.pathname + loc.search );
		} else {
			// Prevent scrolling by storing the page's current scroll offset
			scrollV = document.body.scrollTop;
			scrollH = document.body.scrollLeft;

			// remove hash
			loc.hash = '';

			// Restore the scroll offset, should be flicker free
			document.body.scrollTop = scrollV;
			document.body.scrollLeft = scrollH;
		}
	};

	// set URL hash without scrolling
	window.set_hash_no_scroll = function (hash) {
		hash = hash.replace( /^#/, '' );
		var fx, node = $( '#' + hash );
		if ( node.length ) {
			node.attr( 'id', '' );
			fx = $( '<div></div>' ).css({
				position:'absolute',
				visibility:'hidden',
				top: $( document ).scrollTop() + 'px'
			})
			.attr( 'id', hash )
			.appendTo( document.body );
		}
		document.location.hash = hash;
		if ( node.length ) {
			fx.remove();
			node.attr( 'id', hash );
		}
	};

	// debugging
	window.trace = function () {
		if( window.console && arguments.length ) {
			if ( arguments.length == 1 ) {
				console.log( arguments[0] );
			} else {
				for ( var i in arguments ) {
					console.log( arguments[i] );
				}
			}
		}
	};
} )( window );