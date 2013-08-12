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

/**
 * QtiAuthoring Controller provide actions to edit a QTI item
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @package taoQTI
 * @subpackage actions
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 */

class taoQTI_actions_QtiAuthoring extends tao_actions_CommonModule {

	protected $debugMode = false;

	/**
	 * constructor: initialize the service and the default data
	 * @access public
	 */
	public function __construct(){

		parent::__construct();

		$this->debugMode = false;
		$this->qtiService = taoQTI_models_classes_QTI_Service::singleton();
		$this->service = taoQTI_models_classes_QtiAuthoringService::singleton();
		$this->defaultData();

		taoQTI_models_classes_QTI_Data::setPersistence(true);
	}
	
	/**
	 * Returns the current rdf item
	 * 
	 * @access public
	 * @return core_kernel_classes_Resource
	 */
	public function getCurrentItemResource(){

		$itemResource = null;

		if($this->hasRequestParameter('itemUri')){
			$itemResource = new core_kernel_classes_Resource(tao_helpers_Uri::decode($this->getRequestParameter('itemUri')));
		}else{
			throw new Exception('no item rsource uri found');
		}

		return $itemResource;
	}
	
	/**
	 * Get the current QTI item object
	 * Load the QTI object either from the file or from session or create a new one
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_Item 
	 */
	public function getCurrentItem(){

		$item = null;

		$itemUri = tao_helpers_Uri::decode($this->getRequestParameter('instance'));;
		$itemSerial = '';
		$itemIdentifier = tao_helpers_Uri::getUniqueId($itemUri);//TODO: remove coopling to TAO

		//when actions are executed in the authroing tool, retrieve the item with the serial:
		if($this->hasRequestParameter('itemSerial')){
			
			$itemSerial = tao_helpers_Uri::decode($this->getRequestParameter('itemSerial'));
			$item = $this->qtiService->getItemBySerial($itemSerial);
			
		}else{

			//check to allow page reloading without xml file: debug mode on
			$itemResource = null;
			if(!empty($itemUri)){
				$itemResource = new core_kernel_classes_Resource($itemUri);
				$itemSerial = taoQTI_helpers_qti_ItemAuthoring::getAuthoringItem($itemResource);
				if(!empty($itemSerial)){
					$item = $this->qtiService->getItemBySerial($itemSerial);
				}
			}
			
			if(empty($item) && !is_null($itemResource)){

				$item = $this->qtiService->getDataItemByRdfItem($itemResource);//i1282039875024462900

				if(is_null($item)){
					//create a new item object:
					$item = $this->service->createNewItem($itemIdentifier, $itemResource->getLabel());
				}
				
				taoQTI_helpers_qti_ItemAuthoring::setAuthoringItem($itemResource, $item->getSerial());
			}

			if(empty($item)){
				throw new Exception('a new qti item xml cannot be created');
			}
		}

		if(is_null($item)){
			throw new Exception('there is no item');
		}

		return $item;
	}

	/**
	 * Load the main view for the authoring tool
	 * 
	 * @access public
	 */
	public function index(){
		
		//clear the QTI session data before doing anything else:
		$session = PHPSession::singleton();
		taoQTI_models_classes_QTI_QTISessionCache::singleton()->purge();
		$session->removeAttribute(taoQTI_models_classes_QTI_Data::IDENTIFIERS_KEY);
			
		//required for saving the item in tao:
		$itemUri = $this->getRequestParameter('instance');
		$this->setData('itemUri', tao_helpers_Uri::encode($itemUri));

		$itemResource = new core_kernel_classes_Resource($itemUri);
		foreach($itemResource->getTypes() as $itemClass){
			$this->setData('itemClassUri', tao_helpers_Uri::encode((!is_null($itemClass))?$itemClass->getUri():''));
			break;
		}

		$currentItem = $this->getCurrentItem();
		$itemData = $this->service->getItemData($currentItem);//issue here?
		
		$this->setData('itemSerial', $currentItem->getSerial());
		$this->setData('itemForm', $currentItem->toForm()->render());
		$this->setData('itemData', $itemData);
		$this->setData('jsFramework_path', BASE_WWW.'js/jsframework/');
		$this->setData('qtiAuthoring_path', BASE_WWW.'js/qtiAuthoring/');
		$this->setData('qtiAuthoring_img_path', BASE_WWW.'img/qtiAuthoring/');

		if(isset($_GET['STANDALONE_MODE']) && $_GET['STANDALONE_MODE']){
			$this->setData('includedView', DIR_VIEWS . 'templates/' . "QTIAuthoring/authoring.tpl");
			return parent::setView('sas.tpl', true);
		}else{
			$this->setView("QTIAuthoring/authoring.tpl");
		}

	}
	
	/**
	 * Load the main view for the authoring tool
	 * Ajax call only.
	 * 
	 * @access public
	 */
	public function saveItemData(){
		$saved = false;

		$itemData = $this->getPostedItemData();

		if(!empty($itemData)){
			//save to qti:
			$this->service->saveItemData($this->getCurrentItem(), $itemData);
			$saved = true;
		}

		echo json_encode(array(
			'saved'=>$saved
		));
	}
	
	/**
	 * Save the interaction data to session.
	 * Ajax call only.
	 * 
	 * @access public
	 */
	public function saveInteractionData(){
		$saved = false;

		$interactionData = $this->getPostedInteractionData();
		if(!empty($interactionData)){
			$this->service->setInteractionData($this->getCurrentInteraction(), $interactionData);
			$saved = true;
		}

		if(tao_helpers_Request::isAjax()){
			echo json_encode(array(
				'saved'=>$saved
			));
		}else{
			return $saved;
		}
	}
	
	/**
	 * Save the item data and properties to session, then save the complete object to the item's QTI XML file
	 * Ajax call only.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function saveItem(){
		$saved = false;

		$itemData = $this->getPostedItemData();

		$itemObject = $this->getCurrentItem();
		//save item properties in the option array:
		$options = array(
			'title' => $itemObject->getIdentifier(),
			'label' => '',
			'timeDependent' => false,
			'adaptive' => false
		);
		if($this->getRequestParameter('title') != '') $options['title'] = $this->getRequestParameter('title');
		if($this->hasRequestParameter('label')) $options['label'] = $this->getRequestParameter('label');
		if($this->hasRequestParameter('timeDependent')) $options['timeDependent'] = $this->getRequestParameter('timeDependent');
		if($this->hasRequestParameter('adaptive')) $options['adaptive'] = $this->getRequestParameter('adaptive');
		$this->service->setOptions($itemObject, $options);

		if(!empty($itemData)){
			$this->service->saveItemData($itemObject, $itemData);
		}

		$itemResource = $this->getCurrentItemResource();
		$saved = $this->qtiService->saveDataItemToRdfItem($itemObject, $itemResource);

		if(tao_helpers_Request::isAjax()){
			echo json_encode(array(
				'saved' => $saved
			));
		}

		return $saved;
	}

	/**
	 * Display a quick preview of the current item.
	 * This light preview does not provide simulated execution environment required for response evaluation.
	 * 
	 * @access public
	 */
	public function preview(){
		$parameters = array(
			'root_url' 				=> ROOT_URL,
        	'base_www' 				=> BASE_WWW,
        	'taobase_www' 			=> TAOBASE_WWW,
			'delivery_server_mode' 	=> false,
			'raw_preview'			=> true,
			'debug'					=> true,
        	'qti_lib_www'			=> BASE_WWW .'js/QTI/',
			'qti_base_www'			=> BASE_WWW .'js/QTI/'
		);
		taoItems_models_classes_TemplateRenderer::setContext($parameters, 'ctx_');

		$output = $this->qtiService->renderQTIItem($this->getCurrentItem());

		$output = taoQTI_helpers_qti_ItemAuthoring::filteredData($output);

		$this->setData('output', $output);
		$this->setView("QTIAuthoring/preview.tpl");
	}
	
