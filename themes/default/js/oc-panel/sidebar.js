
//go to the latest tab, if it exists:
var collapsed_bar = $.cookie('sidebar_state');

/*Side bar colapse*/
if($(window).width() > '750'){
  /*Bigger screens*/
  if($(window).width() < '1200'){ // when less than 1200px, do automatically small sidebar
    colapse_sidebar(true);
  }
  // on click triger
  $('.btn-colapse-sidebar').on('click', function(){
    colapse_sidebar($('.table').hasClass('active'));
  });

  if(collapsed_bar == 'collapsed')
    colapse_sidebar(true);
  else
    colapse_sidebar(false);
}else{
  /*Mobile case*/
  // $('.btn-colapse-sidebar').parent().css('display','none'); // hide collapse button since it doesnt work here
  var sidebar = $('.respon-left-panel');
  var main_content = $('.page-wrapper');
  sidebar.addClass('hide'); // when mobile always hide
  $('#mobile_header_btn, .btn-colapse-sidebar').on('click', function(){
    if(sidebar.hasClass('hide')){
      sidebar.removeClass('hide');
      main_content.css('margin-left','230px');
    }
    else{
      sidebar.addClass('hide');
      main_content.css('margin-left','auto');
    }
  });
  
}
/*
  Colapse sidebar function
  makes sidebar to mini sidear with only icons active
*/

function colapse_sidebar(event){  

  if(event)
  {
    //set cookie to be avare of current state of sidebar
    $.cookie('sidebar_state', 'collapsed', { expires: 7, path: '/' });

    $('.panel-sidebar .panel-body table.table').each(function(){
      $('tbody',this).addClass('aside-table');; // hide links in sidebar
    });

    $('.panel-sidebar .panel-title ').each(function(){
      $('a span.title-txt', this).removeClass('active').addClass('hide'); // hide links in sidebar
      $('span', this).show(); // remove class with padding
      $('#accordion').addClass('mini-col');
    });

    $('.panel-sidebar .table').removeClass('active').addClass('colapsed');
    $('.main').css('padding-left','70px');
    $('.no-prem').hide(); // hide adverts
    
    $('.btn-colapse-sidebar span.glyphicon')
      .removeClass('glyphicon-circle-arrow-left')
      .addClass('glyphicon-circle-arrow-right');

    $('.dropdown-sidebar.sbp.active .submenu').removeClass('active'); // in case its mini, do not colapse menu item
    
  }
  else
  {
    //set cookie to be avare of current state of sidebar
    $.cookie('sidebar_state', 'not-collapsed', { expires: 7, path: '/' });

    $('.panel-sidebar .panel-body table.table').each(function(){
      $('tbody', this).removeClass('aside-table');
    });

    $('.panel-sidebar .panel-title').each(function(){
      $('a span.title-txt', this).removeClass('hide').addClass('active');
      $('span', this).show(); // remove class with padding
      $('#accordion').removeClass('mini-col');
    });

    $('.panel-sidebar .table').removeClass('colapsed').addClass('active');
    $('.main').css('padding-left','250px');
    $('.no-prem').show(); // show adverts
    
    $('.btn-colapse-sidebar span.glyphicon')
      .removeClass('glyphicon-circle-arrow-right')
      .addClass('glyphicon-circle-arrow-left');

    $('.dropdown-sidebar.sbp.active .submenu').addClass('active'); // in case its maximized, collapse menu item
    
  }
}

$(function() {
  if(!$('.dropdown-sidebar').hasClass('mini-col')){
    $('.dropdown-sidebar.sbp.active .submenu').addClass('active');
  }
  $('.dropdown-sidebar.sbp.active .dropdown-toggle .glyphicon-chevron-down')
      .removeClass('glyphicon-chevron-down')
      .addClass('glyphicon-chevron-up');
    
    $('.dropdown-sidebar').hover(function(){
      if($(this).hasClass('mini-col') || !$('.submenu li', this).hasClass('active')){
        dropdown($(this));
      }
    });
  
});

// function dropdown(event){
//  var active = $('.submenu',event);
//
//  if(active.hasClass('active'))
//  {
//    active.removeClass('active');
//    $('.submenu .side-name-link',event).addClass('hide');
//  }
//  else
//  {
//    active.addClass('active');
//    $('.submenu .side-name-link',event).removeClass('hide');
//  }
// }

// when reloaded keep acordion colapsed
if(!$('.panel-group').hasClass('mini-col'))
$('li.active').closest('.panel-collapse').addClass('in');
//active link
$('li.active').closest('.br').addClass('active');

//minified sidebar,when click outside close dropdown
$(".respon-left-panel").click(function(e) {
});
$(document).click(function() {
  $(".respon-left-panel .mini-col .panel-collapse").removeClass('in').addClass('collapse'); //click came from somewhere else
});