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
 * tao - taoCoding/models/classes/filter/class.Service.php
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
 * include tao_models_classes_Service
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('tao/models/classes/class.Service.php');

/* user defined includes */
// section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFC-includes begin
// section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFC-includes end

/* user defined constants */
// section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFC-constants begin
// section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFC-constants end

/**
 * Short description of class taoCoding_models_classes_filter_Service
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoCoding
 * @subpackage models_classes_filter
 */
class taoCoding_models_classes_filter_Service
    extends tao_models_classes_Service
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method createFilterForResources
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  array resources
     * @return taoCoding_models_classes_filter_Domain
     */
    public function createFilterForResources($resources)
    {
        $returnValue = null;

        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B11 begin
    	$returnValue  = new taoCoding_models_classes_filter_Domain();

		$facets = array();
		$blacklist = array();
	   	foreach ($resources as $primaryResource) {
			foreach ($primaryResource->getRdfTriples()->getIterator() as $triple) {
				if (!in_array($triple->predicate, $blacklist)) {
					$property = new core_kernel_classes_Property($triple->predicate);
					// known property
					if ($returnValue->hasProperty($property)) {
						$options = $returnValue->getPropertyOptions($property);
						if ($options instanceof taoCoding_models_classes_filter_ResourceOptions) {
							$options->addValue(new core_kernel_classes_Resource($triple->object));
						} else {
							$options->addValue($triple->object);
						}
					// new property
					} else {
						//use it
						if ($this->showPropertyInFilter($property)) {
							if ($property->getRange()->getUri() == RDFS_LITERAL) {
								$options = new taoCoding_models_classes_filter_LiteralOptions();
								$options->addValue($triple->object);
							} else {
								$options = new taoCoding_models_classes_filter_ResourceOptions();
								$options->addValue(new core_kernel_classes_Resource($triple->object));
							}
							$returnValue->addProperty($property, $options);
						// don't use it
						} else {
							$blacklist[] = $property->getUri();
						}
						
					}
				}
			}
	   	}
        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B11 end

        return $returnValue;
    }

    /**
     * Short description of method filterRessources
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  array resources
     * @param  Configuration filter
     * @return array
     */
    public function filterRessources($resources,  taoCoding_models_classes_filter_Configuration $filter)
    {
        $returnValue = array();

        // section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFD begin
        $properties = array();
        foreach ($filter->criteria as $uri => $value) {
        	$properties[] = new core_kernel_classes_Property($uri);
        }
        
        foreach ($resources as $key => $resource) {
        	$props = $resource->getPropertiesValues($properties);
        	$missing = false;
        	foreach ($filter->criteria as $uri => $target) {
        		//if ($uri=="undefined") continue;
			$found = false;
        		if (isset($props[$uri])) {
	        		foreach ($props[$uri] as $r) {
	        			if ($r instanceof core_kernel_classes_Resource) {
	        				if ($r->getUri() == $target) {
	        					$found = true;
	        				}
	        			} else {
	        				if ($r->__toString() == $target) {
	        					$found = true;
	        				}
	        			}
	        		}
        		}
        		if (!$found) {
        			$missing = true;
        		}
        	}
        	if (!$missing) {
        		$returnValue[$key] = $resource;
        	}
        }
        // section 127-0-1-1--4271ee9b:135a5be3c77:-8000:0000000000000AFD end

        return (array) $returnValue;
    }

    /**
     * Short description of method groupBy
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  array resources
     * @param  array properties
     * @return array
     */
    public function groupBy($resources, $properties)
    {
        $returnValue = array();

        // section 127-0-1-1--617ceee1:135a9a1865b:-8000:0000000000000B0F begin
        if (count($properties) > 0) {
        	$property = array_shift($properties);
        	$groups = array();
        	foreach ($ressources as $arr) {
        		$key = isset($arr[$property->getUri()]) ? $arr[$property->getUri()] : null;
        		$groups[$key] = $arr;
        	}
        	$formated = array();
        	foreach ($groups as $group) {
        		$formated[] = $this->groupBy($group, $properties);
        	}
        	$returnValue = array(
        		'id'		=> '',
        		'label'		=> '',
        		'values'	=> $formated
        	);
        } else {
        	$returnValue = $ressources;
        }
        // section 127-0-1-1--617ceee1:135a9a1865b:-8000:0000000000000B0F end

        return (array) $returnValue;
    }

    /**
     * Short description of method showPropertyInFilter
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Resource property
     * @return Boolean
     */
    public function showPropertyInFilter( core_kernel_classes_Resource $property)
    {
        $returnValue = null;

        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B14 begin
    	$userModels	= array_keys(common_ext_ExtensionsManager::singleton()->getUpdatableModels());
        
		$returnValue = false;
		foreach ($property->getRdfTriples()->getIterator() as $triple) {
			if (in_array($triple->modelID, $userModels)) {
				$returnValue = true;
				break;
			}
		}
        // section 127-0-1-1-4faad8c3:135aa857fc3:-8000:0000000000000B14 end

        return $returnValue;
    }

} /* end of class taoCoding_models_classes_filter_Service */

?>