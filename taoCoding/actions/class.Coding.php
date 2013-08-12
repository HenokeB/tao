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
 * tao - taoCoding/actions/class.Coding.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 25.04.2012, 12:04:56 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Jehan Bihin
 * @package taoCoding
 * @subpackage actions
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoCoding_actions_common_module
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoCoding/actions/common/class.module.php');

/* user defined includes */
// section 127-0-1-1--6c558cad:134e707436b:-8000:0000000000000868-includes begin
// section 127-0-1-1--6c558cad:134e707436b:-8000:0000000000000868-includes end

/* user defined constants */
// section 127-0-1-1--6c558cad:134e707436b:-8000:0000000000000868-constants begin
// section 127-0-1-1--6c558cad:134e707436b:-8000:0000000000000868-constants end

/**
 * Short description of class taoCoding_actions_Coding
 *
 * @access public
 * @author Jehan Bihin
 * @package taoCoding
 * @subpackage actions
 */
class taoCoding_actions_Coding
    extends taoCoding_actions_common_module
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Jehan Bihin
     * @return taoCoding_actions_Coding
     */
    public function __construct()
    {
        $returnValue = null;

        // section 127-0-1-1-63ffd2c2:134ea6bf40e:-8000:0000000000003987 begin
		parent::__construct();
		$this->service = taoCoding_models_classes_CodingService::singleton();
        // section 127-0-1-1-63ffd2c2:134ea6bf40e:-8000:0000000000003987 end

        return $returnValue;
    }

    /**
     * abstract function to implement, never used
     *
     * @access public
     * @author Jehan Bihin
     * @return core_kernel_classes_Class
     */
    public function getRootClass()
    {
        $returnValue = null;

        // section 127-0-1-1-63ffd2c2:134ea6bf40e:-8000:000000000000398E begin
		return $this->service->getRootClass();
        // section 127-0-1-1-63ffd2c2:134ea6bf40e:-8000:000000000000398E end

        return $returnValue;
    }

    /**
     * Short description of method index
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function index()
    {
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A8F begin
		//tao_helpers_Scriptloader::addCssFiles();
		//tao_helpers_Scriptloader::addJsFiles();
		$this->setData('deliveriestoEval', $this->service->getDeliveriesToGrade());
		$this->setView("index.tpl");
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A8F end
    }

    /**
     * Short description of method openDelivery
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function openDelivery()
    {
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000BB8 begin
        $delivery = $this->getCurrentDelivery();
        $grader = tao_models_classes_UserService::singleton()->getCurrentUser();
        $filtersetting = $this->getCurrentFilterSetting();
        
        $drToOpen = null;
		
        //get already graded deliveries
		$graded = taoCoding_models_classes_GradingService::singleton()->getOpenDeliveryResults($delivery, $grader, $this->getCurrentFilterSetting(), true);
		$deliveryResultsList = array();
		foreach ($graded as $uri => $state) {
			$dr = new core_kernel_classes_Resource($uri);
			$deliveryResultsList[] = array(
				'id' => $dr->getUri(),
				'label' => $this->service->getAnonymizedLabel($dr),
				'status' => $state
			);
			if (is_null($drToOpen) && $state == 1) {
				$drToOpen = $dr;
			}
		}
		
		//every graded deliveries are completed, get a random new one
		if (is_null($drToOpen)) {
	         $drToOpen = taoCoding_models_classes_GradingService::singleton()->openRandomDeliveryResult($delivery, $grader, $filtersetting);
		}
		
		// no new deliveries available, show last completed delivery
        if (is_null($drToOpen)) {
        	if (count($graded) > 0) {
        		$keys = array_keys($graded);
        		$drToOpen = new core_kernel_classes_Resource(array_pop($keys));
        	} else {
        		throw new common_Exception('Delivery is empty');
        	}
        }
		$resultData = $this->buildResultData($delivery, $drToOpen, $this->getCurrentFilterSetting());
		
		$resultData['deliveryResultsList'] = $deliveryResultsList;

		echo json_encode($resultData);
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000BB8 end
    }

    /**
     * Short description of method getResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function getResult()
    {
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A7C begin
		$dr = $this->getCurrentDeliveryResult();
		$delivery = $this->getCurrentDelivery();

		$resultData = $this->buildResultData($delivery, $dr, $this->getCurrentFilterSetting());

		echo json_encode($resultData);
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A7C end
    }

    /**
     * Short description of method getNextResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function getNextResult()
    {
        // section 127-0-1-1-45086601:1359b15e667:-8000:0000000000000AA0 begin
        $delivery = $this->getCurrentDelivery();
        $filter		= $this->getCurrentFilterSetting();
        $grader		= tao_models_classes_UserService::singleton()->getCurrentUser();
        
        $dr = taoCoding_models_classes_GradingService::singleton()->openRandomDeliveryResult($delivery, $grader, $filter);
        if (!is_null($dr)) {
			$resultData = $this->buildResultData($delivery, $dr, $this->getCurrentFilterSetting());
        } else {
        	$resultData = array('success' => false, 'msg' => __('No more Exams to grade'));
        }

		echo json_encode($resultData);
        // section 127-0-1-1-45086601:1359b15e667:-8000:0000000000000AA0 end
    }

    /**
     * Short description of method gradeResponses
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function gradeResponses()
    {
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A7E begin
		if (!$this->hasRequestParameter('gradeid') || !$this->hasRequestParameter('gradevalue')) {
			throw new common_exception_Error('Missing Argument Exception');
		}
		$gradeId	= $this->getRequestParameter('gradeid');
		$gradeValue = $this->getRequestParameter('gradevalue');
		
		if ($this->hasRequestParameter('responses')) {
			$responseIds = json_decode($this->getRequestParameter('responses')); 
		} else {
			common_Logger::w('Missing parameter responses, trying to determin manualy');
			$item = $this->service->getItemByActivityExecution($this->getCurrentActivityExecution());
			$responseIds = array();
			$qtiItem = taoQTI_models_classes_QTI_Service::singleton()->getDataItemByRdfItem($item);
			$rp = $qtiItem->getResponseProcessing();
			foreach ($qtiItem->getInteractions() as $interaction) {
				if ($rp instanceof taoQTI_models_classes_QTI_response_Composite
					&& $rp->getInteractionResponseProcessing($interaction->getResponse())->getOutcome()->getIdentifier() == $gradeId) {
					$responseIds[] = $interaction->getResponse()->getIdentifier();
				}
			}
		}
		
		$answers = array();
		foreach ($responseIds as $responseid) {
			$answer = $this->service->getAnswer($this->getCurrentActivityExecution(), $responseid);
			if (!is_null($answer)) {
				$answers[] = $answer;
			}
		}
		
		taoCoding_models_classes_GradingService::singleton()->setGrade(
			$this->getCurrentDeliveryResult(),
			$this->getCurrentActivityExecution(),
			$gradeId,
			tao_models_classes_UserService::singleton()->getCurrentUser(),
			$gradeValue,
			$answers
		);
		echo json_encode(array(
			'success'	=> true
		));
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A7E end
    }

    /**
     * Short description of method saveComment
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function saveComment()
    {
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A83 begin
		if (!$this->hasRequestParameter('gradeid') || !$this->hasRequestParameter('comment')) {
			throw new common_exception_Error('Missing Argument Exception');
		}
		
		$gradeId	= $this->getRequestParameter('gradeid');
		$comment	= $this->getRequestParameter('comment');
		$user		= tao_models_classes_UserService::singleton()->getCurrentUser();
		
		$grade = taoCoding_models_classes_GradingService::singleton()->getGradeByOrigin(
			$this->getCurrentActivityExecution(),
			$user,
			$gradeId
		);
		
		if (is_null($grade)) {
			throw new common_Exception('No grade found to comment');
		}
		
		$this->service->saveComment(
			$grade,
			$user,
			$comment
		);
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A83 end
    }

    /**
     * Short description of method openItem
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function openItem()
    {
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A8B begin
		echo json_encode($this->buildItemData($this->getCurrentActivityExecution()));
        // section 127-0-1-1-76f61471:135516056cb:-8000:0000000000000A8B end
    }

    /**
     * Short description of method buildResultData
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @param  Resource deliveryResult
     * @param  Configuration filter
     * @return array
     */
    protected function buildResultData( core_kernel_classes_Resource $delivery,  core_kernel_classes_Resource $deliveryResult,  taoCoding_models_classes_filter_Configuration $filter)
    {
        $returnValue = array();

        // section 127-0-1-1-45086601:1359b15e667:-8000:0000000000000AA5 begin
		//@todo test if anonimisation is required else use testtaker name + id
		$label = $this->service->getAnonymizedLabel($deliveryResult);

		$itemsList = $this->service->getItemList($deliveryResult, $filter);
		if (count($itemsList) > 0) {
			$keys = array_keys($itemsList[0]['items']);
			$first = $itemsList[0]['items'][array_shift($keys)];
			$activity = new core_kernel_classes_Resource($first['itemid']);
		}

		$returnValue = array(
			'success'	=> true,
			'result_id' => $deliveryResult->getUri(),
			'itemsList' => $itemsList,
			'testTakerLabel' => $label,
			'firstItem' => isset($first) ? $this->buildItemData($activity) : null
		);

        // section 127-0-1-1-45086601:1359b15e667:-8000:0000000000000AA5 end

        return (array) $returnValue;
    }

    /**
     * builds the data the client requires to
     * represent the item including answers and metainfo
     * if no itemid is provided, the item is found via the activity
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @param  Resource item
     * @return array
     */
    protected function buildItemData( core_kernel_classes_Resource $activityExecution,  core_kernel_classes_Resource $item = null)
    {
        $returnValue = array();

        // section 127-0-1-1-414af346:1355c378850:-8000:0000000000000A88 begin
        if (is_null($item)) {
        	$item = $this->service->getItemByActivityExecution($activityExecution);
        }
        $gradingservice = taoCoding_models_classes_GradingService::singleton();
        
        $answeredValues = $this->service->getAnsweredValues($activityExecution);
        $grader = tao_models_classes_UserService::singleton()->getCurrentUser();
        
        $grades = $gradingservice->getGradeDataForGrader($grader, $activityExecution);

		$model = $item->getUniquePropertyValue(new core_kernel_classes_Property(TAO_ITEM_MODEL_PROPERTY));
		$type = $model->getUri();

		if ($type == TAO_ITEM_MODEL_QTI) {
			$qtiItem = taoQTI_models_classes_QTI_Service::singleton()->getDataItemByRdfItem($item);
			if (!is_null($qtiItem)) {

				$rp = $qtiItem->getResponseProcessing();
				if ($rp instanceof taoQTI_models_classes_QTI_response_Composite) {
					$measurements = taoItems_models_classes_ItemsService::singleton()->getItemMeasurements($item);
				}
				$interactions = array();
				foreach ($qtiItem->getInteractions() as $interaction) {
					if ($interaction->canRenderTesttakerResponse()) {
						$responseIdentifier = $interaction->getResponse()->getIdentifier();

						//correct
						$correct = $interaction->getResponse()->getCorrectResponses();
						foreach ($correct as $key => $val) {
							$correct[$key] = nl2br($val);
						}

						//answer
						$answer = isset($answeredValues[$responseIdentifier]) ? $answeredValues[$responseIdentifier] : array();

						// scale & guidelines
						$scalearr = array();
						if ($rp instanceof taoQTI_models_classes_QTI_response_Composite) {
							$oid = $rp->getInteractionResponseProcessing($interaction->getResponse())->getOutcome()->getIdentifier();
							if (isset($measurements[$oid])) {
								$scale = $measurements[$oid]->getScale();
								if (!is_null($scale)) {
									$scalearr = array('type' => $scale->getClassUri());
									switch ($scale->getClassUri()) {
										case (taoItems_models_classes_Scale_Discrete::CLASS_URI) :
											$scalearr['min']	= floatval($scale->lowerBound);
											$scalearr['max']	= floatval($scale->upperBound);
											$scalearr['step']	= floatval($scale->distance);
											break;
										default:
											common_Logger::w('unknown scale '.$scale->getClassUri().' for measurement '.$oid);
									}
								} else {
									common_Logger::w('no scale for measurement of outcome '.$oid);
								}
								$interactionData = array(
									'id' 			=> $oid,
									'renderedView'	=> $interaction->renderTesttakerResponseXHTML($answer),
									'correct'		=> $correct,
									'guidelines'	=> nl2br($measurements[$oid]->getDescription()),
									'scale'			=> $scalearr,
									'responses'		=> array($interaction->getResponse()->getIdentifier()),
									/*
									'measurements'		=> array(
										'id'			=> $oid,
										'scale'			=> $scalearr,
										'guidelines'	=> nl2br($measurements[$oid]->getDescription()),
									)
									*/
								);
								if (isset($grades[$oid])) {
									$interactionData['score']	= $grades[$oid]['val'];
									$interactionData['comment']	= $grades[$oid]['comment']; 
								} else {
									$interactionData['comment']	= '';
								}
								$interactions[] = $interactionData;
							} else {
								common_Logger::w('no measurement for outcome '.$oid);
							}
						}

					}
				}
				$content = array(
					'interactions' => $interactions
				);
			} else {
				$content = array();
				common_Logger::w('QTI item '.$itemuri.' could not be loaded', array('CODING', 'QTI'));
			}
		} else {
			$content = array();
			common_Logger::w('Tried to manualy evaluate unsuported item '.$uri, array('CODING'));
		}

		$returnValue = array(
			'itemid' => $activityExecution->getUri(),
			'label' => $item->getLabel(),
			'type' => $type,
			'content' => $content,
			'previewURL' => ROOT_URL.'/taoItems/PreviewApi/runner?uri='.tao_helpers_Uri::encode($item->getUri(), true).'&classUri='.tao_helpers_Uri::encode($type, true).'&context=&match=client&');

        // section 127-0-1-1-414af346:1355c378850:-8000:0000000000000A88 end

        return (array) $returnValue;
    }

} /* end of class taoCoding_actions_Coding */

?>