/**
 * @file
 * Placeholder file for custom sub-theme behaviors.
 *
 */
(function ($, Drupal) {

$(document).ready(function(){

	$('.parents-items li div span').click(function(){
		$(this).parents().siblings('.children-items').slideToggle();
		$(this).parents().parents().siblings().find('ul.children-items').slideUp();
	});
	
	$('#edit-jaar option[value="All"]').text('- Jaar -');
	$('#edit-km option[value="All"]').text('- KM -');
	$('#edit-prijs option[value="All"]').text('- Prijs -');

});

})(jQuery, Drupal);
