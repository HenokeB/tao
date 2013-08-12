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
 * The response processing of a single interaction
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response_interactionResponseProcessing
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * The QTI_Data class represent the abstract model for all the QTI objects.
 * It contains all the attributes of the different kind of QTI objects.
 * It manages the identifiers and serial creation.
 * It provides the serialisation and persistance methods.
 * And give the interface for the rendering.
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/class.Data.php');

/**
 * include taoQTI_models_classes_QTI_response_Composite
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/class.Composite.php');

/**
 * include taoQTI_models_classes_QTI_response_Rule
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/interface.Rule.php');

/* user defined includes */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000003597-includes begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000003597-includes end

/* user defined constants */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000003597-constants begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000003597-constants end

/**
 * The response processing of a single interaction
 *
 * @abstract
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response_interactionResponseProcessing
 */
abstract class taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing
    extends taoQTI_models_classes_QTI_Data
        implements taoQTI_models_classes_QTI_response_Rule
{
    // --- ASSOCIATIONS ---
    // generateAssociationEnd : 

    // --- ATTRIBUTES ---

    /**
     * Short description of attribute SCORE_PREFIX
     *
     * @access private
     * @var string
     */
    const SCORE_PREFIX = 'SCORE_';

    /**
     * Short description of attribute response
     *
     * @access public
     * @var Response
     */
    public $response = null;

    /**
     * Short description of attribute outcome
     *
     * @access public
     * @var Outcome
     */
    public $outcome = null;

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
        throw new common_Exception('Missing getRule implementation for '.get_class($this), array('TAOITEMS', 'QTI', 'HARD'));
        // section 127-0-1-1-3397f61e:12c15e8566c:-8000:0000000000002AFF end

        return (string) $returnValue;
    }

    /**
     * Short description of method create
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  int classID
     * @param  Response response
     * @param  Item item
     * @return taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing
     */
    public static function create($classID,  taoQTI_models_classes_QTI_Response $response,  taoQTI_models_classes_QTI_Item $item)
    {
        $returnValue = null;

        // section 127-0-1-1--409b13b8:1352f8ed821:-8000:00000000000036A0 begin
        switch ($classID) {
        	case taoQTI_models_classes_QTI_response_interactionResponseProcessing_None::CLASS_ID : 
        		$className = "taoQTI_models_classes_QTI_response_interactionResponseProcessing_None";
        		break;
        	case taoQTI_models_classes_QTI_response_interactionResponseProcessing_MatchCorrectTemplate::CLASS_ID : 
        		$className = "taoQTI_models_classes_QTI_response_interactionResponseProcessing_MatchCorrectTemplate";
        		break;
        	case taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponseTemplate::CLASS_ID : 
        		$className = "taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponseTemplate";
        		break;
        	case taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponsePointTemplate::CLASS_ID : 
        		$className = "taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponsePointTemplate";
        		break;
        	case taoQTI_models_classes_QTI_response_interactionResponseProcessing_Custom::CLASS_ID : 
        		$className = "taoQTI_models_classes_QTI_response_interactionResponseProcessing_Custom";
        		break;
        	default :
        		throw new common_exception_Error('Unknown InteractionResponseProcessing Class ID "'.$classID.'"');
        }
        $outcome = self::generateOutcomeDefinition();
        $outcomes = $item->getOutcomes();
        $outcomes[] = $outcome;
        $item->setOutcomes($outcomes);
        $returnValue = new $className($response, $outcome);
        // section 127-0-1-1--409b13b8:1352f8ed821:-8000:00000000000036A0 end

        return $returnValue;
    }

    /**
     * Short description of method generateOutcomeDefinition
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return taoQTI_models_classes_QTI_Outcome
     */
    public static function generateOutcomeDefinition()
    {
        $returnValue = null;

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:0000000000003623 begin
        $returnValue = new taoQTI_models_classes_QTI_Outcome(null, array('baseType' => 'integer', 'cardinality' => 'single'));
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:0000000000003623 end

        return $returnValue;
    }

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @param  Response response
     * @param  Outcome outcome
     * @return mixed
     */
    public function __construct( taoQTI_models_classes_QTI_Response $response,  taoQTI_models_classes_QTI_Outcome $outcome)
    {
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035E5 begin
        $this->response = $response;
        $this->outcome = $outcome;
        parent::__construct();
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035E5 end
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
        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003745 begin
        $this->outcome->destroy();
        parent::destroy();
        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003745 end
    }

    /**
     * Short description of method getResponse
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return taoQTI_models_classes_QTI_Interaction
     */
    public function getResponse()
    {
        $returnValue = null;

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035FE begin
        $returnValue = $this->response;
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035FE end

        return $returnValue;
    }

    /**
     * Short description of method getOutcome
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return taoQTI_models_classes_QTI_Outcome
     */
    public function getOutcome()
    {
        $returnValue = null;

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035FC begin
        $returnValue = $this->outcome;
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:00000000000035FC end

        return $returnValue;
    }

    /**
     * Short description of method getIdentifier
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function getIdentifier()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003747 begin
        $returnValue = $this->getResponse()->getIdentifier().'_rp';
        // section 127-0-1-1-40168e54:135573066b9:-8000:0000000000003747 end

        return (string) $returnValue;
    }

} /* end of abstract class taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing */

?>