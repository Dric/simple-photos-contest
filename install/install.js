$(function() {
	$('#test_db').click(function(){
		var dataString = 'action=test_db&db_name=' + $('#db_name').val() + '&db_prefix=' + $('#db_prefix').val() + '&db_host=' + $('#db_host').val() + '&db_user=' + $('#db_user').val() + '&db_pwd=' + $('#db_pwd').val();
		$.ajax({
			type: "POST",
			url: "ajax_install.php",
			data: dataString,
			cache: false,
			success: function(data)	{
				$('.large').prepend(data);
			} 
		});
		return false;
	});
	var dateFormat = 'Y/m/d';
	var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	/** Datepicker */
	$('#date_begin').Zebra_DatePicker({
  	view: 'months',
		format: dateFormat,
		days: dayNames,
		months: monthNames,
		lang_clear_date: 'Reset',
		pair: $('#date_end'),
		offset: [-200, 100]
	});
	
	$('#date_end').Zebra_DatePicker({
  	view: 'months',
		format: dateFormat,
		days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
		months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		lang_clear_date: 'Reset',
		direction: 1,
		offset: [-200, 100]
	});
	
	/** Close icon on notification messages. */
	$('.close').click(function(){
		$(this).parent().remove();
		tooltip.hide();
	});
	$.fn.extend({
      pwdstr: function(el, strengh) {
			return this.each(function() {

					$(this).keyup(function(){
						$(el).html(getTime($(this).val())[0]);
            $(strengh).attr('class', '');
            $(strengh).addClass(getTime($(this).val())[1]);
					});

					function getTime(str){
          var datefound = false;
          var emailfound = false;
					var poorpwd = false;
  				var time = [];
          var tab = new Array();
          var addedClass = 'btn-danger';
          if (/^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(str)){
            datefound = true;
          }else if( /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.exec(str) && str != '') {
            emailfound = true;
					}else if(str == 'admin' || str == 'password'){
						poorpwd = true;
          }else{

  					var chars = 0;
  					var rate = 2800000000;

  					if((/[a-z]/).test(str)) chars +=  26;
  					if((/[A-Z]/).test(str)) chars +=  26;
  					if((/[0-9]/).test(str)) chars +=  10;
  					if((/[^a-zA-Z0-9]/).test(str)) chars +=  32;

  					var pos = Math.pow(chars,str.length);
  					var s = pos/rate;

            var decimalMillennia = s/(3600*24*365*1000);
            var millenia = Math.floor(decimalMillennia);

            var decimalCenturies = (decimalMillennia-millenia)*10;
            var centuries = Math.floor(decimalCenturies);

            var decimalYears = (decimalCenturies-centuries)*100;
  					var years = Math.floor(decimalYears);

  					var decimalMonths =(decimalYears-years)*12;
  					var months = Math.floor(decimalMonths);

  					var decimalDays = (decimalMonths-months)*30;
  					var days = Math.floor(decimalDays);

  					var decimalHours = (decimalDays-days)*24;
  					var hours = Math.floor(decimalHours);

  					var decimalMinutes = (decimalHours-hours)*60;
  					var minutes = Math.floor(decimalMinutes);

  					var decimalSeconds = (decimalMinutes-minutes)*60;
  					var seconds = Math.floor(decimalSeconds);


            if(millenia > 0){
              addedClass = 'btn-success';
  						if(millenia == 1)
  							time.push("1 millennium, ");
  						else
  							time.push(millenia + " millennia, ");
  					}

            if(centuries > 0){
              if (addedClass == 'btn-danger'){
                addedClass = 'btn-success';
              }
  						if(centuries == 1)
  							time.push("1 century, ");
  						else
  							time.push(centuries + " centuries, ");
  					}

            if(years > 0){
              if (addedClass == 'btn-danger'){
                addedClass = 'btn-success';
              }
  						if(years == 1)
  							time.push("1 year, ");
  						else
  							time.push(years + " years, ");
  					}
  					if(months > 0){
              if (addedClass == 'btn-danger'){
                addedClass = 'btn-warning';
              }
  						if(months == 1)
  							time.push("1 month, ");
  						else
  							time.push(months + " months, ");
  					}
  					if(days > 0){
              if (addedClass == 'btn-danger'){
                addedClass = 'btn-danger';
              }
  						if(days == 1)
  							time.push("1 day, ");
  				 		else
  							time.push(days + " days, ");
  					}
  					if(hours > 0){
              if (addedClass == 'btn-danger'){
                addedClass = 'btn-danger';
              }
  						if(hours == 1)
  							time.push("1 hour, ");
  						else
  							time.push(hours + " hours, ");
  					}
  					if(minutes > 0){
  						if(minutes == 1)
  							time.push("1 minute, ");
  						else
  							time.push(minutes + " minutes, ");
  					}
  					if(seconds > 0){
  						if(seconds == 1)
  							time.push("1 second, ");
  						else
  							time.push(seconds + " seconds, ");
  					}
          }
					if(time.length <= 0 || datefound || emailfound)
						time = "less than a second, ";
					else if(time.length == 1)
						time = time[0];
					else
						time = time[0] + time[1];

					 time =  time.substring(0,time.length-2);
           tab[0] = time.replace(/,([^,]*)$/,' et'+'$1');
           if (datefound){
            tab[0] = tab[0]+' - You entered a date...';
           }
           if (emailfound){
            tab[0] = tab[0]+' - You entered an email address...';
           }
						if (poorpwd){
							tab[0] = tab[0]+' - You entered "admin" or "password" as a password. Please, be smarter than that...';
						}
           tab[1] = addedClass;
          return tab;
					}

			 });
        }
    });
	$('#admin_pwd').pwdstr('#time', '#pwd-str');
});