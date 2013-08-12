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
class QTIModelTestCase extends UnitTestCase {
	
	protected $qtiService;
	
	/**
	 * tests initialization
	 * load qti service
	 */
	public function setUp(){		
		TaoTestRunner::initTest();
		$this->qtiService = taoQTI_models_classes_QTI_Service::singleton();
	}
	
	
	/**
	 * test the QTI objects persistance
	 */
	public function testPersitance(){
		
		taoQTI_models_classes_QTI_Data::setPersistence(true);
		
		//load an item
		$qtiParser = new taoQTI_models_classes_QTI_Parser(dirname(__FILE__).'/samples/choice_multiple.xml');
		$item = $qtiParser->load();
		
		$this->assertTrue($qtiParser->isValid());
		$this->assertNotNull($item);
		$this->assertIsA($item, 'taoQTI_models_classes_QTI_Item');
		
		$serial = $item->getSerial();
		
		// item is no longer saved by destruction, but during creation 
		// unset($item);

		
		$savedItem = $this->qtiService->getItemBySerial($serial);
		$this->assertNotNull($savedItem);
		$this->assertIsA($savedItem, 'taoQTI_models_classes_QTI_Item');
	
		foreach($savedItem->getInteractions() as $interaction){
			foreach($interaction->getChoices() as $choice){
				$composing = $this->qtiService->getComposingData($choice);
				$this->assertIsA($composing, 'taoQTI_models_classes_QTI_Interaction');
				$this->assertEqual($interaction->getSerial(), $composing->getSerial());
				break;
			}
			break;
		}
		
		//try to remove 
		foreach($savedItem->getInteractions() as $interaction){
			foreach($interaction->getChoices() as $choice){
				$choiceSerial = $choice->getSerial();
				$this->assertNotNull($this->qtiService->getDataBySerial($choiceSerial, 'taoQTI_models_classes_QTI_Choice'));
				$this->assertTrue($interaction->removeChoice($choice));
				$this->assertNull($this->qtiService->getDataBySerial($choiceSerial, 'taoQTI_models_classes_QTI_Choice'));
				break;
			}
			$interactionSerial = $interaction->getSerial();
			$this->assertNotNull($this->qtiService->getInteractionBySerial($interactionSerial));
			$this->assertTrue($savedItem->removeInteraction($interaction));
			
			$this->assertNull($this->qtiService->getInteractionBySerial($interactionSerial));
			break;
		}
		

		//real remove
		taoQTI_models_classes_QTI_Data::setPersistence(false);
		unset($savedItem);
		
		$this->assertNull($this->qtiService->getItemBySerial($serial));
	}
	
	/**
	 * test the building of item from all the samples
	 */
	public function testSamples(){
		
		//check if samples are loaded 
		foreach(glob(dirname(__FILE__).'/samples/*.xml') as $file){	
		
			$qtiParser = new taoQTI_models_classes_QTI_Parser($file);
			$item = $qtiParser->load();
			
			$this->assertTrue($qtiParser->isValid());
			$this->assertNotNull($item);
			$this->assertIsA($item, 'taoQTI_models_classes_QTI_Item');
			
			foreach($item->getInteractions() as $interaction){
				$this->assertIsA($interaction, 'taoQTI_models_classes_QTI_Interaction');
				
				foreach($interaction->getChoices() as $choice){
					$this->assertIsA($choice, 'taoQTI_models_classes_QTI_Choice');
				}
			}
		}
	}
	
}
?>