	/**
	 * Dump the entire taoQTI_models_classes_QTI_Item from the session for debugging purpose.
	 * Not suitable for production use.
	 * 
	 * @access public
	 */
	public function debug(){
		$itemObject = $this->getCurrentItem();

		$this->setData('itemObject', $itemObject);
		$this->setData('sessionData', array('not supported'));
		$this->setView("QTIAuthoring/debug.tpl");
	}
	
	
	/**
	 * Prepare data to be saved to the QTI Object model.
	 * It replaces custom tags used in the authoring interface by the QTI object actual placeholders
	 * 
	 * @param taoQTI_models_classes_QTI_Data
	 * @param string
	 * @access protected
	 * @return string
	 */
	protected function filterData(taoQTI_models_classes_QTI_Data $qtiObject, $data){
		
		$pattern = '';
		if($qtiObject instanceof taoQTI_models_classes_QTI_Interaction){
			
			$pattern = '/{{qtiInteraction:'.$qtiObject->getType().':'.$qtiObject->getSerial().'}}/im';
			
		}else if($qtiObject instanceof taoQTI_models_classes_QTI_Choice){
			//hottext (hottext interaction)
			$pattern = '/{{qtiHottext:'.$qtiObject->getSerial().':([^{]*)}}/im';
			
		}else if($qtiObject instanceof taoQTI_models_classes_QTI_Group){
			//gap (gap match interaction)
			$pattern = '/{{qtiGap:'.$qtiObject->getSerial().':([^{]*)}}/';
		}
		
		if(!empty($pattern)){
			$data = preg_replace($pattern, '{'.$qtiObject->getSerial().'}', $data);
		}
		
		return $data;
	}
	
	/**
	 * Returns cleaned posted QTI item data/
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getPostedItemData(){
		
		$returnValue = $_POST['itemData'];
		$item = $this->getCurrentItem();
		//clean the interactions' editing elements:
		foreach($item->getInteractions() as $interaction){
			$returnValue = $this->filterData($interaction, $returnValue);
		}
		//clean the objects' editing elements:
		foreach($item->getObjects() as $object){
			$returnValue = $this->filterData($object, $returnValue);
		}
		
		$returnValue = $this->cleanPostedData($returnValue);
		return $returnValue;
	}
	
	/**
	 * Returns cleaned posted QTI interaction data
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getPostedInteractionData(){
		
		$returnValue = isset($_POST['interactionData'])?$_POST['interactionData']:'';
		$interaction = $this->getCurrentInteraction();
		switch(strtolower($interaction->getType())){
			case 'gapmatch':{
				foreach($interaction->getGroups() as $group){
					$returnValue = $this->filterData($group, $returnValue);
				}
				break;
			}
			case 'hottext':{
				//note for hottext: the chocies of hottext are inline string elements, the order of which are naturally set in the interaction data
				//clean the choices link tags:
				foreach($interaction->getChoices() as $choice){
					$returnValue = $this->filterData($choice, $returnValue);
				}
				break;
			}
		}
		
		$returnValue = $this->cleanPostedData($returnValue);
		return $returnValue;
	}
	
	/**
	 * Returns cleaned posted data
	 * 
	 * @param string
	 * @param boolean
	 * @access protected
	 * @return string
	 */
	protected function getPostedData($key, $required = false){
		$returnValue = '';

		if($this->hasRequestParameter($key)){
			
			$returnValue = $_POST[$key];
			$filterModel = 'blockStatic';
			if($key == 'prompt'){
				$filterModel = 'inlineStatic';
			}
			$returnValue = $this->cleanPostedData($returnValue, $filterModel);
			
		}else{
			if($required){
				throw new Exception('the request data "'.$key.'" cannot be found');
			}
		}

		return $returnValue;
	}
	
	/**
	 * Clean data with a selectable filter model
	 * 
	 * @access protected
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function cleanPostedData($data, $filterModel = 'blockStatic'){
		
		$returnValue = taoQTI_helpers_qti_ItemAuthoring::filteredData($data, $filterModel);

		return $returnValue;
	}
	
	/**
	 * Add a QTI interaction to the current QTI item
	 * 
	 * @access public
	 */
	public function addInteraction(){
		$added = false;
		$interactionSerial = '';

		$interactionType = $this->getRequestParameter('interactionType');
		$itemData = $this->getPostedItemData();

		$item = $this->getCurrentItem();
		if(!empty($interactionType)){
			$interaction = $this->service->addInteraction($item, $interactionType);

			if(!is_null($interaction)){
				//save the itemData, i.e. the location at which the new interaction shall be inserted
				//the location has been marked with {{qtiInteraction:type:new}}
				$itemData = str_ireplace('{{qtiInteraction:'.$interactionType.':new}}', '{'.$interaction->getSerial().'}', $itemData);
				$this->service->saveItemData($item, $itemData);
				$itemData = $this->service->getItemData($item);//do not convert to html entities...

				//everything ok:
				$added = true;
				$interactionSerial = $interaction->getSerial();
			}
		}

		echo json_encode(array(
			'added' => $added,
			'interactionSerial' => $interactionSerial,
			'itemData' => $itemData
		));
	}
	
	/**
	 * Add a hottext to the current QTI interaction
	 * 
	 * @access public
	 */
	public function addHottext(){
		$added = false;
		$choiceSerial = '';//the hot text basically is a "choice"

		$interactionData = $this->getPostedInteractionData();
		$interaction = $this->getCurrentInteraction();

		$choice = $this->service->addChoice($interaction, '', null, null, $interactionData);

		if(!is_null($choice)){
			$interactionData = $this->service->getInteractionData($interaction);//do not convert to html entities...

			//everything ok:
			$added = true;
			$choiceSerial = $choice->getSerial();
		}

		echo json_encode(array(
			'added' => $added,
			'choiceSerial' => $choiceSerial,
			'choiceForm' => $choice->toForm()->render(),
			'interactionData' => $interactionData
		));
	}
	
	/**
	 * Add a choice to the current QTI interaction
	 * 
	 * @access public
	 */
	public function addChoice(){
		$added = false;
		$choiceSerial = '';
		$choiceForm = '';
		$groupSerial = '';

		$interaction = $this->getCurrentInteraction();
		if(!is_null($interaction)){
			try{
				//not null in case of a match or gapmatch interaction:
				$group = null;
				$group = $this->getCurrentGroup();
			}catch(Exception $e){}

			$choice = $this->service->addChoice($interaction, '', null, $group);

			//return id and form:
			if(!is_null($group)) $groupSerial = $group->getSerial();
			$choiceSerial = $choice->getSerial();
			$choiceForm = $choice->toForm()->render();
			$added = true;
		}

		echo json_encode(array(
			'added' => $added,
			'choiceSerial' => $choiceSerial,
			'choiceForm' => $choiceForm,
			'groupSerial' => $groupSerial,
			'reload' => false //@see deprecated function requireChoicesUpdate()
		));
	}

