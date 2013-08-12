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
 * TAO - taoQTI/models/classes/QTI/response/class.Template.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 19.11.2010, 13:12:49 with ArgoUML PHP module 
 * (last revised $Date: 2008-04-19 08:22:08 +0200 (Sat, 19 Apr 2008) $)
 *
 * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include taoQTI_models_classes_QTI_response_ResponseProcessing
 *
 * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/class.ResponseProcessing.php');

/**
 * include taoQTI_models_classes_QTI_response_Rule
 *
 * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
 */
require_once('taoQTI/models/classes/QTI/response/interface.Rule.php');

/* user defined includes */
// section 127-0-1-1--56c234f4:12a31c89cc3:-8000:00000000000023A1-includes begin
// section 127-0-1-1--56c234f4:12a31c89cc3:-8000:00000000000023A1-includes end

/* user defined constants */
// section 127-0-1-1--56c234f4:12a31c89cc3:-8000:00000000000023A1-constants begin
// section 127-0-1-1--56c234f4:12a31c89cc3:-8000:00000000000023A1-constants end

/**
 * Short description of class taoQTI_models_classes_QTI_response_Template
 *
 * @access public
 * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response
 */
class taoQTI_models_classes_QTI_response_Template
    extends taoQTI_models_classes_QTI_response_ResponseProcessing
        implements taoQTI_models_classes_QTI_response_Rule
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute MATCH_CORRECT
     *
     * @access public
     * @var string
     */
    const MATCH_CORRECT = 'http://www.imsglobal.org/question/qti_v2p0/rptemplates/match_correct';

    /**
     * Short description of attribute MAP_RESPONSE
     *
     * @access public
     * @var string
     */
    const MAP_RESPONSE = 'http://www.imsglobal.org/question/qti_v2p0/rptemplates/map_response';

    /**
     * Short description of attribute MAP_RESPONSE_POINT
     *
     * @access public
     * @var string
     */
    const MAP_RESPONSE_POINT = 'http://www.imsglobal.org/question/qti_v2p0/rptemplates/map_response_point';

    /**
     * Short description of attribute uri
     *
     * @access protected
     * @var string
     */
    protected $uri = '';

    /**
     * Short description of attribute file
     *
     * @access protected
     * @var string
     */
    protected $file = '';

    // --- OPERATIONS ---

    /**
     * Short description of method getRule
     *
     * @access public
     * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
     * @return string
     */
    public function getRule()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-3397f61e:12c15e8566c:-8000:0000000000002AFF begin
        if( $this->uri == self::MATCH_CORRECT ){
            $returnValue = taoQTI_models_classes_Matching_Matching::MATCH_CORRECT;
        }
        else if ( $this->uri == self::MAP_RESPONSE ){
            $returnValue = taoQTI_models_classes_Matching_Matching::MAP_RESPONSE;
        }
        else if ( $this->uri == self::MAP_RESPONSE_POINT ){
            $returnValue = taoQTI_models_classes_Matching_Matching::MAP_RESPONSE_POINT;
        }
             
        // section 127-0-1-1-3397f61e:12c15e8566c:-8000:0000000000002AFF end

        return (string) $returnValue;
    }

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
     * @param  string uri
     * @return mixed
     */
    public function __construct($uri)
    {
        // section 127-0-1-1-5ae00f6b:12a36da0066:-8000:0000000000002426 begin
                
    	if( $uri != self::MATCH_CORRECT && 
    		$uri != self::MAP_RESPONSE && 
    		$uri != self::MAP_RESPONSE_POINT ){
    		throw new common_Exception("Unknown response processing template '$uri'");
    	}
    	$this->uri = $uri;
    	
    	$this->file = ROOT_PATH . '/taoQTI/models/classes/QTI/data/rptemplates/' . basename($this->uri). '.xml';
    	if(!file_exists($this->file)){
    		throw new Exception("Unable to load response processing template {$this->uri} in {$this->file}");
    	}
    	
    	parent::__construct();
    	
        // section 127-0-1-1-5ae00f6b:12a36da0066:-8000:0000000000002426 end
    }

    /**
     * Short description of method toQTI
     *
     * @access public
     * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
     * @return string
     */
    public function toQTI()
    {
        $returnValue = (string) '';

        // section 127-0-1-1--5fc6d28e:12aec61bbe9:-8000:00000000000025A8 begin
        
        $tplRenderer = new taoItems_models_classes_TemplateRenderer(
        		self::getTemplatePath() . '/qti.rptemplate.tpl.php', 
        		array('uri' => $this->uri)
        	);
        $returnValue = $tplRenderer->render();
        
        // section 127-0-1-1--5fc6d28e:12aec61bbe9:-8000:00000000000025A8 end

        return (string) $returnValue;
    }

    /**
     * Short description of method getUri
     *
     * @access public
     * @author Cedric Alfonsi, <cedric.alfonsi@tudor.lu>
     * @return string
     */
    public function getUri()
    {
        $returnValue = (string) '';

        // section 10-13-1-39-a5fad37:12c2fc3729c:-8000:00000000000049CC begin
		$returnValue = $this->uri;
        // section 10-13-1-39-a5fad37:12c2fc3729c:-8000:00000000000049CC end

        return (string) $returnValue;
    }

} /* end of class taoQTI_models_classes_QTI_response_Template */

?>