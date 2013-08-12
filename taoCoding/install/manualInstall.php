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
require_once dirname(__FILE__).'/../../tao/includes/raw_start.php';

	$sqlfile = dirname(__FILE__).'/db/extension.sql';
	$rdfs = array(
		dirname(__FILE__).'/../models/ontology/taocoding.rdf'
	);

	$dbCreator = new tao_install_utils_DbCreator(DATABASE_URL, DATABASE_LOGIN, DATABASE_PASS, SGBD_DRIVER);
	$dbCreator->setDatabase(DATABASE_NAME);
	
	try {
		$dbCreator->load($sqlfile);
	} catch (Exception $e) {
		echo "already registered;\n";
	}
		
	$modelCreator = new tao_install_utils_ModelCreator(LOCAL_NAMESPACE);
	foreach ($rdfs as $rdfpath) {
		$xml = simplexml_load_file($rdfpath);
		$attrs = $xml->attributes('xml', true);
		if(!isset($attrs['base']) || empty($attrs['base'])){
			throw new Exception('The namespace of the rdf file to import has to be defined with the "xml:base" attribute of the ROOT node');
		}
		$ns = (string) $attrs['base'];
		//import the model in the ontology
		$modelCreator->insertModelFile($ns, $rdfpath);
	}
	echo "Done";