	/**
	 * Delete one or several interactions from the current QTI item
	 * 
	 * @access public
	 */
	public function deleteInteractions(){

		$deleted = false;

		$interactionSerials = array();
		if($this->hasRequestParameter('interactionSerials')){
			$interactionSerials = $this->getRequestParameter('interactionSerials');
		}
		if(empty($interactionSerials)){
			throw new Exception('no interaction ids found to be deleted');
		}else{
			$item = $this->getCurrentItem();
			$deleteCount = 0;

			//delete interactions:
			foreach($interactionSerials as $interactionSerial){
				$interaction = $this->qtiService->getInteractionBySerial($interactionSerial);
				if(!empty($interaction)){
					$this->service->deleteInteraction($item, $interaction);
					$deleteCount++;
				}else{
					throw new Exception('no interaction found to be deleted with the serial: '.$interactionSerial);
				}
			}

			if($deleteCount == count($interactionSerials)){
				$deleted = true;
			}
		}

		echo json_encode(array(
			'deleted' => $deleted
		));

	}
	
	/**
	 * Delete the current choice from the current QTI interaction
	 * 
	 * @access public
	 */
	public function deleteChoice(){
		$interaction = $this->getCurrentInteraction();
		$deleted = false;

		try{
			$choice = null;
			$choice = $this->getCurrentChoice();
		}catch(Exception $e){}
		if(!is_null($interaction) && !is_null($choice)){
			$this->service->deleteChoice($interaction, $choice);
			$deleted = true;
		}

		if(!$deleted){
			try{
				//for gapmatch interaction, where a gorup is considered as a choice:
				$group = null;
				$group = $this->getCurrentGroup();

				if(!is_null($interaction) && !is_null($group)){
					$this->service->deleteGroup($interaction, $group);
					$deleted = true;
				}
			}catch(Exception $e){
				throw new Exception('cannot delete the choice');
			}
		}

		echo json_encode(array(
			'deleted' => $deleted,
			'reload' => false,//@see deprecated requireChoicesUpdate()
			'reloadInteraction' => ($deleted)?$this->requireInteractionUpdate($interaction):false
		));
	}
	
	/**
	 * Tells if all choices need to be updated if one of them has been updated
	 * (deprecated : matchgroup is deprecated in QTI v 2.1)
	 * 
	 * @access protected
	 */
	protected function requireChoicesUpdate(taoQTI_models_classes_QTI_Interaction $interaction){

		$reload = false;

		//basically, interactions that have choices with the "matchgroup" property
		if(!is_null($interaction)){
			switch(strtolower($interaction->getType())){
				case 'associate':
				case 'match':
				case 'gapmatch':
				case 'graphicgapmatch':{
					$reload = true;
					break;
				}
			}
		}

		return $reload;
	}
	
	/**
	 * Tells if an interaction needs to be updated if a choice has been updated
	 * 
	 * @access protected
	 */
	protected function requireInteractionUpdate(taoQTI_models_classes_QTI_Interaction $interaction){

		$reload = false;

		//basically, interactions that need a wysiwyg data editor:
		if($this->getRequestParameter('reloadInteraction')){
			if(!is_null($interaction)){
				switch(strtolower($interaction->getType())){
					case 'hottext':
					case 'gapmatch':{
						$reload = true;
						break;
					}
				}
			}
		}

		return $reload;
	}

	/**
	 * Gives the editing tag for an interaction.
	 * (method to be called to dynamically update the main itemData editor frame)
	 * 
	 * @access public
	 */
	public function getInteractionTag(){
		$interaction = $this->getCurrentInteraction();
		echo $this->service->getInteractionTag($interaction);
	}

	/**
	 * Get the current QTI interaction we are working on
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_Interaction
	 */
	public function getCurrentInteraction(){
		$returnValue = null;
		if($this->hasRequestParameter('interactionSerial')){
			$interaction = $this->qtiService->getInteractionBySerial($this->getRequestParameter('interactionSerial'));

			if(!empty($interaction)){
				$returnValue = $interaction;
			}
		}else{
			throw new Exception('no request parameter "interactionSerial" found');
		}

		return $returnValue;
	}
	
	/**
	 * Get the current QTI choice we are working on
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_Choice
	 */
	public function getCurrentChoice(){
		$returnValue = null;
		if($this->hasRequestParameter('choiceSerial')){
			$choice = $this->qtiService->getDataBySerial($this->getRequestParameter('choiceSerial'), 'taoQTI_models_classes_QTI_Choice');
			if(!empty($choice)){
				$returnValue = $choice;
			}
		}else{
			throw new Exception('no request parameter "choiceSerial" found');
		}

		return $returnValue;
	}

	/**
	 * Get the current QTI group we are working on
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_Choice
	 */
	public function getCurrentGroup(){
		$returnValue = null;
		if($this->hasRequestParameter('groupSerial')){
			$group = $this->qtiService->getDataBySerial($this->getRequestParameter('groupSerial'), 'taoQTI_models_classes_QTI_Group');
			if(!empty($group)){
				$returnValue = $group;
			}
		}else{
			throw new Exception('no request parameter "groupSerial" found');
		}

		return $returnValue;
	}
	
	/**
	 * Get the current QTI response
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_Response
	 */
	public function getCurrentResponse(){
		$returnValue = null;
		if($this->hasRequestParameter('responseSerial')){
			$response = $this->qtiService->getDataBySerial($this->getRequestParameter('responseSerial'), 'taoQTI_models_classes_QTI_Response');
			if(!empty($response)){
				$returnValue = $response;
			}
		}else{
			try{
				//second chance: try getting the response from the interaction, is set in the request parameter
				$interaction = $this->getCurrentInteraction();
				if(!empty($interaction)){
					$response = $this->service->getInteractionResponse($interaction);
					if(!empty($response)){
						$returnValue = $response;
					}
				}
			}catch(Exception $e){
				throw new common_exception_Error('cannot find the response no request parameter "responseSerial" found');
			}

		}

		return $returnValue;
	}
	
	/**
	 * Get the current response processing
	 * 
	 * @access public
	 * @return taoQTI_models_classes_QTI_response_ResponseProcessing
	 */
	public function getCurrentResponseProcessing(){
		$returnValue = null;
		if($this->hasRequestParameter('responseprocessingSerial')){
			$responseprocessing = $this->qtiService->getDataBySerial($this->getRequestParameter('responseprocessingSerial'), 'taoQTI_models_classes_QTI_response_ResponseProcessing');
			if(!empty($responseprocessing)){
				$returnValue = $responseprocessing;
			}
		}else{
			try{
				//second chance: try getting the responseprocessing from the item
				$item = $this->getCurrentItem();
				if(!empty($item)){
					$responseprocessing = $this->service->getResponseProcessing($item);
					if(!empty($responseprocessing)){
						$returnValue = $responseprocessing;
					}
				}
			}catch(Exception $e){
				throw new Exception('cannot find the responseProcessing no request parameter "responseprocessingSerial" found');
			}

		}

		return $returnValue;
	}
	
