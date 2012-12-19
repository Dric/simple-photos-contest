$(function() {
	
	/** Datepicker */
	$('#date_begin').Zebra_DatePicker({
  	view: 'months',
		format: dateFormat,
		days: dayNames,
		months: monthNames,
		lang_clear_date: 'Réinitialiser',
		pair: $('#date_end'),
		offset: [-200, 100]
	});
	
	$('#date_end').Zebra_DatePicker({
  	view: 'months',
		format: dateFormat,
		days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
		months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		lang_clear_date: 'Réinitialiser',
		direction: 1,
		offset: [-200, 100]
	});
	
	/** Close icon on notification messages. */
	$('.close').click(function(){
		$(this).parent().remove();
		tooltip.hide();
	});
	/** Settings panel animation. */
  $('#settings_disp').click(function () {
      var $s_bar = $('#settings_bar');
			var $s_wrap = $('#settings_wrap');
			if (!$s_bar.hasClass("expanded")){
				$s_bar.stop().animate({ width: '430px' }, { duration: 500 });
				$s_bar.addClass("expanded");
				$(this).children('img').attr('src', 'img/go.png');
			}else{
				$s_bar.stop().animate({ width: '60px' }, { duration: 500 });
				$s_bar.removeClass("expanded");
				$(this).children('img').attr('src', 'img/settings2.png');
			}
  });
	/** Settings panel scrollbar */
	$('#settings_wrap').tinyscrollbar({ axis: 'y', sizethumb: 120, wheel: 20});
});