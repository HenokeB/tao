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

/*
 * define customized action button for jwysiwyg editor specific to TAO QTI authoring tool
 */
function getCustomizedAction(action, params){
	
	var addCSS = function(href){
		require([root_url + '/taoQTI/views/js/qtiAuthoring/lib/murmurhash/murmurhash3_gc.js'], function(){
			var cssId = murmurhash3_32_gc(href, 'cssFileName');  // you could encode the css path itself to generate id..
			if (!document.getElementById(cssId)){
				var head  = document.getElementsByTagName('head')[0];
				var link  = document.createElement('link');
				link.id   = cssId;
				link.rel  = 'stylesheet';
				link.type = 'text/css';
				link.href = href;
				link.media = 'all';
				head.appendChild(link);
			}
		});
	};
	
	var dependencies = {
		
		addChoiceInteraction:{params:['qtiEditInstance']},
		addAssociateInteraction:{params:['qtiEditInstance']},
		addOrderInteraction:{params:['qtiEditInstance']},
		addMatchInteraction:{params:['qtiEditInstance']},
		addInlineChoiceInteraction:{params:['qtiEditInstance']},
		addTextEntryInteraction:{params:['qtiEditInstance']},
		addExtendedTextInteraction:{params:['qtiEditInstance']},
		addHotTextInteraction:{params:['qtiEditInstance']},
		addGapMatchInteraction:{params:['qtiEditInstance']},
		addHotspotInteraction:{params:['qtiEditInstance']},
		addGraphicOrderInteraction:{params:['qtiEditInstance']},
		addGraphicAssociateInteraction:{params:['qtiEditInstance']},
		addGraphicGapMatchInteraction:{params:['qtiEditInstance']},
		addSelectPointInteraction:{params:['qtiEditInstance']},
		addPositionObjectInteraction:{params:['qtiEditInstance']},
		addSliderInteraction:{params:['qtiEditInstance']},
		addUploadInteraction:{params:['qtiEditInstance']},
		addEndAttemptInteraction:{params:['qtiEditInstance']},
		saveItemData:{params:['qtiEditInstance']},
		addObject:{params:['qtiEditInstance']},
		addMathML:{},
		customHTML:{
			js: [root_url + '/taoQTI/views/js/qtiAuthoring/lib/codemirror/codemirror-compressed.js'],
			css: [root_url + '/taoQTI/views/js/qtiAuthoring/lib/codemirror/codemirror.css',
				root_url + '/taoQTI/views/js/qtiAuthoring/lib/codemirror/codemirror-taoQTI.css']
		} 
	};
	
	var customButtons = {
		addChoiceInteraction : {
			visible : true,
			className : 'add_choice_interaction add_qti_interaction',
			exec: function(){
				//display modal window with the list of available type of interactions
				var interactionType = 'choice';

				//insert location of the current interaction in the item:
				this.insertHtml("{{qtiInteraction:choice:new}}");

				//send to request to the server
				params.qtiEditInstance.addInteraction(interactionType, this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add choice interaction'
		},
		addAssociateInteraction : {
			visible : true,
			className: 'add_associate_interaction add_qti_interaction',
			exec: function(){
				var interactionType = 'associate';
				this.insertHtml("{{qtiInteraction:associate:new}}");
				params.qtiEditInstance.addInteraction(interactionType, this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add associate interaction'
		},
		addOrderInteraction : {
			visible : true,
			className: 'add_order_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:order:new}}");
				params.qtiEditInstance.addInteraction('order', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add order interaction'
		},
		addMatchInteraction : {
			visible : true,
			className: 'add_match_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:match:new}}");
				params.qtiEditInstance.addInteraction('match', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add match interaction'
		},
		addInlineChoiceInteraction : {
			visible : true,
			className: 'add_inlinechoice_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml('{{qtiInteraction:inlineChoice:new}}');
				params.qtiEditInstance.addInteraction('inlineChoice', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add inline choice interaction'
		},
		addTextEntryInteraction : {
			visible : true,
			className: 'add_textentry_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml('{{qtiInteraction:textentry:new}}');
				params.qtiEditInstance.addInteraction('textEntry', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add text entry interaction'
		},
		addExtendedTextInteraction : {
			visible : true,
			className: 'add_extendedtext_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml('{{qtiInteraction:extendedtext:new}}');
				params.qtiEditInstance.addInteraction('extendedText', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add extended text interaction'
		},
		addHotTextInteraction : {
			visible : true,
			className: 'add_hottext_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:hottext:new}}");
				params.qtiEditInstance.addInteraction('hottext', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add hot text interaction'
		},
		addGapMatchInteraction : {
			visible : true,
			className: 'add_gapmatch_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:gapmatch:new}}");
				params.qtiEditInstance.addInteraction('gapMatch', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add gap match interaction'
		},
		addHotspotInteraction : {
			visible : true,
			className: 'add_hotspot_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:hotspot:new}}");
				params.qtiEditInstance.addInteraction('hotspot', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add hot spot interaction'
		},
		addGraphicOrderInteraction : {
			visible : true,
			className: 'add_graphicorder_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:graphicorder:new}}");
				params.qtiEditInstance.addInteraction('graphicOrder', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add graphic order interaction'
		},
		addGraphicAssociateInteraction : {
			visible : true,
			className: 'add_graphicassociate_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:graphicassociate:new}}");
				params.qtiEditInstance.addInteraction('graphicAssociate', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add graphic associate interaction'
		},
		addGraphicGapMatchInteraction : {
			visible : true,
			className: 'add_graphicgapmatch_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:graphicgapmatch:new}}");
				params.qtiEditInstance.addInteraction('graphicGapMatch', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add hot spot interaction'
		},
		addSelectPointInteraction : {
			visible : true,
			className: 'add_selectpoint_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:selectpoint:new}}");
				params.qtiEditInstance.addInteraction('selectPoint', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add select point interaction'
		},
		addPositionObjectInteraction : {
			visible : false,
			className: 'add_positionobject_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:positionobject:new}}");
				params.qtiEditInstance.addInteraction('positionObject', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add position object interaction'
		},
		addSliderInteraction : {
			visible : true,
			className: 'add_slider_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:slider:new}}");
				params.qtiEditInstance.addInteraction('slider', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add slider interaction'
		},
		addUploadInteraction : {
			visible : false,
			className: 'add_fileupload_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:upload:new}}");
				params.qtiEditInstance.addInteraction('upload', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add file upload interaction'
		},
		addEndAttemptInteraction : {
			visible : false,
			className: 'add_endattempt_interaction add_qti_interaction',
			exec: function(){
				this.insertHtml("{{qtiInteraction:endattempt:new}}");
				params.qtiEditInstance.addInteraction('endAttempt', this.getContent(), params.qtiEditInstance.itemSerial);
			},
			tooltip: 'add end attempt interaction'
		},
		saveItemData : {
			visible : false,
			className: 'addInteraction add_qti_interaction',
			exec: function(){
				params.qtiEditInstance.saveItemData();
			},
			tooltip: 'save'
		},
		addObject : {
			visible : true,
			className: 'addObject',
			exec: function(){
				var obj = this;
				$.ajax({
					type: "POST",
					url: root_url + "/taoQTI/QtiAuthoring/addObject",
					dataType: 'json',
					data: {
						itemSerial:  params.qtiEditInstance.itemSerial
					},
					async: true,
					success: function(data) {
						if (data.success) {
							obj.insertHtml(data.objectData);
							params.qtiEditInstance.bindObjectLinkListener();
						}
					}
				});
			},
			tooltip: 'insert object',
			groupIndex: 2
		},
		addMathML : {
			visible : true,
			className: 'addObject',
			exec:function(){
				var mathEditorElt = '<textarea id="mathML-editor" rows="4" cols="50"></textarea>';
				var $dialog = $('<div id="dialog-math" title="MathML editor" style="display:none;"><p><span class="ui-icon ui-icon-pencil" style="float: left; margin: 0 7px 20px 0;"></span><span id="dialog-math-message"></span></p>'+mathEditorElt+'</div>').appendTo('#qtiAuthoring_main_container');
				var $mathEditor = $('#mathML-editor');
				var jwysiwyg = this;
				if($dialog && $dialog.length){
					$dialog.dialog({
						resizable: false,
						height:500,
						width:600,
						modal: true,
						open: function(e, ui){
							//initiate the editor here:
							$mathEditor.val('E=mc³');
						},
						buttons:{
							'Cancel': function(){
								$dialog.dialog('close');
							},
							'Generate MathML': function(){
								//get textarea value:
								var mathRaw = $mathEditor.val();

								//send it to the service:
								$.ajax({
									type: 'POST',
									url: root_url + "/taoQTI/QtiAuthoring/getMathML",//replace by your web site url here: 'your/web/service/url'
									dataType: 'json',
									data: {
										mathRaw:  mathRaw
									},
									success: function(data) {
										//render image tag here

										//sample return data
										data = {success:true};
										data.objectData = '<img src="http://www.wiris.net/demo/editor/render.png?mml=%3Cmath%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F1998%2FMath%2FMathML%22%3E%3Cmi%3EE%3C%2Fmi%3E%3Cmo%3E%26%23160%3B%3C%2Fmo%3E%3Cmo%3E%3D%3C%2Fmo%3E%3Cmi%3Em%3C%2Fmi%3E%3Cmsup%3E%3Cmi%3Ec%3C%2Fmi%3E%3Cmn%3E2%3C%2Fmn%3E%3C%2Fmsup%3E%3C%2Fmath%3E&backgroundColor=%23fff" />';
										if (data.success) {
											jwysiwyg.insertHtml(data.objectData);

										}
									}
								});

								$dialog.dialog('close');
							}
						}
					});
				}
			},
			tooltip: 'add MathML',
			groupIndex: 2
		},
		customHTML : {
			groupIndex: 10,
			visible: true,
			exec: function (){

				if (this.viewHTML){
					
					//get data from code mirror and restore textarea
					if(this.codemirrorEditor){
						this.codemirrorEditor.save();
						this.codemirrorEditor.toTextArea();
						this.codemirrorEditor = null;
					}
					
					//set data to jwysiwyg
					var value = $(this.original).val();
					this.setContent(value);
					
					//show jwysiwyg editor:
					$(this.original).hide();
					$(this.editor).parent().css('height', '95%');
					$(this.editor).show();
					
					//show enabled authoring options in jwysiwyg and the authoring tool
					$(this.element).find('ul.panel li').show();
					$(this.element).find('ul.panel li.html').attr('title', __('View source code'));
					if($(this.element).parents('#qtiAuthoring_itemEditor').length){
						$('#qtiAuthoring_menu_interactions_overlay').hide();
					}
					
					if(this.options.events.unsetHTMLview){
						this.options.events.unsetHTMLview(this.editorDoc);
					}

				}else{

					var $ed = $(this.editor);
					this.saveContent();
					var width = $(this.element).outerWidth() - 6;
					var height = $(this.element).height() - $(this.panel).height() - 6;
					$(this.original).css({
						width:  width,
						height: height,
						resize: 'none'
					}).show();
					
					//hide disabled authoring options in jwysiwyg and the authoring tool
					$(this.element).find('ul.panel li').hide();
					$(this.element).find('ul.panel li.html').attr('title', __('Return to normal edition')).show();
					if($(this.element).parents('#qtiAuthoring_itemEditor').length){
						$('#qtiAuthoring_menu_interactions_overlay').show();
					}
					
					//build code mirror
					this.codemirrorEditor  = CodeMirror.fromTextArea(this.original, {
						mode:{
							name:'xml',
							htmlMode:true,
							alignCDATA:true
						}
					});
					this.codemirrorEditor.setSize(width, height);
					this.codemirrorEditor.autoFormatRange({line:0,ch:0},{line:this.codemirrorEditor.lineCount()+1,ch:0});
					this.codemirrorEditor.setCursor(0);
					
					//hide jwysiwyg
					$ed.hide();
					$(this.editor).parent().css('height', 'auto');

					if(this.options.events.setHTMLview){
						this.options.events.setHTMLview(this.editorDoc);
					}

				}

				this.viewHTML = !(this.viewHTML);
			},
			tooltip: __('View source code')
		}
	}
	
	if(customButtons[action]){
		//check dependency : 
		if(dependencies[action]){
			var dep = dependencies[action];
			if(dep.params){
				for(var i in dep.params){
					if(!params[dep.params[i]]){
						throw 'missing required param : '.dep.params[i];
						break;
					}
				}
			}
			
			if(dep.css){
				for(var i in dep.css){
					addCSS(dep.css[i]);
				}
			}
			
			if(dep.js){
				require(dep.js, function(){
					//loading
				});
			}
		}
		
		return customButtons[action];
		
	}else{
		throw 'unknown action : '.action;
	}
}


