jQuery(function($) {

    new WOW().init();
    
    $.stellar({
        horizontalScrolling: false,
        verticalOffset: 40
    });

    $(function(){
        $('#primary-menu').slicknav({
            prependTo: 'nav.main-nav'
        });
        
    });

    $( "i.fa.fa-search" ).on( "click", function() {
        $( "#search-background" ).fadeIn(300);
    });

    $( "#search-background" ).on( "click", function( e ) {
        if ( $( e.target ).is('section') || $( e.target ).is('div.inner') ) {
            $(this).fadeOut(300);
        }
    });
    
    // Swap and move to (with easing) the hero image when a thumb is clicked
    $( "div.feature-thumbnails div img" ).on( "click", function( e ) {

        var hero = $( "div.hero-banner" ),
            thumb = $(this);
            target = $(this).hash;

        hero.fadeOut(500, function () {
            hero.css( "background-image", "url(" + thumb.attr( "src" ) + ")" );
            hero.fadeIn(500);
        });
        
        $( "html, body" ).stop().animate({
            'scrollTop': hero.offset().top
        }, 650, "swing", function () {
            window.location.hash = target;
        });      
    
    });

    // Navigate to a post from the blogroll when that post's div is clicked
    $( "div.blog-roll-post article" ).on( "click", function ( e ){
        window.location.href = $(this).attr("data-link");
    });

    
    // FAQ Visibility Toggle
    $( 'h3.faq-title' ).click( function() {
        
        $( this ).parent().children( 'div.faq-content').slideToggle();
        
    });

    // News Item Date Visibility Toggle
    $( '#news-posts .news-item .image' ).mouseover( function() {
        $( this ).children( '.date').stop().fadeTo( 400, 1);
    });
    $( '#news-posts .news-item .image' ).mouseout( function() {
        $( this ).children( '.date').stop().fadeTo( 400, 0);
    });
    
    var testimonials_carousel = $("#relia-testimonials");
	
    testimonials_carousel.owlCarousel({
        
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem: true,
        autoPlay : 7000,

    }); 

    // ------------
    // Contact Form
    // ------------
    $('#relia-contact-form').submit( function (e) {
       
        e.preventDefault();
        
        $('.mail-sent,.mail-not-sent').hide();
       
        var form = $(this);
        var name = $('.name', form ).val();
        var email = $('.email', form ).val();
        var message = $('textarea.message', form ).val();
        var recipient = $('.recipient', form ).val();
        var url = form.attr('action');
        
        if( name.length < 2 ) {
            alert( 'Please enter a name' );
            return false;
        }
        
        if( message.length < 2 ) {
            alert( 'Please enter a message' );
            return false;
        }
        
        if( ! reliaValidateEmail( email ) ) {
            alert( 'Please enter a valid email address' );
            return false;
        }
        
        var data = {
            
            action : 'relia_send_message',
            name : name,
            email : email,
            message : message,
            recipient : recipient
            
        }
        
        $.post( url, data, function ( response ) {
           console.log( response );
            if( response == 1 ) {
                $('.mail-sent').fadeIn(350);
                form[0].reset();
                
            }else{
                $('.mail-not-sent').fadeIn(350);
            }
            
        });
        
        
    });
    
    function reliaValidateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

});