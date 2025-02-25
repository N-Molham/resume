/**
 * Resume
 */
(function (window) {
    jQuery(function ($) {
        // header navigation items
        let $nav = $('#header').find('.sections-nav ul'),
            // body element depending of browser engine
            $body = $('html, body'),
            $window = $(window),
            // hashchange animate or not
            animate_scroll = true,
            is_animating = false;

        // scroll speed
        const scroll_speed = 500;

        // loop nav sections
        $('.section[data-nav=true]').each(function (index, element) {
            // add nav menu item
            $nav.append('<li class="item"><a href="#' + element.id + '" class="link">' + $(element).find('.section-title:eq(0)').text() + '</a></li>');
        }).on('scrollSpy:enter', function (e) {
            // change if not animating
            if (!is_animating) {
                // mark link as selected
                $nav.find('.link').removeClass('link-selected').filter('[href*=' + e.currentTarget.id + ']').addClass('link-selected');
            }
        }).scrollSpy();

        // nav menu items click
        const $nav_links = $nav.find('.link').on('spy-click click', function (e) {
            // prevent default
            e.preventDefault();

            // add selected class
            $(this).addClass('link-selected');

            // set URL hash
            set_hash_no_scroll(e.currentTarget.hash);
            $window.trigger('spy-hashchange');
        });

        // check init hash
        $window.on('spy-hashchange hashchange', function (e) {
            // prevent default
            e.preventDefault();

            // if hash is set
            if (document.location.hash.length) {
                if (document.getElementById(document.location.hash.replace('#', ''))) {

                    // scroll to section
                    if (animate_scroll) {
                        // animation started
                        is_animating = true;
                        $body.animate({scrollTop: $(document.location.hash).offset().top}, scroll_speed, function () {
                            // animation completed
                            is_animating = false;
                        });
                    } else {
                        animate_scroll = true;
                    }

                    // remove selected class from all nav links > add selected class to target link
                    $nav_links.removeClass('link-selected').filter('[href="' + document.location.hash + '"]').addClass('link-selected');
                }
            }
        })
            // trigger the event on load
            .trigger('spy-hashchange hashchange');
    });

    // remove hash from location
    window.remove_hash = function () {
        let scrollV, scrollH, loc = window.location;
        if ('pushState' in history) {
            // html5
            history.pushState('', document.title, loc.pathname + loc.search);
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
        hash = hash.replace(/^#/, '');
        if (history.pushState) {
            history.pushState(null, null, '#' + hash);
        } else {
            let fx, node = $('#' + hash);
            if (node.length) {
                node.attr('id', '');
                fx = $('<div></div>').css({
                    position: 'absolute',
                    visibility: 'hidden',
                    top: $(document).scrollTop() + 'px'
                })
                    .attr('id', hash)
                    .appendTo(document.body);
            }
            document.location.hash = hash;
            if (node.length) {
                fx.remove();
                node.attr('id', hash);
            }
        }
        return false;
    };
})(window);
