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
 * Text widgets: text entry and extended text QTI's interactions
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
//TEXT ENTRY
//

/**
 * Creates a text entry widget
 * 
 * @see QTIWidget#string_interaction
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.text_entry = function (ctx){
	
	//adapt the field length
	if(ctx.opts['expectedLength']){
		var length = parseInt(ctx.opts['expectedLength']);
		$(ctx.qti_item_id).css('width', length + 'em');
	}
	
	QTIWidget.string_interaction(ctx);
};

//
//EXTENDED TEXT
//

/**
 * Creates a  extended text widget,
 * it can be a big text area or a set of text entries regarding the context
 * 
 * @see QTIWidget#string_interaction
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.extended_text = function (ctx){
	
		
	//usual case: one textarea 
	if($(ctx.qti_item_id).get(0).nodeName.toLowerCase() == 'textarea') {
		
		//adapt the field length
		if(ctx.opts['expectedLength'] || ctx.opts['expectedLines']){
			
			var baseWidth 	= parseInt($(ctx.qti_item_id).css('width')) | 400;
			var baseHeight 	= parseInt($(ctx.qti_item_id).css('height')) | 100;
			if(ctx.opts['expectedLength']){
				var expectedLength 		= parseInt(ctx.opts['expectedLength']) ;
				if(expectedLength > 0){
					var width = expectedLength * 10;
					if( width > baseWidth){
						var height = (width / baseWidth) * 16;
						if(height  > baseHeight){
							$(ctx.qti_item_id).css('height', height + 'px');
						}
					}
					$(ctx.qti_item_id).attr('maxLength', length);
				}
			}
			if(ctx.opts['expectedLines']){
				$(ctx.qti_item_id).css('height', (parseInt(ctx.opts['expectedLines']) * 16) + 'px');
			}
		}
	
		QTIWidget.string_interaction(ctx);
	}
	
	//multiple text inputs
	if($(ctx.qti_item_id).get(0).nodeName.toLowerCase() == 'div') {
		//adapt the fields length
		if(ctx.opts['expectedLength']){
			var expectedLength = parseInt(ctx.opts['expectedLength']) ;
			if(expectedLength > 0){
				$(ctx.qti_item_id + " :text").css('width', (expectedLength * 10) + 'px')
										.attr('maxLength', expectedLength);
			}
		}
		//apply the pattern to all fields
		if(ctx.opts['patternMask']){
			var pattern = new RegExp("/^"+ctx.opts['patternMask']+"$/");
			$(ctx.qti_item_id  + " :text").change(function(){
				$(this).removeClass('field-error');
				if(!pattern.test($(this).val())){
					$(this).addClass('field-error');
				}
			});
		}
		//set the current values if defined
		if(ctx.opts["values"]){
			var values = ctx.opts["values"];
			if(typeof(values) == 'object'){
				for(var i in values){
					var value = values[i];
					if(typeof(value) == 'string' && value != ''){
						$(ctx.qti_item_id+" :text#" + ctx.qti_item_id+ "_"+ i).val(value);
					}
				}
			}
		}
	}
};

/**
 * Initialize the parametrized behavoir of text input likes widgets 
 * It supports now the Regex matching and string cloning 
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.string_interaction = function(ctx){
	
	//add the error class if the value don't match the given pattern
	if(ctx.opts['patternMask']){
		var pattern = new RegExp("^"+ctx.opts['patternMask']+"$");
		$(ctx.qti_item_id).keyup(function(){
			$(this).removeClass('field-error');
			if(!pattern.test($(this).val())){
				$(this).addClass('field-error');
			}
		});
	}
	
	//create a 2nd field to capture the string if the stringIdentifier has been defined
	if(ctx.opts['stringIdentifier']){
		$(ctx.qti_item_id).after("<input type='hidden' id='"+ctx.opts['stringIdentifier']+"' />");
		$("#"+ctx.opts['stringIdentifier']).addClass('qti_text_entry_interaction');
		$(ctx.qti_item_id).change(function(){
			$("#"+ctx.opts['stringIdentifier']).val($(this).val());
		});
	}
	
	if(ctx.opts["values"]){
		var value = ctx.opts["values"];
		if(typeof(value) == 'string' && value != ''){
			$(ctx.qti_item_id).val(value);
		}
	}
};