	/**
	 * Get the current outcome to be edited
	 * 
	 * @return taoQTI_models_classes_QTI_Outcome 
	 */
	public function getCurrentOutcome(){
		$returnValue = null;
		if($this->hasRequestParameter('outcomeSerial')){
			$outcome = $this->qtiService->getDataBySerial($this->getRequestParameter('outcomeSerial'), 'taoQTI_models_classes_QTI_Outcome');
			if(!empty($outcome)){
				$returnValue = $outcome;
			}
		}else{
			throw new common_exception_Error('cannot find the outcome no request parameter "outcomeSerial" found');
		}

		return $returnValue;
	}

	/**
	 * Return the interaction editing interface.
	 * (To be called at the same time as edit response)
	 * 
	 * @access public
	 */
	public function editInteraction(){

		$interaction = $this->getCurrentInteraction();

		//build the form with its method "toForm"
		$myForm = $interaction->toForm();
		
		//get the itnteraction's choices
		$choices = $this->service->getInteractionChoices($interaction);
		$choiceForms = array();

		$interactionTypeLabel = $interaction->getType();
		$interactionType = strtolower($interactionTypeLabel);
		switch($interactionType){
			case 'match':{
				$i = 0;
				$groupSerials = array();
				foreach($choices as $groupSerial=>$group){

					$groupSerials[$i] = $groupSerial;
					$choiceForms[$groupSerial] = array();
					foreach($group as $choice){
						$choiceForms[$groupSerial][$choice->getSerial()] = $choice->toForm()->render();
					}
					$i++;
				}
				$this->setData('groupSerials', $groupSerials);
				break;
			}
			case 'gapmatch':{
				/*
				//get group form:
				$groupForms = array();
				foreach($this->service->getInteractionGroups($interaction) as $group){
					//order does not matter:
					$groupForms[] = $group->toForm($interaction)->render();
				}
				$this->setData('formGroups', $groupForms);

				//get choice forms:
				foreach($choices as $order=>$choice){
					$choiceForms[$choice->getSerial()] = $choice->toForm()->render();
				}
				*/
				break;
			}
			//graphic interactions:
			case 'graphicgapmatch':{
				$groups = array();
				foreach($interaction->getGroups() as $group){
					$groups[] = $group->getSerial();
				}

				$this->setData('groups', $groups);
			}
			case 'hotspot':
			case 'graphicorder':
			case 'graphicassociate':
			case 'selectpoint':
			case 'positionobject':{
				$object = $interaction->getObject();

				$bgImagePath = '';
				if(isset($object['data'])){
					if(!empty($object['data'])){
						$bgImagePath = trim($object['data']);
						//in case of relative path, we use the service
						if(!preg_match("/^http/", $bgImagePath)){
							$bgImagePath =  _url('getMediaResource', 'Items', 'taoItems',array(
								'path' => urlencode($bgImagePath),
								'uri' => $this->getRequestParameter('uri'),
								'classUri' => $this->getRequestParameter('classUri')
							));
						}
					}
				}
				$this->setData('backgroundImagePath',$bgImagePath);

				if(isset($object['width'])){
					$this->setData('width', (intval($object['width'])>0)?$object['width']:'');
				}
				if(isset($object['height'])){
					$this->setData('height', (intval($object['height'])>0)?$object['height']:'');
				}
				break;
			}
			default:{
				//get choice forms:
				foreach($choices as $order=>$choice){
					$choiceForms[$choice->getSerial()] = $choice->toForm()->render();
				}
			}
		}

		//display the template, according to the type of interaction
		$templateName = 'QTIAuthoring/form_interaction_'.strtolower($interaction->getType()).'.tpl';
		$this->setData('interactionType', ucfirst($interactionTypeLabel));
		$this->setData('interactionSerial', $interaction->getSerial());
		$this->setData('formInteraction', $myForm->render());
		$this->setData('formChoices', $choiceForms);
		$this->setData('interactionData', $this->service->getInteractionData($interaction));
		//$this->setData('interactionData', html_entity_decode($this->service->getInteractionData($interaction)));
		$this->setData('orderedChoices', $choices);
		$this->setView($templateName);
	}

	/**
	 * Display the choices editing view
	 * 
	 * - called on interaction edit form loaded
	 * - called when the choices forms need to be reloaded
	 * 
	 * @access public
	 */
	public function editChoices(){

		$interaction = $this->getCurrentInteraction();

		//get the itnteraction's choices
		$choices = $this->service->getInteractionChoices($interaction);
		$choiceForms = array();

		$interactionType = strtolower($interaction->getType());
		switch($interactionType){
			case 'match':{
				$i = 0;
				$groupSerials = array();
				foreach($choices as $groupSerial=>$group){

					$groupSerials[$i] = $groupSerial;
					$choiceForms[$groupSerial] = array();
					foreach($group as $choice){
						$choiceForms[$groupSerial][$choice->getSerial()] = $choice->toForm()->render();
					}
					$i++;
				}
				$this->setData('groupSerials', $groupSerials);
				break;
			}
			case 'gapmatch':
			case 'graphicgapmatch':{
				//get group form:
				$groupForms = array();
				foreach($this->service->getInteractionGroups($interaction) as $group){
					//order does not matter:
					$groupForms[$group->getSerial()] = $group->toForm($interaction)->render();
				}
				$this->setData('formGroups', $groupForms);
				
				//get choice forms:
				foreach($choices as $order=>$choice){
					$choiceForms[$choice->getSerial()] = $choice->toForm()->render();
				}
				break;
			}
			default:{
				//get choice forms:
				foreach($choices as $order=>$choice){
					$choiceForms[$choice->getSerial()] = $choice->toForm()->render();
				}
			}
		}

		$templateName = 'QTIAuthoring/form_choices_'.strtolower($interaction->getType()).'.tpl';
		$this->setData('choiceType', ucfirst($this->service->getInteractionChoiceName($interactionType)).'s');
		$this->setData('formChoices', $choiceForms);
		$this->setData('orderedChoices', $choices);
		$this->setView($templateName);
	}
	
