import Posts from './posts.js';
import Like from './like.js';
import Search from './search.js';
import Contact from './contact.js';
import User from './user.js';
import UserAnon from './userAnon.js';
import Comments from './comments.js';
import CommentLike from './comment-like.js';
import Codes from './codes.js';

jQuery(document).ready(function($){

  window.scroll(0, 0);

  /*Page load actions*/
  let scroll = window.scrollY;
  if (scroll > 282){
    $('header').addClass('scrolled');
  } else {
    $('header').removeClass('scrolled');
  }

  /*Page scroll actions*/
  $(window).on('scroll', function() {
    let scroll = window.scrollY;
    if (scroll > 282){
      $('header').addClass('scrolled');
    } else {
      $('header').removeClass('scrolled');
    }
  });

  /*Scroll to comments on single page*/
  $('.post-comments-link').on('click', function(e){
    e.preventDefault();
    $('html').animate({
      scrollTop: ($('#single-post-comments').offset().top - 96)
    }, 500);
  });

  /*Scroll to comment input on quote click*/
  $('.comment-quote').on('click', function(e){
    e.preventDefault();
    $('html').animate({
      scrollTop: ($('#single-post-comments').offset().top - 96)
    }, 250);
  });

  /*Toggle menu*/
  $('#menu-button').on('click', () => {
    $('#resp-menu').toggleClass('open');
  })

});
