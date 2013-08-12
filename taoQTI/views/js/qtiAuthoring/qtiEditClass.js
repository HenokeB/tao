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

qtiEdit.instances = [];

qtiEdit.css = [];

qtiEdit.setFrameCSS = function(css){
	qtiEdit.css = css;
}

qtiEdit.getFrameCSS = function(){
	return qtiEdit.css;
}

function qtiEdit(itemSerial, options){

	var defaultFormContainers = {
		itemDataContainer : '#itemEditor_wysiwyg',
		interactionFormContent : '#qtiAuthoring_interaction_container',
		responseProcessingFormContent : '#qtiAuthoring_processingEditor',
		cssFormContent: '#qtiAuthoring_cssManager',
		responseMappingOptionsFormContainer : '#qtiAuthoring_mapping_container',
		responseGrid: 'qtiAuthoring_response_grid'
	};

	var formContainers = $.extend({}, defaultFormContainers);

	this.interactions = [];
	this.itemSerial = itemSerial;
	this.itemDataContainer = formContainers.itemDataContainer;
	this.interactionFormContent = formContainers.interactionFormContent;
	this.responseProcessingFormContent = formContainers.responseProcessingFormContent;
	this.responseMappingOptionsFormContainer = formContainers.responseMappingOptionsFormContainer;
	this.responseGrid = formContainers.responseGrid;
	this.cssFormContent = formContainers.cssFormContent;

	//init windows options:
	this.windowOptions = 'width=800,height=600,menubar=no,toolbar=no,scrollbars=1';

	this.currentInteraction = null;

	var instance = this;
	
	this.itemEditor = $(this.itemDataContainer).wysiwyg({
		css: qtiEdit.getFrameCSS(),
		iFrameClass: 'wysiwyg-item',
		controls: {
			strikeThrough : {visible : false},
			underline     : {visible : false},
			insertTable 	: {visible : false},

			justifyLeft   : {visible : true},
			justifyCenter : {visible : true},
			justifyRight  : {visible : true},
			justifyFull   : {visible : true},

			indent  : {visible : true},
			outdent : {visible : true},

			subscript   : {visible : true},
			superscript : {visible : true},

			undo : {visible : !util.msie()},
			redo : {visible : !util.msie()},

			insertOrderedList    : {visible : true},
			insertUnorderedList  : {visible : true},
			insertHorizontalRule : {visible : false},
			
			cut   : {visible : false},
			copy  : {visible : false},
			paste : {visible : false},
			html  : getCustomizedAction('customHTML'),
			removeFormat: {visible : false},
			
			addChoiceInteraction: getCustomizedAction('addChoiceInteraction', {qtiEditInstance:instance}),
			addAssociateInteraction: getCustomizedAction('addAssociateInteraction', {qtiEditInstance:instance}),
			addOrderInteraction: getCustomizedAction('addOrderInteraction', {qtiEditInstance:instance}),
			addMatchInteraction: getCustomizedAction('addMatchInteraction', {qtiEditInstance:instance}),
			addInlineChoiceInteraction: getCustomizedAction('addInlineChoiceInteraction', {qtiEditInstance:instance}),
			addTextEntryInteraction: getCustomizedAction('addTextEntryInteraction', {qtiEditInstance:instance}),
			addExtendedTextInteraction: getCustomizedAction('addExtendedTextInteraction', {qtiEditInstance:instance}),
			addHotTextInteraction: getCustomizedAction('addHotTextInteraction', {qtiEditInstance:instance}),
			addGapMatchInteraction: getCustomizedAction('addGapMatchInteraction', {qtiEditInstance:instance}),
			addHotspotInteraction: getCustomizedAction('addHotspotInteraction', {qtiEditInstance:instance}),
			addGraphicOrderInteraction: getCustomizedAction('addGraphicOrderInteraction', {qtiEditInstance:instance}),
			addGraphicAssociateInteraction: getCustomizedAction('addGraphicAssociateInteraction', {qtiEditInstance:instance}),
			addGraphicGapMatchInteraction: getCustomizedAction('addGraphicGapMatchInteraction', {qtiEditInstance:instance}),
			addSelectPointInteraction: getCustomizedAction('addSelectPointInteraction', {qtiEditInstance:instance}),
			addPositionObjectInteraction: getCustomizedAction('addPositionObjectInteraction', {qtiEditInstance:instance}),
			addSliderInteraction: getCustomizedAction('addSliderInteraction', {qtiEditInstance:instance}),
			addUploadInteraction: getCustomizedAction('addUploadInteraction', {qtiEditInstance:instance}),
			addEndAttemptInteraction: getCustomizedAction('addEndAttemptInteraction', {qtiEditInstance:instance}),
			saveItemData: getCustomizedAction('saveItemData', {qtiEditInstance:instance})
		},
		events: {
			keyup : function(e){
				if(instance.getDeletedInteractions(true).length > 0){
					if(util.msie()){//undo unavailable in msie
						var deletedInteractions = instance.getDeletedInteractions();
						instance.deleteInteractions(deletedInteractions);
					}else{
						util.confirmBox(__('Interaction Deletion'), __('please confirm deletion of the interaction'), {
							'Cancel':function(){
								instance.itemEditor.wysiwyg('undo');
								$(this).dialog('close');
							},
							'Delete Interaction':function(){
								var deletedInteractions = instance.getDeletedInteractions();
								instance.deleteInteractions(deletedInteractions);
								$(this).dialog('close');
							}
						});
					}
					return false;
				}
				return true;
			},
			frameReady : function(editor){
				editor.setContent(qtiEdit.buildInteractionPlaceholder(editor.getContent()));
				//the binding require the modified html data to be ready
				instance.bindInteractionLinkListener();
				instance.bindObjectLinkListener();
				editor.editorDoc.body.focus();
			},
			unsetHTMLview : function(){
				instance.bindInteractionLinkListener();
				instance.bindObjectLinkListener();
			},
			beforeSetContent : function(content){
				return qtiEdit.buildInteractionPlaceholder(content);
			},
			beforeSaveContent : function(content){
				return qtiEdit.restoreInteractionPlaceholder(content);
			},
			afterGetContent : function(content){
				return qtiEdit.restoreInteractionPlaceholder(content);
			}
		}
	});
	this.loadResponseProcessingForm();

	qtiEdit.instances[this.itemSerial] = this;
}

