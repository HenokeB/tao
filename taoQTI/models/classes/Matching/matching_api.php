<?php
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
?>
<?php
/**
 * Matching API interface.
 * Provides functions to manage the communication with a the TAO matching engine from an XHTML item.
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 *
 */

/**
 * The tao matching object
 */
$taoMatching = null;

/////////////////////
// TAO Matching //
///////////////////

/**
 * Init the TAO Matching Engine
 * @return {bool}
 */
function matching_init () {
	global $taoMatching;
	$taoMatching = new taoQTI_models_classes_Matching_Matching ();
}

/**
 * Evaluate the rule
 */
function matching_evaluate () {
	global $taoMatching;
	return $taoMatching->evaluate ();
}

/**
 * Get the outcomes generated after the rule evaluation
 * @return {JSON}
 */
function matching_getOutcomes () {
	global $taoMatching;
	return $taoMatching->getJSonOutcomes ();
}

/** Set the correct responses of the item
 * @param {JSON} data The correct responses
 */
function matching_setCorrects ($data) {
	global $taoMatching;
	return $taoMatching->setCorrects ($data);
}

/**
 * Set the mapping of the item
 * @param {JSON} data The map
 */
function matching_setMaps ($data) {
    global $taoMatching;
    return $taoMatching->setMaps ($data);
}

/**
 * Set the area mapping of the item
 * @param {JSON} data The area map
 */
function matching_setAreaMaps ($data) {
    global $taoMatching;
    return $taoMatching->setAreaMaps ($data);
}

/**
 * Set the outcome variables of the item
 * @param {JSON} data The outcome variables
 */
function matching_setOutcomes ($data) {
	global $taoMatching;
	return $taoMatching->setOutcomes ($data);
}

/**
 * Set the user' responses
 * @param {JSON} data The response variables
 */
function matching_setResponses ($data) {
	global $taoMatching;
	return $taoMatching->setResponses ($data);
}

/**
 * Set the rule of the item
 * @param {string} rule The rule
 */
function matching_setRule ($rule) {
	global $taoMatching;
	$taoMatching->setRule ($rule);
}

/**
 * get the rule of the item
 * @param {string} rule The rule
 * @todo This function is used for test. Remove it in production version
 */
function matching_getRule () {
	global $taoMatching;
	return $taoMatching->getRule ();
}

?>
