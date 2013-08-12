/*  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2013 (original work) Open Assessment Techonologies SA (under the project TAO-PRODUCT);
 *               
 * 
 */
/**
 * Choices widgets: simple, multiple and inline choice QTI's interactions
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 *
 * @requires jquery {@link http://www.jquery.com}
 * @requries raphael {@link http://raphaeljs.com/}
 */

/**
 * @namespace QTIWidget
 */
var QTIWidget = QTIWidget || {};

//
// CHOICE
//
/**
 * Creates a choice list widget
 * @see QTIWidget#simple_choice
 * @see QTIWidget#multiple_choice
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.choice = function(ctx){

	var maxChoices =  parseInt(ctx.opts["maxChoices"]);
	if(maxChoices > 1 || maxChoices == 0){
		QTIWidget.multiple_choice(ctx);
	}
	else{
		QTIWidget.simple_choice(ctx);
	}
};


/**
 * Creates a simple choice list widget
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.simple_choice = function (ctx){

	//add the main class
	$(ctx.qti_item_id).addClass('qti_simple_interaction');

	//change the class to activate the choice on click
	$(ctx.qti_item_id +" ul li").bind("click",function(){
		$(ctx.qti_item_id+" ul li").removeClass("tabActive");
		$(this).addClass("tabActive");
	});

	//set the current value if defined
	if(ctx.opts["values"]){
		var value = ctx.opts["values"];
		if(typeof(value) == 'string' && value != ''){
			$(ctx.qti_item_id+" ul li#"+value).addClass("tabActive");
		}
	}
};

/**
 * Creates a multiple choice list widget
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.multiple_choice = function (ctx){

	//add the main class
	$(ctx.qti_item_id).addClass('qti_multi_interaction');

	//change the class to activate the choices on click
	$(ctx.qti_item_id+" ul li").bind("click",function(){
		if ($(this).hasClass("tabActive")) {
			$(this).removeClass("tabActive");
		}
		else {
			if ($(ctx.qti_item_id+" ul li.tabActive").length < ctx.opts["maxChoices"] || ctx.opts["maxChoices"] == 0) {
				$(this).addClass("tabActive");
			}
		}
	});

	//set the current values if defined
	if(ctx.opts["values"]){
		var values = ctx.opts["values"];
		if(typeof(values) == 'object'){
			for(var i in values){
				var value = values[i];
				if(typeof(value) == 'string' && value != ''){
					$(ctx.qti_item_id+" ul li#"+value).addClass("tabActive");
				}
			}
		}
	}
};

//
//	INLINE CHOICE
//

/**
 * We use the html <i>select</i> widget,
 * the function is listed only to keep the same behavior than the other
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.inline_choice = function (ctx){
	if(ctx.opts["values"]){
		var value = ctx.opts["values"];
		if(typeof(value) == 'string' && value != ''){
			$(ctx.qti_item_id+" option[value='"+value+"']").attr('selected', true);
		}
	}
};