qtiEdit.prototype.addInteraction = function(interactionType, itemData, itemSerial){

	if(!itemSerial){
		itemSerial = this.itemSerial;
	}

	var instance = this;
	itemData = util.htmlEncode(itemData);

	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/addInteraction",
	   data: {
			'interactionType': interactionType,
			'itemData': itemData,
			'itemSerial': itemSerial
	   },
	   dataType: 'json',
	   success: function(r){
			//set the content:
			instance.itemEditor.wysiwyg('setContent', $("<div/>").html(r.itemData).html());

			//then add listener
			instance.bindInteractionLinkListener();
			instance.bindObjectLinkListener();
	   }
	});
}

qtiEdit.prototype.bindInteractionLinkListener = function(editorDoc){
	//destroy all listeners:

	//reset the interaction array:
	var instance = this;
	instance.interactionSerials = [];


	var links = qtiEdit.getEltInFrame('.qti_interaction_link', editorDoc);

	for(var i in links){

		var $interaction = links[i];
		var interactionSerial = $interaction.attr('id');

		instance.interactions[interactionSerial] = interactionSerial;
		$interaction.mousedown(function(e){
			e.preventDefault();
		});
		$interaction.click(function(e){
			e.preventDefault();
			instance.currentInteractionSerial = $(this).attr('id');
			instance.loadInteractionForm(instance.currentInteractionSerial);
		});
		qtiEdit.makeNoEditable($interaction);

		//append the delete button:
		var $interactionContainer = $interaction.parent('.qti_interaction_box');
		$interactionContainer.bind('dragover drop',function(e){
			e.preventDefault();
			return false;
		});
		qtiEdit.makeNoEditable($interactionContainer);
		
		var $deleteButton = $('<span class="qti_interaction_box_delete"></span>').appendTo($interactionContainer);
				$deleteButton.attr('title', __('Delete interaction'));
		$deleteButton.hide();
		$deleteButton.bind('click', {'interactionSerial': interactionSerial}, function(e){
					e.preventDefault();
					util.confirmBox(__('Interaction Deletion'), __('Please confirm interaction deletion'), {
						'Delete Interaction':function(){
							instance.deleteInteractions([e.data.interactionSerial]);
							$(this).dialog('close');
						}
					});
					return false;
		});
		$deleteButton.bind('mousedown contextmenu',function(e){
			e.preventDefault();
		});

		qtiEdit.makeNoEditable($deleteButton);

		$interaction.parent().hover(function(){
			$(this).children('.qti_interaction_box_delete').show();
			if($(this).hasClass('qti_interaction_inline')){
				$(this).css('padding-right', '20px');
			}
		},function(){
			$(this).children('.qti_interaction_box_delete').hide();
			if($(this).hasClass('qti_interaction_inline')){
				$(this).css('padding-right', 0);
			}
		});
	}
}

