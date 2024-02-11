$(document).ready(function() {
  $('.animated-gradient').on('mouseover', function() {
    // Code to change animation or add interaction
    $(this).addClass('interaction-class');
  });

  $('.animated-gradient').on('mouseout', function() {
    // Code to revert animation or remove interaction
    $(this).removeClass('interaction-class');
  });
});