	/**
	 * Save the QTI interaction properties from the editing form to session
	 * 
	 * @access public
	 */
	public function saveInteraction(){

		$interaction = $this->getCurrentInteraction();

		$myForm = $interaction->toForm();

		$saved = false;
		$reloadResponse = false;
		$newGraphicObject = array();

		if($myForm->isSubmited()){
			if($myForm->isValid()){
				$values = $myForm->getValues();

				if(isset($values['interactionIdentifier'])){
					if($values['interactionIdentifier'] != $interaction->getIdentifier()){
						$this->service->setIdentifier($interaction, $values['interactionIdentifier']);
					}
					unset($values['interactionIdentifier']);
				}

				//for block interactions
				if(isset($values['prompt'])){
					$this->service->setPrompt($interaction, $this->getPostedData('prompt'));
					unset($values['prompt']);
				}

				//for graphic interactions:
				if(isset($values['object_width'])){
					if(intval($values['object_width'])) $newGraphicObject['width'] = intval($values['object_width']);
					unset($values['object_width']);
				}

				if(isset($values['object_height'])){
					if(intval($values['object_height'])) $newGraphicObject['height'] = intval($values['object_height']);
					unset($values['object_height']);
				}

				$errorMessage = '';
				if(isset($values['object_data'])){

					$oldObject = $interaction->getObject();

					//get mime type
					$imageFilePath = trim($values['object_data']);
					$imgProperties = $this->getImageProperties($imageFilePath);
					if(!empty($imgProperties)){
						$newGraphicObject['data'] = $imageFilePath;
						$newGraphicObject['type'] = $imgProperties['mime'];
					}else{
						$errorMessage = __('invalid image mime type');
					}
					unset($values['object_data']);
				}

				$interaction->setObject($newGraphicObject);
				if(!empty($errorMessage)) $newGraphicObject['errorMessage'] = $errorMessage;

				unset($values['interactionSerial']);

				foreach($values as $key=>$value){
					if(preg_match('/^max/', $key)){
						if($interaction->getOption($key) != $value){
							$reloadResponse = true;
						}
						break;
					}
				}

				//save all options before updating the interaction response
				$this->service->editOptions($interaction, $values);
				if($reloadResponse){
					//update the cardinality, just in case it has been changed:
					//may require upload of the response form, since the maximum allowed response may have changed!
					$this->service->updateInteractionResponseOptions($interaction);

					//costly...
					//then simulate get+save response data to filter affected response variables
					$this->service->saveInteractionResponse($interaction, $this->service->getInteractionResponseData($interaction));
				}

				$choiceOrder = array();
				if(isset($_POST['choiceOrder'])){

					$choiceOrder = $_POST['choiceOrder'];

				}elseif( isset($_POST['choiceOrder0']) && isset($_POST['choiceOrder1'])){//for match interaction

					for($i=0; $i<2; $i++){//TODO: to be tested...
						$groupOrder = $_POST['choiceOrder'.$i];
						if(isset($groupOrder['groupSerial'])){
							$groupSerial = $groupOrder['groupSerial'];
							unset($groupOrder['groupSerial']);
							$choiceOrder[$groupSerial] = $groupOrder;
						}
					}

				}
				$this->service->setInteractionData($interaction, $this->getPostedInteractionData(), $choiceOrder);

				$saved  = true;
			}
		}

		echo json_encode(array(
			'saved' => $saved,
			'reloadResponse' => $reloadResponse,
			'newGraphicObject' => $newGraphicObject
		));

	}

	/**
	 * Get an array containing the information related to the image with the given url
	 * 
	 * @param string $imageFilePath
	 * @return array
	 */
	private function getImageProperties($imageFilePath){

		$returnValue = array();

		if(!empty($imageFilePath)){

			if(!preg_match("/^http/", $imageFilePath)){
				if($this->hasSessionAttribute('uri') && $this->hasSessionAttribute('classUri')){
					$itemService = taoItems_models_classes_ItemsService::singleton();
					$classUri = tao_helpers_Uri::decode($this->getSessionAttribute('classUri'));
					if($itemService->isItemClass(new core_kernel_classes_Class($classUri))){
						$item = new core_kernel_classes_Resource( tao_helpers_Uri::decode($this->getSessionAttribute('uri')));
						if(!is_null($item)){
							$folder 	= $itemService->getItemFolder($item);
							$imageFilePath 	= tao_helpers_File::concat(array($folder, $imageFilePath));
						}
					}
				}
			}

			if (@fclose(@fopen($imageFilePath, "r"))){//check if file remotely exists, might be improved with cURL

				$mimeType = tao_helpers_File::getMimeType($imageFilePath);
				$validImageType = array(
					'image/png',
					'image/jpeg',
					'image/bmp',
					'image/gif',
					'image/vnd.microsoft.icon',
					'image/tiff'
				);

				if(in_array($mimeType, $validImageType)){
					$returnValue['mime'] = $mimeType;
				}

			}

		}

		return $returnValue;
	}
	
	/**
	 * Save choice data and properties
	 * 
	 * @access public
	 */
	public function saveChoice(){
		$choice = $this->getCurrentChoice();

		$myForm = $choice->toForm();
		$saved = false;
		$identifierUpdated = false;
		$errorMessage = '';

		if($myForm->isSubmited()){
			if($myForm->isValid()){

				$values = $myForm->getValues();
				unset($values['choiceSerial']);//choiceSerial to be deleted since only used to get the choice qti object

				if(isset($values['choiceIdentifier'])){
					if($values['choiceIdentifier'] != $choice->getIdentifier()){
						$this->service->setIdentifier($choice, $values['choiceIdentifier']);
						$identifierUpdated = true;
					}
					unset($values['choiceIdentifier']);
				}

				if(isset($values['data'])){
					$this->service->setData($choice, $this->getPostedData('data'));
					unset($values['data']);
				}

				//for graphic interactions:
				$newGraphicObject = array();
				if(isset($values['object_width'])){
					if(intval($values['object_width'])) $newGraphicObject['width'] = intval($values['object_width']);
					unset($values['object_width']);
				}
				if(isset($values['object_height'])){
					if(intval($values['object_height'])) $newGraphicObject['height'] = intval($values['object_height']);
					unset($values['object_height']);
				}
				if(isset($values['object_data'])){

					// $oldObject = $choice->getObject();

					//get mime type
					$imageFilePath = trim($values['object_data']);
					$imgProperties = $this->getImageProperties($imageFilePath);
					if(!empty($imgProperties)){
						$newGraphicObject['data'] = $imageFilePath;
						$newGraphicObject['type'] = $imgProperties['mime'];
					}else{
						$errorMessage = __('invalid image mime type for the image file '.$imageFilePath);
					}
					unset($values['object_data']);
				}
				$choice->setObject($newGraphicObject);
				// unset($values['object_data']);

				//finally save the other options:
				$this->service->setOptions($choice, $values);

				$saved = true;
			}
		}

		if($identifierUpdated){
			$interaction = $this->qtiService->getComposingData($choice);
			$interaction->addChoice($choice);
			$interaction = null;
		}

		echo json_encode(array(
			'saved' => $saved,
			'choiceSerial' => $choice->getSerial(),
			'identifierUpdated' => $identifierUpdated,
			'reload' => false,//@see requireChoicesUpdate()
			'errorMessage' => (string)$errorMessage
		));
	}

	/**
	 * Save the group properties, specific to gapmatch interaction where groups are considered as a gap:
	 * not called when the choice order has been changed, such changes are done by saving the itneraction data
	 * 
	 * @access public
	 */
	public function saveGroup(){
		$group = $this->getCurrentGroup();
		$interaction = $this->qtiService->getComposingData($group);

		$myForm = $group->toForm();
		$saved = false;
		$identifierUpdated = false;
		$newIdentifier = '';

		if($myForm->isSubmited()){
			if($myForm->isValid()){

				$values = $myForm->getValues();

				if(isset($values['groupIdentifier'])){
					if($values['groupIdentifier'] != $group->getIdentifier()){
						$newIdentifier = $values['groupIdentifier'];
						$identifierUpdated = $this->service->setIdentifier($group, $values['groupIdentifier']);
					}
				}

				$matchGroup = array();
				if(!empty($values['matchGroup']) && is_array($values['matchGroup'])){
					foreach($values['matchGroup'] as $choiceIdentifier){
						$choice = $this->service->getInteractionChoiceByIdentifier($interaction, $choiceIdentifier);
						if(!is_null($choice)){
							$matchGroup[] = $choice->getSerial();
						}
					}
				}
				unset($values['matchGroup']);
				$group->setChoices($matchGroup);


				$choiceOrder = array();
				if(isset($_POST['choiceOrder'])){
					$choiceOrder = $_POST['choiceOrder'];
				}
				$this->service->setGroupData($group, $choiceOrder, null, true);//the 3rd parameter interaction is not required as the method only depends on the group

				unset($values['groupSerial']);
				unset($values['groupIdentifier']);
				$this->service->setOptions($group, $values);

				$saved = true;
			}
		}

		$choiceFormReload = false;
		if($identifierUpdated){

			$choiceFormReload = $this->requireChoicesUpdate($interaction);
			$interaction->addGroup($group);

		}
		$interaction = null;

		echo json_encode(array(
			'saved' => $saved,
			'groupSerial' => $group->getSerial(),
			'identifierUpdated' => $identifierUpdated,
			'newIdentifier' => $newIdentifier,
			'reload' => $choiceFormReload
		));
	}
	
