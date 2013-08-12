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
 * TAO QTI initialize a QTI item in a <b>TAO Context</b>. 
 * It's a conveniance script to use it with the TAO platform.
 * On window load event, the recovery context is initialized and qti widget as well.
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 * @requires jquery {@link http://www.jquery.com}
 */
$(window).load(function(){

	var matchingParam 	= new Object();
	var responseToId	= new Object();
	var itemIdentifier	= $("div.qti_item").attr('id') || '';
	
	//check if the values have been saved in a context
	if(typeof(getRecoveryContext) == 'function'){
		for(var serial in qti_initParam){
			if(qti_initParam[serial]['responseIdentifier']){
				try{	//if the context is found, we initialize the widgets with the values 
					responseToId[qti_initParam[serial]['responseIdentifier']] = itemIdentifier + '_' +qti_initParam[serial]['id'];
					qti_initParam[serial]['values'] = $.parseJSON(getRecoveryContext(responseToId[qti_initParam[serial]['responseIdentifier']]));
				} catch(parseException) { }
			}
		}
	}
	
	qti_init(qti_initParam);

    // validation process - catch event after all interactions have collected their data
    $("#qti_validate").bind("click",function(){

    	if(typeof(matchingGetResponses) == 'function' && typeof(setAnsweredValues) == 'function'){
        	//push the answered values 
        	var responses = matchingGetResponses();
        	var answeredValues = null;
        	var size = 0;
        	for(var key in responses){
            	if(answeredValues == null){
            		answeredValues = new Object();
            	}
        		answeredValues[responses[key]['identifier']] = responses[key]['value'];
        		size++;
        		//set the answered values in the context
        		setRecoveryContext(responseToId[responses[key]['identifier']], JSON.stringify(responses[key]['value']));
        	}
        	if($.isPlainObject(answeredValues)){
            	
        		//set the answered values to the taoApi 
				setAnsweredValues(JSON.stringify(answeredValues));
        	}
        	
            // Evaluate the user's responses
            matchingEvaluate();
    	}
    });
});