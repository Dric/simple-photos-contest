$(function() {
	$('#date_begin').Zebra_DatePicker({
  	view: 'months',
		format: 'd/m/Y',
		days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
		months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		lang_clear_date: 'Réinitialiser',
		pair: $('#date_end'),
		offset: [-200, 100]
	});
	$('#date_end').Zebra_DatePicker({
  	view: 'months',
		format: 'd/m/Y',
		days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
		months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		lang_clear_date: 'Réinitialiser',
		direction: 1,
		offset: [-200, 100]
	});
	$('.close').click(function(){
		$(this).parent().remove();
		tooltip.hide();
	});
});