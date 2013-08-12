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
 * TAO - taoItems\models\classes\QTI\class.QTISessionCache.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 18.01.2013, 14:05:26 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Jerome Bogaerts, <jerome.bogaerts@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include common_cache_SessionCache
 *
 * @author Jerome Bogaerts, <jerome.bogaerts@tudor.lu>
 */
require_once('common/cache/class.SessionCache.php');

/* user defined includes */
// section 127-0-1-1--18485ef3:13542665222:-8000:00000000000065AA-includes begin
// section 127-0-1-1--18485ef3:13542665222:-8000:00000000000065AA-includes end

/* user defined constants */
// section 127-0-1-1--18485ef3:13542665222:-8000:00000000000065AA-constants begin
// section 127-0-1-1--18485ef3:13542665222:-8000:00000000000065AA-constants end

/**
 * Short description of class taoQTI_models_classes_QTI_QTISessionCache
 *
 * @access public
 * @author Jerome Bogaerts, <jerome.bogaerts@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */
class taoQTI_models_classes_QTI_QTISessionCache
    extends common_cache_SessionCache
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute SESSION_KEY
     *
     * @access public
     * @var string
     */
    const SESSION_KEY = 'cache_qti';

    // --- OPERATIONS ---

} /* end of class taoQTI_models_classes_QTI_QTISessionCache */

?>