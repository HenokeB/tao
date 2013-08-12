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
 * TAO -
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 27.01.2012, 11:42:41 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoQTI_models_classes_QTI_ParsingException
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/class.ParsingException.php');

/* user defined includes */
// section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064C8-includes begin
// section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064C8-includes end

/* user defined constants */
// section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064C8-constants begin
// section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064C8-constants end

/**
 * Short description of class
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */
class taoQTI_models_classes_QTI_UnexpectedResponseProcessingException
    extends taoQTI_models_classes_QTI_ParsingException
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

        // section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064CA begin
        $returnValue = common_Logger::TRACE_LEVEL;
        // section 127-0-1-1-742be356:1351eb93b2d:-8000:00000000000064CA end

        return (int) $returnValue;
    }

} /* end of class taoQTI_models_classes_QTI_UnexpectedResponseProcessingException */

?>