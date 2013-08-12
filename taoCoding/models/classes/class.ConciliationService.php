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
 * tao - taoCoding/models/classes/class.ConciliationService.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 09.05.2012, 10:59:59 with ArgoUML PHP module 
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
// section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BEF-includes begin
// section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BEF-includes end

/* user defined constants */
// section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BEF-constants begin
// section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BEF-constants end

/**
 * Short description of class taoCoding_models_classes_ConciliationService
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes
 */
class taoCoding_models_classes_ConciliationService
    extends tao_models_classes_Service
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute STATE_CONFLICTED
     *
     * @access public
     * @var int
     */
    const STATE_CONFLICTED = 0;

    /**
     * Short description of attribute STATE_IN_PROGRESS
     *
     * @access public
     * @var int
     */
    const STATE_IN_PROGRESS = 1;

    /**
     * Short description of attribute STATE_CONCILIATED
     *
     * @access public
     * @var int
     */
    const STATE_CONCILIATED = 2;

    /**
     * Short description of attribute DELIVERYRESULT_LIST_CACHEKEY
     *
     * @access private
     * @var string
     */
    const DELIVERYRESULT_LIST_CACHEKEY = 'conciliation_dr_list';

    // --- OPERATIONS ---

    /**
     * Short description of method getConciliationStatus
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @return int
     */
    public function getConciliationStatus( core_kernel_classes_Resource $activityExecution)
    {
        $returnValue = (int) 0;

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BFD begin
        $returnValue = self::STATE_CONFLICTED;
        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BFD end

        return (int) $returnValue;
    }

    /**
     * Short description of method processGradedDelivery
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return mixed
     */
    public function processGradedDelivery( core_kernel_classes_Resource $delivery)
    {
        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C0A begin
		$conciliated = $this->autoConciliateDelivery($delivery);
		$delivery->editPropertyValues(new core_kernel_classes_Property(TAO_DELIVERY_ACTIVE_PROP), GENERIS_FALSE);
		if ($conciliated) {
			common_Logger::i('Delivery '.$delivery->getLabel().'('.$delivery->getUri().') autmoaticly conciliated', 'TAOCODING');
			$pps = taoCoding_models_classes_PostProcessingService::singleton();
			$pps->processDelivery($delivery);
        } else {
			common_Logger::i('Delivery '.$delivery->getLabel().'('.$delivery->getUri().') conflicted after auto conciliated', 'TAOCODING');
        	$delivery->editPropertyValues(new core_kernel_classes_Property(TAO_DELIVERY_CODINGSTATUS_PROP), TAO_DELIVERY_CODINGSTATUS_CONCILIATION);
        }
		if ($conciliated) {
		}
        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C0A end
    }

    /**
     * Conciliates the grades of a delivery
     * Returns true if successful and false if conflicted
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return boolean
     */
    public function autoConciliateDelivery( core_kernel_classes_Resource $delivery)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1--2c28de10:136df09899f:-8000:0000000000000C1F begin
    	$returnValue = true;
    	$drs = taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery);
    	common_Logger::i('auto concilitating '.count($drs).' deliveryResults for '.$delivery, array('TAOCODING'));
    	foreach ($drs as $deliveryResult) {
        	$conciliated = $this->autoConciliateDeliveryResult($deliveryResult);
        	if (!$conciliated) {
        		$returnValue = false;
        	} 
        }
        // section 127-0-1-1--2c28de10:136df09899f:-8000:0000000000000C1F end

        return (bool) $returnValue;
    }

    /**
     * Conciliates the grades of a deliveryresult
     * Returns true if successful and false if conflicted
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @return boolean
     */
    public function autoConciliateDeliveryResult( core_kernel_classes_Resource $deliveryResult)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C0D begin
        $returnValue = true;
		$responseClass = new core_kernel_classes_Class(TAO_RESULT_RESPONSE);
        $responses = $responseClass->searchInstances(array(
        	PROPERTY_MEMBER_OF_RESULT	=> $deliveryResult->getUri()
        ));
    	common_Logger::d('auto concilitating '.count($responses).' responses for '.$deliveryResult, array('TAOCODING'));
        foreach ($responses as $response) {
        	$conciliated = $this->autoConciliateResponse($response);
        	if (!$conciliated) {
        		$returnValue = false;
        	}
        }
        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C0D end

        return (bool) $returnValue;
    }

    /**
     * Short description of method autoConciliateResponse
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource response
     * @return boolean
     */
    public function autoConciliateResponse( core_kernel_classes_Resource $response)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C2E begin
        $gradeClass = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $grades = $gradeClass->searchInstances(array(
        	PROPERTY_VARIABLE_DERIVATED	=> $response->getUri()
        ));
        if (empty($grades)) {
        	throw new common_Exception('No grades for response '.$response);
        	return false;
        }
        
        $conflict = false;
        $model = null;
        foreach ($grades as $grade) {
        	$data = $grade->getPropertiesValues(array(
        		new core_kernel_classes_Property(RDF_VALUE),
        		new core_kernel_classes_Property(PROPERTY_VARIABLE_AUTHOR),
        		new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER)
        	));
        	$value = (string)array_pop($data[RDF_VALUE]);
        	if (is_null($model)) {
        		$model = $value;
        	} else {
        		if ($value != $model) {
        			$conflict = true;
        		}
        	}
        }
        // conciliate if not co nflicted
        if (!$conflict) {
        	$this->concolidate($response, $model);
        }
        $returnValue = !$conflict;
        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C2E end

        return (bool) $returnValue;
    }

    /**
     * Short description of method getDeliveryCodingStatistics
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return array
     */
    public function getDeliveryCodingStatistics( core_kernel_classes_Resource $delivery)
    {
        $returnValue = array();

        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C13 begin
		$activityClasseIDs = taoCoding_models_classes_CodingService::singleton()->getActivityClassIDs($delivery, null);
		$manualGrades = array();
		foreach ($activityClasseIDs as $itemURI => $acURI) {
			$item = new core_kernel_classes_Resource($itemURI);
			$manualGrades[$itemURI] = taoCoding_models_classes_CodingService::singleton()->getManualGradingIdentifiers($item);
		}
        $min = null;
        $max = null;
        $sum = 0;
        $count = 0;
    	foreach (taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery) as $deliveryResult) {
    		// foreach item
    		// get grades for each measurement
    		// adapt min, max, sum, count
        }
        $returnValue = array(
        	'min'	=> $min,
			'max'	=> $max,
			'average'	=> $sum / $count
        );
        // section 127-0-1-1--465f7179:136ab0c6662:-8000:0000000000000C13 end

        return (array) $returnValue;
    }

    /**
     * Short description of method getStatisticsForDelivery
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return array
     */
    public function getStatisticsForDelivery( core_kernel_classes_Resource $delivery)
    {
        $returnValue = array();

        // section 127-0-1-1--1e907f43:136bb43eac7:-8000:0000000000000C16 begin
        
        //@todo requires optimisation, very slow at the moment
        $min = null;
        $max = 0;
        $sum = 0;
        $count = 0;
        foreach (taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery) as $dr) {
        	$stats = $this->getStatisticsForDeliveryResult($dr);
        	if (is_null($min) || $min > $stats['min']) {
        		$min = $stats['min'];
        	}
        	if ($max < $stats['max']) {
        		$max = $stats['max'];
        	}
        	$sum += $stats['avg'];
        	$count++;
        }
        $returnValue = array(
        	'min' => is_null($min) ? 0 : $min,
        	'max' => $max,
        	'avg' => ($count == 0 ? 0 : $sum / $count)
        );
        // section 127-0-1-1--1e907f43:136bb43eac7:-8000:0000000000000C16 end

        return (array) $returnValue;
    }

    /**
     * Short description of method getStatisticsForDeliveryResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @return array
     */
    public function getStatisticsForDeliveryResult( core_kernel_classes_Resource $deliveryResult)
    {
        $returnValue = array();

        // section 127-0-1-1--1e907f43:136bb43eac7:-8000:0000000000000C18 begin
        $codingservice = taoCoding_models_classes_CodingService::singleton();
        $true = new core_kernel_classes_Resource(GENERIS_TRUE);
        $min = null;
        $max = 0;
        $sum = 0;
        $count = 0;
		$process = $deliveryResult->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_RESULT_OF_PROCESS));
        foreach (wfEngine_helpers_ProcessUtil::getActivityExecutions($process) as $activityExecution) {
			$gradeClass = new core_kernel_classes_Class(TAO_RESULT_GRADE);
	        $grades = $gradeClass->searchInstances(array(
	        	PROPERTY_VARIABLE_ORIGIN	=> $activityExecution->getUri()
	        ));
	        
	        $ids = $codingservice->getManualGradingIdentifiers($codingservice->getItemByActivityExecution($activityExecution));
	        $counts = array_fill_keys($ids, 0);
	        foreach ($grades as $grade) {
				$data = $grade->getPropertiesValues(array(
		        	new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER),
		        	new core_kernel_classes_Property(PROPERTY_GRADE_FINAL),
		        ));
		        $identifier = (string)array_pop($data[PROPERTY_VARIABLE_IDENTIFIER]);
		        // not a final grade
		        if (!isset($data[PROPERTY_GRADE_FINAL]) || $true->equals(array_pop($data[PROPERTY_GRADE_FINAL]))) {
		        	if (!isset($counts[$identifier])) {
		        		common_Logger::w('unknown grade: '.$identifier);
		        	} else {
		        		$counts[$identifier]++;
		        	}
		        }
	        };
	        foreach ($counts as $id => $gradecount) {
	        	if (is_null($min) || $min > $gradecount) {
	        		$min = $gradecount;
	        	}
	        	if (is_null($max) || $max < $gradecount) {
	        		$max = $gradecount;
	        	}
	        	$sum += $gradecount;
	        	$count++;
	        }
        }
        $avg = $count > 0 ?  $sum / $count : 0;
        $returnValue = array(
        	'min' => is_null($min) ? 0 : $min,
        	'max' => $max,
        	'avg' => $avg
        );
        // section 127-0-1-1--1e907f43:136bb43eac7:-8000:0000000000000C18 end

        return (array) $returnValue;
    }

    /**
     * Short description of method concolidate
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource response
     * @param  string value
     * @param  Resource grader
     * @return mixed
     */
    public function concolidate( core_kernel_classes_Resource $response, $value,  core_kernel_classes_Resource $grader = null)
    {
        // section 127-0-1-1-6ff9eeea:136c0d697a5:-8000:0000000000000C15 begin
        
        // find associated grades @todo prevent second search
        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $grades = $type->searchInstances(array(
        	PROPERTY_VARIABLE_DERIVATED	=> $response->getUri()
        ));
        if (empty($grades)) {
        	throw new common_Exception('No grades to conciliate for response '.$response);
        }
        
        //check if not already concolidated
        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
        $searchArr =  array(
        	PROPERTY_GRADE_FINAL			=> GENERIS_TRUE,
        	PROPERTY_VARIABLE_DERIVATED		=> $grades
        );
        $finals = $type->searchInstances($searchArr);
        if (count($finals) > 1) {
        	throw new common_exception_InconsistentData('Response '.$response->getUri().' has already been concolidated several times');
        } elseif (count($finals) == 1) {
        	$conciliated = array_pop($finals);
        	$conciliated->editPropertyValues(new core_kernel_classes_Property(RDF_VALUE), $value);
	        if (!is_null($grader)) {
	        	$conciliated->editPropertyValues(new core_kernel_classes_Property(PROPERTY_VARIABLE_AUTHOR), $grader);
	        }
        } else {
			// create new conciliation
	        // get supporting data
	        $data = $response->getPropertiesValues(array(
	        	new core_kernel_classes_Property(PROPERTY_MEMBER_OF_RESULT),
	        	new core_kernel_classes_Property(PROPERTY_VARIABLE_ORIGIN),
	        	
	        ));
	        $deliveryResult = array_pop($data[PROPERTY_MEMBER_OF_RESULT]);
	        $activityExecution = array_pop($data[PROPERTY_VARIABLE_ORIGIN]);
	        $identifier = current($grades)->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_VARIABLE_IDENTIFIER));
	        
	        $data = array(
				PROPERTY_MEMBER_OF_RESULT	=> $deliveryResult,
				PROPERTY_VARIABLE_ORIGIN	=> $activityExecution,
				PROPERTY_VARIABLE_DERIVATED	=> $grades,
				RDF_VALUE					=> $value,
				PROPERTY_GRADE_FINAL		=> GENERIS_TRUE,
				RDFS_COMMENT					=> 'Final grade of '.$response->getUri(),
	        	PROPERTY_VARIABLE_IDENTIFIER	=> $identifier
	        );
	        if (!is_null($grader)) {
	        	$data[PROPERTY_VARIABLE_AUTHOR] = $grader;
	        }
	        
	        $type = new core_kernel_classes_Class(TAO_RESULT_GRADE);
	        $responseResource = $type->createInstanceWithProperties($data);
        }
        // section 127-0-1-1-6ff9eeea:136c0d697a5:-8000:0000000000000C15 end
    }

    /**
     * Short description of method getOpenDeliveryResults
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @param  Resource conciliator
     * @param  Configuration filterSetting
     * @param  boolean forceRecalc
     * @return array
     */
    public function getOpenDeliveryResults( core_kernel_classes_Resource $delivery,  core_kernel_classes_Resource $conciliator,  taoCoding_models_classes_filter_Configuration $filterSetting, $forceRecalc = false)
    {
        $returnValue = array();

        // section 127-0-1-1--68e2bbfc:136e8bd79ee:-8000:0000000000000C2D begin
        $hash = $delivery->getUri().$filterSetting->getHash();
		$found = false;
        if (!$forceRecalc) {
        	try {
	        	// load from session
	        	$data = common_cache_SessionCache::singleton()->get(self::DELIVERYRESULT_LIST_CACHEKEY);
	        	if ($data['hash'] == $hash) {
	        		$returnValue = $data['list'];
					$found = true;
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
	        	PROPERTY_VARIABLE_AUTHOR	=> $conciliator->getUri(),
	        	PROPERTY_MEMBER_OF_RESULT	=> $results,
	        	PROPERTY_GRADE_FINAL		=> GENERIS_TRUE
	        ), array('like' => false));
	        
	        // @todo real algorithm
	        $gradecount = array();
	        foreach ($grades as $grade) {
	        	$gradecount[$grade->getUri()] = 1;
	        }
	        foreach ($gradecount as $uri => $count) {
	        	$returnValue[$uri] = self::STATE_CONFLICTED;
	        	/* 
	        	$returnValue[$uri] = $count == $totalManualGrades
	        		? self::STATE_CONFLICTED
	        		: self::STATE_CONCILIATED;
	        		*/
	        }
	        /**/
        	$returnValue = array();
	        // store in session
			common_cache_SessionCache::singleton()->put(array(
				'hash'		=> $hash,
				'list'		=> $returnValue
			), self::DELIVERYRESULT_LIST_CACHEKEY);
        	common_Logger::d('stored to session');
        }
        // section 127-0-1-1--68e2bbfc:136e8bd79ee:-8000:0000000000000C2D end

        return (array) $returnValue;
    }

    /**
     * Short description of method openRandomConflictingDR
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return core_kernel_classes_Resource
     */
    public function openRandomConflictingDR( core_kernel_classes_Resource $delivery)
    {
        $returnValue = null;

        // section 127-0-1-1-68426edc:13730c44e77:-8000:0000000000000C3B begin
        
		//$gradingStatus	= $this->getOpenDeliveryResults($delivery, $grader, $filterSetting);
        //$gradedIDs		= array_keys($gradingStatus);
        $locked = array();
     	$instances		= taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery);
        foreach ($instances as $key => $ressource) {
        	if (in_array($ressource->getUri(), $locked)) {
        		unset($instances[$key]);
        	}
        }
        
		//@todo use weights to get less corrected deliveryResult more often
        if (count($instances) > 0) {
			$key		= array_rand($instances);
			$returnValue = $instances[$key];
        }
        /*
        if (!is_null($returnValue)) {
        	$gradingStatus[$returnValue->getUri()] = self::STATE_UNGRADED;
        	common_cache_SessionCache::singleton()->put(array(
				'delivery'	=> $delivery->getUri(),
				'list'		=> $gradingStatus,
				'filter'	=> $filterSetting->getHash()
			), self::DELIVERYRESULT_LIST_CACHEKEY);
        }
        */
        // section 127-0-1-1-68426edc:13730c44e77:-8000:0000000000000C3B end

        return $returnValue;
    }

} /* end of class taoCoding_models_classes_ConciliationService */

?>