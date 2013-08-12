/**
 * Actions component
 *
 * @require jquery >= 1.4.2 [http://jquery.com/]
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @author Jehan Bihin
 */


define(['require', 'jquery', 'class', 'helpers'], function(req, $) {
	var GenerisActions = Class.extend({
		init: function() {
			this.mainTree = undefined;
		},
		setMainTree: function(tree) {
			this.mainTree = tree;
		},
		/**
		 * conveniance method to select a resource
		 * @param {String} uri
		 */
		select: function(uri) {
			this.mainTree.selectTreeNode(uri);
		},
		/**
		 * conveniance method to subclass
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		subClass: function(uri, classUri, url) {
			var options = this.mainTree.getTreeOptions(classUri);
			if (options) {
				options.id = classUri;
				options.url = url;
				this.mainTree.addClass(options.NODE, options.TREE_OBJ, options);
			}
		},
		/**
		 * conveniance method to instanciate
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		instanciate: function (uri, classUri, url) {
			var options = this.mainTree.getTreeOptions(classUri);
			if (options) {
				options.id = classUri;
				options.url = url;
				this.mainTree.addInstance(options.NODE, options.TREE_OBJ, options);
			}
		},
		/**
		 * conveniance method to instanciate
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		removeNode: function (uri, classUri, url) {
			var options = this.mainTree.getTreeOptions(uri);
			if (!options) {
				options = this.mainTree.getTreeOptions(classUri);
			}
			if (options) {
				options.url = url;
				this.mainTree.removeNode(options.NODE, options.TREE_OBJ, options);
			}
		},
		/**
		 * conveniance method to clone
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		duplicateNode: function(uri, classUri, url) {
			var options = this.mainTree.getTreeOptions(uri);
			if (options) {
				options.url = url;
				this.mainTree.cloneNode(options.NODE, options.TREE_OBJ, options);
			}
		},
		/**
		 * move a selected node
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		moveNode: function(uri, classUri, url) {
			var options = this.mainTree.getTreeOptions(uri);
			if (options) {
				this.mainTree.moveInstance(options.NODE, options.TREE_OBJ);
			}
		},
		/**
		 * Open a popup
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		fullScreen: function(uri, classUri, url) {
			url += '?uri='+uri+'&classUri='+classUri;
			var width = parseInt($(window).width());
			if(width < 800){
				width = 800;
			}
			var height = parseInt($(window).height()) + 50;
			if(height < 600){
				height = 600;
			}
			var windowOptions = {
				'width' 	: width,
				'height'	: height,
				'menubar'	: 'no',
				'resizable'	: 'yes',
				'status'	: 'no',
				'toolbar'	: 'no',
				'dependent' : 'yes',
				'scrollbar' : 'yes'
			};
			var params = '';
			for (var key in windowOptions) {
				params += key + '=' + windowOptions[key] + ',';
			}
			params = params.replace(/,$/, '');
			window.open(url, 'preview', params);
		},
		/**
		 * Add a new property
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		addProperty: function (uri, classUri, url) {
			var index = ($(".form-group").size());
			$.ajax({
				url: url,
				type: "POST",
				data: {
					index: index,
					classUri: classUri
				},
				dataType: 'html',
				success: function(response){
					$(".form-group:last").after(response);
					formGroupElt = $("#property_" + index);
					if(formGroupElt){
						formGroupElt.addClass('form-group-opened');
					}
					//window.location = '#propertyAdder';
				}
			});
		},
		/**
		 * Load the result table with the tree instances in parameter
		 * @deprecated
		 * @param {String} uri
		 * @param {String} classUri
		 * @param {String} url
		 */
		resultTable: function(uri, classUri, url) {
			options = this.mainTree.getTreeOptions(classUri);
			TREE_OBJ = options.TREE_OBJ;
			NODE = options.NODE;

			function getInstances(TREE_OBJ, NODE){
				NODES = new Array();
				$.each(TREE_OBJ.children(NODE), function(i, CNODE){
					if ($(CNODE).hasClass('node-instance')) {
						NODES.push($(CNODE).prop('id'));
					}
					if ($(CNODE).hasClass('node-class')) {
						subNodes = getInstances(TREE_OBJ, CNODE);
						NODES.concat(subNodes);
					}
				});
				return NODES;
			}
			data = {};
			instances = getInstances(TREE_OBJ, NODE);

			i=0;
			while(i< instances.length){
				data['uri_'+i] = instances[i];
				i++;
			}
			data.classUri = classUri;
			helpers._load(helpers.getMainContainerSelector(uiBootstrap.tabs), url, data);
		},
		/**
		 * init and load the meta data component
		 * @param {String} uri
		 * @param {String} classUri
		 */
		loadMetaData: function(uri, classUri, url) {
			$("#comment-form-container").dialog('destroy');
			 $.ajax({
				url: url,
				type: "POST",
				data:{uri: uri, classUri: classUri},
				dataType: 'html',
				success: function(response){
					$('#section-meta').html(response);
					$('#section-meta').show();

					//meta data dialog
					var commentContainer = $("#comment-form-container");
					if (commentContainer) {

						$("#comment-editor").click(function(){
							var commentContainer = $(this).parents('td');
							if($('.comment-area', commentContainer).length == 0){

								var commentArea = $("<textarea id='comment-area'></textarea>");
								var commentField = $('span#comment-field', commentContainer);
								commentArea.val(commentField.html())
											.width(parseInt(commentContainer.width()) - 5)
											.height(parseInt(commentContainer.height()));
								commentField.empty();
								commentArea.bind('keypress blur' , function(event){
									if(event.type == 'keypress'){
										if (event.which != '13') {
											return true;
										}
										event.preventDefault();
									}
									$.ajax({
										url: helpers._url('saveMetadata', 'MetaData', 'tao'),
										type: "POST",
										data: {comment: $(this).val(), uri: $('#uri').val(), classUri:$('#classUri').val() },
										dataType: 'json',
										success: function(response){
											if (response.saved) {
												commentArea.remove();
												commentField.text(response.comment);
											}
										}
									});
								});
								commentContainer.prepend(commentArea);
							}
						});
					}
				}
			});
		}
	});

	return GenerisActions;
});