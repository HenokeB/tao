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
$(function() {
	$('#deliveries').change(function() {
		
		if ($('#deliveries').val() == null) {
			
			$('#filter-selector, #stats, .grade-all').hide();
			$('#selectors form input#grader, #selectors form input#closer').hide();
			return;
		}

		if ($('#deliveries option:selected').hasClass('closed') || codingAction == 'Coding') {
			//Get filters
			$.ajax({
				type: "POST",
				url: root_url + "/taoCoding/"+codingAction+"/getFilter",
				data: 'delivery='+$('#deliveries').val(),
				dataType: 'json',
				success: function(r) {
					$('#filter-selector').show();
					$('.grade-all').show();
					if ($('#stats').length) {
						$('#stats').hide();
						$('#selectors form input#closer').hide();
					}
					$('#selectors form input#grader').show();
					html = '';
					for (i in r.filter) {
						html += '<optgroup ref="'+r.filter[i].id+'" label="'+r.filter[i].label+'">';
						html += '<option value="'+r.filter[i].id+'" class="entire">&nbsp;'+__('All')+' \''+r.filter[i].label+'\'</option>';
						for (j in r.filter[i].values) {
							html += '<option value="'+r.filter[i].values[j].id+'">&nbsp;'+r.filter[i].values[j].label+'</option>';
						}
						html += '</optgroup>';
					}
					$('#filters').html(html);
					$('#filters option:first-child').prop('selected', 'selected');
				}
			});
		} else {
			//Get stats
			$.ajax({
				type: "POST",
				url: root_url + "/taoCoding/"+codingAction+"/getGradingStatistics",
				data: 'delivery='+$('#deliveries').val(),
				dataType: 'html',
				success: function(r) {
					$('#stats-content').html(r);
					$('#stats').show();
					$('#filter-selector').hide();
					$('#grade-all').hide();
					$('#selectors form input#grader').hide();
					$('#selectors form input#closer').show();
				}
			});
		}
	}).change();

	$('#filters').change(function() {
		if ($('#deliveries').val() == null) {
			$('#selectors form input#grader .grade-all').hide();
			return;
		}
	});
	//$('.grade-all').click(function(e){e.preventDefault();alert('yipie');})
	$('#selectors form input#grader ,.grade-all').click(function(e) {
		//Get result
		e.preventDefault();
		fdata = {};
		fdata['delivery'] = $('#deliveries').val();
		if (!($(this).hasClass('grade-all')))
		{fdata['filter'] = getFilter();}
		fdata['showList'] = true;
		$.ajax({
			type: "POST",
			url: root_url + "/taoCoding/"+codingAction+"/openDelivery",
			data: fdata,
			dataType: 'json',
			success: function(r) {
				$('#selectors form').hide();
				$('.history, #evalitems, #navigation, #footer-navigation').show();
				$('.history ul').empty();

				//Add history and current dr
				for (i in r.deliveryResultsList) $('.history ul').append('<li id="'+uriEncode(r.deliveryResultsList[i].id)+'" class="state'+r.deliveryResultsList[i].status+'">'+r.deliveryResultsList[i].label+getHistoryStateIcon(r.deliveryResultsList[i].status)+'</li>');
				setCurrentDR(r.result_id, r.testTakerLabel);

				$('.history li').click(function() {getDR($(this));});

				setNavigation(r.itemsList);
				if (r.firstItem != 'null') openItem(r.firstItem);

				//Indicate the delivery
				$('.history .title').html($('#deliveries option[value="'+$('#deliveries').val()+'"]').text()+' <a href="#">('+__('change')+')</a>');
				$('.history .title a').click(function() {
					$('#deliveries').val(null);
					$('.history, #evalitems, #navigation, #footer-navigation').hide();
					$('#selectors form').show();
					$('#deliveries').change();
					$.ajax();
					
				});
			}
		});
		//return false;
	});

	

	$('#selectors form input#closer').click(function(e) {
		//Get result
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: root_url + "/taoCoding/"+codingAction+"/closeGrading",
			data: 'delivery='+$('#deliveries').val(),
			dataType: 'json',
			success: function(r) {
				if (r.success) {
					$('#deliveries option:selected').addClass('closed').parent().change();
				}
			}
		});
		//return false;
	});

  $('.history, #evalitems, #navigation, #footer-navigation').hide();

	$('#commentForm').hide();

  $('#nav_prev').click(function(){
		//Prev item, filter, dr
		$prev = $('#navigation .filter.open li.item.sel').prev('li.item');

		if ($prev.length == 0) {
			//Expand prev filter
			$prev = $('#navigation .filter.open').prev('.filter');
			$('span', $prev).click();
			$('li.item:last-child', $prev).click();
		} else $prev.click();
		refreshPrevNext();
	});
	$('#nav_next').click(function(){
		//Next item, filter, dr
		$next = $('#navigation .filter.open li.item.sel').next('li.item');
		if ($next.length == 0) {
			//Expand next filter
			$next = $('#navigation .filter.open').next('.filter');
			$('span', $next).click();
		} else $next.click();
		refreshPrevNext();
	});

	//GetNext
	$('.history button').click(function(){
		nextDR();
	});
});

