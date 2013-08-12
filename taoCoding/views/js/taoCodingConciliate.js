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
var codingAction = 'Conciliation';

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
				$('.history button, #navigation #nav_next').removeAttr('disabled');
			}
		}
	});
}

function setScore(itemid, gradeid, gradevalue) {
	$.ajax({
		type: "POST",
		url: root_url + "/taoCoding/"+codingAction+"/setFinalGrade",
		data: 'gradeid='+gradeid+'&gradevalue='+gradevalue+'&itemid='+itemid+'&dr='+uriDecode($('#selectors .history ul li.sel').prop('id')),
		dataType: 'json',
		success: function(r) {
			//...
			refreshScore();
			$('#evalitems #'+uriEncode(itemid)+' #'+uriEncode(gradeid)+' .comment').show();
		}
	});
}