qtiEdit.prototype.bindObjectLinkListener = function(editorDoc){
	var instance = this;
	var objects = qtiEdit.getEltInFrame('.qti_object_link', editorDoc);
	for (var i in objects) {
		var object = objects[i];

		$(object).unbind('click').click(function(event){
			event.preventDefault();
			$.ajax({
				type: "POST",
				url: root_url + "/taoQTI/QtiAuthoring/editObject",
				dataType: 'json',
				data: {
					itemSerial: instance.itemSerial,
					objectSerial: $(this).attr('id')
				},
				async: true,
				success: function(data) {
					$('<div id="editObjectFrm" title="'+data.title+'">'+data.html+'</div>').dialog({
						modal: true,
						width: 400,
						height: 300,
						buttons: [
							{
								text: __('Save'),
								click: function() {
									$.ajax({
										type: "POST",
										url: root_url + "/taoQTI/QtiAuthoring/editObject",
										dataType: 'json',
										data: $('#editObjectFrm form').serialize(),
										async: true,
										success: function(data) {
											if (data.success) $('#editObjectFrm').dialog('close');
										}
									});
								}
							}
						]
					});
				}
			});
		});

		qtiEdit.makeNoEditable(object);

		//Append the delete button:
		var objectContainer = object.parent('.qti_object_block');
		objectContainer.bind('dragover drop',function(e){
			e.preventDefault();
			return false;
		});
		qtiEdit.makeNoEditable(objectContainer);

		var $deleteButton = $('<span class="qti_object_delete"></span>').appendTo(objectContainer);
		$deleteButton.attr('title', __('Delete object'));
		$deleteButton.hide();
		$deleteButton.bind('click', {'objectSerial': $(object).attr('id')}, function(e){
			e.preventDefault();
			util.confirmBox(__('Object Deletion'), __('Please confirm object deletion'), {
				'Delete Object':function(){
					instance.deleteObject([e.data.objectSerial]);
					$(this).dialog('close');
				}
			});
			return false;
		});
		$deleteButton.bind('mousedown contextmenu',function(e){
			e.preventDefault();
		});

		qtiEdit.makeNoEditable($deleteButton);

		object.parent().hover(function(){
			$(this).children('.qti_object_delete').show();
			if($(this).hasClass('qti_object_block')){
				$(this).css('padding-right', '20px');
			}
		},function(){
			$(this).children('.qti_object_delete').hide();
			if($(this).hasClass('qti_object_block')){
				$(this).css('padding-right', 0);
			}
		});
	}
}

qtiEdit.makeNoEditable = function($DOMelement){
	if($DOMelement.length){
		$DOMelement.attr('readonly', true);
		$DOMelement.attr('contenteditable', false);
		$DOMelement.focus(function(e){
//			CL('focusing');
			if (e.preventDefault) {e.preventDefault();} else {e.returnValue = false;}
			return false;
		});

		$DOMelement.keydown(function(){
//			CL('key downed');
		});
		$DOMelement.bind('mousedown contextmenu keypress keydown', function(e){
//			CL('misc event: ', e.type);
			if (e.preventDefault) {e.preventDefault();}else {e.returnValue = false;}
			return false;
		});
	}
}

qtiEdit.prototype.loadInteractionForm = function(interactionSerial){
	var self = this;

	if(this.itemSerial){
		$.ajax({
		   type: "POST",
		   url: root_url + "/taoQTI/QtiAuthoring/editInteraction",
		   data: {
				'interactionSerial': interactionSerial,
				'itemSerial': this.itemSerial,
				'uri':this.itemUri,
				'classUri':this.itemClassUri
		   },
		   dataType: 'html',
		   success: function(form){
				var $interactionForm = $(self.interactionFormContent);
				$interactionForm.empty();
				$interactionForm.html(form);
				qtiEdit.initFormElements($interactionForm);
				if ($myTab) $myTab.tabs("select", 1);
		   }
		});
	}

}

/*
* Format common qti authoring form elements
*/
qtiEdit.initFormElements = function($container){
	qtiEdit.mapFileManagerField($container);
	qtiEdit.mapHtmlEditor($container);
	qtiEdit.mapElementValidator($container);
}

qtiEdit.mapElementValidator = function($container){
	$container.find('input').each(function(){
		var $formElt = $(this);
		var attrName = $formElt.attr('name');
		switch(attrName){
			case 'base':
			case 'expectedLength':
			case 'step':{
				require([root_url  + 'taoQTI/views/js/qtiAuthoring/src/validators/class.Integer.js'], function(Validator){
					qtiEdit.bindValidator($formElt, new Validator());
				});
				break;
			}
			case 'choiceIdentifier':{
				require([root_url  + 'taoQTI/views/js/qtiAuthoring/src/validators/class.Uniqueidentifier.js'], function(Validator){
					var validatorIdentifier = new Validator({
						'element':$formElt
					});
					qtiEdit.bindValidator($formElt, validatorIdentifier);
				});
				break;
			}
			case 'upperBound':
			case 'lowerBound':{
				
				require([root_url  + 'taoQTI/views/js/qtiAuthoring/src/validators/class.Float.js'], function(Validator){
					var validatorFloat = new Validator();
					qtiEdit.bindValidator($formElt, validatorFloat, function($elt, ok){
						if(ok){
							var cleanedValue = validatorFloat.cleanInput($formElt.val());
							$formElt.val(cleanedValue);
						}
					});
				});
				break;
			}
		} 
	});
}

