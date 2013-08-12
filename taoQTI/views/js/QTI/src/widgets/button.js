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
 * Buttons like widgets: slider and upload QTI's interactions
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 * 
 * @requires jquery {@link http://www.jquery.com}
 * @requries jquery.uploadify {@link http://www.uploadify.com/}
 */

/**
 * @namespace QTIWidget
 */
var QTIWidget = QTIWidget || {};

//
// SLIDER
//

/**
 * Creates a slider widget
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.slider = function(ctx){
	
	//add the containers
	$(ctx.qti_item_id).append("<input type='hidden' id='"+ctx.qti_item_id.substring(1, ctx.qti_item_id.length)+"_qti_slider_value' />")
						.append("<div class='qti_slider'></div>")
						.append("<div class='qti_slider_label'></div>");
	
	var containerWidth = parseInt($(ctx.qti_item_id).width());
	
	//get the options
	var min 		= parseInt(ctx.opts['lowerBound']);
	var max 		= parseInt(ctx.opts['upperBound']);
	
	var step = 1;//default value as per QTI standard
	if(ctx.opts.step != undefined || ctx.opts.step !== null){
		step = parseInt(ctx.opts.step)?parseInt(ctx.opts.step):0.001;//if step is null, should simulate pseudo continuous step
	}
	var stepLabel 	= (ctx.opts['stepLabel'] === true || ctx.opts['stepLabel'] === 'true');
	stepLabel = false;
	var reverse 	= (ctx.opts['reverse'] === true || ctx.opts['reverse'] === 'true');
	var orientation = 'horizontal';
	if($.inArray(ctx.opts['orientation'], ['horizontal', 'vertical']) > -1){
		orientation = ctx.opts['orientation'];
	}

	//calculate and adapt the slider size
	var sliderSize = ((max - min) / step) * 20;

	if(orientation == 'horizontal'){
		if(sliderSize > containerWidth){
			sliderSize = containerWidth - 20;
		}
		$(ctx.qti_item_id).addClass('qti_slider_horizontal');
		$(ctx.qti_item_id+' .qti_slider').width(sliderSize+'px');
		$(ctx.qti_item_id+' .qti_slider_label').width((sliderSize + 40)+'px');
	}else{
		var maxHeight = 300;
		if(sliderSize > maxHeight){
			sliderSize = maxHeight;
		}
		$(ctx.qti_item_id).addClass('qti_slider_vertical');
		$(ctx.qti_item_id+' .qti_slider').height(sliderSize+'px');
		$(ctx.qti_item_id+' .qti_slider_label').height((sliderSize + 20)+'px');
	}
	
	//mark the bounds
	if(!stepLabel){
		var displayMin = min;
		var displayMax = max;
		if(reverse){
			displayMin = max;
			displayMax = min;
		}
		$(ctx.qti_item_id+' .qti_slider_label')
			.append("<span class='slider_min'>"+displayMin+"</span>")
			.append("<span class='slider_cur highlight'>"+displayMin+"</span>")
			.append("<span class='slider_max'>"+displayMax+"</span>");
		
	}else{
		//add a label to the steps, 
		//if there not the place we calculate the better interval
		$(ctx.qti_item_id).addClass('qti_slider_step');
		
		var stepSpacing = 20;
		var displayRatio = 1;
		if((max * 20) > sliderSize){
			do{
				stepSpacing = (sliderSize / (max / displayRatio));
				displayRatio++;
			}while(stepSpacing < 25);
			displayRatio--;
		}
		var i = 0;
		var stepWidth = (step<1)?1:step;
		for(i = min; i <= max; i += (stepWidth * displayRatio)){
			var displayIndex = i;
			if(reverse){
				displayIndex = max + min - i;
			}
			var $iStep = $("<span class=\"_"+displayIndex+"\">"+displayIndex+"</span>");
			if(orientation == 'horizontal'){
				$iStep.css({left: ((i / displayRatio)  * stepSpacing) + 5 + 'px'});
			}
			else{
				$iStep.css({top: ((i / displayRatio)  * stepSpacing) + 5 + 'px'});
			}
			$(ctx.qti_item_id+' .qti_slider_label').append($iStep);
		}
		
		//always add the last step
		i -= (step * displayRatio);
		if(i != max){
			if(i < max){
				$(ctx.qti_item_id+' .qti_slider_label span:last').remove();
			}
			var displayMax = max;
			if(reverse){
				displayMax = min;
			}
			var $iStep = $("<span>"+displayMax+"</span>");
			if(orientation == 'horizontal'){
				$iStep.css({right:'0px'});
			}
			else{
				$iStep.css({bottom:'0px'});
			}
			$(ctx.qti_item_id+' .qti_slider_label').append($iStep);
		}
	}
	
	//the input that has always the slider value
	var $sliderVal = $(ctx.qti_item_id+"_qti_slider_value");
	
	//set the start value
	var val = min;
	if( (reverse && orientation == 'horizontal') || (!reverse && orientation == 'vertical') ){
		val = max;
	}
	//get the current value if defined
	if(ctx.opts["values"]){
		val = ctx.opts["values"];
	}
	
	//create the slider
	$(ctx.qti_item_id+' .qti_slider').slider({
		value: ( (reverse && orientation == 'horizontal') || (!reverse && orientation == 'vertical') ) ? (max + min) - val : val,
		min: min,
		max: max,
		step: step,
		orientation: orientation,
		animate:'fast',
		slide: function(event, ui) {
			var val = ui.value;
			if( (reverse && orientation == 'horizontal') || (!reverse && orientation == 'vertical') ){
				val = (max + min) - ui.value;
			}
			val = Math.round(val * 1000)/1000;
			$sliderVal.val(val);
			QTIWidget.slider_highlight(ctx, val);
		}
	});
	$sliderVal.val(val);
	QTIWidget.slider_highlight(ctx, val);
	
	
};

QTIWidget.slider_highlight = function(ctx, value) {
  if (!$(ctx.qti_item_id).hasClass('qti_slider_step')) $(ctx.qti_item_id+' '+'.slider_cur').text(value);
  else {
	$(ctx.qti_item_id+' .qti_slider_label span').removeClass('highlight');
	$(ctx.qti_item_id+' .qti_slider_label span._'+value).addClass('highlight');
  }
}


//
// UPLOAD
//

/**
 * Creates a file upload widget
 * @see AsyncFileUpload
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.upload = function(ctx){
	
	var uploaderElt = $(ctx.qti_item_id + '_uploader');
	if(uploaderElt.length > 0){
		
		var fileExt = '*';
		if(ctx.opts['ext']){
			if(ctx.opts['ext'] != ''){
				fileExt = '*.' + ctx.opts['ext'];
			}
		}
		
		var uploadOptions = {
			"scriptData": {'session_id' : ctx.opts['session_id']},
			"basePath"  : ctx.wwwPath,
			"rootUrl"	: '',
			"fileDesc"	: 'Allowed files type: ' + fileExt,
			"fileExt"	: fileExt,
			"target"	: ctx.qti_item_id + '_data',
			"folder"    : "/"
		};
		
		new AsyncFileUpload('#'+uploaderElt.attr('id'), uploadOptions);
	}
};


//
// END ATTEMPT
//

/**
 * Creates a button to trigger an end of item attempt
 * @todo not implemented yet
 * @methodOf QTIWidget
 * @param {Object} ctx the QTIWidget context
 */
QTIWidget.end_attempt = function(ctx){
	$(ctx.qti_item_id).val(ctx.opts['title']);
	$(ctx.qti_item_id).click(function(){
		$(ctx.qti_item_id+'_data').val(1);
		$("#qti_validate").click();
	});
}