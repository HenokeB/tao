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
 * Copyright (c) 2009-2012 (original work) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 *               
 * 
 */
?>
<?php

error_reporting(E_ALL);

/**
 * tao - taoCoding/models/classes/class.CodingService.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 25.04.2012, 12:04:56 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoResults_models_classes_ResultsService
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoResults/models/classes/class.ResultsService.php');

/* user defined includes */
// section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A90-includes begin
// section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A90-includes end

/* user defined constants */
// section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A90-constants begin
// section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A90-constants end

/**
 * Short description of class taoCoding_models_classes_CodingService
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes
 */
class taoCoding_models_classes_CodingService
    extends taoResults_models_classes_ResultsService
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute manualIdentifiersCache
     *
     * @access private
     * @var array
     */
    private $manualIdentifiersCache = array();

    // --- OPERATIONS ---

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function __construct()
    {
        // section 127-0-1-1--4a39c2fc:1357783911e:-8000:0000000000000A9A begin
		parent::__construct();
		/*$resultNS = RESULT_ONTOLOGY;
		$taoDeliveryResultClassUri = $resultNS . "#" . "TAO_DELIVERY_RESULTS";
		*/
		$this->resultClass = new core_kernel_classes_Class(TAO_DELIVERY_RESULT);
        // section 127-0-1-1--4a39c2fc:1357783911e:-8000:0000000000000A9A end
    }

    /**
     * Short description of method getAnonymizedLabel
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @return string
     */
    public function getAnonymizedLabel( core_kernel_classes_Resource $deliveryResult)
    {
        $returnValue = (string) '';

        // section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A92 begin
		$returnValue = substr(md5($deliveryResult->getUri()), 0, 5);
        // section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000000A92 end

        return (string) $returnValue;
    }

    /**
     * Short description of method getAnswer
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @param  string identifier
     * @return core_kernel_classes_Resource
     */
    public function getAnswer( core_kernel_classes_Resource $activityExecution, $identifier)
    {
        $returnValue = null;

        // section 127-0-1-1--4717253d:1362b3edc80:-8000:0000000000003E9A begin
        $class = new core_kernel_classes_Class(TAO_RESULT_RESPONSE);
        $answers = $class->searchInstances(array(
        	PROPERTY_VARIABLE_ORIGIN 		=> $activityExecution->getUri(),
        	PROPERTY_VARIABLE_IDENTIFIER	=> $identifier
        ), array('like' => false));
        if (count($answers) == 1) {
        	$returnValue = array_shift($answers);
        } elseif (count($answers) > 1) {
        	throw new common_Exception(count($answers).' answers for ('.$activityExecution->getUri().','.$identifier.')');
        }
        // section 127-0-1-1--4717253d:1362b3edc80:-8000:0000000000003E9A end

        return $returnValue;
    }

    /**
     * Short description of method getAnsweredValues
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @return array
     */
    public function getAnsweredValues( core_kernel_classes_Resource $activityExecution)
    {
        $returnValue = array();

        // section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000003C2C begin
        $class = new core_kernel_classes_Class(TAO_RESULT_RESPONSE);
        $answers = $class->searchInstances(array(
        	PROPERTY_VARIABLE_ORIGIN => $activityExecution->getUri()
        ));
        foreach ($answers as $answer) {
        	$props = $answer->getPropertiesValues(array(
        		new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER),
        		new core_kernel_classes_Property(RDF_VALUE)
        	));
        	// should be:
        	if (isset($props[PROPERTY_VARIABLE_IDENTIFIER])) {
        		if (count($props[PROPERTY_VARIABLE_IDENTIFIER]) > 1) {
        			throw new common_exception_Error('the result variable('.$answer->getUri().') has multiple identifiers');
        		}
        		$identifier = (string)array_shift($props[PROPERTY_VARIABLE_IDENTIFIER]);
        		if (!isset($returnValue[$identifier])) {
        			$returnValue[$identifier] = array();
        		}
        		if (isset($props[RDF_VALUE])) {
        			foreach ($props[RDF_VALUE] as $literal) {
        				$returnValue[$identifier][] = (string)$literal;
        			}
        		}
        	} elseif (isset($props[RDF_VALUE])) {
        		//fallback
	        	foreach ($props[RDF_VALUE] as $valuelit) {
	        		common_Logger::i('found '.(string)$valuelit);
	        		$struct = json_decode((string)$valuelit, true);
	        		foreach ($struct as $key => $val) {
	        			if (isset($returnValue[$key])) {
	        				$returnValue[$key][] = $val;
	        			} else {
	        				$returnValue[$key] = array($val);
	        			}
	        		}
	        	}
        	}
        }
        // section 127-0-1-1-57082bb1:135773fec28:-8000:0000000000003C2C end

        return (array) $returnValue;
    }

    /**
     * Short description of method getDeliveriesToGrade
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    public function getDeliveriesToGrade()
    {
        $returnValue = array();

        // section 127-0-1-1--68420520:135a02c8f9c:-8000:0000000000000AA9 begin
        $class = new core_kernel_classes_Class(TAO_DELIVERY_CLASS);
        $deliveries = $class->searchInstances(array(
        	TAO_DELIVERY_CODINGMETHOD_PROP => TAO_DELIVERY_CODINGMETHOD_MANUAL,
        	TAO_DELIVERY_CODINGSTATUS_PROP	=> TAO_DELIVERY_CODINGSTATUS_GRADING 
        ), array(
        	'recursive'						=> true
        ));
	if (count($deliveries)==0) {
	    
	    throw new common_exception_Error(__("There is currently no delivery defined in the system that requires Human Based Grading. Please configure a delivery accordingly"));

	}
		foreach ($deliveries as $res) {
			$returnValue[$res->getUri()] = $res->getLabel();
		}
        // section 127-0-1-1--68420520:135a02c8f9c:-8000:0000000000000AA9 end

        return (array) $returnValue;
    }

    /**
     * Short description of method getDeliveriesToConciliate
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    public function getDeliveriesToConciliate()
    {
        $returnValue = array();

        // section 127-0-1-1-74b053b:136828054b1:-8000:0000000000003C95 begin
     	$class = new core_kernel_classes_Class(TAO_DELIVERY_CLASS);
        $deliveries = $class->searchInstances(array(
        	TAO_DELIVERY_CODINGMETHOD_PROP => TAO_DELIVERY_CODINGMETHOD_MANUAL,
        	TAO_DELIVERY_CODINGSTATUS_PROP	=> TAO_DELIVERY_CODINGSTATUS_CONCILIATION 
        ), array(
        	'recursive'						=> true
        ));
        
		foreach ($deliveries as $res) {
			$returnValue[$res->getUri()] = $res->getLabel();
		}
        // section 127-0-1-1-74b053b:136828054b1:-8000:0000000000003C95 end

        return (array) $returnValue;
    }

    /**
     * Short description of method getItemList
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @param  Configuration filterSetting
     * @return array
     */
    public function getItemList( core_kernel_classes_Resource $deliveryResult,  taoCoding_models_classes_filter_Configuration $filterSetting)
    {
        $returnValue = array();

        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B17 begin
        if (count($filterSetting->groupBy) > 1) {
        	throw new common_Exception('getItemList requires at most one groupBy');
        }

        $grader = tao_models_classes_UserService::singleton()->getCurrentUser();
        $process = $deliveryResult->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_RESULT_OF_PROCESS));
        $activityExecutions = wfEngine_helpers_ProcessUtil::getActivityExecutions($process);
         
        $deliveryItems = array(); 
        $status = array();
        foreach ($activityExecutions as $activity) {
        	$item = $this->getItemByActivityExecution($activity);
        	$deliveryItems[$activity->getUri()] = $item; 
        	$status[$activity->getUri()] = taoCoding_models_classes_GradingService::singleton()->getGradingStatus($activity, $grader, $item);
        }
		$deliveryItems = taoCoding_models_classes_filter_Service::singleton()->filterRessources($deliveryItems, $filterSetting);
		
		if (count($filterSetting->groupBy) > 0) {
			$groupBy = $filterSetting->groupBy;
			$groupBy = array_shift($groupBy); // only take 1 level
			$literal = !is_null($groupBy->getRange()) ? $groupBy->getRange()->getUri() === RDFS_LITERAL : true;
			$groups = array();
			foreach ($deliveryItems as $activityExecutionUri => $item) {
				$data =  array(
					'itemid'	=> $activityExecutionUri,
					'label'		=> $item->getLabel(),
					'state'		=> $status[$activityExecutionUri]
				);
				$affiliation = array();
				foreach ($item->getPropertyValues($groupBy) as $value) {
					if (!isset($groups[$value])) {
						if (!$literal) {
							$res = new core_kernel_classes_Resource($value);
							$label = $res->getLabel();
						} else {
							$label = $value;
						}
						$groups[$value] = array(
							'id'	=> $value,
							'label'	=> $label,
							'items'	=> array($data)
						);
					} else {
						$groups[$value]['items'][] = $data;
					}
				}
			}
		} else {
			$groups = array(0 => array(
				'id'	=> '',
				'label'	=> __('all'),
				'items'	=> array()
			));
			foreach ($deliveryItems as $activityExecutionUri => $item) {
				$groups[0]['items'][] =  array(
					'itemid'	=> $activityExecutionUri,
					'label'		=> $item->getLabel(),
					'state'		=> $status[$activityExecutionUri]
				);
			}
		}
		$returnValue = array_values($groups);
        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B17 end

        return (array) $returnValue;
    }

    /**
     * expensiv function to get the items
     * asociated to the activity executions
     * conserves order
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @return core_kernel_classes_Resource
     */
    public function getItemByActivityExecution( core_kernel_classes_Resource $activityExecution)
    {
        $returnValue = null;

        // section 127-0-1-1--4717253d:1362b3edc80:-8000:0000000000000B48 begin
		$activityClass = $activityExecution->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITY_EXECUTION_ACTIVITY));
		$returnValue = taoTests_models_classes_TestAuthoringService::singleton()->getItemByActivity($activityClass);
        // section 127-0-1-1--4717253d:1362b3edc80:-8000:0000000000000B48 end

        return $returnValue;
    }

    /**
     * Short description of method getItemGradingStatus
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @param  Resource grader
     * @return string
     */
    public function getItemGradingStatus( core_kernel_classes_Resource $activityExecution,  core_kernel_classes_Resource $grader)
    {
        $returnValue = (string) '';

        // section 127-0-1-1--62623a80:13634e55679:-8000:0000000000000B65 begin
        $item = $this->getItemByActivityExecution($activityExecution);
        
        // outcome => gradevalue
        $grades			= taoCoding_models_classes_GradingService::singleton()->getGradeDataForGrader($grader, $activityExecution);
        $measurements	= taoItems_models_classes_ItemsService::singleton()->getItemMeasurements($item);
        $done = 0;
        $missing = 0;
        foreach ($measurements as $measurement) {
        	if ($measurement->isHumanAssisted()) {
        		if (isset($grades[$measurement->getIdentifier()])) {
        			$done++;
        		} else {
        			$missing++;
        		}
        	}
        }
        
        $returnValue = $done == 0 ? 0 : ($missing == 0 ? 2 : 1);
        // section 127-0-1-1--62623a80:13634e55679:-8000:0000000000000B65 end

        return (string) $returnValue;
    }

    /**
     * Short description of method saveComment
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource grade
     * @param  Resource grader
     * @param  string comment
     * @return mixed
     */
    public function saveComment( core_kernel_classes_Resource $grade,  core_kernel_classes_Resource $grader, $comment)
    {
        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BD0 begin
        $grade->setPropertyValue(
        	new core_kernel_classes_Property(RDFS_COMMENT),
        	$comment
        );
        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BD0 end
    }

    /**
     * Short description of method getComment
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource grade
     * @param  Resource grader
     * @return string
     */
    public function getComment( core_kernel_classes_Resource $grade,  core_kernel_classes_Resource $grader)
    {
        $returnValue = (string) '';

        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BD6 begin
        $prop = $grade->getOnePropertyValue(new core_kernel_classes_Property(RDFS_COMMENT));
        $returnValue = $prop;
        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BD6 end

        return (string) $returnValue;
    }

    /**
     * Short description of method getDeliveryResults
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return array
     */
    public function getDeliveryResults( core_kernel_classes_Resource $delivery)
    {
        $returnValue = array();

        // section 127-0-1-1--3482bde2:136593b0fd4:-8000:0000000000000B7D begin
        $returnValue = $this->getRootClass()->searchInstances(array(
        	PROPERTY_RESULT_OF_DELIVERY => $delivery->getUri()
        ));
        // section 127-0-1-1--3482bde2:136593b0fd4:-8000:0000000000000B7D end

        return (array) $returnValue;
    }

    /**
     * Short description of method getManualGradingIdentifiers
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource item
     * @return array
     */
    public function getManualGradingIdentifiers( core_kernel_classes_Resource $item)
    {
        $returnValue = array();

        // section 127-0-1-1--712e3d92:13673ba101d:-8000:0000000000000BBC begin
        if (!isset($this->manualIdentifiersCache[$item->getUri()])) {
        	$this->manualIdentifiersCache[$item->getUri()] = array();
	        $measurements	= taoItems_models_classes_ItemsService::singleton()->getItemMeasurements($item);
	        foreach ($measurements as $measurement) {
	        	if ($measurement->isHumanAssisted()) {
	        		$this->manualIdentifiersCache[$item->getUri()][] = $measurement->getIdentifier();
	        	}
	        }
        }
        $returnValue = $this->manualIdentifiersCache[$item->getUri()];
        // section 127-0-1-1--712e3d92:13673ba101d:-8000:0000000000000BBC end

        return (array) $returnValue;
    }

    /**
     * Short description of method closeDeliveryGrading
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return Boolean
     */
    public function closeDeliveryGrading()
    {
        $returnValue = null;

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000C00 begin
        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000C00 end

        return $returnValue;
    }

    /**
     * returns the filtered ActiviyClasses of a delivery
     * with the associated itemURIs as keys
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @param  Configuration filterSetting
     * @return array
     */
    public function getActivityClassIDs( core_kernel_classes_Resource $delivery,  taoCoding_models_classes_filter_Configuration $filterSetting)
    {
        $returnValue = array();

        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000C13 begin
		// get all items of delivery, expensiv
		$items = array();
		foreach (taoDelivery_models_classes_DeliveryService::singleton()->getDeliveryItems($delivery) as $item) {
			$items[] = $item;
		}
		
		// filter items according to filtersettings
		if (!is_null($filterSetting)) {
			$items = taoCoding_models_classes_filter_Service::singleton()->filterRessources($items, $filterSetting);
		}
		
        // get activity classes
        $content = $delivery->getUniquePropertyValue(new core_kernel_classes_Property(TAO_DELIVERY_PROCESS));
        $classActivities = $content->getPropertyValues(new core_kernel_classes_Property(PROPERTY_PROCESS_ACTIVITIES));
        
        // filter activityclasses by items
        foreach ($classActivities as $activityClassUri) {
        	$activityClass = new core_kernel_classes_Resource($activityClassUri);
	        $service = $activityClass->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITIES_INTERACTIVESERVICES));
			foreach ($service->getPropertyValues(new core_kernel_classes_Property(PROPERTY_CALLOFSERVICES_ACTUALPARAMETERIN)) as $parameter) {
				$parameterRessource = new core_kernel_classes_Resource($parameter);
				$paramvals = $parameterRessource->getPropertiesValues(array(
					new core_kernel_classes_Property(PROPERTY_ACTUALPARAMETER_FORMALPARAMETER),
					new core_kernel_classes_Property(PROPERTY_ACTUALPARAMETER_CONSTANTVALUE)
				));
	        		
				$formal = array_pop($paramvals[PROPERTY_ACTUALPARAMETER_FORMALPARAMETER]);
				if ($formal->getUri() == INSTANCE_FORMALPARAM_ITEMURI && isset($paramvals[PROPERTY_ACTUALPARAMETER_CONSTANTVALUE])) {
					$item = array_pop($paramvals[PROPERTY_ACTUALPARAMETER_CONSTANTVALUE]);
					if (in_array($item, $items)) {
						$returnValue[$item->getUri()] = $activityClass->getUri();
					}
					break;
				}
			}
		}
        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000C13 end

        return (array) $returnValue;
    }

} /* end of class taoCoding_models_classes_CodingService */

?>