var main = function() {
    $('.icon-menu').click(function() {
        $('.drawer').animate({
            left: '0px'
        }, 200);
        
       /* $('body').animate({
            left:'285px'
        }, 200);*/
    });
	
	$('.icon-close').click(function() {
        $('.drawer').animate({
            left: '-285px'
        }, 200);
        
       /* $('body').animate({
            left:'0px'
        }, 200);*/
    });
};


$(document).ready(main);