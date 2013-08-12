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
 * Copyright (c) 2013 (original work) Open Assessment Techonologies SA (under the project TAO-PRODUCT);
 *               
 * 
 */
?>
<?php

error_reporting(E_ALL);

/**
 * This exception is used by the server-sided evaluation of the Matching rule.
 * exitResponse is encountered no further rules should be executed.
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_Matching
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1-6f9e545f:134ec499acb:-8000:000000000000358C-includes begin
// section 127-0-1-1-6f9e545f:134ec499acb:-8000:000000000000358C-includes end

/* user defined constants */
// section 127-0-1-1-6f9e545f:134ec499acb:-8000:000000000000358C-constants begin
// section 127-0-1-1-6f9e545f:134ec499acb:-8000:000000000000358C-constants end

/**
 * This exception is used by the server-sided evaluation of the Matching rule.
 * exitResponse is encountered no further rules should be executed.
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_Matching
 */
class taoQTI_models_classes_Matching_ExitResponseException
    extends common_Exception
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method getSeverity
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return int
     */
    public function getSeverity()
    {
        $returnValue = (int) 0;

        // section 127-0-1-1-6f9e545f:134ec499acb:-8000:0000000000003590 begin
        $returnValue = common_Logger::TRACE_LEVEL;
        // section 127-0-1-1-6f9e545f:134ec499acb:-8000:0000000000003590 end

        return (int) $returnValue;
    }

} /* end of class taoQTI_models_classes_Matching_ExitResponseException */

?>