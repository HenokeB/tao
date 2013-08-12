<script type="text/javascript" src="<?=BASE_WWW?>js/taoCodingConciliate.js"></script>
<script type="text/javascript" src="<?=BASE_WWW?>js/taoCoding.js"></script>
<script type="text/javascript" src="<?=BASE_WWW?>../../taoQTI/views/js/QTI/qti.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=BASE_WWW?>css/taoCoding.css" />
<!--<link rel="stylesheet" type="text/css" href="<?=BASE_WWW?>../../taoItems/views/js/QTI/css/qti.min.css" />-->

<div class="main-container" id="coding_finalgrade">
	<div id="selectors">
		<form>
			<div>
				<?= __('Deliveries') ?>
				<select id="deliveries" name="deliveries" size="10">
<?php foreach (get_data('deliveryList') as $d): ?>
					<option value="<?= $d['id'] ?>" class="<?php if ($d['closed']) echo 'closed' ?>"><?= str_replace('_', ' ', $d['label']) ?></option>
<?php endforeach; ?>
				</select>
			</div>
			<div id="filter-selector">
				<?= __('Facettes') ?>
				<select id="filters" name="filters" size="10"></select>
			</div>
			<div id="stats">
				<?= __('Statistics') ?>
				<div id="stats-content"></div>
			</div>
			<div class="actions"><input type="submit" id="grader" value="<?= __('Conciliate') ?>" /> <input type="submit" id="closer" value="<?= __('Close') ?>" /></div>
		</form>

		<div class="history containerDisplay">
			<span class="title"><?= __('Test-takers scored') ?></span>
			<ul></ul>
			<button><?= __('Next test taker') ?></button>
		</div>
	</div>

	<div id="navigation"><ul class="nav"></ul></div>
	<div id="evalitems"></div>

	<div class="clearfix"></div>
	<div id="footer-navigation">
		<button id="nav_prev"><?= __('Previous') ?></button> <button id="nav_next"><?= __('Next') ?></button>
	</div>

	<div id="commentForm" title="<?= __('Grade comment') ?>"><textarea></textarea></div>
</div>