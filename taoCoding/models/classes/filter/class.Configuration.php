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
 * tao - taoCoding/models/classes/filter/class.Configuration.php
 *
 * $Id$
 *
 * This file is part of tao.
 *
 * Automatically generated on 25.04.2012, 11:59:59 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE1-includes begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE1-includes end

/* user defined constants */
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE1-constants begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE1-constants end

/**
 * Short description of class taoCoding_models_classes_filter_Configuration
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */
class taoCoding_models_classes_filter_Configuration
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute criteria
     *
     * @access public
     * @var array
     */
    public $criteria = array();

    /**
     * Short description of attribute groupBy
     *
     * @access public
     * @var array
     */
    public $groupBy = array();

    // --- OPERATIONS ---

    /**
     * Short description of method fromJSONArray
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  array parameters
     * @return taoCoding_models_classes_filter_Configuration
     */
    public static function fromJSONArray($parameters)
    {
        $returnValue = null;

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE5 begin
        //@todo implemnt, this is only an example
        $returnValue = new self();
        if (isset($parameters['restrictions']) && is_array($parameters['restrictions'])) {
        	foreach ($parameters['restrictions'] as $key => $value) {
        		$returnValue->addCriterion(new core_kernel_classes_Property($key), $value);
        	}
        }
        if (isset($parameters['groupBy'])) {
        	$returnValue->addGroupBy(new core_kernel_classes_Property($parameters['groupBy']));
        }
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE5 end

        return $returnValue;
    }

    /**
     * Short description of method addCriterion
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @param  string value
     * @return mixed
     */
    public function addCriterion( core_kernel_classes_Resource $property, $value)
    {
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE7 begin
        $this->criteria[$property->getUri()] = $value;
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AE7 end
    }

    /**
     * Short description of method addGroupBy
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @return mixed
     */
    public function addGroupBy( core_kernel_classes_Resource $property)
    {
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000B00 begin
        $this->groupBy[] = $property;
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000B00 end
    }

    /**
     * Short description of method getHash
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function getHash()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000C19 begin
        $description = '';
        foreach ($this->criteria as $prop => $value) {
        	$description .= $prop.' '.$value.'  ';
        }
        foreach ($this->groupBy as $res) {
        	$description .= $res->getUri().' ';
        }
        $returnValue = md5($description);
        // section 127-0-1-1-7f6733c0:1367cb377e1:-8000:0000000000000C19 end

        return (string) $returnValue;
    }

} /* end of class taoCoding_models_classes_filter_Configuration */

?>