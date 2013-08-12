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
 * The TAO layer ontop of the LtiSession
 *
 * @access public
 * @author Joel Bout, <joel@taotesting.com>
 * @package ltiProvider
 * @subpackage models_classes
 * @see ltiProvider_models_classes_LtiSession
 */
class ltiProvider_models_classes_TaoLtiSession extends ltiProvider_models_classes_LtiSession
{
	private $toolResource;
	
	private $ltiConsumer	= null;
	private $ltiLink		= null;
	
	private $user	= null;
	private $roles	= null;
	
	public function __construct($ltiVariables, core_kernel_classes_Resource $toolResource) {
		parent::__construct($ltiVariables);
		$this->toolResource	= $toolResource;
	}

	/**
	 * Returns the LTI Consumer resource associated to this lti session
	 * 
	 * @access public
	 * @author Joel Bout, <joel@taotesting.com>
	 * @return core_kernel_classes_Resource resource of LtiConsumer
	 * @throws tao_models_classes_oauth_Exception thrown if no Consumer found for key
	 */
	public function getLtiConsumerResource() {
		if (is_null($this->ltiConsumer)) {
			$dataStore = new tao_models_classes_oauth_DataStore();
			$this->ltiConsumer = $dataStore->findOauthConsumerResource($this->getOauthKey());
		}
		return $this->ltiConsumer;
	}
	
	public function getLtiLinkResource() {
		if (is_null($this->ltiLink)) {
			$class = $this->getToolService()->getRemoteLinkClass();
			// search for existing resource
			$instances = $class->searchInstances(array(
				PROPERTY_LTI_LINK_ID		=> $this->getResourceLinkID(),
				PROPERTY_LTI_LINK_CONSUMER	=> $this->getLtiConsumerResource()
			), array(
				'like'	=> false, 'recursive' => false
			));
			if (count($instances) > 1) {
				throw new common_exception_Error('Multiple resources for link '.$this->getResourceLinkID());
			}
			if (count($instances) == 1) {
				// use existing link
				$this->ltiLink = current($instances);
			} else {
				// spawn new link
				$this->ltiLink = $class->createInstanceWithProperties(array(
					PROPERTY_LTI_LINK_ID		=> $this->getResourceLinkID(),
					PROPERTY_LTI_LINK_CONSUMER	=> $this->getLtiConsumerResource(),
				));
			}
		}
		return $this->ltiLink;
	}

	/**
	 * Returns the TAO LTI tool service 
	 * 
	 * @return ltiProvider_models_classes_LtiTool
	 */
	public function getToolService() {
		return ltiProvider_models_classes_LtiTool::getToolService($this->toolResource);
	}
	
	public function getTaoUser() {
		if (is_null($this->user)) {
			$service = ltiProvider_models_classes_LtiService::singleton();
			$this->user = $service->findUser($this);
			if (is_null($this->user)) {
				$this->user = $service->spawnUser($this);
			} else {
				$service->syncUser($this->user, $this);
			}
		}
		return $this->user;
	}
	
	public function getTaoUserRoles() {
		if (is_null($this->roles)) {
			$this->roles = array();
			foreach ($this->getUserRoles() as $role) {
				$taoRole = ltiProvider_models_classes_LtiUtils::mapLTIRole2TaoRole($role);
				if (!is_null($taoRole)) {
					$this->roles[] = $taoRole;
				}
			}
		}
		return $this->roles;
	}
	
}

?>