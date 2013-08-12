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
var codingAction = 'Coding';

function nextDR() {
	$('.history button, #navigation #nav_next').prop('disabled', 'disabled');
	fdata = {};
	fdata['delivery'] = $('#deliveries').val();
	fdata['filter'] = getFilter();
	fdata['showList'] = false;
	$.ajax({
		type: "POST",
		url: root_url + "/taoCoding/"+codingAction+"/getNextResult",
		data: fdata,
		dataType: 'json',
		success: function(r) {
			if (r.success) {
				setCurrentDR(r.result_id, r.testTakerLabel);
				$('#evalitems').empty();
				setNavigation(r.itemsList);
				if (r.firstItem != 'null') openItem(r.firstItem);
			} else helpers.createErrorMessage(r.msg);
			$('.history button, #navigation #nav_next').removeAttr('disabled');
		}
	});
}

function setScore(itemid, gradeid, gradevalue) {
	$.ajax({
		type: "POST",
		url: root_url + "/taoCoding/"+codingAction+"/gradeResponses",
		data: 'gradeid='+gradeid+'&gradevalue='+gradevalue+'&itemid='+itemid+'&dr='+uriDecode($('#selectors .history ul li.sel').prop('id')),
		dataType: 'json',
		success: function(r) {
			refreshScore();
			$('#evalitems #'+uriEncode(itemid)+'___'+uriEncode(gradeid)+' .comment').show();
		}
	});
}

function getDR($obj) {
	fdata = {};
	fdata['delivery'] = $('#deliveries').val();
	fdata['filter'] = getFilter();
	fdata['dr'] = uriDecode($obj.prop('id'));
	$.ajax({
		type: "POST",
		url: root_url + "/taoCoding/"+codingAction+"/getResult",
		data: fdata,
		dataType: 'json',
		success: function(r) {
			$('#evalitems').empty();
			setNavigation(r.itemsList);
			if (r.firstItem != 'null') openItem(r.firstItem);
		}
	});
	$('.history li.sel').removeClass('sel');
	$obj.addClass('sel');
	return false;
}

function refreshScore() {
	total = 0;
	max = 0;
	$('div.item.sel').each(function(i) {
		switch ($(this).prop('item-type')) {
			//QTI
			case 'http://www.tao.lu/Ontologies/TAOItem.rdf#QTI':
				$('.scoring', $(this)).each(function(j) {
					switch ($(this).prop('scale-type')) {
						//Discrete
						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#DiscreteScale':
							if ($('li.sel', $(this)).length) total += parseFloat($('li.sel', $(this))[0].getAttributeNode("value").nodeValue);
							max += parseFloat($('li:last-child', $(this))[0].getAttributeNode("value").nodeValue);
							break;

						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#NumericalScale':
							break;

						case 'http://www.tao.lu/Ontologies/TAOItem.rdf#EnumerationScale':
							break;
					}
				});
			break;
		}

		//Refresh item navigation
		sbs = $('.score-buttons', $(this)).length;
		sbss = $('.score-buttons li.sel', $(this)).length;
		$('#navigation li.open li.sel > img').remove();
		if (sbss == 0) $('#navigation li.open li.sel').addClass('state0').removeClass('state1').removeClass('state2').append(getHistoryStateIcon(0));
		else if (sbss < sbs) $('#navigation li.open li.sel').removeClass('state0').addClass('state1').removeClass('state2').append(getHistoryStateIcon(1));
		else if (sbss == sbs) $('#navigation li.open li.sel').removeClass('state0').removeClass('state1').addClass('state2').append(getHistoryStateIcon(2));

		//Refresh head navigation
		st0 = $('#navigation li.open li.state0').length;
		st1 = $('#navigation li.open li.state1').length;
		st2 = $('#navigation li.open li.state2').length;
		$("#navigation li.open span img").remove();
		if (st0 > 0 && st1 == 0 && st2 == 0) $('#navigation li.open span').addClass('state0').removeClass('state1').removeClass('state2').append(getHistoryStateIcon(0));
		else if (st0 == 0 && st1 == 0 && st2 > 0) $('#navigation li.open span').removeClass('state0').removeClass('state1').addClass('state2').append(getHistoryStateIcon(2));
		else $('#navigation li.open span').removeClass('state0').addClass('state1').removeClass('state2').append(getHistoryStateIcon(1));

		$('.total', $(this)).text(__('Item Total')+' : '+total+' / '+max);

		//Refresh global state
		st0 = $('#navigation li.filter span.state0').length;
		st1 = $('#navigation li.filter span.state1').length;
		st2 = $('#navigation li.filter span.state2').length;
		$("div.history li.sel img").remove();
		if (st0 > 0 && st1 == 0 && st2 == 0) $("div.history li.sel").append(getHistoryStateIcon(0));
		else if (st0 == 0 && st1 == 0 && st2 > 0) $("div.history li.sel").append(getHistoryStateIcon(2)).addClass('state2');
		else $("div.history li.sel").append(getHistoryStateIcon(1));
	});
}