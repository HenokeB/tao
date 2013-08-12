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
 * tao - taoCoding/actions/class.Conciliation.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 25.04.2012, 16:19:51 with ArgoUML PHP module
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
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
// section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B80-includes begin
// section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B80-includes end

/* user defined constants */
// section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B80-constants begin
// section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B80-constants end

/**
 * Short description of class taoCoding_actions_Conciliation
 *
 * @access private
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage actions
 */
class taoCoding_actions_Conciliation
    extends taoCoding_actions_common_module
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

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
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B8D begin
        parent::__construct();
		$this->service = taoCoding_models_classes_CodingService::singleton();
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B8D end
    }

    /**
     * Short description of method getRootClass
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return core_kernel_classes_Resource
     */
    public function getRootClass()
    {
        $returnValue = null;

        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B90 begin
		return $this->service->getRootClass();
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B90 end

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
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B8B begin
        $open = $this->service->getDeliveriesToGrade();
    	$closed = $this->service->getDeliveriesToConciliate();
        $statelist = array();
        foreach ($open as $id => $label) {
        	$statelist[] = array(
        		'id'	=> $id,
        		'label'	=> $label,
        		'closed' => false
        	);
        }
        foreach ($closed as $id => $label) {
        	$statelist[] = array(
        		'id'	=> $id,
        		'label'	=> $label,
        		'closed' => true
        	);
        }
        $this->setData('deliveryList', $statelist);
		$this->setView("finalgrades.tpl");
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B8B end
    }

    /**
     * Short description of method getGradingStatistics
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function getGradingStatistics()
    {
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BCC begin

        //simulate expensiv calculations
        $stats = taoCoding_models_classes_ConciliationService::singleton()->getStatisticsForDelivery($this->getCurrentDelivery());
        $this->setData('min', $stats['min']);
		$this->setData('max', $stats['max']);
		$this->setData('avg', $stats['avg']);
		$allowClose = $stats['min'] >= 1;
		if ($allowClose) {
			if (taoDelivery_models_classes_DeliveryService::singleton()->isDeliveryOpen($this->getCurrentDelivery())) {
				$allowClose = false;
			}
		}
        $this->setData('allowClose', $allowClose);
		$this->setView('gradingstatistics.tpl');
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BCC end
    }

    /**
     * Short description of method closeGrading
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function closeGrading()
    {
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B85 begin
		$delivery = $this->getCurrentDelivery();
		taoCoding_models_classes_ConciliationService::singleton()->processGradedDelivery($delivery);
		$status = $delivery->getUniquePropertyValue(new core_kernel_classes_Property(TAO_DELIVERY_CODINGSTATUS_PROP))->getUri();
        echo json_encode(array(
        	'success' => true,
        	'status'	=> $status
        ));
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B85 end
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
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B89 begin
        $delivery = $this->getCurrentDelivery();
        $grader = tao_models_classes_UserService::singleton()->getCurrentUser();
        $filtersetting = $this->getCurrentFilterSetting();

        $drToOpen = null;

        //get already conciliated deliveries
		$conciliated = taoCoding_models_classes_ConciliationService::singleton()->getOpenDeliveryResults($delivery, $grader, $this->getCurrentFilterSetting(), true);
		$deliveryResultsList = array();
		foreach ($conciliated as $uri => $state) {
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
	         $drToOpen = taoCoding_models_classes_ConciliationService::singleton()->openRandomConflictingDR($delivery);
		}

		// no new deliveries available, show last completed delivery
        if (is_null($drToOpen)) {
        	if (count($conciliated) > 0) {
        		$keys = array_keys($graded);
        		$drToOpen = new core_kernel_classes_Resource(array_pop($keys));
        	} else {
        		throw new common_Exception('Delivery is empty');
        	}
        }
		$this->setCurrentDeliveryResult($drToOpen);


		$itemsList = $this->service->getItemList($drToOpen, $this->getCurrentFilterSetting());
		//@todo find first conflict
		//@todo remove filter dependency
    	if (count($itemsList) > 0) {
			$keys = array_keys($itemsList[0]['items']);
			$first = $itemsList[0]['items'][array_shift($keys)];
			$activityExecution = new core_kernel_classes_Resource($first['itemid']);
		}
		$this->setCurrentActivityExecution($activityExecution);

        $resultData['deliveryResultList']	= $this->buildDeliveryResultListData();
        $resultData['itemList']				= $this->buildItemListData();
        $resultData['item']					= $this->buildItemData();
        $resultData['resultid']				= $this->getCurrentDeliveryResult()->getUri();

		echo json_encode(array(
			'success'				=> true,
        	'deliveryResultList'	=> $this->buildDeliveryResultListData(),
        	'itemList'				=> $this->buildItemListData(),
        	'item'					=> $this->buildItemData(),
        	'resultid'				=> $this->getCurrentDeliveryResult()->getUri()
		));
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B89 end
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
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B81 begin
        $this->getCurrentDelivery();
        /*
    	$resultData = array(
			'result_id' => '123',
			'itemsList' => array(),
			'testTakerLabel' => 'label',
			'firstItem' => null
		);
		*/
		$dr = null;
        if (is_null($dr)) {
			$resultData = $this->getResultData($delivery, $dr, $this->getCurrentFilterSetting());
        } else {
        	$resultData = array('success' => false, 'msg' => __('No more Exams to conciliate'));
        }

		echo json_encode($resultData);
        // section 127-0-1-1-58ee693c:136629bac46:-8000:0000000000000B81 end
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
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BCE begin
        $this->getCurrentDelivery();
        $this->getCurrentDeliveryResult();
        $resultData = array(
			'result_id' => '123',
			'itemsList' => array(),
			'testTakerLabel' => 'label',
			'firstItem' => null
		);

		echo json_encode($resultData);
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BCE end
    }

    /**
     * Short description of method setFinalGrade
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function setFinalGrade()
    {
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BD0 begin
        $this->getCurrentDelivery();
        $this->getCurrentDeliveryResult();
        $this->getCurrentActivityExecution();
        echo json_encode(array('success' => true));
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BD0 end
    }

    /**
     * Short description of method setFinalComment
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function setFinalComment()
    {
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BD2 begin
        $this->getCurrentDelivery();
        $this->getCurrentDeliveryResult();
        $this->getCurrentActivityExecution();
        echo json_encode(array('success' => true));
        // section 127-0-1-1-126e3ec5:13677260e4f:-8000:0000000000000BD2 end
    }

    /**
     * Short description of method buildItemData
     *
     * @access private
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    private function buildItemData()
    {
        $returnValue = array();

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BD5 begin
		$item			= $this->service->getItemByActivityExecution($this->getCurrentActivityExecution());
        $concilicator	= tao_models_classes_UserService::singleton()->getCurrentUser();

        $responseValues = $this->service->getAnsweredValues($this->getCurrentActivityExecution());

		$model = $item->getUniquePropertyValue(new core_kernel_classes_Property(TAO_ITEM_MODEL_PROPERTY));

		if ($model->getUri() == TAO_ITEM_MODEL_QTI) {
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
						$answer = isset($responseValues[$responseIdentifier]) ? $responseValues[$responseIdentifier] : array();

						// scale & guidelines
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
									'guidelines'	=> nl2br($measurements[$oid]->getDescription()),
									'correct'		=> $correct,
									'scale'			=> $scalearr,
									'responses'		=> array($interaction->getResponse()->getIdentifier()),
									'grades'		=> array()
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
			'itemid'	=> $this->getCurrentActivityExecution()->getUri(),
			'label'		=> $item->getLabel(),
			'type'		=> $model->getUri(),
			'content'	=> $content,
			'previewURL' => ROOT_URL.'/taoItems/PreviewApi/runner?uri='.tao_helpers_Uri::encode($item->getUri(), true).'&classUri='.tao_helpers_Uri::encode($model->getUri(), true).'&context=&match=client&');

        // section 127-0-1-1-1a1692bb:13677a86fc9:-8000:0000000000000BD5 end

        return (array) $returnValue;
    }

    /**
     * Short description of method buildItemListData
     *
     * @access private
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    private function buildItemListData()
    {
        $returnValue = array();

        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C42 begin
		$returnValue = $this->service->getItemList($this->getCurrentDeliveryResult(), $this->getCurrentFilterSetting());
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C42 end

        return (array) $returnValue;
    }

    /**
     * Short description of method buildDeliveryResultListData
     *
     * @access private
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    private function buildDeliveryResultListData()
    {
        $returnValue = array();

        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C40 begin
        $delivery = $this->getCurrentDelivery();
        $grader = tao_models_classes_UserService::singleton()->getCurrentUser();

		$conciliated = taoCoding_models_classes_ConciliationService::singleton()->getOpenDeliveryResults($delivery, $grader, $this->getCurrentFilterSetting(), true);
		foreach ($conciliated as $uri => $state) {
			$dr = new core_kernel_classes_Resource($uri);
			$returnValue[] = array(
				'id'		=> $dr->getUri(),
				'label'		=> taoCoding_models_classes_CodingService::singleton()->getAnonymizedLabel($dr),
				'status'	=> $state
			);
		}
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C40 end

        return (array) $returnValue;
    }

} /* end of class taoCoding_actions_Conciliation */

?>