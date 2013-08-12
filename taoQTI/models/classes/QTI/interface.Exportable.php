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
 * By implementing the exportable interface, the object must export it's data to
 * formats defined here.
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1--3f707dcb:12af06fca53:-8000:0000000000004158-includes begin
// section 127-0-1-1--3f707dcb:12af06fca53:-8000:0000000000004158-includes end

/* user defined constants */
// section 127-0-1-1--3f707dcb:12af06fca53:-8000:0000000000004158-constants begin
// section 127-0-1-1--3f707dcb:12af06fca53:-8000:0000000000004158-constants end

/**
 * By implementing the exportable interface, the object must export it's data to
 * formats defined here.
 *
 * @access public
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI
 */
interface taoQTI_models_classes_QTI_Exportable
{


    // --- OPERATIONS ---

    /**
     * Export the data in XHTML format
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return string
     */
    public function toXHTML();

    /**
     * EXport the data in the QTI XML format
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return string
     */
    public function toQTI();

    /**
     * EXport the data into TAO's objects Form
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return tao_helpers_form_xhtml_Form
     */
    public function toForm();

} /* end of interface taoQTI_models_classes_QTI_Exportable */

?>