	/**
	 * Add a group to the current interaction
	 * A QTI gap basically is a "group", the content of which is by default all available choices in the interaction
	 * 
	 * @access public
	 */
	public function addGroup(){
		$added = false;
		$groupSerial = '';
		$interaction = $this->getCurrentInteraction();
		$interactionData = $this->getPostedInteractionData();
		
		$group = $this->service->addGroup($interaction, $interactionData);

		if(!is_null($group)){
			$interactionData = $this->service->getInteractionData($interaction);//do not convert to html entities...

			//everything ok:
			$added = true;
			$groupSerial = $group->getSerial();
		}

		echo json_encode(array(
			'added' => $added,
			'groupSerial' => $groupSerial,
			'groupForm' => $group->toForm()->render(),
			'interactionData' => $interactionData,
			'reload' => false//@see deprecated requireChoicesUpdate()
		));
	}
	
	/**
	 * Display the response processing form
	 * 
	 * @access public
	 */
	public function editResponseProcessing(){

		$item = $this->getCurrentItem();

		$formContainer = new taoQTI_actions_QTIform_ResponseProcessing($item);
		$myForm = $formContainer->getForm();

		$this->setData('form', $myForm->render());
		$processingType = $formContainer->getProcessingType();

		$warningMessage = '';
		if($processingType == 'custom'){
			$warningMessage = __('The custom response processing type is currently not fully supported in this tool. Removing interactions or choices is not recommended.');
		}

		$this->setData('warningMessage', $warningMessage);
		$this->setView('QTIAuthoring/form_response_processing.tpl');
	}

	/**
	 * Save the response processing mode of the QTI item
	 * 
	 * @access public
	 */
	public function saveItemResponseProcessing(){

		$item = $this->getCurrentItem();
		$responseProcessingType = tao_helpers_Uri::decode($this->getRequestParameter('responseProcessingType'));
		$customRule = $this->getRequestParameter('customRule');

		$saved = $this->service->setResponseProcessing($item, $responseProcessingType, $customRule);

		echo json_encode(array(
			'saved' => $saved,
			'responseMode' => taoQTI_helpers_qti_InteractionAuthoring::isResponseMappingMode($responseProcessingType)
		));
	}
	
	/**
	 * Save the reponse processing of the interaction
	 * 
	 * @access public
	 */
	public function saveInteractionResponseProcessing(){
		$response = $this->getCurrentResponse();
		$rp = $this->getCurrentResponseProcessing();

		if(!is_null($response) && !is_null($rp)){
			if ($rp instanceof taoQTI_models_classes_QTI_response_TemplatesDriven) {
				$saved					= false;
				$setResponseMappingMode	= false;
				$templateHasChanged		= false;
				if($this->hasRequestParameter('processingTemplate')){
					$processingTemplate = tao_helpers_Uri::decode($this->getRequestParameter('processingTemplate'));
					if ($rp->getTemplate($response) != $processingTemplate) {
						$templateHasChanged = true;
					}
					$saved = $rp->setTemplate($response, $processingTemplate);
					if($saved) {
						$setResponseMappingMode = taoQTI_helpers_qti_InteractionAuthoring::isResponseMappingMode($processingTemplate);
					}
				}
				echo json_encode(array(
					'saved'						=> $saved,
					'setResponseMappingMode'	=> $setResponseMappingMode,
					'hasChanged'				=> $templateHasChanged
				));
			} elseif ($rp instanceof taoQTI_models_classes_QTI_response_Composite) {
				$currentIRP		= $rp->getInteractionResponseProcessing($response);
				$currentClass	= get_class($currentIRP);
				$saved			= false;
				$classID		= $currentClass::CLASS_ID;

				if($this->hasRequestParameter('interactionResponseProcessing')) {
					$item = $this->qtiService->getComposingData($rp);
					$classID		= $this->getRequestParameter('interactionResponseProcessing');
					if ($currentClass::CLASS_ID != $classID) {
						$newIRP = taoQTI_models_classes_QTI_response_interactionResponseProcessing_InteractionResponseProcessing::create(
							$classID,
							$response,
							$item
						);
						$rp->replace($newIRP);
						$saved = true;
					}
				}
				echo json_encode(array(
					'saved'						=> $saved,
					'setResponseOptionsMode'	=> $classID
				));
			}
		}

	}

	/**
	 * Display the reponse mapping options form
	 * The reponse processing template must be map or areamap
	 * 
	 * @access public
	 */
	public function editMappingOptions(){
		$response = $this->getCurrentResponse();

		$formContainer = new taoQTI_actions_QTIform_Mapping($response);

		$this->setData('form', $formContainer->getForm()->render());
		$this->setView('QTIAuthoring/form_response_mapping.tpl');

	}
	
	/**
	 * Save interaction responses (choice, correct, score)
	 * 
	 * @access public
	 */
	public function saveResponse(){

		$saved = false;

		//get the response from the interaction:
		$interaction = $this->getCurrentInteraction();

		if($this->hasRequestParameter('responseData')){
			$responseData = $this->getRequestParameter('responseData');
			$saved = $this->service->saveInteractionResponse($interaction, $responseData);
		}

		echo json_encode(array(
			'saved' => $saved
		));
	}
	
	/**
	 * Save interaction reponses properties (baseType, ordered, etc.)
	 * 
	 * @access public
	 */
	public function saveResponseProperties(){

		$saved = false;
		$response = $this->getCurrentResponse();

		if(!is_null($response)){

			if($this->hasRequestParameter('baseType')){
				if($this->hasRequestParameter('baseType')){
					$this->service->editOptions($response, array('baseType'=>$this->getRequestParameter('baseType')));
					$saved = true;
				}

				if($this->hasRequestParameter('ordered')){
					if(intval($this->getRequestParameter('ordered')) == 1){
						$this->service->editOptions($response, array('cardinality'=>'ordered'));
					}else{
						//reset the cardinality:
						$parentInteraction = $this->qtiService->getComposingData($response);
						if(!is_null($parentInteraction)){
							$this->service->editOptions($response, array('cardinality' => $parentInteraction->getCardinality() ));
							$parentInteraction = null;//destroy it!
						}else{
							throw new Exception('cannot find the parent interaction');
						}

					}
					$saved = true;
				}
			}

		}

		echo json_encode(array(
			'saved' => $saved,
		));
	}

