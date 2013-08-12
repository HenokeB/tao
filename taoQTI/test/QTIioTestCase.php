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
class QTIioTestCase extends UnitTestCase {
	
	protected $qtiService;
	protected $itemService;
	
	/**
	 * tests initialization
	 */
	public function setUp(){		
		TaoTestRunner::initTest();
		$this->qtiService  = taoQTI_models_classes_QTI_Service::singleton();
		$this->itemService = taoItems_models_classes_ItemsService::singleton();
	}
	
	
	/**
	 * test basically the import and deployment of QTI items
	 */
	public function testDeploy(){
		
		taoQTI_models_classes_QTI_Data::setPersistence(false);

		foreach(glob(dirname(__FILE__).'/samples/*.xml') as $file){	
		
			$qtiItem = $this->qtiService->loadItemFromFile($file);
			$this->assertNotNull($qtiItem);
			$this->assertIsA($qtiItem, 'taoQTI_models_classes_QTI_Item');
			
			$rdfItem = $this->itemService->createInstance($this->itemService->getRootClass());
			$this->assertNotNull($rdfItem);
			$this->assertIsA($rdfItem, 'core_kernel_classes_Resource');
			
			$rdfItem->setPropertyValue(new core_kernel_classes_Property(TAO_ITEM_MODEL_PROPERTY), TAO_ITEM_MODEL_QTI);
			$this->assertTrue($this->itemService->hasItemModel($rdfItem, array(TAO_ITEM_MODEL_QTI)));
			
			$this->assertTrue($this->qtiService->saveDataItemToRdfItem($qtiItem, $rdfItem));
			
			$deployParams = array(
				'delivery_server_mode'	=> false,
				'matching_server'		=> false,
				'qti_lib_www'			=> BASE_WWW .'js/QTI/',
				'qti_base_www'			=> BASE_WWW .'js/QTI/'
			);
			
			$basePreview = common_ext_ExtensionsManager::singleton()->getExtensionById('taoItems')->getConstant('BASE_PREVIEW');
			$folderName = substr($rdfItem->getUri(), strpos($rdfItem->getUri(), '#') + 1);
        	$itemFolder = $basePreview . $folderName;
        	$itemPath = "{$itemFolder}/index.html";
			if(!is_dir($itemFolder)){
        		mkdir($itemFolder);
        	}
        	$itemUrl = tao_helpers_Uri::getUrlForPath($itemPath);
        	
        	//deploy the item
        	$this->assertTrue($this->itemService->deployItem($rdfItem, $itemPath, $itemUrl,  $deployParams));
			
			$this->assertTrue(!empty($itemUrl));
			$this->assertTrue(is_dir($itemFolder));
			
			//echo "<br /><iframe width='900px' height='400px' src='$itemUrl'></iframe><br />";
			
			$this->assertTrue($this->itemService->deleteItem($rdfItem));
			if (file_exists($itemPath)) {
				$this->fail('itemPath was not deleted');
				tao_helpers_File::remove($itemPath, true);
			}
		}
	}

}
?>