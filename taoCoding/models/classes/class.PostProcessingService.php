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
 * tao - taoCoding/models/classes/class.PostProcessingService.php
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
// section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C31-includes begin
// section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C31-includes end

/* user defined constants */
// section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C31-constants begin
// section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C31-constants end

/**
 * Short description of class taoCoding_models_classes_PostProcessingService
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes
 */
class taoCoding_models_classes_PostProcessingService
    extends tao_models_classes_Service
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method processDelivery
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource delivery
     * @return mixed
     */
    public function processDelivery( core_kernel_classes_Resource $delivery)
    {
        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C36 begin
    	$drs = taoCoding_models_classes_CodingService::singleton()->getDeliveryResults($delivery);
    	foreach ($drs as $deliveryResult) {
        	$this->processDeliveryResult($deliveryResult);
        }
        $delivery->editPropertyValues(new core_kernel_classes_Property(TAO_DELIVERY_CODINGSTATUS_PROP), TAO_DELIVERY_CODINGSTATUS_COMMITED);
        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C36 end
    }

    /**
     * Short description of method processDeliveryResult
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource deliveryResult
     * @return mixed
     */
    public function processDeliveryResult( core_kernel_classes_Resource $deliveryResult)
    {
        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C33 begin
        //throw new common_Exception('Delivery Result post processing not yet implemented');
        // section 127-0-1-1--1f554305:136e33138a4:-8000:0000000000000C33 end
    }

} /* end of class taoCoding_models_classes_PostProcessingService */

?>