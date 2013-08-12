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

/**
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package filemanager
 * @subpackage action
 */
abstract class ltiProvider_actions_ToolModule extends tao_actions_CommonModule {
	
	/**
	 * Entrypoint of every tool
	 */
	public function launch() {
		$ltiSession = new ltiProvider_models_classes_TaoLtiSession($_POST, $this->getToolResource());
		try {
			$service = ltiProvider_models_classes_LtiService::singleton();
			$service->startLTISession($ltiSession);
			$this->run();
		} catch (ltiProvider_models_classes_LtiException $e) {
			$this->returnError(__('The LTI connection could not be established'));
		}
	}
	
	/**
	 * run() contains the actual tool's controller
	 */
	abstract protected function run();
	
	/**
	 * Returns the tool resource of this action
	 * 
	 * @return core_kernel_classes_Resource
	 */
	abstract protected function getToolResource();
	
	/**
	 * Only launch should ever be called on a tool, and should be available even without session
	 * 
	 * (non-PHPdoc)
	 * @see tao_actions_CommonModule::_isAllowed()
	 */
	protected function _isAllowed()
	{
		$context = Context::getInstance();
		$action = $context->getActionName();
		
		return $action == 'launch';
	}
}
?>