qtiEdit.bindValidator = function($formElt, validator, afterValidate){
	
	var $errTag = $('<div class="form-error ui-state-error ui-corner-all"/>').insertAfter($formElt).hide();
	var start= new Date().getTime();
	var now = 0;
	var timelimit = 800;//any modificaiton within "timelimit" milliseconds won't be checked
	var timer = null;
	var currentValue = $formElt.val();
	$formElt.bind('keyup.qtiAuthoring paste.qtiAuthoring',function(){
		
		var callbackFunc = function(ok){
			if(ok){
				if(typeof afterValidate == 'function'){
					afterValidate($formElt, ok);
				}
				$errTag.hide();
			}else{
				$errTag.show().html(validator.getMessage());
			}
		};
		
		var validate = function(){
			validator.validate($formElt.val(), callbackFunc);
		}
		
		clearTimeout(timer);
		if(currentValue == $formElt.val()){
			callbackFunc(true);
		}else{
			now = new Date().getTime();
			if(now-start>timelimit){
				validate();
				start = now;//reset after validation
			}else{
				timer = setTimeout(validate, timelimit);
			}
		}
		
	});
}

qtiEdit.mapFileManagerField = function($container){
	
	$container.find('input.qti-file-img').each(function(){
		
		var imgPath = $(this).val();
		var $modifiedForm = $(this).parents('form');
		var $eltHeight = $modifiedForm.find('input#object_height');
		var $eltWidth = $modifiedForm.find('input#object_width');
		var height = parseInt($eltHeight.val());
		var width = parseInt($eltWidth.val());
		var functionDisplayPreview = function(elt, imagePath, width, height){
			if($(elt).hasClass('qti-with-preview') && width && height){
				var maxHeight = 150;
				var maxWidth = 150;
				var baseRatio = width/height;
				var previewDescription = '';
				if(Math.max(width, height)<150){
					//no need to resize
					previewDescription = __('preview (1:1)');
				}else{
					//resize to the maximum lenght:
					previewDescription = __('preview (real size:')+' '+width+'px*'+height+'px)';
					if(height>width){
						height = maxHeight;
						width = height*baseRatio;
					}else{
						width = maxWidth;
						height = width/baseRatio;
					}
				}

				//insert the image preview
				var $parentElt = $(elt).parent();
				var $previewElt = $parentElt.find('div.qti-img-preview');
				if(!$previewElt.length){
					$previewElt = $('<div class="qti-img-preview">').appendTo($parentElt);
				}
				// var $descriptionElt = $();
				$previewElt.empty().html('<img src="'+util.getMediaResource(imagePath)+'" style="width:'+width+'px;height:'+height+'px;" title="preview" alt="no preview available"/>');
				$previewElt.append('<br/><span class="qti-img-preview-label">'+previewDescription+'</span>');
			}
			
		}
		
		//elt file input
		var functionAddSlider = function($elt, imgPath, oldWidth, oldHeight){
			
			//need to redefine the scope of $modifiedForm, $eltHeight and $eltWidth
			var $modifiedForm = $elt.parents('form');
			var $eltHeight = $modifiedForm.find('input#object_height');
			var $eltWidth = $modifiedForm.find('input#object_width');
			var theOldWidth = oldWidth;
			var theOldHeight = oldHeight;
			
			var $parent = $elt.parent();
			if($elt.hasClass('qti-with-resizer') && $parent.find('div.qti-img-resize-slider').length == 0){
				//do append slider:
				var $slider = $('<div class="qti-img-resize-slider"></div>').appendTo($elt.parent());
				$slider.slider({
					range: "min",
					value: 100,
					min: 10,
					max: 200,
					slide: function(e, ui) {
						var percentage = ui.value;
						var newHeight = Math.round(percentage/100*theOldHeight);
						var newWidth = Math.round(percentage/100*theOldWidth);
						$eltHeight.val(newHeight);
						$eltWidth.val(newWidth);
						functionDisplayPreview($elt, imgPath, newWidth, newHeight);
					},
					stop: function(e, ui) {
						$eltHeight.change();//trigger change event
						$eltWidth.change();
					}
				});
			}
		}

		if(imgPath){
			if($(this).hasClass('qti-with-preview') && width && height){
				functionDisplayPreview(this, imgPath, width, height);
				functionAddSlider($(this), imgPath, width, height);
			} 
		}

		if($.fn.fmbind){
			//dynamically change the style:
			$(this).width('50%');

			//add tao file manager
			$(this).fmbind({type: 'image', showselect: true}, function(elt, newImgPath, mediaData){
				
				//need to redefine the scope of $modifiedForm, $eltHeight and $eltWidth
				var $modifiedForm = $(elt).parents('form');
				var $eltHeight = $modifiedForm.find('input#object_height');
				var $eltWidth = $modifiedForm.find('input#object_width');
				var height = width = 0;
				if(mediaData){
					if(mediaData.height) height = mediaData.height;
					if(mediaData.width) width = mediaData.width;
				}
				
				imgPath = newImgPath;//record the newImgPath in the function wide scope
				$(elt).val(imgPath);

				if($modifiedForm.length){
					//find the active interaction:
					for(var itemSerial in qtiEdit.instances){
						var item = qtiEdit.instances[itemSerial];
						var interaction = item.currentInteraction;
						if(interaction){
							var id = $modifiedForm.attr('id');
							if(id.indexOf('ChoiceForm') == 0){
								interaction.modifiedChoices[id] = 'modified';//it is a choice form:
							}else if(id.indexOf('InteractionForm') == 0){
								interaction.setModifiedInteraction(true);
							}else if(id.indexOf('GroupForm') == 0){
								interaction.modifiedGroups[id] = 'modified';
							}
						}
					}
				}

				if(height) $eltHeight.val(height);
				if(width) $eltWidth.val(width);

				functionDisplayPreview(elt, imgPath, width, height);
				functionAddSlider($(elt), imgPath, width, height);
			});
		}
	});

}

