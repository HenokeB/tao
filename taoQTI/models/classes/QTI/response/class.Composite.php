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
 * TAO - taoQTI/models/classes/QTI/response/class.Composite.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 20.03.2012, 16:31:57 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoQTI_models_classes_QTI_response_ResponseProcessing
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/class.ResponseProcessing.php');

/**
 * The response processing of a single interaction
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/interactionResponseProcessing/class.InteractionResponseProcessing.php');

/**
 * include taoQTI_models_classes_QTI_response_Rule
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/interface.Rule.php');

/* user defined includes */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009010-includes begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009010-includes end

/* user defined constants */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009010-constants begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009010-constants end

/**
 * Short description of class taoQTI_models_classes_QTI_response_Composite
 *
 * @abstract
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response
 */
abstract class taoQTI_models_classes_QTI_response_Composite
    extends taoQTI_models_classes_QTI_response_ResponseProcessing
        implements taoQTI_models_classes_QTI_response_Rule
{
    // --- ASSOCIATIONS ---
    // generateAssociationEnd : 

    // --- ATTRIBUTES ---

    /**
     * Short description of attribute components
     *
     * @access protected
     * @var array
     */
    protected $components = array();

    /**
     * Short description of attribute outcomeIdentifier
     *
     * @access protected
     * @var string
     */
    protected $outcomeIdentifier = '';

    // --- OPERATIONS ---

    /**
     * Short description of method getRule
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function getRule()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-3397f61e:12c15e8566c:-8000:0000000000002AFF begin
		foreach ($this->components as $irp) {
        	$returnValue .= $irp->getRule();
        }
		foreach ($this->getCompositionRules() as $rule) {
			$returnValue .= $rule->getRule();
		}
        // section 127-0-1-1-3397f61e:12c15e8566c:-8000:0000000000002AFF end

        return (string) $returnValue;
    }

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Item item
     * @param  string outcomeIdentifier
     * @return mixed
     */
    public function __construct( taoQTI_models_classes_QTI_Item $item, $outcomeIdentifier = 'SCORE')
    {
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003671 begin
        parent::__construct();
        $this->outcomeIdentifier = $outcomeIdentifier;
		$outcomeExists = false;
        foreach ($item->getOutcomes() as $outcome) {
        	if ($outcome->getIdentifier() == $outcomeIdentifier) {
        		$outcomeExists = true;
        		break;
        	}
        }
        if (!$outcomeExists) {
        	$outcomes = $item->getOutcomes();
        	$outcomes[] = new taoQTI_models_classes_QTI_Outcome($outcomeIdentifier, array('baseType' => 'integer', 'cardinality' => 'single'));
        	$item->setOutcomes($outcomes);
        }
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003671 end
    }

    /**
     * Short description of method create
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Item item
     * @return taoQTI_models_classes_QTI_response_ResponseProcessing
     */
    public static function create( taoQTI_models_classes_QTI_Item $item)
    {
        $returnValue = null;

        // section 127-0-1-1-6f11fd4b:1350ab5145f:-8000:0000000000003612 begin
        $returnValue = new taoQTI_models_classes_QTI_response_Summation($item);
        foreach ($item->getInteractions() as $interaction) {
        	$irp = taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing::create(
        		taoQTI_models_classes_QTI_response_interactionResponseProcessing_None::CLASS_ID
        		, $interaction->getResponse()
        		, $item
        	);
			$returnValue->add($irp, $item);
        }
        // section 127-0-1-1-6f11fd4b:1350ab5145f:-8000:0000000000003612 end

        return $returnValue;
    }

    /**
     * Short description of method takeOverFrom
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  ResponseProcessing responseProcessing
     * @param  Item item
     * @return taoQTI_models_classes_QTI_response_Composite
     */
    public static function takeOverFrom( taoQTI_models_classes_QTI_response_ResponseProcessing $responseProcessing,  taoQTI_models_classes_QTI_Item $item)
    {
        $returnValue = null;

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035DC begin
        if ($responseProcessing instanceof static) {
	        // already good
        	$returnValue = $responseProcessing;
        } elseif ($responseProcessing instanceof taoQTI_models_classes_QTI_response_Template) {
	        // IMS Template
        	$rp = new taoQTI_models_classes_QTI_response_Summation($item, 'SCORE');
        	foreach ($item->getInteractions() as $interaction) {
        		$response = $interaction->getResponse();
        		try {
	        		$irp = taoQTI_models_classes_QTI_response_interactionResponseProcessing_Template::createByTemplate(
	        			$responseProcessing->getUri(), $response, $item);
        		} catch (Exception $e) {
        			$rp->destroy();
        			throw new taoQTI_models_classes_QTI_response_TakeoverFailedException();
        		}
        		$rp->add($irp, $item);
        	}
        	$returnValue = $rp;
        } elseif ($responseProcessing instanceof taoQTI_models_classes_QTI_response_TemplatesDriven) {
	        // TemplateDriven
        	$rp = new taoQTI_models_classes_QTI_response_Summation($item, 'SCORE');
        	foreach ($item->getInteractions() as $interaction) {
        		$response = $interaction->getResponse();
        		try {
	        		$irp = taoQTI_models_classes_QTI_response_interactionResponseProcessing_Template::createByTemplate(
	        			$responseProcessing->getTemplate($response)
	        			, $response
	        			, $item
	        		);
        		} catch (Exception $e) {
        			$rp->destroy();
        			throw new taoQTI_models_classes_QTI_response_TakeoverFailedException();
        		}
	        	$rp->add($irp, $item);
        	}
        	$returnValue = $rp;
        } else {
        	common_Logger::d('Composite ResponseProcessing can not takeover from '.get_class($responseProcessing).' yet');
        	throw new taoQTI_models_classes_QTI_response_TakeoverFailedException();
        }
        
	    common_Logger::i('Converted to Composite', array('TAOITEMS', 'QTI'));
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035DC end

        return $returnValue;
    }

    /**
     * Short description of method add
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  InteractionResponseProcessing interactionResponseProcessing
     * @return mixed
     */
    public function add( taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing $interactionResponseProcessing)
    {
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035F6 begin
        $this->components[] = $interactionResponseProcessing;
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035F6 end
    }

    /**
     * Short description of method getInteractionResponseProcessing
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Response response
     * @return taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing
     */
    public function getInteractionResponseProcessing( taoQTI_models_classes_QTI_Response $response)
    {
        $returnValue = null;

        // section 127-0-1-1-6f11fd4b:1350ab5145f:-8000:000000000000362E begin
        foreach ($this->components as $irp) {
        	if ($irp->getResponse() == $response) {
        		$returnValue = $irp;
        		break;
        	}
        }
        if (is_null($returnValue))
       		throw new common_Exception('No interactionResponseProcessing defined for '.$response->getIdentifier());
        // section 127-0-1-1-6f11fd4b:1350ab5145f:-8000:000000000000362E end

        return $returnValue;
    }

    /**
     * Short description of method getIRPByOutcome
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Outcome outcome
     * @return taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing
     */
    public function getIRPByOutcome( taoQTI_models_classes_QTI_Outcome $outcome)
    {
        $returnValue = null;

        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000003BCB begin
        foreach ($this->components as $irp) {
        	if ($irp->getOutcome() == $outcome) {
        		$returnValue = $irp;
        		break;
        	}
        }
        // section 127-0-1-1--28e405a3:1362f0fe41e:-8000:0000000000003BCB end

        return $returnValue;
    }

    /**
     * Short description of method replace
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  InteractionResponseProcessing newInteractionResponseProcessing
     * @return mixed
     */
    public function replace( taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing $newInteractionResponseProcessing)
    {
        // section 127-0-1-1--409b13b8:1352f8ed821:-8000:00000000000036A9 begin
        $oldkey = null;
        foreach ($this->components as $key => $component) {
        	if ($component->getResponse() == $newInteractionResponseProcessing->getResponse()) {
        		$oldkey = $key;
        		break;
        	}
        }
        if (!is_null($oldkey)) {
        	$this->components[$oldkey]->destroy();
        	unset($this->components[$oldkey]);
        } else {
        	common_Logger::w('Component to be replaced not found', array('TAOITEMS', 'QTI'));
        }
        $this->add($newInteractionResponseProcessing);
        // section 127-0-1-1--409b13b8:1352f8ed821:-8000:00000000000036A9 end
    }

    /**
     * Short description of method toQTI
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function toQTI()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:0000000000003626 begin
        $returnValue = "<responseProcessing>";
    	foreach ($this->components as $irp) {
        	$returnValue .= $irp->toQTI();
        }
        $returnValue .= $this->getCompositionQTI();
        $returnValue .= "</responseProcessing>";
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:0000000000003626 end

        return (string) $returnValue;
    }

    /**
     * Short description of method takeNoticeOfAddedInteraction
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Interaction interaction
     * @param  Item item
     * @return mixed
     */
    public function takeNoticeOfAddedInteraction( taoQTI_models_classes_QTI_Interaction $interaction,  taoQTI_models_classes_QTI_Item $item)
    {
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003662 begin
        $irp = taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing::create(
        	taoQTI_models_classes_QTI_response_interactionResponseProcessing_MatchCorrectTemplate::CLASS_ID,
        	$interaction->getResponse(),
        	$item
        );
        $this->add($irp);
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003662 end
    }

    /**
     * Short description of method takeNoticeOfRemovedInteraction
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Interaction interaction
     * @param  Item item
     * @return mixed
     */
    public function takeNoticeOfRemovedInteraction( taoQTI_models_classes_QTI_Interaction $interaction,  taoQTI_models_classes_QTI_Item $item)
    {
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003668 begin
        $irpExisted = false;
        foreach ($this->components as $key => $irp) {
        	if ($irp->getResponse() === $interaction->getResponse()) {
        		unset($this->components[$key]);
        		$irp->destroy();
        		$irpExisted = true;
        		break;
        	}
        }
        if (!$irpExisted) { 
        	common_Logger::w('InstanceResponseProcessing not found for removed interaction '.$interaction->getIdentifier(), array('TAOITEMS', 'QTI'));
        }
        // section 127-0-1-1-53d7bbd:135145c7d03:-8000:0000000000003668 end
    }

    /**
     * Short description of method getForm
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Response response
     * @return tao_helpers_form_Form
     */
    public function getForm( taoQTI_models_classes_QTI_Response $response)
    {
        $returnValue = null;

        // section 127-0-1-1-7fd95e33:1350eecc263:-8000:0000000000003636 begin
        $formContainer = new taoQTI_actions_QTIform_CompositeResponseOptions($this, $response);
        $returnValue = $formContainer->getForm();
        // section 127-0-1-1-7fd95e33:1350eecc263:-8000:0000000000003636 end

        return $returnValue;
    }

    /**
     * Short description of method destroy
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return mixed
     */
    public function destroy()
    {
        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003743 begin
        foreach ($this->components as $component) {
        	$component->destroy();
        }
        parent::destroy();
        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003743 end
    }

    /**
     * Short description of method getCompositionQTI
     *
     * @abstract
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public abstract function getCompositionQTI();

    /**
     * Short description of method getCompositionRules
     *
     * @abstract
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return array
     */
    public abstract function getCompositionRules();

} /* end of abstract class taoQTI_models_classes_QTI_response_Composite */

?>