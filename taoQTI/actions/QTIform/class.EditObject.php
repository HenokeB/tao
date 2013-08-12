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
 * TAO - taoQTI/actions/QTIform/class.EditObject.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 29.06.2012, 10:55:58 with ArgoUML PHP module
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Jehan Bihin, <jehan.bihin@tudor.lu>
 * @package taoItems
 * @subpackage actions_QTIform
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This class provide a container for a specific form instance.
 * It's subclasses instanciate a form and it's elements to be used as a
 *
 * @author Jehan Bihin, <jehan.bihin@tudor.lu>
 */
require_once('tao/helpers/form/class.FormContainer.php');

/* user defined includes */
// section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2E-includes begin
// section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2E-includes end

/* user defined constants */
// section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2E-constants begin
// section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2E-constants end

/**
 * Short description of class taoQTI_actions_QTIform_EditObject
 *
 * @access public
 * @author Jehan Bihin, <jehan.bihin@tudor.lu>
 * @package taoItems
 * @subpackage actions_QTIform
 */
class taoQTI_actions_QTIform_EditObject
    extends tao_helpers_form_FormContainer
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute object
     *
     * @access public
     * @var Object
     */
    public $object = null;

    /**
     * Short description of attribute item
     *
     * @access public
     * @var Item
     */
    public $item = null;

    // --- OPERATIONS ---

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Jehan Bihin, <jehan.bihin@tudor.lu>
     * @param  Object object
     * @param  Item item
     * @return mixed
     */
    public function __construct( taoQTI_models_classes_QTI_Object $object,  taoQTI_models_classes_QTI_Item $item)
    {
        // section 127-0-1-1-4f2fac4c:138139660bb:-8000:0000000000003B37 begin
        $this->object = $object;
        $this->item = $item;
        parent::__construct();
        // section 127-0-1-1-4f2fac4c:138139660bb:-8000:0000000000003B37 end
    }

    /**
     * Short description of method initForm
     *
     * @access public
     * @author Jehan Bihin, <jehan.bihin@tudor.lu>
     * @return mixed
     */
    public function initForm()
    {
        // section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2F begin
    $this->form = tao_helpers_form_FormFactory::getForm('EditObjectForm');
		$this->form->setActions(array(), 'bottom');
        // section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B2F end
    }

    /**
     * Short description of method initElements
     *
     * @access public
     * @author Jehan Bihin, <jehan.bihin@tudor.lu>
     * @return mixed
     */
    public function initElements()
    {
        // section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B31 begin
    $elt = tao_helpers_form_FormFactory::getElement('objectSerial', 'Hidden');
		$elt->setValue($this->object->getSerial());
		$this->form->addElement($elt);

    $itemElt = tao_helpers_form_FormFactory::getElement('itemSerial', 'Hidden');
		$itemElt->setValue($this->item->getSerial());
		$this->form->addElement($itemElt);

		$urlElt = tao_helpers_form_FormFactory::getElement('objecturl', 'Textbox');
		$urlElt->setDescription(__('URL'));
		$urlElt->setHelp(__('Required'));
		$urlElt->addValidators(array(
			tao_helpers_form_FormFactory::getValidator('NotEmpty'),
			tao_helpers_form_FormFactory::getValidator('Url')
		));
		$urlElt->setValue($this->object->getOption('data'));
		$this->form->addElement($urlElt);

		$heightElt = tao_helpers_form_FormFactory::getElement('height', 'Textbox');
		$heightElt->setDescription(__('Height'));
		$heightElt->addValidators(array(
			tao_helpers_form_FormFactory::getValidator('Integer')
		));
		$heightElt->setValue($this->object->getOption('height'));
		$this->form->addElement($heightElt);

		$widthElt = tao_helpers_form_FormFactory::getElement('width', 'Textbox');
		$widthElt->setDescription(__('Width'));
		$widthElt->addValidators(array(
			tao_helpers_form_FormFactory::getValidator('Integer')
		));
		$widthElt->setValue($this->object->getOption('width'));
		$this->form->addElement($widthElt);
		/*
		$typeElt = tao_helpers_form_FormFactory::getElement('type', 'Textbox');
		$typeElt->setDescription(__('Type'));
		$this->form->addElement($typeElt);
		*/
        // section 127-0-1-1--451f3cfb:1380fae694a:-8000:0000000000003B31 end
    }

} /* end of class taoQTI_actions_QTIform_EditObject */

?>