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
 * tao - taoCoding/actions/common/class.module.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 28.08.2012, 16:39:08 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage actions_common
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include tao_actions_TaoModule
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('tao/actions/class.TaoModule.php');

/* user defined includes */
// section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F0F-includes begin
// section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F0F-includes end

/* user defined constants */
// section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F0F-constants begin
// section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F0F-constants end

/**
 * Short description of class taoCoding_actions_common_module
 *
 * @abstract
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage actions_common
 */
abstract class taoCoding_actions_common_module
    extends tao_actions_TaoModule
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute delivery
     *
     * @access private
     * @var Resource
     */
    private $delivery = null;

    /**
     * Short description of attribute deliveryResult
     *
     * @access public
     * @var Resource
     */
    public $deliveryResult = null;

    /**
     * Short description of attribute activityExecution
     *
     * @access public
     * @var Resource
     */
    public $activityExecution = null;

    // --- OPERATIONS ---

    /**
     * Short description of method getFilter
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function getFilter()
    {
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F13 begin
        $delivery	= $this->getCurrentDelivery();
		$items = taoDelivery_models_classes_DeliveryService::singleton()->getDeliveryItems($delivery);
		
		$filter = taoCoding_models_classes_filter_Service::singleton()->createFilterForResources($items);

		echo json_encode(array(
			'filter' => $filter->toJSONArray()
		));
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F13 end
    }

    /**
     * Short description of method getCurrentDelivery
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return core_kernel_classes_Resource
     */
    protected function getCurrentDelivery()
    {
        $returnValue = null;

        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F15 begin
        if (is_null($this->delivery)) {
	    	if ($this->hasRequestParameter('delivery')) {
				$this->delivery = new core_kernel_classes_Class($this->getRequestParameter('delivery'));
			} else {
				throw new common_exception_Error('Called getCurrentDelivery without parameter delivery');
			}
        }
        $returnValue = $this->delivery;
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F15 end

        return $returnValue;
    }

    /**
     * Short description of method getCurrentDeliveryResult
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return core_kernel_classes_Resource
     */
    protected function getCurrentDeliveryResult()
    {
        $returnValue = null;

        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F17 begin
		if (is_null($this->deliveryResult)) {
	    	if ($this->hasRequestParameter('dr')) {
				$this->deliveryResult = new core_kernel_classes_Resource($this->getRequestParameter('dr'));
			} else {
				throw new common_exception_Error('Called getCurrentDeliveryResult without parameter dr');
			}
		}
        $returnValue = $this->deliveryResult;
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F17 end

        return $returnValue;
    }

    /**
     * Short description of method getCurrentFilterSetting
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return taoCoding_models_classes_filter_Configuration
     */
    protected function getCurrentFilterSetting()
    {
        $returnValue = null;

        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F19 begin
		if ($this->hasRequestParameter('filter')) {
			$returnValue = taoCoding_models_classes_filter_Configuration::fromJSONArray($this->getRequestParameter('filter'));
		} else {
			common_Logger::w('Called getFilterSettings without parameter filter, using default Filter');
			$returnValue = new taoCoding_models_classes_filter_Configuration();
		}
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F19 end

        return $returnValue;
    }

    /**
     * Short description of method getCurrentActivityExecution
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return core_kernel_classes_Resource
     */
    protected function getCurrentActivityExecution()
    {
        $returnValue = null;

        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F1B begin
        if (is_null($this->activityExecution)) {
			if ($this->hasRequestParameter('itemid') && common_Utils::isUri($this->getRequestParameter('itemid'))) {
				$this->activityExecution = new core_kernel_classes_Resource($this->getRequestParameter('itemid'));
			} else {
				throw new common_exception_Error('Called getCurrentActivityExecution without valid parameter itemid');
			}
        }
        $returnValue = $this->activityExecution;
        // section 127-0-1-1-57039e11:13672d5bb4f:-8000:0000000000000F1B end

        return $returnValue;
    }

    /**
     * Short description of method setCurrentDeliveryResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @return mixed
     */
    public function setCurrentDeliveryResult( core_kernel_classes_Resource $deliveryResult)
    {
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C4D begin
        $this->deliveryResult = $deliveryResult;
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C4D end
    }

    /**
     * Short description of method setCurrentActivityExecution
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource activityExecution
     * @return mixed
     */
    public function setCurrentActivityExecution( core_kernel_classes_Resource $activityExecution)
    {
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C50 begin
        $this->activityExecution = $activityExecution;
        // section 127-0-1-1-2333fe0:136e95b071d:-8000:0000000000000C50 end
    }

} /* end of abstract class taoCoding_actions_common_module */

?>