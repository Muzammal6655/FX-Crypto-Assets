// slider
$(document).ready(function(e){
$(".testimonial-slider").owlCarousel({
loop:true,
margin:14,
nav:true,
autoplay:true,
autoplayTimeout:3000,
navText: [
    '<i class="fa fa-angle-left" aria-hidden="true"></i>',
    '<i class="fa fa-angle-right" aria-hidden="true"></i>'
],
responsive:{
    0:{
        items:1
    },
    600:{
        items:1
    },
    800:{
        items:2
    },
    1000:{
        items:2
    }
}
});
});
//posslider
$(document).ready(function(e){
	$('.product-slider').owlCarousel({
    loop:true,
    margin:8,
	nav:true,
	autoplay:false,
	autoplayTimeout:3000,
	navText: [
		'<i class="fa fa-angle-left" aria-hidden="true"></i>',
		'<i class="fa fa-angle-right" aria-hidden="true"></i>'
	],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        800:{
            items:3
        },
        1000:{
            items:4
        }
    }
	});
});