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
 * TAO QTI API
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 * 
 * @requires jquery {@link http://www.jquery.com}
 */

/**
 * The QTIWidgetFactory class enables you to build a QTI widget 
 * from XHTML elements and the given options
 * 
 * @class QTIWidgetFactory
 * @property {Object} options the interaction of parameters 
 */
var QTIWidgetFactory = function(options){
	
	/**
	 * To access the widget options 
	 * @fieldOf QTIWidgetFactory
	 * @type {Object}
	 */
	this.opts = options;

	/**
	 * the interaction selector, all elements selected must be inside this element,
	 * to be able to have some interactions in the same item
	 * @fieldOf QTIWidgetFactory
	 * @type {String}
	 */
	this.qti_item_id = "#"+this.opts["id"];
	
	
	/**
	 * the path of that library from an url,
	 * to access images.
	 * @fieldOf QTIWidget
	 * @type {String}
	 */
	this.wwwPath = '';
	//use the global variable qti_base_www
	if(typeof(qti_base_www) != 'undefined'){
		this.wwwPath = qti_base_www;
		if(!/\/$/.test(this.wwwPath) && this.wwwPath != ''){
			this.wwwPath += '/';
		}
	}
	
	/**
	 * @fieldOf QTIWidget
	 * @type {boolean}
	 */
	this.graphicDebug  = false; 
	//use the global variable qti_debug
	if(typeof(qti_debug) != 'undefined'){
		this.graphicDebug = qti_debug;
	}
	
	/**
	 * Build the widget of typename
	 * @param {String} typename
	 */
	this.build = function(typeName, resultCollector){
		if(typeof(qti_debug) != 'undefined'){
			if(!QTIWidget[typeName]){
				alert("Error: Unknow widget " + typeName);
			}
		}
		QTIWidget[typeName](this, resultCollector);	//call the right method into the widgets/* file
	};

};

/**
 * Utilities
 */

/**
 * Get the coordinates of the center of the polygon, relative to the parent canvas
 * @param raphaelPolygon 
 * @param $relativeCanvas 
 * @returns {Object}
 */
QTIWidgetFactory.getShapeCenter = function(raphaelShape){
	var box = raphaelShape.getBBox();
	return {
		x: box.x + (box.width/2),
		y: box.y + (box.height/2)
	}
}

/**
 * Get the coordinates of a poly shape reagrding it's path
 * @function
 * @param path 
 * @returns {Array}
 */
QTIWidgetFactory.polyCoordonates = function(path){
	var pathArray=new Array();
	pathArray=path.split(",");
	var pathArrayLength=pathArray.length;		
	// autoClose if needed
	if ((pathArray[0]!=pathArray[pathArrayLength-2]) && (pathArray[1]!=pathArray[pathArrayLength-1])){
		pathArray.push(pathArray[0]);
		pathArray.push(pathArray[1]);
	}		
	// move to first point
	pathArray[0]="M"+pathArray[0];		
	for (var a=1;a<pathArrayLength;a++){
		if (QTIWidgetFactory.isPair(a)){
			pathArray[a]="L"+pathArray[a];
		}
	}		
	return pathArray.join(" ");		
}

/**
 * Check if number is pair or not
 * @function
 * @param number
 * @returns {Number}
 */
QTIWidgetFactory.isPair = function(number){
	return (!number%2);
}

/**
 * Set the style for visible but deactivated raphael shape
 * @function
 * @param raphaelShape
 * @param animationDuration (in ms)
 */
QTIWidgetFactory.setDeactivatedShapeAttr = function(raphaelShape, animationDuration){
	if(raphaelShape && raphaelShape.animate){
		raphaelShape.animate({
			'fill' : '#E0DCDD',
			'fill-opacity' :  .3,
			'stroke-opacity': .8,
			'stroke' : '#ABA9AA'
		}, (animationDuration)?animationDuration:100);
	}
}

/**
 * Set the style for activated raphael shape
 * @function
 * @param raphaelShape
 */
QTIWidgetFactory.setActivatedShapeAttr = function(raphaelShape){
	if(raphaelShape && raphaelShape.animate){
		raphaelShape.animate({
			'fill' : 'green',
			'fill-opacity' :  .2,
			'stroke-opacity': .5,
			'stroke' : 'green'
		},100);
	}
}

/**
 * Set the style for activated raphael shape
 * @function
 * @param raphaelShape
 */
QTIWidgetFactory.setForbiddenShapeAttr = function(raphaelShape){
	if(raphaelShape && raphaelShape.attr){
		raphaelShape.animate({
			'fill' : 'red',
			'fill-opacity' :  .2,
			'stroke-opacity': .5,
			'stroke' : 'red'
		},100);
	}
}

/**
 * Animate the raphael shape
 * @function
 * @param raphaelShape
 */
QTIWidgetFactory.animateForbiddenShape = function(raphaelShape){
	QTIWidgetFactory.setForbiddenShapeAttr(raphaelShape);
	setTimeout(function(){
		QTIWidgetFactory.setDeactivatedShapeAttr(raphaelShape, 400);
	}, 400);
}

/**
 * Animate the raphael shape
 * @function
 * @param raphaelShape
 */
QTIWidgetFactory.animateAllowedShape = function(raphaelShape){
	QTIWidgetFactory.setActivatedShapeAttr(raphaelShape);
	setTimeout(function(){
		QTIWidgetFactory.setDeactivatedShapeAttr(raphaelShape, 400);
	}, 400);
}
