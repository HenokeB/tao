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
 * tao - taoCoding/models/classes/filter/class.ResourceOptions.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 25.04.2012, 12:01:19 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoCoding_models_classes_filter_PropertyOptions
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoCoding/models/classes/filter/class.PropertyOptions.php');

/* user defined includes */
// section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF5-includes begin
// section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF5-includes end

/* user defined constants */
// section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF5-constants begin
// section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF5-constants end

/**
 * Short description of class taoCoding_models_classes_filter_ResourceOptions
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */
class taoCoding_models_classes_filter_ResourceOptions
    extends taoCoding_models_classes_filter_PropertyOptions
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method addValue
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource resource
     * @return mixed
     */
    public function addValue( core_kernel_classes_Resource $resource)
    {
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF9 begin
        $this->addOption($resource->getUri(), $resource->getLabel());
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF9 end
    }

} /* end of class taoCoding_models_classes_filter_ResourceOptions */

?>