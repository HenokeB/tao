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
if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

//require_once dirname(__FILE__).'/../../includes/ims-blti/blti.php';

class ltiProvider_models_classes_LtiService extends tao_models_classes_Service 
{
	const LIS_CONTEXT_ROLE_NAMESPACE = 'urn:lti:role:ims/lis/';

	const LTICONTEXT_SESSION_KEY	= 'LTICONTEXT';
	
	protected function __construct() {
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function startLTISession(ltiProvider_models_classes_TaoLtiSession $ltiSession) {
		$oauthService = tao_models_classes_oauth_Service::singleton();
		if (!$oauthService->isCurrentRequestValid()) {
			throw new ltiProvider_models_classes_LtiException('Invalid LTI signature');
		}
		core_kernel_users_Service::singleton()->startSession($ltiSession->getTaoUser());
		PHPSession::singleton()->setAttribute(self::LTICONTEXT_SESSION_KEY, $ltiSession);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return ltiProvider_models_classes_TaoLtiSession 
	 */
	public function getLTISession() {
		return PHPSession::singleton()->getAttribute(self::LTICONTEXT_SESSION_KEY);
	}
	
	public function getCredential($key) {
		$class = new core_kernel_classes_Class(CLASS_LTI_CONSUMER);
		$instances = $class->searchInstances(array(PROPERTY_OAUTH_KEY => $key), array('like' => false));
		if (count($instances) == 0) {
			throw new ltiProvider_models_classes_LtiException('No Credentials for consumer key '.$key);
		}
		if (count($instances) > 1) {
			throw new ltiProvider_models_classes_LtiException('Multiple Credentials for consumer key '.$key);
		}
		return current($instances);
	}
	
	public function getLTIUser($ltiContext) {
		
		if (is_null($this->user)) {
			$this->user = $this->findUser($ltiContext);
			if (is_null($this->user)) {
				$this->user = $this->spawnUser($ltiContext);
			}
		}
		return $this->user;
	}
	
	/**
	 * Searches if this user was already created in TAO
	 * 
	 * @param ltiProvider_models_classes_LtiSession $ltiContext
	 * @throws ltiProvider_models_classes_LtiException
	 * @return core_kernel_classes_Resource
	 */
	public function findUser(ltiProvider_models_classes_TaoLtiSession $ltiContext) {
		$class = new core_kernel_classes_Class(CLASS_LTI_USER);
		$instances = $class->searchInstances(array(
			PROPERTY_USER_LTIKEY		=> $ltiContext->getUserID(),
			PROPERTY_USER_LTICONSUMER	=> $ltiContext->getLtiConsumerResource()
		), array(
			'like'	=> false
		));
		if (count($instances) > 1) {
			throw new ltiProvider_models_classes_LtiException('Multiple user accounts found for user key \''.$ltiContext->getUserID().'\'');
		}
		return count($instances) == 1 ? current($instances) : null;
	}
	
	/**
	 * 
	 * @param ltiProvider_models_classes_LtiSession $ltiContext
	 * @return core_kernel_classes_Resource
	 */
	public function spawnUser(ltiProvider_models_classes_TaoLtiSession $ltiContext) {
		$class = new core_kernel_classes_Class(CLASS_LTI_USER);
		$lang = tao_models_classes_LanguageService::singleton()->getLanguageByCode(DEFAULT_LANG);
		$props = array(
			PROPERTY_USER_LTIKEY		=> $ltiContext->getUserID(),
			PROPERTY_USER_LTICONSUMER	=> $ltiContext->getLtiConsumerResource(),
			PROPERTY_USER_UILG			=> $lang,
			PROPERTY_USER_DEFLG			=> $lang,
			RDFS_LABEL					=> $ltiContext->getUserFullName()
		);
		if ($ltiContext->hasVariable(ltiProvider_models_classes_TaoLtiSession::LIS_PERSON_NAME_GIVEN)) {
			$props[PROPERTY_USER_FIRSTNAME] = $ltiContext->getUserGivenName();
		}
		if ($ltiContext->hasVariable(ltiProvider_models_classes_TaoLtiSession::LIS_PERSON_NAME_FAMILY)) {
			$props[PROPERTY_USER_LASTNAME] = $ltiContext->getUserFamilyName();
		}
		if ($ltiContext->hasVariable(ltiProvider_models_classes_TaoLtiSession::LIS_PERSON_CONTACT_EMAIL_PRIMARY)) {
			$props[PROPERTY_USER_MAIL] = $ltiContext->getUserEmail();
		}
		$user = $class->createInstanceWithProperties($props);
		common_Logger::i('added User '.$user->getLabel());
		$rolesService = tao_models_classes_RoleService::singleton();
		foreach ($ltiContext->getTaoUserRoles() as $taoRole) {
			common_Logger::i('added Role '.$taoRole->getLabel());
			core_kernel_users_Service::singleton()->attachRole($user, $taoRole);
		}
		return $user;
	}
	
	public function syncUser(core_kernel_classes_Resource $user, ltiProvider_models_classes_LtiSession $ltiContext) {
		
	}

}

?>