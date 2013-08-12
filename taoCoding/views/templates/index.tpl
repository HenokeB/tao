<script type="text/javascript" src="<?=BASE_WWW?>js/taoCodingScoring.js"></script>
<script type="text/javascript" src="<?=BASE_WWW?>js/taoCoding.js"></script>
<script type="text/javascript" src="<?=BASE_WWW?>../../taoQTI/views/js/QTI/qti.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=BASE_WWW?>css/taoCoding.css" />
<!--<link rel="stylesheet" type="text/css" href="<?=BASE_WWW?>../../taoItems/views/js/QTI/css/qti.min.css" />-->

<div class="main-container" id="coding_scoreconcil">
	<div id="selectors">
		<form>
			<div>
				<?= __('Select the delivery for grading:') ?>
				<select id="deliveries" name="deliveries" size="10">
<?php foreach (get_data('deliveriestoEval') as $id => $label): ?>
					<option value="<?= $id ?>"><?= str_replace('_', ' ', $label) ?></option>
<?php endforeach; ?>
				</select>
			</div>
			<div class="grade-all">
				<input type="submit" id="gradeAllItems" value="<?= __('Grade All Items') ?>" />
			</div>
			<div id="filter-selector">
				 <?= __('(Optionnal) restrict grading to:') ?>
			    <div>
				    
				    <select id="filters" name="filters" size="10"></select>
			    </div>
			</div>
			<div class="actions"><input type="submit" id="grader" value="<?= __('Grade Items') ?>" /></div>
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