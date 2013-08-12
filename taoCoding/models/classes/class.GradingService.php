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
 * tao - taoCoding/models/classes/class.GradingService.php
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
 * include tao_models_classes_Service
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('tao/models/classes/class.Service.php');

/* user defined includes */
// section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B55-includes begin
// section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B55-includes end

/* user defined constants */
// section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B55-constants begin
// section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B55-constants end

/**
 * Short description of class taoCoding_models_classes_GradingService
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes
 */
class taoCoding_models_classes_GradingService
    extends tao_models_classes_Service
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute STATE_UNGRADED
     *
     * @access public
     * @var int
     */
    const STATE_UNGRADED = 0;

    /**
     * Short description of attribute STATE_PARTIALY_GRADED
     *
     * @access public
     * @var int
     */
    const STATE_PARTIALY_GRADED = 1;

    /**
     * Short description of attribute STATE_GRADED
     *
     * @access public
     * @var int
     */
    const STATE_GRADED = 2;

    /**
     * Short description of attribute DELIVERYRESULT_LIST_CACHEKEY
     *
     * @access private
     * @var string
     */
    const DELIVERYRESULT_LIST_CACHEKEY = 'grading_dr_list';

    // --- OPERATIONS ---

    /**
     * returns the grade ressource assigned to
     * the answer by the grader or null if none assigned
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource answer
     * @param  Resource grader
     * @return core_kernel_classes_Resource
     */
    public function getGrade( core_kernel_classes_Resource $answer,  core_kernel_classes_Resource $grader)
    {
        $returnValue = null;

        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B5F begin
        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $grades = $type->searchInstances(array(
        	PROPERTY_VARIABLE_DERIVATED => $answer->getUri(),
        	PROPERTY_VARIABLE_AUTHOR	=> $grader->getUri()
        ));
        if (count($grades) > 1) {
        	common_Logger::w('More then 1 grade for answer('.$answer->getUri().') by the same grader('.$grader->getLabel().')');
        }
        if (count($grades) > 0) {
        	$returnValue = array_shift($grades);
        }
        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B5F end

        return $returnValue;
    }

    /**
     * Short description of method setGrade
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @param  Resource activityExecution
     * @param  string identifier
     * @param  Resource grader
     * @param  string grade
     * @param  array answers
     * @return mixed
     */
    public function setGrade( core_kernel_classes_Resource $deliveryResult,  core_kernel_classes_Resource $activityExecution, $identifier,  core_kernel_classes_Resource $grader, $grade, $answers = array())
    {
        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B5A begin
        
        // look for an old grade, delete
        $formerGrade = $this->getGradeByOrigin($activityExecution, $grader, $identifier);
        if (!is_null($formerGrade)) {
        	$formerGrade->removePropertyValues(new core_kernel_classes_Property(RDF_VALUE));
        	$formerGrade->setPropertyValue(
        		new core_kernel_classes_Property(RDF_VALUE), $grade
        	);
        } else {
        	$data = array(
				PROPERTY_MEMBER_OF_RESULT	=> $deliveryResult,
				PROPERTY_VARIABLE_ORIGIN	=> $activityExecution,
				PROPERTY_VARIABLE_AUTHOR	=> $grader,
				RDF_VALUE					=> $grade
			);
	        if (!is_null($identifier)) {
	        	$data[PROPERTY_VARIABLE_IDENTIFIER] = $identifier;
	        }
	        if (count($answers) > 0) {
	        	$data[PROPERTY_VARIABLE_DERIVATED] = $answers;
	        }
	        
	        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
	        $answerResource = $type->createInstanceWithProperties($data);
        }
        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000000B5A end
    }

    /**
     * Short description of method getGradeByOrigin
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource origin
     * @param  Resource grader
     * @param  string identifier
     * @return core_kernel_classes_Resource
     */
    public function getGradeByOrigin( core_kernel_classes_Resource $origin,  core_kernel_classes_Resource $grader, $identifier)
    {
        $returnValue = null;

        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BDA begin
        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $grades = $type->searchInstances(array(
        	PROPERTY_VARIABLE_ORIGIN	=> $origin->getUri(),
        	PROPERTY_VARIABLE_AUTHOR	=> $grader->getUri()
        ));
        
        foreach ($grades as $grade) {
	        $gradeid = $grade->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER));
	        if ($identifier == $gradeid) {
	        	$returnValue = $grade;
	        	break;
	        }
        }
        // section 127-0-1-1--22cbdaad:1363e8722fc:-8000:0000000000003BDA end

        return $returnValue;
    }

    /**
     * Short description of method getGradeDataForGrader
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource grader
     * @param  Resource activityExecution
     * @return array
     */
    public function getGradeDataForGrader( core_kernel_classes_Resource $grader,  core_kernel_classes_Resource $activityExecution)
    {
        $returnValue = array();

        // section 127-0-1-1--1944fdc1:1364ef91da7:-8000:0000000000000B79 begin
     	$type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $grades = $type->searchInstances(array(
        	PROPERTY_VARIABLE_ORIGIN	=> $activityExecution->getUri(),
        	PROPERTY_VARIABLE_AUTHOR	=> $grader->getUri()
        ));
        foreach ($grades as $grade) {
	        $properties = $grade->getPropertiesValues(array(
	        	new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER),
	        	new core_kernel_classes_Property(RDF_VALUE),
	        	new core_kernel_classes_Property(RDFS_COMMENT)
	        ));
	        $identifier = (string)array_shift($properties[PROPERTY_VARIABLE_IDENTIFIER]);
	        $value		= (string)array_shift($properties[RDF_VALUE]);
	        $comment	= isset($properties[RDFS_COMMENT]) ? (string)array_shift($properties[RDFS_COMMENT]) : '';
	        
        	$returnValue[$identifier] = array(
        		'val'		=> $value,
        		'comment'	=> $comment
        	); 
        }
        // section 127-0-1-1--1944fdc1:1364ef91da7:-8000:0000000000000B79 end

        return (array) $returnValue;
    }

    /**
     * Short description of method getGradingStatus
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @param  Resource grader
     * @param  Resource item
     * @return int
     */
    public function getGradingStatus( core_kernel_classes_Resource $activityExecution,  core_kernel_classes_Resource $grader,  core_kernel_classes_Resource $item = null)
    {
        $returnValue = (int) 0;

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BD8 begin
        $item = is_null($item) ? taoCoding_models_classes_CodingService::singleton()->getItemByActivityExecution($activityExecution) : $item;
        
        // outcome => gradevalue
        $grades			= $this->getGradeDataForGrader($grader, $activityExecution);
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
        
        $returnValue = $done == 0 ? self::STATE_UNGRADED : ($missing == 0 ? self::STATE_GRADED : self::STATE_PARTIALY_GRADED);
        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BD8 end

        return (int) $returnValue;
    }

    /**
     * Short description of method getDeliveryResultStatus
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @param  Resource grader
     * @return int
     */
    public function getDeliveryResultStatus( core_kernel_classes_Resource $deliveryResult,  core_kernel_classes_Resource $grader)
    {
        $returnValue = (int) 0;

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000C05 begin
        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000C05 end

        return (int) $returnValue;
    }

    /**
     * Short description of method getOpenDeliveryResults
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @param  Resource grader
     * @param  Configuration filterSetting
     * @param  boolean forceRecalc
     * @return array
     */
    public function getOpenDeliveryResults( core_kernel_classes_Resource $delivery,  core_kernel_classes_Resource $grader,  taoCoding_models_classes_filter_Configuration $filterSetting, $forceRecalc = false)
    {
        $returnValue = array();

        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000BF5 begin
        $found = false;
        if (!$forceRecalc) {
        	try {
	        	// load from session
	        	$data = common_cache_SessionCache::singleton()->get(self::DELIVERYRESULT_LIST_CACHEKEY);
	        	if ($data['delivery'] == $delivery->getUri() && $data['filter'] == $filterSetting->getHash()) {
	        		$returnValue = $data['list'];
					$found = true;
	        		common_Logger::d('loaded from session');
	        	}
	        } catch (common_cache_NotFoundException $e) {
	        	// do nothing
	        }
        }
        
        if (!$found) {
        	$activityClasseIDs = taoCoding_models_classes_CodingService::singleton()->getActivityClassIDs($delivery, $filterSetting);
			$totalManualGrades = 0;
			foreach ($activityClasseIDs as $itemURI => $acURI) {
				$item = new core_kernel_classes_Resource($itemURI);
				$totalManualGrades += count(taoCoding_models_classes_CodingService::singleton()->getManualGradingIdentifiers($item));
			}
	        $results = taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery);
	        $class = new core_kernel_classes_Class(TAO_RESULT_GRADE);
	        $grades = $class->searchInstances(array(
	        	PROPERTY_VARIABLE_AUTHOR => $grader->getUri(),
	        	PROPERTY_MEMBER_OF_RESULT => $results
	        ), array('like' => false));
	        
	        $gradecount = array();
	        foreach ($grades as $grade) {
	        	$gradeVars = $grade->getPropertiesValues(array(
					new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER),
	        		new core_kernel_classes_Property(PROPERTY_VARIABLE_ORIGIN),
					new core_kernel_classes_Property(PROPERTY_MEMBER_OF_RESULT)
				));
				$execution = array_pop($gradeVars[PROPERTY_VARIABLE_ORIGIN]);
				$executionClass = $execution->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITY_EXECUTION_ACTIVITY));
				if (in_array($executionClass->getUri(), $activityClasseIDs)) {
					$dr = array_pop($gradeVars[PROPERTY_MEMBER_OF_RESULT]);
		        	$gradecount[$dr->getUri()] = isset($gradecount[$dr->getUri()]) ? $gradecount[$dr->getUri()] + 1 : 1 ;
				}
	        }
	        foreach ($gradecount as $uri => $count) {
	        	$returnValue[$uri] = $count == $totalManualGrades
	        		? taoCoding_models_classes_GradingService::STATE_GRADED
	        		: taoCoding_models_classes_GradingService::STATE_PARTIALY_GRADED;
	        }
	        // store in session
			common_cache_SessionCache::singleton()->put(array(
				'delivery'	=> $delivery->getUri(),
				'list'		=> $returnValue,
				'filter'	=> $filterSetting->getHash()
			), self::DELIVERYRESULT_LIST_CACHEKEY);
        }
        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000BF5 end

        return (array) $returnValue;
    }

    /**
     * Short description of method openRandomDeliveryResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @param  Resource grader
     * @param  Configuration filterSetting
     * @return core_kernel_classes_Resource
     */
    public function openRandomDeliveryResult( core_kernel_classes_Resource $delivery,  core_kernel_classes_Resource $grader,  taoCoding_models_classes_filter_Configuration $filterSetting)
    {
        $returnValue = null;

        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000BF9 begin
		$gradingStatus	= $this->getOpenDeliveryResults($delivery, $grader, $filterSetting);
        $gradedIDs		= array_keys($gradingStatus);
     	$instances		= taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery);
        foreach ($instances as $key => $ressource) {
        	if (in_array($ressource->getUri(), $gradedIDs)) {
        		unset($instances[$key]);
        	}
        }
        
		//@todo use weights to get less corrected deliveryResult more often
        while (is_null($returnValue) && count($instances) > 0) {
			$key		= array_rand($instances);
			$process	= $instances[$key]->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_RESULT_OF_PROCESS));
			$status		= $process->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_PROCESSINSTANCES_STATUS));
			if ($status->getUri() == INSTANCE_PROCESSSTATUS_FINISHED) {
				$returnValue = $instances[$key];
			} else {
				unset($instances[$key]);
			}
        }
        if (!is_null($returnValue)) {
        	$gradingStatus[$returnValue->getUri()] = self::STATE_UNGRADED;
        	common_cache_SessionCache::singleton()->put(array(
				'delivery'	=> $delivery->getUri(),
				'list'		=> $gradingStatus,
				'filter'	=> $filterSetting->getHash()
			), self::DELIVERYRESULT_LIST_CACHEKEY);
        }
        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000BF9 end

        return $returnValue;
    }

} /* end of class taoCoding_models_classes_GradingService */

?>