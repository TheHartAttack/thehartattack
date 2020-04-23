import Like from './like.js';
import Search from './search.js';
import Contact from './contact.js';
import User from './user.js';
import Comments from './comments.js';
import CommentLike from './comment-like.js';
import Codes from './codes.js';

jQuery(document).ready(function($){

/*Page load actions*/
var screenWidth = window.innerWidth;
if (screenWidth <= 1024){
  var headerSvg = document.getElementById('header-svg');
  headerSvg.setAttribute('viewBox', '0 0 2208 298');
}
var headerHeight = $('#header-svg').height();
var footerHeight = $('#footer-svg').height();
var headerFooterHeight = headerHeight + footerHeight;
var glitchWidth = $('.glitch-container').width();
$('.glitch-container').height(glitchWidth);
var paddingTop;

if (screenWidth <= 1536){
  paddingTop = 8;
} else if (screenWidth > 1536){
  paddingTop = 16;
}

if (screenWidth > 1536){
  $('.header-tha-icon-scrolled-1').css('transform', 'unset');
  $('.header-tha-icon-scrolled-2').css('transform', 'unset');
} else if (screenWidth <= 1536 && screenWidth > 1200){
  $('.header-tha-icon-scrolled').css('visibility', 'visible');
  $('.header-tha-icon-scrolled-1').css('transform', 'translateX(144px)');
  $('.header-tha-icon-scrolled-2').css('transform', 'translateX(-144px)');
} else if (screenWidth <= 1200){
  $('.header-tha-icon-scrolled').css('visibility', 'hidden');
}

if (screenWidth <= 1200 && screenWidth > 1024){
  $('.header-tha-icon-1').css('transform', 'translate(144px, 0px)');
  $('.header-tha-icon-2').css('transform', 'translate(-144px, 0px)');
  $('.header-pentagram-1').css('transform', 'translate(72px, 0px)');
  $('.header-pentagram-2').css('transform', 'translate(-72px, 0px)');
} else if (screenWidth <= 1024) {
  $('.header-tha-icon-1').css('transform', 'translate(504px, -42px) scale(0.75)');
  $('.header-tha-icon-2').css('transform', 'translate(-504px, -42px) scale(0.75)');
}
$('#main-container').css('min-height', 'calc(100vh - (' + headerFooterHeight + 'px)');

if (screenWidth > 1024){
  $('header').css('top', -(headerHeight*0.75)+6)
} else {
  $('header').css('top', 'unset')
}

if (screenWidth > 1024){
  $('a.post').height((($('a.post').width())/16) * 4.5);
  $('a.post:nth-of-type(1)').height((($('a.post').width())/16) * 6.75);
} else if (screenWidth <= 1024 && screenWidth > 512){
  $('a.post').height((($('a.post').width())/16) * 9);
} else if (screenWidth <= 512){
  $('a.post').height($('a.post').width());
}

/*Page resize actions*/
$(window).on('resize', function(){
  screenWidth = window.innerWidth;
  var headerSvg = document.getElementById('header-svg');
  if (screenWidth <= 1024){
    headerSvg.setAttribute('viewBox', '0 0 2208 298');
  } else {
    headerSvg.setAttribute('viewBox', '0 0 2208 338');
  }
  headerHeight = $('#header-svg').height();
  footerHeight = $('#footer-svg').height();
  headerFooterHeight = headerHeight + footerHeight;
  scroll = window.scrollY;
  paddingTop;
  if (screenWidth <= 1536){
    paddingTop = 8;
  } else if (screenWidth > 1536){
    paddingTop = 16;
  }
  if (screenWidth > 1536){
    $('.header-tha-icon-scrolled-1').css('transform', 'unset');
    $('.header-tha-icon-scrolled-2').css('transform', 'unset');
  } else if (screenWidth <= 1536 && screenWidth > 1200){
    $('.header-tha-icon-scrolled').css('visibility', 'visible');
    $('.header-tha-icon-scrolled-1').css('transform', 'translateX(144px)');
    $('.header-tha-icon-scrolled-2').css('transform', 'translateX(-144px)');
  } else if (screenWidth <= 1200){
    $('.header-tha-icon-scrolled').css('visibility', 'hidden');
  }
  if (screenWidth > 1200){
    $('.header-tha-icon-1').css('transform', 'unset');
    $('.header-tha-icon-2').css('transform', 'unset');
    $('.header-pentagram-1').css('transform', 'unset');
    $('.header-pentagram-2').css('transform', 'unset');
  } else if (screenWidth <= 1200 && screenWidth > 1024){
    $('.header-tha-icon-1').css('transform', 'translate(144px, 0px)');
    $('.header-tha-icon-2').css('transform', 'translate(-144px, 0px)');
    $('.header-pentagram-1').css('transform', 'translate(72px, 0px)');
    $('.header-pentagram-2').css('transform', 'translate(-72px, 0px)');
  } else if (screenWidth <= 1024){
    $('.header-tha-icon-1').css('transform', 'translate(504px, -42px) scale(0.75)');
    $('.header-tha-icon-2').css('transform', 'translate(-504px, -42px) scale(0.75)');
  }
  var glitchWidth = $('.glitch-container').width();
  $('.glitch-container').height(glitchWidth);
  $('#main-container').css('min-height', 'calc(100vh - (' + headerFooterHeight + 'px)');
  $('a.search-result').height($('.search-result').width());

  if (screenWidth > 1024){
    $('header').css('top', -(headerHeight*0.75)+6)
  } else {
    $('header').css('top', 'unset')
  }

  if (screenWidth > 1024){
    $('a.post').height((($('a.post').width())/16) * 4.5);
    $('a.post:nth-of-type(1)').height((($('a.post').width())/16) * 6.75);
  } else if (screenWidth <= 1024 && screenWidth > 768){
    $('a.post').height((($('a.post').width())/16) * 9);
  } else if (screenWidth <= 768 && screenWidth > 512){
    $('a.post').height((($('a.post').width())/16) * 12.5);
  } else if (screenWidth <= 512){
    $('a.post').height($('a.post').width());
  }
});

/*Page scroll actions*/
$(window).on('scroll', function() {
  scroll = window.scrollY;
  headerHeight = $('#header-svg').height();
  screenWidth = window.innerWidth;
  if (scroll > (headerHeight*5)/8){
    $('.header-tha-icon-scrolled').fadeIn(250);
  } else {
    $('.header-tha-icon-scrolled').fadeOut(250);;
  }
  if (scroll > ((headerHeight*0.75)-4)){
    scroll = ((headerHeight*0.75)-4);
  }
  var treeScale = (3/scroll)*25;
  var pumpkinScale = (3/scroll)*25;
  if (treeScale > 1){treeScale = 1} else if (treeScale < 0.4){treeScale = 0.4};
  if (pumpkinScale > 1){pumpkinScale = 1} else if (pumpkinScale < 0.66){pumpkinScale = 0.66};
  if (screenWidth > 1200){
    $('.header-xmas-tree-1').css('transform', 'scale(' + treeScale + ')');
    $('.header-xmas-tree-2').css('transform', 'scale(' + treeScale + ')');
    $('.header-halloween-pumpkin-1').css('transform', 'scale(' + pumpkinScale + ')');
    $('.header-halloween-pumpkin-2').css('transform', 'scale(' + pumpkinScale + ')');
  }
});

/*Set header hex transform origins*/
  var hexArray = document.getElementsByClassName('header-hex');
  hexArray = Array.from(hexArray);
  hexArray.forEach(function(hex){
    var hexXY = hex.getBBox();
    var hexX = hexXY.x + (hexXY.width / 2);
    var hexY = hexXY.y + (hexXY.height / 2);
    hex.style.transformOrigin = hexX + "px " + hexY + "px";
  });

  /*Set header logo transform origin*/
  var headerLogo = document.getElementById('the-hart-attack-logo');
  var headerLogoXY = headerLogo.getBBox();
  var headerLogoX = headerLogoXY.x + (headerLogoXY.width / 2);
  var headerLogoY = headerLogoXY.y;
  headerLogo.style.transformOrigin = headerLogoX + "px " + headerLogoY + "px";


  /*Set header hex buttons transform origins*/
    var hexArray = document.getElementsByClassName('header-hex-button');
    hexArray = Array.from(hexArray);
    hexArray.forEach(function(hex){
      var hexXY = hex.getBBox();
      var hexX = hexXY.x + (hexXY.width / 2);
      var hexY = hexXY.y + (hexXY.height / 2);
      hex.style.transformOrigin = hexX + "px " + hexY + "px";
    });

/*Set header THA icons transform origins*/
  var thaIconArray = document.getElementsByClassName('header-tha-icon');
  thaIconArray = Array.from(thaIconArray);
  thaIconArray.forEach(function(thaIcon){
    var thaIconXY = thaIcon.getBBox();
    var thaIconX = thaIconXY.x + (thaIconXY.width / 2);
    var thaIconY = thaIconXY.y + (thaIconXY.height / 2);
    thaIcon.style.transformOrigin = thaIconX + "px " + thaIconY + "px";
  });

/*Set header xmas tree transform origins*/
  var thaIconArray = document.getElementsByClassName('header-xmas-tree');
  thaIconArray = Array.from(thaIconArray);
  thaIconArray.forEach(function(thaIcon){
    var thaIconXY = thaIcon.getBBox();
    var thaIconX = thaIconXY.x + (thaIconXY.width / 2);
    var thaIconY = thaIconXY.y + (thaIconXY.height);
    thaIcon.style.transformOrigin = thaIconX + "px " + thaIconY + "px";
  });

/*Set header halloween pumpkin transform origins*/
  var thaIconArray = document.getElementsByClassName('header-halloween-pumpkin');
  thaIconArray = Array.from(thaIconArray);
  thaIconArray.forEach(function(thaIcon){
    var thaIconXY = thaIcon.getBBox();
    var thaIconX = thaIconXY.x + (thaIconXY.width / 2);
    var thaIconY = thaIconXY.y + (thaIconXY.height);
    thaIcon.style.transformOrigin = thaIconX + "px " + thaIconY + "px";
  });

/*Spin header hexes on click*/
  $('#test-button').on('click', function(){
    $('.header-hex').addClass('header-hex-animate');
    setTimeout(function(){
      $('.header-hex').removeClass('header-hex-animate');
    }, 10000);
  });

/*Set footer hex transform origins*/
  var hexArray = document.getElementsByClassName('footer-hex');
  hexArray = Array.from(hexArray);
  hexArray.forEach(function(hex){
    var hexXY = hex.getBBox();
    var hexX = hexXY.x + (hexXY.width / 2);
    var hexY = hexXY.y + (hexXY.height / 2);
    hex.style.transformOrigin = hexX + "px " + hexY + "px";
  });

/*Set social link hex transform origins*/
  var hexArray = document.getElementsByClassName('social-link-hex');
  hexArray = Array.from(hexArray);
  hexArray.forEach(function(hex){
    var hexXY = hex.getBBox();
    var hexX = hexXY.x + (hexXY.width / 2);
    var hexY = hexXY.y + (hexXY.height / 2);
    hex.style.transformOrigin = hexX + "px " + hexY + "px";
  });

/*Set social link icon transform origins*/
  var hexArray = document.getElementsByClassName('social-link-icon');
  hexArray = Array.from(hexArray);
  hexArray.forEach(function(hex){
    var hexXY = hex.getBBox();
    var hexX = hexXY.x + (hexXY.width / 2);
    var hexY = hexXY.y + (hexXY.height / 2);
    hex.style.transformOrigin = hexX + "px " + hexY + "px";
  });

/*Instafeed*/
//  var instafeedResize = function(){
//    $('#instafeed a').height($('#instafeed a').width());
//  };
//  var feed = new Instafeed({
//        get: 'user',
//        userId: '271222565',
//        clientId: '7ca96b22b96f40e9926b47d1f4145a47',
//        accessToken: '271222565.1677ed0.3e3b5d5ce1234e3a9bf241fba1b164cf',
//        resolution: 'low_resolution',
//        limit: 8,
//        after: instafeedResize
//    });
//  feed.run();

/*Set single post title section height*/
  if (screenWidth <= 1024){
    $('.single-post-title-section').height($('.single-post-title-section').width());
  } else {
    $('.single-post-title-section').height((($('.single-post-title-section').width())/16)*4.5);
  }
  $(window).on('resize', function(){
    if (screenWidth <= 1024){
      $('.single-post-title-section').height($('.single-post-title-section').width());
    } else {
      $('.single-post-title-section').height((($('.single-post-title-section').width())/16)*4.5);
    }
  });

/*Scroll to comments on single page*/
$('.post-comments-link').on('click', function(e){
  e.preventDefault();
  $('html').animate({
    scrollTop: ($('#single-post-comments').offset().top - 100)
  }, 500);
});

/*Scroll to comment input on quote click*/
$('.comment-quote').on('click', function(e){
  e.preventDefault();
  $('html').animate({
    scrollTop: ($('#comments-form').offset().top - 100)
  }, 250);
});


});
