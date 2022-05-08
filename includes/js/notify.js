/*
*  Notify Bar - jQuery plugin
*
*  Copyright (c) 2009 Dmitri Smirnov
*
*  Licensed under the MIT license:
*  http://www.opensource.org/licenses/mit-license.php
*  
*  Version: 1.0.3
*
*  Project home:
*  http://www.dmitri.me/blog/notify-bar
*/

/**
 *  param object
 */
$.notifyBar = function(settings)
{
	var bar = {};

	this.shown = false;

	if( !settings) {
	settings = {};
	}
	// HTML inside bar
	this.html           = settings.html || "Your message here";

	//How long bar will be delayed, doesn't count animation time.
	this.delay          = settings.delay || 2500;

	//How long this bar will be slided up and down
	this.animationSpeed = settings.animationSpeed || "normal";

	//Use own jquery object usually DIV, or use default
	this.jqObject       = settings.jqObject;
	
	this.obj			= settings.obj || "<div class='bar'></div>";

	if( this.jqObject) {
	bar = this.jqObject;
	this.html = bar.html();
	} else {
		bar = $(this.obj)
			//basic css rules
			.attr("id", "notifyBar")
			.css("z-index", "32768")
			//additional css rules, which you can modify as you wish.
			.css('top',$(window).scrollTop()+'px');
	}

bar.html(this.html).hide();
var id =  bar.attr("id");
switch (this.animationSpeed) {
	case "slow":
		asTime = 600;
		break;
	case "normal":
		asTime = 400;
		break;
	case "fast":
		asTime = 200;
		break;
	default:
		asTime = this.animationSpeed;
}
if( bar != 'object'); {
	$("body").prepend(bar);
}
bar.slideDown(asTime);
$(window).scroll(function(){
	bar.css('top',$(window).scrollTop()+'px');
});
	setTimeout("$('#" + id + "').slideUp(" + asTime +",function(){ $('#"+ id +"').remove(); });", this.delay + asTime);
};