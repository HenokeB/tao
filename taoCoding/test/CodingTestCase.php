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
 * Copyright (c) 2009-2012 (original work) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 *               
 * 
 */
?>
<?php
require_once dirname(__FILE__) . '/../../tao/test/TaoTestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';

/**
 *
 * @author Jehan Bihin, <taosupport@tudor.lu>
 * @package taoCoding
 * @subpackage test
 */
class CodingTestCase extends UnitTestCase {

	/**
	 *
	 * @var taoItems_models_classes_codingService
	 */
	protected $codingService = null;

	/**
	 * tests initialization
	 */
	public function setUp() {
		TaoTestRunner::initTest();
	}

	/**
	 * Test the user service implementation
	 * @see tao_models_classes_ServiceFactory::get
	 * @see taoItems_models_classes_codingService::__construct
	 */
	public function testService() {
		/*$codingService = taoItems_models_classes_codingService::singleton();
		$this->assertIsA($codingService, 'tao_models_classes_Service');
		$this->assertIsA($codingService, 'taoItems_models_classes_codingService');

		$this->codingService = $codingService;*/
	}

	/**
	 *
	 */
	public function testBuildItem() {

	}
}
?>