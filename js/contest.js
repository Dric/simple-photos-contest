$(document).ready(function(){
  $("span.on_img").mouseover(function (){
  	$(this).addClass("over_img");
  });
  $("span.on_img").mouseout(function (){
    $(this).removeClass("over_img");
  });
	/*$('.img').mouseover(function (){
  	$(this).stop().animate({ opacity: 1.0 }, 350);
  });
  $('.img').mouseout(function (){
    $(this).stop().animate({ opacity: 0.9 }, 350);
  });*/
});
$(function() {
		if ($(".graph").length > 0){
  	// We have a graph to display !
		$(".graph").each(function(){
			var contest = $(this).data('contest');
			$('#contest_graph_'+contest).jqBarGraph({
				data: arrayOfData[contest],
				title: $(this).data('title'),
				//barSpace: 20,
				color: '#ff0056',
				width: '90%' 
			});
		});
	}

	$(document).on('click', '.close', function(){
		$(this).parent().remove();
		tooltip.hide();
	});

	if ($('#wrap').length && noTiling == false) {
		/*var wall = new freewall("#wrap");
		 wall.reset({
		 selector: '.img-container',
		 animate: true,
		 cellW: 'auto',
		 cellH: maxWidth,
		 gutterY: 20,
		 onResize: function() {
		 wall.fitWidth();
		 }
		 });
		 wall.fitWidth();*/
		$('#wrap').freetile({
			selector    : '.img-container',
			animate     : true,
			elementDelay: 30
		});

	}
	$(".love").click(function(){
		var id = $(this).attr("id");
		var contest = $(this).data('contest');
		var dataString = 'id='+ id + '&contest=' + contest ;
		var parent = $(this);
		$(this).fadeOut(300);
		$.ajax({
			type: "POST",
			url: "ajax_love.php",
			data: dataString,
			cache: false,
			success: function(html)	{
				parent.html(html);
				parent.fadeIn(300);
			} 
		});
		return false;
	});
	$('#log_button').click(function(){
		$('#login').toggle('slow');
	});
	$(document).keypress(function(e) {
    if(e.which == 13 && $('#login_auth').val().length > 0) {
			login_ajax();
    }
	});
	$('#log_send').click(function(){
		login_ajax();
	});
	
	function login_ajax(){
		var dataString = 'action=login&pwd=' + $('#login_auth').val() ;
		$.ajax({
			type: "POST",
			url: "ajax_love.php",
			data: dataString,
			cache: false,
			success: function(data)	{
				if (data.indexOf('<div class="alert error">') != -1){
					$('#login').prepend(data);
				}else{
					location.href = 'admin.php';
				}
			} 
		});
		return false;
	}
});

// Coded by Travis Beckham
// Heavily modified by Craig Erskine
// extended to TagName img by reddog (and little personal tip)
tooltip = {
   name : "tooltipDiv",
   offsetX : -30,
   offsetY : 20,
   tip : null
};
tooltip.init = function () {

var tipNameSpaceURI = "http://www.w3.org/1999/xhtml";
if(!tipContainerID){ var tipContainerID = "tooltipDiv";}
var tipContainer = document.getElementById(tipContainerID);

if(!tipContainer){
  tipContainer = document.createElementNS ? document.createElementNS(tipNameSpaceURI, "div") : document.createElement("div");
  tipContainer.setAttribute("id", tipContainerID);
  tipContainer.style.display = "none";
  document.getElementsByTagName("body").item(0).appendChild(tipContainer);
}

   if (!document.getElementById) return;

   this.tip = document.getElementById (this.name);
   if (this.tip) document.onmousemove = function (evt) {tooltip.move (evt)};


infobulle=function (ancre){
	var a, sTitle, zTitle;
   	var anchors = document.getElementsByTagName (ancre);
   	for (var i = 0; i < anchors.length; i ++) {
    	a = anchors[i];
    	sTitle = a.getAttribute("title");
		zTitle = a.getAttribute("alt");
   		if(sTitle) {
			a.setAttribute("tiptitle", sTitle);
  	    	a.removeAttribute("title");
  	    	a.removeAttribute("alt");
  	    	a.onmouseover = function() {tooltip.show(this.getAttribute('tiptitle'))};
  	    	a.onmouseout = function() {tooltip.hide()};
		} else if(zTitle) {
			a.setAttribute("tiptitle", zTitle);
        	a.removeAttribute("title");
        	a.removeAttribute("alt");
        	a.onmouseover = function() {tooltip.show(this.getAttribute('tiptitle'))};
        	a.onmouseout = function() {tooltip.hide()};
    	}
	}
}
infobulle("a");
infobulle("img");
infobulle("span");

};
tooltip.move = function (evt) {
   var x=0, y=0;
   if (document.all) {// IE

      x = (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
      y = (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
      x += window.event.clientX;
      y += window.event.clientY;

   } else {// Mozilla
      x = evt.pageX;
      y = evt.pageY;
   }
   var ecranwidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;
   if (x + this.offsetX > (ecranwidth-140)){
      x = x-40;
   }
   this.tip.style.left = (x + this.offsetX) + "px";
   this.tip.style.top = (y + this.offsetY) + "px";
};
tooltip.show = function (text) {
   if (!this.tip) return;
   this.tip.innerHTML = text;
   this.tip.style.visibility = "visible";
   this.tip.style.display = "block";
};
tooltip.hide = function () {
   if (!this.tip) return;
   this.tip.style.visibility = "hidden";
   this.tip.style.display = "none";
   this.tip.innerHTML = "";
};

window.onload = function () {

	tooltip.init ();

};