qtiEdit.mapHtmlEditor = function($container){
	//map the wysiwyg editor to the html-area fields
	$container.find('.qti-html-area').each(function(){
		if ($(this).css('display') != 'none' && !$(this).siblings('.wysiwyg').length){

			var controls = {

			  strikeThrough : {visible : false},//not allowed in QTI html
			  underline     : {visible : false},
			  insertTable 	: {visible : false},

			  justifyLeft   : {visible : true},
			  justifyCenter : {visible : true},
			  justifyRight  : {visible : true},
			  justifyFull   : {visible : true},

			  indent  : {visible : true},
			  outdent : {visible : true},

			  subscript   : {visible : true},
			  superscript : {visible : true},

			  undo : {visible : !util.msie()},
			  redo : {visible : !util.msie()},

			  insertOrderedList    : {visible : true},
			  insertUnorderedList  : {visible : true},
			  insertHorizontalRule : {visible : false},
			  cut   : {visible : false},
			  copy  : {visible : false},
			  paste : {visible : false},
			  html  : getCustomizedAction('customHTML'),
			  h1: {visible: false},
			  h2: {visible: false},
			  h3: {visible: false},
			  h4: {visible: false},
			  h5: {visible: false},
			  h6: {visible: false},

			  addChoiceInteraction: {visible:false},
			  addAssociateInteraction: {visible:false},
			  addOrderInteraction: {visible:false},
			  addMatchInteraction: {visible:false},
			  addInlineChoiceInteraction: {visible:false},
			  addTextEntryInteraction: {visible:false},
			  addExtendedTextInteraction: {visible:false},
			  addHotTextInteraction: {visible:false},
			  addGapMatchInteraction: {visible:false},
			  addHotspotInteraction: {visible:false},
			  addGraphicOrderInteraction: {visible:false},
			  addGraphicAssociateInteraction: {visible:false},
			  addGraphicGapMatchInteraction: {visible:false},
			  addSelectPointInteraction: {visible:false},
			  addPositionObjectInteraction: {visible:false},
			  addSliderInteraction: {visible:false},
			  addUploadInteraction: {visible:false},
			  addEndAttemptInteraction: {visible:false},
			  createHotText: {visible:false},
			  createGap: {visible:false},
			  saveItemData: {visible:false},
			  saveInteractionData: {visible:false}
			};
			
			var iFrameClass = 'wysiwyg-htmlarea';
			if($(this).parents('form#InteractionForm').length){
				iFrameClass += ' wysiwyg-interaction';
			}
			
			$(this).wysiwyg({
				css:qtiEdit.getFrameCSS(),
				iFrameClass: iFrameClass,
				controls: controls
			});
		}
	});
}

//TODO: side effect to be fully tested
qtiEdit.destroyHtmlEditor = function($container){
	$container.find('.qti-html-area').each(function(){
		// if ($(this).css('display') != 'none' && $(this).siblings('.wysiwyg').length){
			try{
				$(this).wysiwyg('destroy');
			}catch(err){

			}
		// }
	});
}

qtiEdit.getEltInFrame = function(selector, selectedDocument){
	var foundElts = [];

	if(selectedDocument){
		$(selector, selectedDocument).each(function(){
			foundElts.push($(this));
		});
	}else{
		// for each iframe:
		$('iframe').each(function(){

			// get its document
			$(this).each( function(){
				var selectedDocument = this.contentWindow.document;
				$(selector, selectedDocument).each(function(){
					foundElts.push($(this));
				});
			});

		});
	}

	return foundElts;
}