function openItem(item) {
	$('#evalitems .item').hide().removeClass('sel');
	itemid = uriEncode(item.itemid); //uriEncode(item.testid)+'___'+
	
	if ($('#'+itemid).length) {
		$('#'+itemid).show().addClass('sel');
	} else {
		//Install the Item
		$('#evalitems').append('<div class="item sel containerDisplay" id="'+itemid+'" item-type="'+item.type+'"><span class="title">'+item.label+'</span><a href="'+item.previewURL+'" class="preview" target="_blank">'+__('Preview')+'</a></div>');
		switch (item.type) {
			case 'http://www.tao.lu/Ontologies/TAOItem.rdf#QTI':
				for (i in item.content.interactions) {
					interactionid = itemid+'___'+uriEncode(item.content.interactions[i].id);
					$('#evalitems #'+itemid).append('<div class="interaction" id="'+interactionid +'"><div class="qitem">'+item.content.interactions[i].renderedView+'</div></div>');
					$('#evalitems #'+itemid+' #'+interactionid).append('<div class="scoring"><input type="text" class="item-scoring" /></div><div class="actions"><span class="guidelines tooltip" data-tooltip="'+__('Guidelines')+'"></span><span class="correct tooltip" data-tooltip="'+__('Correct')+'"></span><span class="comment tooltip" data-tooltip="'+__('Comment')+'"></span></div>');
					if (item.content.interactions[i].comment.length) $('#evalitems #'+itemid+' #'+interactionid+' .comment').addClass('filled');
					if (item.content.interactions[i].score == undefined) $('#evalitems #'+itemid+' #'+interactionid+' .comment').hide();
					$('#evalitems #'+itemid+' #'+interactionid).append('<div class="guidelines-content"><p>'+item.content.interactions[i].guidelines+'</p></div><div class="correct-content"></div><div class="comment-content">'+item.content.interactions[i].comment+'</div>');
					for (k in item.content.interactions[i].correct) {
						$('#evalitems #'+itemid+' #'+interactionid+' .correct-content').append('<p>'+item.content.interactions[i].correct[k]+'</p>');
					}
					$('#evalitems #'+itemid+' #'+interactionid+' .guidelines-content').append('<span class="title">'+__('Guidelines')+'</span><span class="closer tooltip" data-tooltip="'+__('Close')+'">x</span>');
					$('#evalitems #'+itemid+' #'+interactionid+' .guidelines-content .closer').click(function(){$(this).parent().hide();});
					$('#evalitems #'+itemid+' #'+interactionid+' .correct-content').append('<span class="title">'+__('Correct')+'</span><span class="closer tooltip" data-tooltip="'+__('Close')+'">x</span>');
					$('#evalitems #'+itemid+' #'+interactionid+' .correct-content .closer').click(function(){$(this).parent().hide();});

					//Delete button no-needed with empty content
					if (item.content.interactions[i].guidelines.length == 0) {
						$('#evalitems #'+itemid+' #'+interactionid+' .actions .correct').remove();
						$('#evalitems #'+itemid+' #'+interactionid+' .correct-content').remove();
					}
					if (item.content.interactions[i].correct.length == 0) {
						$('#evalitems #'+itemid+' #'+interactionid+' .actions .guidelines').remove();
						$('#evalitems #'+itemid+' #'+interactionid+' .guidelines-content').remove();
					}

					//Scoring widget
					$scoring = $('#'+interactionid +' .scoring');
					$scoring.prop('scale-type', item.content.interactions[i].scale.type);
					switch (item.content.interactions[i].scale.type) {
						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#DiscreteScale':
							$scoring.append('<ul class="score-buttons"></ul>');
							$('.item-scoring', $scoring).hide();

							if (item.content.interactions[i].scale.step > 0 && item.content.interactions[i].scale.max / item.content.interactions[i].scale.step <= 100) {
								for (j = item.content.interactions[i].scale.min; j <= item.content.interactions[i].scale.max; j+=item.content.interactions[i].scale.step) {
									v = Math.round(j*100)/100;
									$('.score-buttons', $scoring).append('<li value="'+v+'">'+v+'</li>');
								}
								w = $('.score-buttons', $scoring).width();
								nb = $('.score-buttons li', $scoring).length;
								$('.score-buttons li', $scoring).each(function(j) {
									$(this).width((w / nb) - parseInt($(this).css('border-right-width')) + 'px'); //.prop('value', $(this).text());
									//Preselect
									if (item.content.interactions[i].score != undefined && item.content.interactions[i].score == parseFloat($(this).text())) $(this).addClass('sel');
									//Tooltiping
									if ($(this).width() < 10) $(this).prop('data-tooltip', $(this).text()).text('').addClass('tooltip');
								}).click(function() {
									$('li', $(this).parent()).removeClass('sel');
									ids = this.parentNode.parentNode.parentNode.id.split('___');
									itemid		= uriDecode(ids[0]);
									responseid	= uriDecode(ids[1]);
									setScore(itemid, responseid, parseFloat($(this).prop('value')));
									$(this).addClass('sel');
								});
							}
							break;

						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#NumericalScale':
							break;

						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#EnumerationScale':
							break;
					}

					$('#'+interactionid +' .actions .guidelines').click(function() {$('.guidelines-content', $(this).parent().parent()).toggle()});
					$('#'+interactionid +' .actions .correct').click(function() {$('.correct-content', $(this).parent().parent()).toggle()});
					$('#'+interactionid +' .actions .comment').click(function() {
						if (!$('.comment-content', $(this).parent().parent()).length) $('#commentForm textarea').val('');
						else $('#commentForm textarea').val($('.comment-content', $(this).parent().parent()).text());
						$('#commentForm').prop('pid', $(this).parent().parent().prop('id'));
						$('#commentForm').dialog({
							height: 232,
							width: 300,
							modal: true,
							resizable: true,
							buttons: [
								{
									text: __("Save"),
									click: function() {
										//Save
										$txta = $('textarea', $(this));
										$('.comment-content', $('#'+$(this).prop('pid'))).text($txta.val());
										if ($txta.val().length) $('.comment', $('#'+$(this).prop('pid'))).addClass('filled').show();
										else $('.comment', $('#'+$(this).prop('pid'))).removeClass('filled').show();

										ids = $(this).prop('pid').split('___');
										itemid			= uriDecode(ids[0]);
										responseid	= uriDecode(ids[1]);
										setComment(itemid, responseid, $txta.val());

										$(this).dialog("close");
									}
								},
								{
									text: __("Cancel"),
									click: function() {
										$(this).dialog("close");
									}
								}
							]
						});
					});
				}
				//Score Total
				$('#evalitems #'+itemid).append('<div class="total"></div>');
				break;

			default:
				$('#evalitems #'+itemid).text(__('Not implemented !'));
				break;
		}

		$('#evalitems #'+itemid+' .guidelines-content, #evalitems #'+itemid+' .correct-content, #evalitems #'+itemid+' .comment-content').hide();
		refreshScore();

		//Tooltip
		$('#evalitems #'+itemid+' .tooltip').mouseover(function() {
			//showTooltip($(this).removeClass('tooltip').css('position', 'relative'));
		});
	}
	refreshPrevNext();
}

