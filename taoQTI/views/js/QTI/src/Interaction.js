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
 * The QTIInteraction class represent a QTI interaction on runtime.
 * It initialize the widget and the result collection for an interaction
 *
 * @class QTIInteraction
 * @property {Object} initObj the params of the interaction (parts of qti_initParam identified by the interaction id)
 */
function QTIInteraction(initObj){
	
	var _this = this;
	
	//keep the current instance pointer
	this.options = initObj;
	
	//instantiate the widget class with the given interaction parameters
	var myQTIWidgetFactory = new QTIWidgetFactory(initObj);
	
	//instantiate the result class with the given interaction parameters
	this.resultCollector = new QTIResultCollector(initObj);
	
	//get the interaction type to identify the method 
	this.typeName = initObj["type"].replace('qti_', '').replace('_interaction', '');
	
	//call the widget initialization method
	myQTIWidgetFactory.build(this.typeName, this.resultCollector);
	
	// validation process
	$("#qti_validate").bind("click",function(e){
		e.preventDefault();
		
		$('body').css('cursor', 'wait');
		
		// Set the matching engine with the user's data	
		if(typeof(matchingSetResponses) == 'function'){
			// Get user's data
			matchingSetResponses([_this.getResponses()]);//the issue with the associate response is here ! should send a simple array instead of an array of array
		}
	});
	
	this.getResponses = function(){
		return this.resultCollector[this.typeName]();
	}
}