	/**
	 * Save response coding options
	 * 
	 * @access public
	 */
	public function saveResponseCodingOptions(){

		$interaction = $this->getCurrentInteraction();
		$rp = $this->getCurrentResponseProcessing();
		$form = null;

		// cases
		if ($rp instanceof taoQTI_models_classes_QTI_response_TemplatesDriven) {
			$form = 'template';
		} elseif ($rp instanceof taoQTI_models_classes_QTI_response_Composite) {
			$irp = $rp->getInteractionResponseProcessing($interaction->getResponse());
			if ($irp instanceof taoQTI_models_classes_QTI_response_interactionResponseProcessing_None) {
				$form = 'manual';
			} elseif (in_array(get_class($irp), array(
				'taoQTI_models_classes_QTI_response_interactionResponseProcessing_MatchCorrectTemplate',
				'taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponseTemplate',
				'taoQTI_models_classes_QTI_response_interactionResponseProcessing_MapResponsePointTemplate'))) {

				$form = 'template';
			}
		}

		if ($form == 'template') {
			$response = $interaction->getResponse();
			$mappingOptions = $_POST;

			$this->service->setMappingOptions($response, $mappingOptions);
			$saved = true;

			echo json_encode(array(
				'saved' => $saved
			));

		} elseif ($form == 'manual') {
			$irp = $rp->getInteractionResponseProcessing($interaction->getResponse());
			$saved = false;
			$outcome = $this->getCurrentOutcome();

			// set guidelines
			if ($this->hasRequestParameter('guidelines')) {
				$values = array(
					'interpretation' => $this->getRequestParameter('guidelines')
				);
				$saved = $this->service->editOptions($outcome, $values) || $saved;
			}

			// set correct answer
			if ($this->hasRequestParameter('correct')) {
				$responseData = array(array(
					'choice1' => $this->getRequestParameter('correct'),
					'correct' => 'yes'
				));
				$saved = $this->service->saveInteractionResponse($this->getCurrentInteraction(), $responseData) || $saved;
			}

			// set guidelines
			if ($this->hasRequestParameter('defaultValue')) {
				$irp->setDefaultValue($this->getRequestParameter('defaultValue'));
				$saved = true;
			}

			// set scale
			if ($this->hasRequestParameter('scaletype')) {

				if (strlen(trim($this->getRequestParameter('scaletype'))) > 0) {
					$uri = tao_helpers_Uri::decode($this->getRequestParameter('scaletype'));
					$scale = taoItems_models_classes_Scale_Scale::createByClass($uri);

					if ($this->hasRequestParameter('min')) {
						$scale->lowerBound = (floatval($this->getRequestParameter('min')));
					}
					if ($this->hasRequestParameter('max')) {
						$scale->upperBound = (floatval($this->getRequestParameter('max')));
					}
					if ($this->hasRequestParameter('dist')) {
						$scale->distance = (floatval($this->getRequestParameter('dist')));
					}
					$outcome->setScale($scale);
					$saved = true;
				} else {
					$outcome->removeScale();
					$saved = true;
				}
			}

			echo json_encode(array(
				'saved' => $saved
			));
		} else {
			echo json_encode(array(
				'saved' => false
			));
		}

	}

	/**
	 * Edit the interaction response:
	 * 
	 * @access public
	 */
	public function editResponse(){

		$item = $this->getCurrentItem();
		$responseProcessing = $item->getResponseProcessing();
		$interaction = $this->getCurrentInteraction();
		$response = $this->service->getInteractionResponse($interaction);

		$displayGrid = false;
		$isResponseMappingMode = false;
		$columnModel = array();
		$responseData = array();
		$xhtmlForms = array();
		$interactionType = strtolower($interaction->getType());

		//response options independant of processing
		$response = $this->service->getInteractionResponse($interaction);
		$responseForm = $response->toForm();
		if(!is_null($responseForm)){
			$xhtmlForms[] = $responseForm->render();
		}

		//set the processing mode
		$rpform = $responseProcessing->getForm($response);
		if (!is_null($rpform)) {
			$xhtmlForms[] = $rpform->render();
		}

		$data = array(
			'ok' => true,
			'interactionType' => $interactionType,
			'maxChoices' => intval($interaction->getCardinality(true)),
			'forms'	=> $xhtmlForms,
		);
		//proccessing related form
		foreach (taoQTI_helpers_qti_InteractionAuthoring::getIRPData($item, $interaction) as $key => $value) {
			if (isset($data[$key]) && is_array($data[$key])) {
				foreach ($value as $v) {
					$data[$key][] = $v;
				}
			} else {
				$data[$key]= $value;
			}
		}
		$data['responseForm'] = implode('', $data['forms']);

		echo json_encode($data);

	}
	
	/**
	 * Display the css manager interface
	 * 
	 * @access public
	 */
	public function manageStyleSheets(){
		//create upload form:
		$item = $this->getCurrentItem();
		$formContainer = new taoQTI_actions_QTIform_CSSuploader($item, $this->getRequestParameter('itemUri'));
		$myForm = $formContainer->getForm();

		if($myForm->isSubmited()){
			if($myForm->isValid()){
				$data = $myForm->getValues();

				if(isset($data['css_import']['uploaded_file'])){
					//get the file and store it in the proper location:
					$baseName = basename($data['css_import']['uploaded_file']);

					$fileData = $this->getCurrentStyleSheet($baseName);

					if(!empty($fileData)){
						tao_helpers_File::move($data['css_import']['uploaded_file'], $fileData['path']);

						$cssFiles = $item->getStyleSheets();
						$cssFiles[] = array(
							'title' => empty($data['title'])?$data['css_import']['name']:$data['title'],
							'href' => $fileData['href'],
							'type' => 'text/css',
							'media' => 'screen'//@TODO:default to "screen" make suggestion of other devises such as "handheld" when mobile ready
						);
						$item->setStyleSheets($cssFiles);
					}
				}

			}
		}

		$cssFiles = array();
		foreach($item->getStyleSheets() as $file){
			$cssFiles[] = array(
				'href' => $file['href'],
				'title' => $file['title'],
				'downloadUrl' => _url('getStyleSheet', null, null, array(
						'itemSerial' => tao_helpers_Uri::encode($item->getSerial()),
						'itemUri' 	=> tao_helpers_Uri::encode($this->getCurrentItemResource()->getUri()),
						'css_href' => $file['href']
				))
			);
		}

		$this->setData('formTitle', __('Manage item content'));
		$this->setData('myForm', $myForm->render());
		$this->setData('cssFiles', $cssFiles);
		$this->setView('QTIAuthoring/css_manager.tpl');
	}
	
	/**
	 * Delete a style sheet
	 * 
	 * @access public
	 */
	public function deleteStyleSheet(){

		$deleted = false;

		$fileData = $this->getCurrentStyleSheet();

		//get the full path of the file and unlink the file:
		if(!empty($fileData)){
			tao_helpers_File::remove($fileData['path']);

			$item = $this->getCurrentItem();

			$files = $item->getStylesheets();

			foreach($files as $key=>$file){
				if($file['href'] == $fileData['href']){
					unset($files[$key]);
				}
			}

			$item->setStylesheets($files);

			$deleted = true;
		}

		echo json_encode(array('deleted' => $deleted));
	}
	
