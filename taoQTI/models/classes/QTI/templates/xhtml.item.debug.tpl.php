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
<?if(DEBUG_MODE):?>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/util.js"></script>
<?endif;?>	
<?if($hasGraphics):?>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/raphael/raphael.min.js"></script>
	<?if(DEBUG_MODE):?>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/raphael/raphael-collision/raphael-collision.js"></script>
	<?else:?>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/raphael/raphael-collision.min.js"></script>
	<?endif;?>
<?endif;?>

<?if($hasUpload):?>
	<link rel="stylesheet" type="text/css" href="<?=$ctx_taobase_www?>js/jquery.uploadify/uploadify.css" media="screen" />
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery.uploadify/jquery.uploadify.v2.1.4.min.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/jquery.uploadify/swfobject.js"></script>
	<script type="text/javascript" src="<?=$ctx_taobase_www?>js/AsyncFileUpload.js"></script>
<?endif;?>

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
	<?if(DEBUG_MODE):?>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/constants.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/context.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/api.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/wfApi.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/ProcessExecution.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/ActivityExecution.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/Variable.js"></script>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/src/RecoveryContext.js"></script>
	<?else:?>
	<script type="text/javascript" src="<?=$ctx_root_url?>/wfEngine/views/js/wfApi/wfApi.min.js"></script>
<?endif;?>
<?endif;?>

<?if(DEBUG_MODE):?>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Variable.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.VariableFactory.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.AreaMap.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.BaseTypeVariable.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Collection.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Shape.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Ellipse.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.List.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Map.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Matching.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.MatchingRemote.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Poly.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/class.Tuple.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/matching_api.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>src/matching_constant.js"></script>
<?else:?>	
	<script type="text/javascript" src="<?=$ctx_qti_matching_www?>taoMatching.min.js"></script>
<?endif;?>	
<?if(DEBUG_MODE):?>	
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/init.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/initTaoApis.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/ResultCollector.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/Interaction.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/Widget.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/associate.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/button.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/choice.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/match.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/order.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/spot.js"></script>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>src/widgets/text.js"></script>
<?else:?>
	<script type="text/javascript" src="<?=$ctx_qti_lib_www?>qti.min.js"></script>
<?endif;?>
	
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
