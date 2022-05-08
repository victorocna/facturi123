/**
 * --------------------------------------------------------------------
 * jQuery-Plugin "jmenu.jQuery.js"
 * by Scott Jehl, scott@filamentgroup.com
 * http://www.filamentgroup.com
 * reference article: http://www.filamentgroup.com/lab/update_date_range_picker_with_jquery_ui/
 * demo page: http://www.filamentgroup.com/examples/jmenu/
 *
 * Copyright (c) 2008 Filament Group, Inc
 * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
 *
 * Dependencies: jquery, jquery UI datepicker, date.js library (included at bottom), jQuery UI CSS Framework
 * Changelog:
 *	10.23.2008 initial Version
 *  11.12.2008 changed dateFormat option to allow custom date formatting (credit: http://alexgoldstone.com/)
 *  01.04.09 updated markup to new jQuery UI CSS Framework
 *  01.19.2008 changed presets hash to support different text
 * --------------------------------------------------------------------
 */
jQuery.fn.zonepicker = function(settings){
	var rangeInput = jQuery(this);
	
	//defaults
	var options = jQuery.extend({
		presetRanges: [
			{text: 'Today', ida:'0' },
			{text: 'Last 7 days', ida:'0'},
			{text: 'Month to date', ida:'0' },
			{text: 'Year to date', ida:'0' }
		],
		presets: {
		},
		doneButtonText: 'Done',
		closeOnSelect: true, //if a complete selection is made, close the menu
		arrows: false,
		posX: null,
		autoCompleteExtra:'',
		posY: null,
		appendTo: 'body',
		ant:'',
		onClose: function(){},
		onOpen: function(){}
	}, settings);
	
	//build picker and
	var rp = jQuery('<div class="ui-jmenu ui-widget ui-helper-clearfix ui-widget-content ui-corner-bottom" style="border:solid 1px #ffcc66;border-top:none;"></div>');
	var rpPresets = (function(){
		var ul = jQuery('<ul class="ui-widget-content"></ul>').appendTo(rp);
		jQuery.each(options.presetRanges,function(){
			if (this.separator == true) jQuery('<hr style="margin:2px 0 2px 0">').appendTo(ul);
			else if (this.separator == false) jQuery('<div></div>').appendTo(ul);
			else {
			jQuery('<li id="'+this.ida+'" class="ui-corner-all"><a href="#" style="color: #000;">'+ this.text +'</a></li>')
			.appendTo(ul);
			}
		});
		if (options.presets) {
		var x=0;
		jQuery.each(options.presets, function(key, value) {
			jQuery('<li class="ui-jmenu-'+ key +' preset_'+ x +' ui-helper-clearfix ui-corner-all"><span class="ui-icon ui-icon-triangle-1-e"></span><a href="#">'+ value +'</a></li>')
			.appendTo(ul);
			x++;
		});
		}
		
		ul.find('li').hover(
				function(){	jQuery(this).addClass('ui-state-hover'); },
				function(){	jQuery(this).removeClass('ui-state-hover');	}
			).click(function(){
				if (rp.find('li').is('.ui-state-active')) rp.find('li').removeClass('ui-state-active');
				jQuery(this).addClass('ui-state-active').clickAct();
				return false;
			});
		return ul;
	})();

	function showRP(){
		rp.slideDown(100);
		if ($(document).find('.ui-daterangepicker').is(':visible')) $(document).find('.ui-daterangepicker').hide();
		options.onOpen();
	}
	function hideRP(){
		rp.fadeOut(100, function(){ options.onClose(); });
	}
	function toggleRP(){
		if(rp.is(':visible')){ hideRP(); }
		else { showRP(); }
	}

	//preset menu click events	
	jQuery.fn.clickAct = function(){
		if(jQuery(this).is('.ui-jmenu-dateRange')){
			rp.find('.menur-end').show(100, function(){
			rpPickers.show();
			rpPickers.find('#sr').focus(function(){
				if ($('#sr').val() == 'Cautare rapida') rpPickers.find('#sr').val('').css('text-transform', 'uppercase');
			})
			.blur(function(){
				if ($('#sr').val() == '') rpPickers.find('#sr').css('text-transform', 'none').val('Cautare rapida');
			})
			.keyup(function(ev) {
				$.get(rangeInput.attr('href')+'?vl='+rpPickers.find('#sr').val(), function(data){
					//alert (data);
					rp.find('.lista-furnizori').empty();
					rp.find('.lista-furnizori').html(data);
				});
			});
			});
		}
		else{
			rp.find('.menur-end').hide(100, function(){
				rpPickers.hide();
			});
			rp.hide();
			
			rangeInput.val($(this).find('.text-imp').text());
			rangeInput.attr('iid',$(this).attr('id'));
			query_furnizor($(this).attr('id'));

			hideRP();
		}
		return false;
	};

	if (options.presets) {
		var rpPickers = jQuery('<div class="ranges ui-widget ui-corner-all ui-helper-clearfix"><div class="menur-end"><span class="title-end ui-state-hover ui-corner-top" style="text-align:left; padding: 5px 10px;"><input type="text" class="textbox ui-corner-all" style="width:15em; font-size:1em; padding: 5px; font-weight: bold; color: #2e6e9e; border: solid 1px #ccc;" id="sr" value="Cautare rapida"></span></div></div>').appendTo(rp);
		var content_url = rangeInput.attr('href');
		var box = jQuery('<div class="ui-widget-content lista-furnizori"></div>').appendTo(rpPickers.find('.menur-end'));
		menuContent = $.ajax({type:'GET',url: '/modules/'+content_url, success:function(data){
				jQuery(data).appendTo(box);
			}
		});
	}

	//inputs toggle rangepicker visibility
	jQuery(this).click(function(){
		toggleRP();
		return false;
	});
	//hide em all
	rpPickers.css('display', 'none');
	//inject rp
	jQuery(options.appendTo).append(rp);
	//wrap and position
	rp.wrap('<div class="ui-jmenucontain"></div>');
	if(options.posX){
		rp.parent().css('left', options.posX);
	}
	if(options.posY){
		rp.parent().css('top', options.posY);
	}
	//add arrows (only available on one input)
	jQuery(document).click(function(){
		if (rp.is(':visible')){
			hideRP();
		}
	}).keyup(function(e){
		if (e.keyCode == 27 || e.keyCode == 9) {
			if (rp.is(':visible')){
				hideRP();
			}}
		});

	rp.click(function(){return false;}).hide();
	return this;
};

function action(id,tip,obj) {
	$('#furnizor').val(obj.text());
	$(document).click();
	if (tip == '0') query_furnizor(id);
	if (tip == '1') query_client(id);
	if (tip == '9') query_platitor(id);
}