function showTooltip($obj) {
	$('.tooltipjs, .tooltipjsarrow').remove();
	$tt = $('<div class="tooltipjs">'+$obj.attr('data-tooltip')+'</div>').appendTo($obj);
	$tt.css({
		top: (parseInt($obj.innerHeight()) + 16) + 'px',
		left: ((parseInt($obj.innerWidth()) - parseInt($tt.innerWidth())) / 2) + 'px'
	});
	if (($tt.outerWidth() + $tt.offset().left) > $(window).width()) $tt.css('left', ($(window).width() - $obj.offset().left - $tt.outerWidth()) + 'px');
	$tta = $('<div class="tooltipjsarrow"></div>').appendTo($obj);
	$tta.css({
		top: (parseInt($tt.css('top')) - 25) + 'px',
		left: ((parseInt($obj.innerWidth()) - 26) / 2) + 'px'
	}); //.animate({top: '-=25px', opacity: 1}, 200);
	$tt.delay(2000).fadeOut();
	$tta.delay(2000).fadeOut();
	//$tta.animate({}, 1500, '', function() {}).animate({top: "39px"}, 1000, '', function() { $(this).remove() });
}

function navAccordion($obj) {
	$obj.addClass('accordion');
	$('li.filter:first-child', $obj).addClass('first open');
	$('.filter ul', $obj).hide();
	$('.open ul', $obj).show();
	//$('li.filter:last-child', $obj).addClass('open');
	//Init sliding
	$('.filter span', $obj).click(function(){
		$p = $(this).parent();
		if ($p.hasClass('open')) return;
		$('.filter ul', $obj).hide();

		$('.filter.open', $obj).removeClass('open');
		$p.addClass('open');
		$('ul', $p).show();
		//Open first of the filter
		$('.filter.open li:first-child', $obj).click();
	});
	$('.filter:first span', $obj).click();
	$('.filter.open li:first-child', $obj).addClass('sel');
}

