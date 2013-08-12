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
<responseDeclaration identifier="<?=$identifier?>" <?=$rowOptions?> >
    <?if(isset($correctResponses) && count($correctResponses) > 0):?>
        <correctResponse>
            <?foreach($correctResponses as $value):?>
            	<value><?=$value?></value>
            <?endforeach?>
        </correctResponse>
	<?endif?>
	
	<?if(isset($mapping) && count($mapping) > 0):?>
        <mapping defaultValue="<?echo isset($mappingDefaultValue)?floatval($mappingDefaultValue):0;?>" <?=$mappingOptions?>>
            <?foreach($mapping as $key => $value):?>
            	<mapEntry mapKey="<?=$key?>" mappedValue="<?=$value?>"/>
            <?endforeach?>
        </mapping>
	<?endif?>
	
	<?if(isset($areaMapping) && count($areaMapping) > 0):?>
        <areaMapping defaultValue="<?echo isset($areaMappingDefaultValue)?floatval($areaMappingDefaultValue):0;?>" <?=$areaMappingOptions?>>
            <?foreach($areaMapping as $areaMapEntry):?>
            	<areaMapEntry <?foreach($areaMapEntry as $key => $value):?><?=$key?>="<?=$value?>" <?endforeach?> />
            <?endforeach?>
        </areaMapping>
	<?endif?>
</responseDeclaration>
