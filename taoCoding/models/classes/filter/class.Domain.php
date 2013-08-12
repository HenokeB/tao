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
 * Defines the properties and possible values a filter can take
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
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AAE-includes begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AAE-includes end

/* user defined constants */
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AAE-constants begin
// section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AAE-constants end

/**
 * Defines the properties and possible values a filter can take
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */
class taoCoding_models_classes_filter_Domain
{
    // --- ASSOCIATIONS ---
    // generateAssociationEnd :     // generateAssociationEnd : 

    // --- ATTRIBUTES ---

    /**
     * Short description of attribute properties
     *
     * @access private
     * @var array
     */
    private $properties = array();

    // --- OPERATIONS ---

    /**
     * Short description of method hasProperty
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @return Boolean
     */
    public function hasProperty( core_kernel_classes_Resource $property)
    {
        $returnValue = null;

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AF2 begin
        $returnValue = isset($this->properties[$property->getUri()]);
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AF2 end

        return $returnValue;
    }

    /**
     * Short description of method addProperty
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @param  PropertyOptions options
     * @return mixed
     */
    public function addProperty( core_kernel_classes_Resource $property,  taoCoding_models_classes_filter_PropertyOptions $options)
    {
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AB5 begin
        $this->properties[$property->getUri()] = array(
        	'property'	=> $property,
        	'options'	=> $options
        );
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AB5 end
    }

    /**
     * Short description of method removeProperty
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @return mixed
     */
    public function removeProperty( core_kernel_classes_Resource $property)
    {
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AB9 begin
        unset($this->properties[$property->getUri()]);
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AB9 end
    }

    /**
     * Short description of method getPropertyOptions
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @return taoCoding_models_classes_filter_PropertyOptions
     */
    public function getPropertyOptions( core_kernel_classes_Resource $property)
    {
        $returnValue = null;

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AF5 begin
        if ($this->hasProperty($property)) {
        	$returnValue = $this->properties[$property->getUri()]['options'];
        } else {
        	common_Logger::w('Tried to remove nonpresent property '.$property.' from filter');
        }
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AF5 end

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

        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC0 begin
        foreach ($this->properties as $p) {
        	$property = $p['property'];
        	$returnValue[] = array(
        		'id'		=> $property->getUri(),
        		'label'		=> $property->getLabel(),
        		'values'	=> $p['options']->toJSONArray()
        	);
        }
        // section 127-0-1-1--9a19b46:135a440c85f:-8000:0000000000000AC0 end

        return (array) $returnValue;
    }

} /* end of class taoCoding_models_classes_filter_Domain */

?>