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
 * This script provides the initialization function to build QTI component
 * from an XHTML source
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 * @requires jquery {@link http://www.jquery.com}
 */

/**
 * The qti_initParam var is used everywhere in the QTI document to collect the interactions parameters
 * @example <code>qti_initParam['interaction_serial_1234'] = {id : 'interaction_1', type : 'qti_order_interaction',  responseIdentifier : 'RESPONSE'}</code> 
 * @type {Object}
 */
var qti_initParam  	= new Object();

/**
 * Initialize the QTI environment
 * 
 * @param {Object} qti_initParam the parameters of ALL item's interaction
 * @return void
 */
function qti_init(qti_initParam){
	for (var i in qti_initParam){
		new QTIInteraction(qti_initParam[i]);
	}
}