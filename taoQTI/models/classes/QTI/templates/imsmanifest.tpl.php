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
<manifest 
	xmlns="http://www.imsglobal.org/xsd/imscp_v1p1" 
	xmlns:imsqti="http://www.imsglobal.org/xsd/imsqti_v2p0" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.imsglobal.org/xsd/imscp_v1p1 imscp_v1p1.xsd http://www.imsglobal.org/xsd/imsqti_v2p0 imsqti_v2p0.xsd"
	identifier="<?=$manifestIdentifier?>"
>
    <organizations/>
    <resources>
        <resource identifier="<?=$qtiItem->getIdentifier()?>" type="imsqti_item_xmlv2p0" href="<?=$qtiFilePath?>">
            <metadata>
                <schema>IMS QTI Item</schema>
                <schemaversion>2.0</schemaversion>      
                <imsqti:qtiMetadata>
                    <imsqti:timeDependent><?=($qtiItem->getOption('timeDependent'))?'true':'false'?></imsqti:timeDependent>
                    <?foreach($qtiItem->getInteractions() as $interaction):?>
                    <imsqti:interactionType><?=$interaction->getType()?></imsqti:interactionType>
                     <?endforeach?>
                    <imsqti:feedbackType><?=($qtiItem->getOption('adaptive'))?'adaptive':'nonadaptive'?></imsqti:feedbackType>
                    <imsqti:solutionAvailable></imsqti:solutionAvailable>
                    <imsqti:toolName><?=$qtiItem->getOption('toolName')?></imsqti:toolName>
                    <imsqti:toolVersion><?=$qtiItem->getOption('toolVersion')?></imsqti:toolVersion>
                    <imsqti:toolVendor>TAO Initiative</imsqti:toolVendor>
                </imsqti:qtiMetadata>
            </metadata>
            <file href="<?=$qtiFilePath?>"/>
            <?foreach($medias as $media):?>
            <file href="<?=$media?>"/>
            <?endforeach?>
        </resource>
    </resources>
</manifest>
