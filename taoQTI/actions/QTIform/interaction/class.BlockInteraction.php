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
 * TAO - taoItems\actions\QTIform\interaction\class.BlockInteraction.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 05.01.2011, 11:32:49 with ArgoUML PHP module 
 * (last revised $Date: 2008-04-19 08:22:08 +0200 (Sat, 19 Apr 2008) $)
 *
 * @author Somsack SIPASSEUTH, <s.sipasseuth@gmail.com>
 * @package taoItems
 * @see http://www.imsglobal.org/question/qti_v2p0/imsqti_infov2p0.html#element10250
 * @subpackage actions_QTIform_interaction
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoQTI_actions_QTIform_interaction_Interaction
 *
 * @author Somsack SIPASSEUTH, <s.sipasseuth@gmail.com>
 * @see http://www.imsglobal.org/question/qti_v2p0/imsqti_infov2p0.html#element10247
 */
require_once('taoQTI/actions/QTIform/interaction/class.Interaction.php');

/* user defined includes */
// section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506A-includes begin
// section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506A-includes end

/* user defined constants */
// section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506A-constants begin
// section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506A-constants end

/**
 * Short description of class
 *
 * @abstract
 * @access public
 * @author Somsack SIPASSEUTH, <s.sipasseuth@gmail.com>
 * @package taoItems
 * @see http://www.imsglobal.org/question/qti_v2p0/imsqti_infov2p0.html#element10250
 * @subpackage actions_QTIform_interaction
 */
abstract class taoQTI_actions_QTIform_interaction_BlockInteraction
    extends taoQTI_actions_QTIform_interaction_Interaction
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method setCommonElements
     *
     * @access public
     * @author Somsack SIPASSEUTH, <s.sipasseuth@gmail.com>
     * @return mixed
     */
    public function setCommonElements()
    {
        // section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506C begin
		
		parent::setCommonElements();
		
		//the prompt field is the interaction's data for a block interaction, that's why the id is data and not 
		$promptElt = tao_helpers_form_FormFactory::getElement('prompt', 'Textarea');//should be an htmlarea... need to solve the conflict with the 
		$promptElt->setAttribute('class', 'qti-html-area');
		$promptElt->setDescription(__('Prompt'));
		$interactionData = taoQTI_helpers_qti_ItemAuthoring::filteredData($this->interaction->getPrompt());
		if(!empty($interactionData)){
			$promptElt->setValue($interactionData);
		}
		$this->form->addElement($promptElt);
		
        // section 10-13-1-39-643eb156:12d51696e7c:-8000:000000000000506C end
    }

} /* end of abstract class taoQTI_actions_QTIform_interaction_BlockInteraction */

?>