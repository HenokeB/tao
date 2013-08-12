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

if(!function_exists('matching_init')){
	require_once (dirname(__FILE__).'/Matching/matching_api.php');
}

/**
 * Short description of class taoQTI_models_classes_ItemModel
 *
 * @access public
 * @author Joel Bout, <joel@taotesting.com>
 * @package taoQTI
 * @subpackage models_classes
 */
class taoQTI_models_classes_ItemModel
        implements taoItems_models_classes_itemModel,
                   taoItems_models_classes_evaluatableItemModel
{

    /**
     * constructor called by itemService
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @return mixed
     */
    public function __construct()
    {
    }

    /**
     * render used for deploy and preview
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @return string
     */
    public function render( core_kernel_classes_Resource $item)
    {
        $returnValue = (string) '';

		$qitService = taoQTI_models_classes_QTI_Service::singleton();
		
		//@todo : enable multi item authoring at the same time in the cache object
		//@todo : remove the quick hack to enable preview during authoring
		$qtiItem = null;
		$itemSerial = taoQTI_helpers_qti_ItemAuthoring::getAuthoringItem($item);
		if(!empty($itemSerial)){
			taoQTI_models_classes_QTI_Data::setPersistence(true);//retrive from cache
			$qtiItem = $qitService->getItemBySerial($itemSerial);
		}
		if(is_null($qtiItem)){
			$qtiItem = $qitService->getDataItemByRdfItem($item, false);
		}
    	
		if(!is_null($qtiItem)) {
			$returnValue = $qitService->renderQTIItem($qtiItem);
		} else {
			common_Logger::w('No qti data for item '.$item->getUri().' in '.__FUNCTION__, 'taoQTI');
		}

        return (string) $returnValue;
    }

    /**
     * Short description of method evaluate
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @param  array responses
     * @return array
     */
    public function evaluate( core_kernel_classes_Resource $item, $responses)
    {
        $returnValue = array();

        $qtiService = taoQTI_models_classes_QTI_Service::singleton();
		$qtiItem = $qtiService->getDataItemByRdfItem($item);

		$itemMatchingData = $qtiItem->getMatchingData();

		matching_init();
		matching_setRule($itemMatchingData["rule"]);
		matching_setAreaMaps($itemMatchingData["areaMaps"]);
		matching_setMaps($itemMatchingData["maps"]);
		matching_setCorrects($itemMatchingData["corrects"]);
		matching_setResponses($responses);
		matching_setOutcomes($itemMatchingData["outcomes"]);

		try {
			// Evaluate the user's response
			matching_evaluate();
			// get the outcomes
			$outcomes = matching_getOutcomes();

			// Check if outcomes are scalar
			try {
				foreach($outcomes as $outcome) {
					if(! is_scalar($outcome['value'])){
						throw new Exception(__CLASS__.'::'.__FUNCTION__.' outcomes are not scalar');
					}
				}
				$returnValue = $outcomes;
			}
			catch(Exception $e){
		 		;//
			}
		}
		catch(Exception $e){
			;//
		}

        return (array) $returnValue;
    }

} /* end of class taoQTI_models_classes_ItemModel */

?>