	/**
	 * Get a style sheet
	 * 
	 * @access public
	 */
	public function getStyleSheet(){
		$fileData = $this->getCurrentStyleSheet();
		if (!empty($fileData)) {
			$fileName = basename($fileData['path']);

			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Description: File Transfer");
			header("Content-Type: text/css");
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($fileData['path']));

			echo file_get_contents($fileData['path']);
		} else {
			throw new Exception('The style file cannot be found');
		}
	}
	
	/**
	 * Get a data of a stylesheet
	 * 
	 * @access public
	 * @param basename
	 * @return array
	 */
	public function getCurrentStyleSheet($baseName=''){
		$returnValue = array();
		$itemResource = $this->getCurrentItemResource();
		$basePath = taoItems_models_classes_ItemsService::singleton()->getItemFolder($itemResource);
		$baseWWW = taoItems_models_classes_ItemsService::singleton()->getRuntimeFolder($itemResource);

		if (!empty($baseName)) {
			//creation mode:
			$css_href = 'style/'.$baseName;

			$returnValue = array(
				'href' => $css_href,
				'type' => 'text/css',
				'title' => $baseName,
				'path' => $basePath.'/'.$css_href,
				'hrefAbsolute' => $baseWWW.'/'.$css_href
			);
		} else {
			//get mode:
			$css_href = $this->getRequestParameter('css_href');
			if (!empty($css_href)) {
				$files = $this->getCurrentItem()->getStylesheets();
				foreach ($files as $file) {
					if ($file['href'] == $css_href) {
						$returnValue = $file;
						$returnValue['path'] = $basePath.'/'.$css_href;
						$returnValue['hrefAbsolute'] = $baseWWW.'/'.$css_href;
						break;
					}
				}
			}
		}

		return $returnValue;
	}
	
	/**
	 * Add a object into the item body
	 * 
	 * @access public
	 */
	public function addObject() {
		$object = new taoQTI_models_classes_QTI_Object(null, array('data' => '', 'type' => ''));
		$this->getCurrentItem()->addObject($object);
		echo json_encode(array(
				'success'	=> true,
				'objectSerial'	=> $object->getSerial(),
				'objectData'	=> $this->service->getObjectTag($object)
			));
	}
	
	/**
	 * Delete objects
	 * 
	 * @access public
	 */
	public function deleteObjects() {
		$deleted = false;

		$objectSerials = array();
		if ($this->hasRequestParameter('objectSerials')) {
			$objectSerials = $this->getRequestParameter('objectSerials');
		}
		if (empty($objectSerials)) {
			throw new Exception('no object ids found to be deleted');
		} else {
			$item = $this->getCurrentItem();
			$deleteCount = 0;

			//delete objects:
			foreach ($objectSerials as $objectSerial) {
				$object = $this->qtiService->getDataBySerial($objectSerial);
				if (!empty($object)) {
					$this->service->deleteObject($item, $object);
					$deleteCount++;
				} else {
					throw new Exception('no object found to be deleted with the serial: '.$objectSerial);
				}
			}

			if ($deleteCount == count($objectSerials)) {
				$deleted = true;
			}
		}

		echo json_encode(array(
			'deleted' => $deleted
		));
	}

	public function editObject() {
		//instantiate the item content form container
		foreach ($this->getCurrentItem()->getObjects() as $object) {
			if ($object->getSerial() == $this->getRequestParameter('objectSerial')) {
				$myObject = $object;
				break;
			}
		}
		if (!isset($myObject)) {
			throw new common_Exception('Object not found');
		}

		$formContainer = new taoQTI_actions_QTIform_EditObject($myObject, $this->getCurrentItem());
		$myForm = $formContainer->getForm();

		if ($myForm->isSubmited() && $myForm->isValid()) {
			//Url
			$url = $this->getRequestParameter('objecturl');
			$myObject->setOption('data', $url);

			//Mime-type
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
			$content = curl_exec($ch);
			if (!curl_errno($ch)) {
				$info = curl_getinfo($ch);
				$myObject->setOption('type', $info['content_type']);
			} else {
				common_Logger::d('Error getting the ressource : '.$url, array('TAOITEMS', 'QTI'));
			}
			curl_close($ch);

			//Width
			if ($this->hasRequestParameter('width') && intval($this->getRequestParameter('width')) > 0) {
				$myObject->setOption('width', $this->getRequestParameter('width'));
			}

			//Height
			if ($this->hasRequestParameter('height') && intval($this->hasRequestParameter('height')) > 0) {
				$myObject->setOption('height', $this->getRequestParameter('height'));
			}

			echo json_encode(array(
				'success'	=> true,
			));
		} else {
			echo json_encode(array('html' => $myForm->render(), 'title' =>  __('Edit object')));
		}
	}
	
	protected function getRequestQTIobject($required = true){
		
		$returnValue = null;
		
		$type = '';
		$serial = '';
		if($this->hasRequestParameter('type') && $this->hasRequestParameter('serial')){
			
			$type = strtolower($this->getRequestParameter('type'));
			$types = array(
				'item' => 'taoQTI_models_classes_QTI_Item',
				'interaction' => 'taoQTI_models_classes_QTI_Interaction',
				'choice' => 'taoQTI_models_classes_QTI_Choice',
				'group' => 'taoQTI_models_classes_QTI_Group'
			);
			
			if(isset($types[$type])){
				$serial = $this->getRequestParameter('serial');
				$returnValue = $this->qtiService->getDataBySerial($serial, $types[$type]);
			}
			
		}
		
		if($required && is_null($returnValue)){
			throw new Exception('cannot retrive the qti object : '.$type.'/'.$serial);
		}
		
		return $returnValue;
	}
	
	/**
	 * Generic method to save QTI object's attributes
	 * 
	 * @access public
	 */
	public function saveAttribute(){
		
		$success = false;
		$messages = array();
		$qtiObject = $this->getRequestQTIobject();
		if(!is_null($qtiObject) && $this->hasRequestParameter('attribute') && $this->hasRequestParameter('value')){
			$attribute = $this->getRequestParameter('attribute');
			$value = $this->getRequestParameter('value');
			
			//validate attribute value against attribute validators:
			$qtiObject->setOption($attribute, $value);
			$success = true;
		}
		
		echo json_encode(array(
			'success' => $success,
			'messages' => $messages
		));
	}
	
	/**
	 * Check if an identifier is used by any of a QTI object within the current QTI item
	 * 
	 * @access public
	 */
	public function isIdentifierUsed(){
		$used = false;
		if($this->hasRequestParameter('identifier')){
			$used = $this->qtiService->isIdentifierUsed($this->getRequestParameter('identifier'));
		}
		echo json_encode(array(
			'used' => $used
		));
	}
	
	/**
	 * Process mathML and rendered the mathML object.
	 * (Yet to be implemented)
	 * 
	 * @access public
	 */
	public function getMathML(){
		$mathRaw = $this->getPostedData('mathRaw');
		echo json_encode(array('mathRaw'=>$mathRaw, 'ok'=>true, 'render'=>'<img src="http://dummyimage.com/160x60/fa2323/fff.png&text=MathML" alt="math expression"/>'));
	}
}
?>