function setNavigation(itemsList) {
	$('#navigation .nav').empty();
	//Fill the filter name to have the space occupy
	for (s in itemsList) {
		ulid = uriEncode(itemsList[s].id);
		$('#navigation .nav').append('<li class="filter"><span>'+itemsList[s].label+'</span><ul id="'+ulid+'" class="items"></ul></li>');
		state = 0;
		if (itemsList[s].items.length) {
			for (i in itemsList[s].items) {
				itemsList[s].items[i].id = uriEncode(itemsList[s].items[i].itemid); //uriEncode(itemsList[s].items[i].testid)+'___'+
				$('#navigation .nav #'+ulid).append('<li ref="'+itemsList[s].items[i].id+'" class="item state'+itemsList[s].items[i].state+'">'+itemsList[s].items[i].label+getHistoryStateIcon(itemsList[s].items[i].state)+'</li>')
				state += parseInt(itemsList[s].items[i].state);
			}
			statecoef = state / itemsList[s].items.length;
			if (statecoef > 0 && statecoef < 2) state = 1;
			else if (statecoef == 2) state = 2;
		}
		$('> span', $('#navigation .nav #'+ulid).parent()).addClass('state'+state).append(getHistoryStateIcon(state));
	}
	navAccordion($('#navigation .nav'));
	$('.nav .item').click(function() {
		itemid = uriDecode($(this).attr('ref'));
		if (!$('#'+$(this).prop('ref')).length) {
			$.ajax({
				type: "POST",
				url: root_url + "/taoCoding/"+codingAction+"/openItem",
				data: 'dr='+uriDecode($('#selectors .history ul li.sel').prop('id'))+'&itemid='+itemid, //'&testid='+testid+
				dataType: 'json',
				success: function(r) {
					openItem(r);
				}
			});
		} else openItem({itemid: itemid}); //testid: testid,
		$('#navigation .filter .item.sel').removeClass('sel');
		$(this).addClass('sel');
		refreshPrevNext();
	});
}

function setComment(itemid, gradeid, comment) {
	$.ajax({
		type: "POST",
		url: root_url + "/taoCoding/"+codingAction+"/saveComment",
		data: 'gradeid='+gradeid+'&comment='+comment+'&itemid='+itemid,
		dataType: 'json',
		success: function(r) {
			//...
		}
	});
}

var uriHT = {};
var uriHTi = 0;
function uriEncode(uri) {
	if (uriHT[uri] == undefined) {
		n = 'uri_' + uriHTi++;
		uriHT[uri] = n;
		uriHT[n] = uri;
		return n;
	} else return uriHT[uri];
}

function uriDecode(uri) {
	if (uriHT[uri] != undefined) n = uriHT[uri];
	else n = 'error'; //Else error
	return n;
}

function getFilter() {
	filter = {};
	filter['restrictions'] = {};
	if (!$('#filters :selected').hasClass('entire')) {
		refParent = $('#filters :selected').parent().attr('ref');
		filter['restrictions'][refParent] = $('#filters').val();
		filter['groupBy'] = refParent;
	} else filter['groupBy'] = $('#filters').val();
	return filter;
}

function setCurrentDR(id, label) {
	id = uriEncode(id);
	$('.history ul .sel').removeClass('sel');
	if ($('#'+id).length) $('#'+id).addClass('sel');
	else $('<li id="'+id+'" class="sel">'+label+'</li>').appendTo($('.history ul')).click(function() {getDR($(this));});
}

function refreshPrevNext() {
  if ($('#navigation > ul > li:first-child li:first-child').hasClass('sel')) $('#nav_prev').prop('disabled', 'disabled');
	else $('#nav_prev').removeAttr('disabled');
	if ($('#navigation > ul > li:last-child li:last-child').hasClass('sel')) $('#nav_next').prop('disabled', 'disabled');
	else $('#nav_next').removeAttr('disabled');
}

function getHistoryStateIcon(state) {
	img = '';
	switch (state) {
		case 2:
			img = 'checked.png';
			alt = __('graded');
			break;

		case 12:
			img = 'checked.png';
			alt = __('conciliated');
			break;
	}
	if (img != '') return ' <img src="'+root_url+'/taoCoding/views/img/'+img+'" alt="'+alt+'"/>';
	else return '';
}