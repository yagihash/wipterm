$(function() {
  $("nav#top_bar ul").css("margin-left", (window.innerWidth - 900) / 2 + "px");
  $(window).resize(function() {
    $("nav#top_bar ul").css("margin-left", (window.innerWidth - 900) / 2 + "px");
  });
  $("nav#top_bar ul li a").hover(function() {
    $(this).stop().animate({
      "background-color" : "#77DDD1",
    }, 100);
  }, function() {

    $(this).stop().animate({
      "background-color" : "#C4E9E0"
    }, 300);
  });
  $("div#login input[type=submit]").click(function() {
    $(this).css("background-color", "#FFFFFF");
  });
});
