<div class="main-container">
	<div id="form-title" class="ui-widget-header ui-corner-top ui-state-default">
		<?=get_data('formTitle')?>
	</div>
	<div id="form-container" class="ui-widget-content ui-corner-bottom">
		<table>
			<tr>
				<th>key</th>
				<th>secret</th>
				<th>launch URL</th>
			</tr>
		<?foreach ($links as $link) :?>
			<tr>
				<td><?= $link['key']?></td>
				<td><?= $link['secret']?></td>
				<td><a href="<?= $link['url']?>"><?= $link['url']?></a></td>
			</tr>
		<?endforeach;?>
		</table>
		<a class="nav" href="<?= _url('createDeliveryLink', 'LinkManagement', null, array('uri' => $delivery));?>">
			create Link
		</a>
	</div>
</div>
<?include(TAO_TPL_PATH . 'footer.tpl');?>
