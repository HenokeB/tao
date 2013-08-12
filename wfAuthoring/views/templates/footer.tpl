<script type="text/javascript">
$(function(){

	<?if(get_data('uri') && get_data('classUri') && strpos(get_data('module'), 'Process') !== false):?>
		helpers.updateTabUrl(uiBootstrap.tabs, 'process_authoring', "<?=_url('authoring', 'Process', null, array('uri' => get_data('uri'), 'classUri' => get_data('classUri') ))?>");
	<?else:?>
		uiBootstrap.tabs.tabs('disable', helpers.getTabIndexByName('process_authoring'));
	<?endif?>

	<?if(get_data('reload')):?>
		uiBootstrap.initTrees();
	<?endif?>
});
</script>