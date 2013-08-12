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
include_once("../../../../tao/lib/jstools/minify.php");

//minimify QTI Javascript sources using JSMin
$jsFiles = array (
	'./src/Widget.js',
	'./src/widgets/associate.js',
	'./src/widgets/button.js',
	'./src/widgets/choice.js',
	'./src/widgets/match.js',
	'./src/widgets/order.js',
	'./src/widgets/spot.js',
	'./src/widgets/text.js',
	'./src/ResultCollector.js',
	'./src/Interaction.js',
	'./src/init.js',
	'./src/initTaoApis.js'			//remove this line to use the QTI API without TAO
);
minifyJSFiles($jsFiles, "qti.min.js");

//minimify QTI CSS sources using JSMin
$cssFiles = array (
	'../../css/normalize.css',
	'../../css/base.css',
	'./css/qti.css'
);
minifyCSSFiles($cssFiles, dirname(__FILE__)."/css/qti.min.css");

exit(0);
?>