qtiEdit.availableInteractions = function(userOptions){
	
	var defaultOptions = {
		active:true
	};
	
	var options = (userOptions) ? $.extend(defaultOptions, userOptions) : defaultOptions;
	
	var availableInteractions = {
		'choice':{
			'display':'block',
			'label':__('Choice Interaction'),
			'short':'choice',
			'icon':img_url+'QTI_choice.png',
			'active':true
		},
		'inlinechoice':{
			'display':'inline',
			'label':__('Inline Choice Interaction'),
			'short':'inline choice',
			'icon':img_url+'QTI_inlineChoice.png',
			'active':true
		},
		'associate':{
			'display':'block',
			'label':__('Associate Interaction'),
			'short':'associate',
			'icon':img_url+'QTI_associate.png',
			'active':true
		},
		'order':{
			'display':'block',
			'label':__('Order Interaction'),
			'short':'order',
			'icon':img_url+'QTI_order.png',
			'active':true
		},
		'match':{
			'display':'block',
			'label':__('Match Interaction'),
			'short':'match',
			'icon':img_url+'QTI_match.png',
			'active':true
		},
		'gapmatch':{
			'display':'block',
			'label':__('Gap Match Interaction'),
			'short':'gap match',
			'icon':img_url+'QTI_gapMatch.png',
			'active':true
		},
		'textentry':{
			'display':'inline',
			'label':__('Textentry Interaction'),
			'short':'text entry',
			'icon':img_url+'QTI_textentry.png',
			'active':true
		},
		'extendedtext':{
			'display':'block',
			'label':__('Extended Text Interaction'),
			'short':'extended text',
			'icon':img_url+'QTI_extendedText.png',
			'active':true
		},
		'media':{
			'display':'block',
			'label':__('Media Interaction'),
			'short':'media',
			'icon':img_url+'QTI_media.png',
			'active':false
		},
		'hottext':{
			'display':'block',
			'label':__('Hottext Interaction'),
			'short':'hottext',
			'icon':img_url+'QTI_hottext.png',
			'active':true
		},
		'hotspot':{
			'display':'block',
			'label':__('Hotspot Interaction'),
			'short':'hotspot',
			'icon':img_url+'QTI_hotspot.png',
			'active':true
		},
		'graphicorder':{
			'display':'block',
			'label':__('Graphic Order Interaction'),
			'short':'graphic order',
			'icon':img_url+'QTI_graphicOrder.png',
			'active':true
		},
		'graphicassociate':{
			'display':'block',
			'label':__('Graphic Associate Interaction'),
			'short':'graphic associate',
			'icon':img_url+'QTI_graphicAssociate.png',
			'active':true
		},
		'graphicgapmatch':{
			'display':'block',
			'label':__('Graphic Gap Match Interaction'),
			'short':'graphic gap',
			'icon':img_url+'QTI_graphicGapmatch.png',
			'active':true
		},
		'selectpoint':{
			'display':'block',
			'label':__('Select Point Interaction'),
			'short':'select point',
			'icon':img_url+'QTI_selectPoint.png',
			'active':true
		},
		'positionobject':{
			'display':'block',
			'label':__('Position Object Interaction'),
			'short':'position object',
			'icon':img_url+'QTI_positionObject.png',
			'active':false
		},
		'slider':{
			'display':'block',
			'label':__('Slider Interaction'),
			'short':'slider',
			'icon':img_url+'QTI_slider.png',
			'active':true
		},
		'fileupload':{
			'display':'block',
			'label':__('File Upload Interaction'),
			'short':'file upload',
			'icon':img_url+'QTI_fileUpload.png',
			'active':false
		},
		'endattempt':{
			'display':'block',
			'label':__('End Attempt Interaction'),
			'short':'end attempt',
			'icon':img_url+'QTI_endAttempt.png',
			'active':false
		}
	};
	
	var returnValue = {};
	
	if(options.active){
		for(var interaction in availableInteractions){
			if(availableInteractions[interaction].active){
				returnValue[interaction] = availableInteractions[interaction];
			}
		}
	}else{
		returnValue = availableInteractions;
	}
	
	return returnValue;
}

qtiEdit.getInteractionModel = function(type){
	if(type && typeof type == 'string'){
		var interactions = qtiEdit.availableInteractions();
		if(interactions[type]){
			return interactions[type];
		}else{
			throw 'unknown interaction model : '.type;
			return null;
		}
	}
}

qtiEdit.buildInteractionPlaceholder = function(htmlString){
	var regex = new RegExp('{{qtiInteraction:(' + Object.keys(qtiEdit.availableInteractions()).join('|') + '):([\\w]+)}}', 'img');

	htmlString = htmlString.replace(regex,
	function(original, type, serial){
		var returnValue = original;
		
		var interactionModel = qtiEdit.getInteractionModel(type)
		if(interactionModel){
			var displayClass = 'qti_interaction_block';
			var element = 'div';
			if(interactionModel.display == 'inline'){
				displayClass = 'qti_interaction_inline';
				element = 'span';
			}
			var typeClass = 'qti_interaction_type_'+type;
			returnValue = '<'+element+' id="'+serial+'" class="qti_interaction_box '+displayClass+' '+typeClass+'"><input id="'+serial+'" class="qti_interaction_link" value="'+interactionModel.label+'" type="button"/></'+element+'>'
			returnValue += '&nbsp;'
		}
		
		return returnValue;
	});
	
	return htmlString;
}

