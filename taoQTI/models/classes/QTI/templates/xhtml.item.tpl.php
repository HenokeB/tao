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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>QTI Item <?=$identifier?></title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?=$ctx_qti_base_www?>css/qti.min.css" media="screen" />
<?if($hasSlider):?>
	<link rel="stylesheet" type="text/css" href="<?=$ctx_taobase_www?>css/custom-theme/jquery-ui-1.8.22.custom.css" />
<?endif?>

	<!-- user CSS -->
<?foreach($stylesheets as $stylesheet):?>
	<link rel="stylesheet" type="text/css" href="<?=$stylesheet['href']?>" media="<?=$stylesheet['media']?>" />
<?endforeach?>

	<!-- LIB -->
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery-ui-1.8.23.custom.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/json.min.js"></script>
<?if($hasGraphics):?>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/raphael/raphael.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/raphael/raphael-collision.min.js"></script>
<?endif?>

<?if($hasUpload):?>
	<link rel="stylesheet" type="text/css" href="<?=$ctx_taobase_www?>js/jquery.uploadify/uploadify.css" media="screen" />
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery.uploadify/jquery.uploadify.v2.1.4.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery.uploadify/swfobject.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/AsyncFileUpload.js"></script>
<?endif?>

	<script type="text/javascript">
		var qti_base_www = "<?=$ctx_qti_base_www?>";
		var root_url = "<?=$ctx_root_url?>";
		<?if($ctx_debug):?>
		var qti_debug = true;
		<?endif?>
	</script>

	<!-- JS REQUIRED -->
	<script type="text/javascript" src="<?=$ctx_base_www?>js/taoApi/taoApi.min.js"></script>
<?if(!$ctx_raw_preview):?>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/wfApi.min.js"></script>
<?endif?>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>taoMatching.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>qti.min.js"></script>
</head>
<body>
	<div id='<?=$identifier?>' class="qti_item">
		<h1><?=$options['title']?></h1>
		<div class="qti_item_body">
			<?=$data?>

			<!-- validation button -->
			<div class="qti_control">
			<?if($ctx_raw_preview):?>
				<a href="#" id="qti_validate" style="visibility:hidden;">Validate</a>
			<?else:?>
				<a href="#" id="qti_validate">Validate</a>
			<?endif?>
			</div>
		</div>
	</div>
</body>
</html>
