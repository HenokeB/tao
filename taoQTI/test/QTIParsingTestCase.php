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
require_once dirname(__FILE__) . '/../../tao/test/TaoTestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';

/**
 *
 * @author Bertrand Chevrier, <taosupport@tudor.lu>
 * @package taoQTI
 * @subpackage test
 */
class QTIParsingTestCase extends UnitTestCase {
	
	protected $qtiService;
	
	/**
	 * tests initialization
	 */
	public function setUp(){		
		TaoTestRunner::initTest();
		$this->qtiService = taoQTI_models_classes_QTI_Service::singleton();
	}
	
	
	/**
	 * test qti file parsing: validation and loading in a non-persistant context
	 */
	public function testFileParsing(){
		
		taoQTI_models_classes_QTI_Data::setPersistence(false);
		
		//check if wrong files are not validated correctly
		foreach(glob(dirname(__FILE__).'/samples/wrong/*.*') as $file){
			
			$qtiParser = new taoQTI_models_classes_QTI_Parser($file);
			
			$qtiParser->validate();
			
			$this->assertFalse($qtiParser->isValid());
			$this->assertTrue(count($qtiParser->getErrors()) > 0);
			$this->assertTrue(strlen($qtiParser->displayErrors()) > 0);
		}
		
		//check if samples are loaded 
		foreach(glob(dirname(__FILE__).'/samples/*.xml') as $file){
			
			$qtiParser = new taoQTI_models_classes_QTI_Parser($file);
			$qtiParser->validate();
			
			if(!$qtiParser->isValid())
				echo $qtiParser->displayErrors();
			
			$this->assertTrue($qtiParser->isValid());
			
			$item = $qtiParser->load();
			
			$this->assertIsA($item, 'taoQTI_models_classes_QTI_Item');
		}
	}
	
	/**
	 * test the building an QTI_Item object from it's XML definition
	 */
	public function testBuilding(){
		
		taoQTI_models_classes_QTI_Data::setPersistence(false);
		
		$qtiParser = new taoQTI_models_classes_QTI_Parser(dirname(__FILE__).'/samples/choice.xml');
		$item = $qtiParser->load();
		
		$this->assertTrue($qtiParser->isValid());
		$this->assertNotNull($item);
		$this->assertIsA($item, 'taoQTI_models_classes_QTI_Item');
		
		$this->assertEqual(count($item->getInteractions()),1, 'nr of interactions in choice.xml differs from 1');
		
		$this->assertFalse(strlen($item->getData()) == 0, 'itembody empty');
		foreach($item->getInteractions() as $interaction){
			$this->assertIsA($interaction, 'taoQTI_models_classes_QTI_Interaction');
			
			foreach($interaction->getChoices() as $choice){
				$this->assertIsA($choice, 'taoQTI_models_classes_QTI_Choice');
			}
		}
	
	}
	
}
?>