qtiEdit.restoreInteractionPlaceholder = function(htmlString){
	var regex = '';
	if(util.msie()){
		regex = new RegExp('<(SPAN|DIV)[^<]*id=([^<"]*)\\s[^<]*class="[^<"]*qti_interaction_type_(\\w+)[^<"]*"[^<]*>(?:[^<>]*</?(EM|STRONG|FONT|B|I|BR|INPUT|SUB|SUP|H\\d)[^<>]*>[^<>]*|[^<>]*<SPAN[^<>]*>[^<>]*</SPAN>[^<>]*|<SPAN[^>]*/>)*</\\1>(?:&nbsp;)?', 'img');
	}else{
		regex = new RegExp('<(span|div)[^<]*id="([^<"]*)"\\s[^<]*class="[^<"]*qti_interaction_type_(\\w+)[^<"]*"[^<]*>(?:[^<>]*</?(em|strong|font|b|i|br|input|sub|sup|h\\d)[^<>]*>[^<>]*|[^<>]*<span[^<>]*>[^<>]*</span>[^<>]*|<span[^>]*/>)*</\\1>(?:&nbsp;)?', 'img');
	}
	
	htmlString = htmlString.replace(regex,
		function(original, div, serial, type){
			var returnValue = original;
			var interactionModel = qtiEdit.getInteractionModel(type)
			if(interactionModel){
				returnValue = '{{qtiInteraction:'+type+':'+serial+'}}';
			}
			return returnValue;
		});
	
	return htmlString;
}


qtiEdit.createInfoMessage = function(message){
	helpers.createInfoMessage('<img src="'+img_url+'ok.png" alt="" style="float:left;margin-right:10px;"/>'+message);
}

/**
 * Set the text in the title bar (currently disabled)
 */
qtiEdit.setTitleBar = function(text){
	$('#qtiAuthoring_title_container').text(text);
}

/**
 * get item content after filtering out the editing tag
 */
qtiEdit.prototype.getItemContent = function(){
	this.itemEditor.wysiwyg('saveContent');
	return this.itemEditor.val();
}

//the global save function
qtiEdit.prototype.save = function(itemUri){

	if(!this.itemUri) throw 'item uri cannot be empty';

	//save item data then export to rdf item:
	var itemProperties = $('#AssessmentItem_Form').serializeObject();
	itemProperties.itemSerial = this.itemSerial;
	itemProperties.itemUri = this.itemUri;
	itemProperties.itemData = util.htmlEncode(this.getItemContent());
	
	//could check if an interaction is being edited, so suggest to save it too:
	var saveItemFunction = function(){
		$.ajax({
		   type: "POST",
		   url: root_url + "/taoQTI/QtiAuthoring/saveItem",
		   data: itemProperties,
		   dataType: 'json',
		   success: function(r){
				if(r.saved){
					qtiEdit.createInfoMessage(__('The item has been successfully saved'));
					
					//update title here:
					qtiEdit.setTitleBar(itemProperties.title);
				}
		   }
		});
	}
	
	if(this.currentInteraction){
		this.saveCurrentInteraction(saveItemFunction);
	}else{
		saveItemFunction();
	}
	
}

qtiEdit.prototype.preview = function(){
   //use global generisActions api to display fullscreen preview
	generisActions.fullScreen(this.itemUri, this.itemClassUri, '/taoItems/Items/fullScreenPreview');
}

qtiEdit.prototype.debug = function(){
	window.open(root_url+'/taoQTI/QtiAuthoring/debug?itemSerial='+this.itemSerial, 'QTIDebug', this.windowOptions);
}

qtiEdit.prototype.exportItem = function(){
	//when the export action is transformed into a service:
	window.open(root_url+'/taoItems/Items/downloadItemContent?uri='+this.itemUri+'&classUri='+this.itemClassUri, 'QTIExport', this.windowOptions);
}

qtiEdit.prototype.saveItemData = function(itemSerial){

	var instance = this;

	if(!itemSerial){
		itemSerial = instance.itemSerial;
	}

	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/saveItemData",
	   data: {
			'itemData': util.htmlEncode(instance.getItemContent()),
			'itemSerial': itemSerial
	   },
	   dataType: 'json',
	   success: function(r){
			// CL('item saved');
	   }
	});
}

qtiEdit.prototype.getDeletedInteractions = function(one){
	var deletedInteractions = [];
	var itemData = $(this.itemDataContainer).val();//TODO: improve with the use of regular expressions:
	for(var interactionSerial in this.interactions){
		if(itemData.indexOf(interactionSerial)<0){
			//not found so considered as deleted:
			deletedInteractions.push(interactionSerial);
			if(one){
				return deletedInteractions;
			}
		}
	}

	return deletedInteractions;
}

qtiEdit.prototype.deleteInteractions = function(interactionSerials){

	if(!interactionSerials || interactionSerials.length <=0){
		return false;
	}

	var data = '';
	//prepare the data to be sent:
	for(var i in interactionSerials){
		data += 'interactionSerials['+ i +']=' + interactionSerials[i] + '&';
		delete this.interactions[interactionSerials[i]];
	}
	data += 'itemSerial=' + this.itemSerial;

	var instance = this;

	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/deleteInteractions",
	   data: data,
	   dataType: 'json',
	   success: function(r){

			if(r.deleted){
				for(var i in interactionSerials){
					var interactionSerial = interactionSerials[i];
					if(instance.interactionSerial == interactionSerial){
						// destroy the interaction form:
						$(instance.interactionFormContent).empty();
					}
					delete instance.interactions[interactionSerial];

					//delete:
					var $interactions = qtiEdit.getEltInFrame('div#'+interactionSerial);
					if($interactions.length){

						if($interactions[0]){
							var $interactionBlock = $interactions[0];
							$interactionBlock.empty();
							$interactionBlock.detach();
						}
					}
				}

				//unload the interaction form if needed

				//save item data, i.e. validate the changes operated on the item data:
				instance.saveItemData();

			}else{

				for(var i in interactionSerials){
					instance.interactions[interactionSerials[i]] = interactionSerials[i];
				}

			}

	   }
	});

}

