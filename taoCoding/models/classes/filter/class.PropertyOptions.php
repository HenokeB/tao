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
 * tao - taoCoding/models/classes/filter/class.PropertyOptions.php
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
 * Defines the properties and possible values a filter can take
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoCoding/models/classes/filter/class.Domain.php');

/* user defined includes */
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC4-includes begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC4-includes end

/* user defined constants */
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC4-constants begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC4-constants end

/**
 * Short description of class taoCoding_models_classes_filter_PropertyOptions
 *
 * @abstract
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */
abstract class taoCoding_models_classes_filter_PropertyOptions
{
    // --- ASSOCIATIONS ---
    // generateAssociationEnd :     // generateAssociationEnd : 

    // --- ATTRIBUTES ---

    /**
     * Short description of attribute values
     *
     * @access private
     * @var array
     */
    private $values = array();

    // --- OPERATIONS ---

    /**
     * Short description of method hasOption
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  string key
     * @return Boolean
     */
    public function hasOption($key)
    {
        $returnValue = null;

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AD4 begin
        $returnValue = isset($this->values[$id]);
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AD4 end

        return $returnValue;
    }

    /**
     * Short description of method toJSONArray
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    public function toJSONArray()
    {
        $returnValue = array();

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000B02 begin
        foreach ($this->values as $key => $value) {
        	$returnValue[] = array(
        		'id'	=> $key,
        		'label'	=> $value
        	);
        }
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000B02 end

        return (array) $returnValue;
    }

    /**
     * Short description of method addOption
     *
     * @access protected
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  string key
     * @param  string value
     * @return mixed
     */
    protected function addOption($key, $value)
    {
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF1 begin
        $this->values[$key] = $value;
        // section 127-0-1-1--4775bb88:135a5422f47:-8000:0000000000000AF1 end
    }

} /* end of abstract class taoCoding_models_classes_filter_PropertyOptions */

?>