qtiEdit.prototype.deleteObject = function(objectSerials){
	if(!objectSerials || objectSerials.length <= 0){
		return false;
	}

	var data = '';
	//prepare the data to be sent:
	for(var i in objectSerials){
		data += 'objectSerials['+ i +']=' + objectSerials[i] + '&';
		//delete this.objects[objectSerials[i]];
	}
	data += 'itemSerial=' + this.itemSerial;

	var instance = this;

	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/deleteObjects",
	   data: data,
	   dataType: 'json',
	   success: function(r){
			if (r.deleted) {
				for (var i in objectSerials) {
					var objectSerial = objectSerials[i];
					if (instance.objectSerial == objectSerial) {
						// destroy the object form:
						$(instance.objectFormContent).empty();
					}
					//delete instance.objects[objectSerial];

					//delete:
					var $objects = qtiEdit.getEltInFrame('#'+objectSerial);
					if ($objects.length) {
						if ($objects[0]) {
							var $objectBlock = $objects[0].parent();
							$objectBlock.empty();
							$objectBlock.detach();
						}
					}
				}
				//save item data, i.e. validate the changes operated on the item data:
				instance.saveItemData();
			} else {
				for (var i in objectSerials) {
					instance.objects[objectSerials[i]] = objectSerials[i];
				}
			}
	   }
	});

}

qtiEdit.prototype.loadResponseProcessingForm = function(){

	var self = this;

	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/editResponseProcessing",
	   data: {
			'itemSerial': self.itemSerial
	   },
	   dataType: 'html',
	   success: function(form){
			$(self.responseProcessingFormContent).html(form);
	   }
	});
}

qtiEdit.prototype.saveItemResponseProcessing = function($myForm){
	var self = this;
	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/saveItemResponseProcessing",
	   data: $myForm.serialize(),
	   dataType: 'json',
	   success: function(r){
			if(r.saved){
				self.setResponseMode(r.setResponseMode);
				qtiEdit.createInfoMessage(__('The response processing has been saved'));
			}
	   }
	});
}

qtiEdit.prototype.setResponseMode = function(visible){
	if (visible && !this.responseMode) this.responseMode = true;
	else this.responseMode = false;
	if (((visible && !this.responseMode) || (!visible)) && this.currentInteraction) this.loadInteractionForm(this.currentInteraction.interactionSerial);
}

qtiEdit.prototype.loadStyleSheetForm = function(empty){

	var self = this;

	//check if the form is not empty:
	var post = '';
	if($('#css_uploader').length && !empty){
		post = $('#css_uploader').serialize();
		post += '&itemUri='+this.itemUri;
	}else{
		post = {itemSerial: this.itemSerial, itemUri: this.itemUri};
	}
	
	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/manageStyleSheets",
	   data: post,
	   dataType: 'html',
	   success: function(form){
			$(self.cssFormContent).html(form);
			if($('#css_uploader').length){
				
				var $cssForm = $('form#css_uploader').hide();
				$('#cssFormToggleButton').toggle(function(){
					$(this).switchClass('ui-icon-circle-plus', 'ui-icon-circle-minus');
					$cssForm.slideToggle();
				},function(){
					$(this).switchClass('ui-icon-circle-minus', 'ui-icon-circle-plus');
					$cssForm.slideToggle();
				});
				
				$('#css_uploader').find('input#title').each(function(){$(this).val('');});//reset the css name after reload
				$('#css_uploader').find('input#submit').unbind('click').bind('click', function(){
					self.loadStyleSheetForm();//submit manually to reset the event handler
					return false;
				});

			}
	   }
	});
}

qtiEdit.prototype.deleteStyleSheet = function(css_href){
	var self = this;
	$.ajax({
	   type: "POST",
	   url: root_url + "/taoQTI/QtiAuthoring/deleteStyleSheet",
	   data: {
			'itemSerial': this.itemSerial,
			'itemUri': this.itemUri,
			'css_href': css_href
	   },
	   dataType: 'json',
	   success: function(r){
			if(r.deleted){
				self.loadStyleSheetForm(true);
			}
	   }
	});
}

qtiEdit.prototype.saveCurrentInteraction = function(callback, reloadResponse){
	if(this.currentInteraction){
		var interaction = this.currentInteraction;
		interaction.saveAll(callback, reloadResponse);
	}
}
