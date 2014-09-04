<script type="text/javascript">

	telenok.addPresentation('{{$presentation}}', function(param)
	{
		function presentationTreeTab()
		{
			var presentationDomId = '';
			var moduleKey = '';
			var presentationParam = {};
			var _this = this;
			
			this.getPresentationDomId = function()
			{
				return _this.presentationDomId;
			}

			this.setPageHeader = function()
			{
				jQuery('div.page-header', '#' + _this.presentationDomId).html('<h1>'
					+ _this.presentationParam.pageHeader[0] + '<small><i class="fa fa-angle-double-right"></i> '
					+ _this.presentationParam.pageHeader[1] + '</small></h1>');
				return _this;
			}

			this.setParam = function(param)
			{
				_this.presentationParam = param;
				_this.presentationDomId = telenok.getPresentationDomId(param.presentation);
				_this.moduleKey = param.key;
				return _this;
			}

			this.reloadTab = function(param)
			{
				if (!param.tabKey) return _this;

				var id = _this.presentationDomId + '-tab-' + param.tabKey;
				var $el = jQuery('#' + id);

				if ($el.size())
				{
					$el.html(param.tabContent);
				}

				return _this;
			}

			this.addTab = function(param)
			{
				if (!param.tabKey) return _this;

				var id = _this.presentationDomId + '-tab-' + param.tabKey;
				var tabs = jQuery('div.telenok-presentation-tabs', '#' + _this.presentationDomId);

				if (jQuery('div#' + id, tabs).length)
				{
					jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a[href="#' + id + '"]', tabs).tab('show');
					return _this;
				}

				var tabTemplate = "<li><a href='#" + id + "' data-toggle='tab' data-page-id='" + param.pageId + "'><i class='green fa fa-home bigger-110'></i>&nbsp;" + param.tabLabel + "&nbsp;<i class='fa fa-times red' style='cursor:pointer;'></i></a></li>";
				var $li = jQuery(tabTemplate);
				jQuery('ul.nav-tabs#nav-tabs-{{$presentation}}', tabs).append($li);
				jQuery('div.tab-content#tab-content-{{$presentation}}', tabs).append("<div class='tab-pane web-page-structure' id='" + id + "' data-web-page-id='" + param.pageId + "'>" + param.tabContent + "</div>");

				jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).on('shown.bs.tab', function (e) 
				{
					telenok_module_web_page_pid = jQuery(this).data('page-id'); 
				}).tab('show');  

				jQuery('a i.fa.fa-times', $li).click(function()
					{
						var tabId = jQuery('a', $li).attr('href');
							jQuery(tabId).remove();
							$li.remove();
							jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).tab('show'); 
					});

				$li.on('mousedown', function(event)
					{
						if (event.which == 2)
						{
							event.stopPropagation();
							event.preventDefault();
							jQuery('i.fa.fa-times', this).click();
						}
					});

				return _this;
			}

			this.addTabByURL = function(param, reload)
			{
				jQuery.ajax({
						url: param.url,
						method: 'get',
						dataType: 'json',
						data: param.data || {},

					})
					.success(function(data)
					{
						if (reload)
						{
							_this.reloadTab({pageId: data.pageId, tabKey: data.tabKey, tabLabel: data.tabLabel, tabContent: data.tabContent});
						}
						else
						{
							_this.addTab({pageId: data.pageId, tabKey: data.tabKey, tabLabel: data.tabLabel, tabContent: data.tabContent});
						}

						if (jQuery.isFunction(param.after))
						{
							param.after();
						}
					});
					return _this;
			}

			this.addDataTable = function(param)
			{
				param = jQuery.extend({}, {
					"multipleSelection": true,
					"aoColumns": [],
					"bAutoWidth": true,
					"bProcessing": true,
					"bServerSide": param.sAjaxSource ? true : false,
					"bDeferRender": '',
					"bJQueryUI": false,
					"iDisplayLength": {{ $iDisplayLength }},
					"sDom": "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'T><'col-md-6'p>>",
					"oTableTools": {
						"aButtons": [
							{
								"sExtends": "text",
								"sButtonText": "<i class='fa fa-plus smaller-90'></i> {{{ $controller->LL('list.btn.create') }}}",
								'sButtonClass': 'btn-success btn-sm' + (param.btnCreateDisabled ? ' disabled ' : ''),
								"fnClick": function(nButton, oConfig, oFlash) {
									if (param.btnCreateDisabled || !param.btnCreateUrl) return false;
									else _this.addTabByURL({url: param.btnCreateUrl});
								}
							},
							{
								"sExtends": "text",
								"sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{{ $controller->LL('list.btn.refresh') }}}",
								'sButtonClass': 'btn-sm',
								"fnClick": function(nButton, oConfig, oFlash) {
									jQuery('#' + param.domId).dataTable().fnReloadAjax();
								}
							},
							{
								"sExtends": "collection",
								'sButtonClass': 'btn btn-sm btn-light',
								"sButtonText": "<i class='fa fa-check-square-o smaller-90'></i> {{{ $controller->LL('list.btn.select') }}}",
								"aButtons": [
									{
										"sExtends": "text",
										"sButtonText": "<i class='fa fa-pencil-square-o'></i> {{{ $controller->LL('btn.edit') }}}",
										"fnClick": function(nButton, oConfig, oFlash)
										{
											if (param.btnListEditUrl)
											{
												_this.addTabByURL({
													url: param.btnListEditUrl,
													data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize()
												});
											}
										}
									},
									{
										"sExtends": "text",
										'sButtonClass':  (param.btnListDeleteDisabled ? ' disabled ' : ''),
										"sButtonText": "<i class='fa fa-trash-o'></i> {{{ $controller->LL('btn.delete') }}}",
										"fnClick": function(nButton, oConfig, oFlash) {
											if (param.btnListDeleteDisabled || !param.btnListDeleteUrl) return false;
											else {
												var this_ = this;
												jQuery.ajax({
													url: param.btnListDeleteUrl,
													method: 'post',
													dataType: 'json',
													data: jQuery('input[name=tableCheckAll\\[\\]]:checked', this.dom.table).serialize()
												}).done(function(data) {
													if (data.success) {
														jQuery('input[name=tableCheckAll\\[\\]]:checked', this_.dom.table).closest("tr").remove();
													}
												});
											}
										}
									}
								]
							},
							{
								"sExtends": "text",
								'sButtonClass': 'btn btn-sm btn-light',
								"sButtonText": "<i class='fa fa-search'></i> {{{ $controller->LL('btn.filter') }}}",
								"fnClick": function(nButton, oConfig, oFlash) 
									{
										jQuery('div.filter', jQuery(this.dom.table).closest('div.container-table')).toggle();
									}
							}
						]
					},
					"oLanguage": {
						"oPaginate": {
							"sNext": "{{{ \Lang::get('core::default.btn.next') }}}",
							"sPrevious": "{{{ \Lang::get('core::default.btn.prev') }}}",
						},
						"sEmptyTable": "{{{ \Lang::get('core::default.table.empty') }}}",
						"sSearch": "{{{ \Lang::get('core::default.table.search') }}}",
						"sInfo": "{{{ \Lang::get('core::default.table.showed') }}}",
						"sInfoEmpty": "{{{ \Lang::get('core::default.table.empty.showed') }}}",
						"sZeroRecords": "{{{ \Lang::get('core::default.table.empty.filtered') }}}",
						"sInfoFiltered": "",
					}
				}, param);

				jQuery('#' + param.domId).dataTable(param);

				return _this;
			}

			this.reloadDataTableOnClick = function(param)
			{
				if (jQuery('#' + _this.getPresentationDomId() + '-grid-' + param.gridId).size())
				{
					jQuery('#' + _this.getPresentationDomId() + '-grid-' + param.gridId)
						.dataTable()
						.fnReloadAjax(param.url + '?' + jQuery.param(param.data));
				}

				return this;
			}

			this.deleteByURL = function(dom_obj, url)
			{
				jQuery.ajax({
						url: url,
						method: 'post',
						dataType: 'json'
					})
					.done(function(data)
					{
						if (data.success)
						{
							jQuery(dom_obj).closest("tr").remove();
						}
					});
			}
		}

		telenok.setBreadcrumbs(param.breadcrumbs);
		var presentation = new presentationTreeTab();
		presentation.setParam(param).setPageHeader();
		return presentation;
	});
</script>

<div>
    <div class="page-header position-relativee"></div>
	<div>
		<div class="col-xs-12 telenok-presentation-tabs"> 
			<div class="tabbable">
				<ul class="nav nav-tabs" id="nav-tabs-{{$presentation}}"></ul>
				<div class="tab-content module-web-page-view-container" id="tab-content-{{$presentation}}"></div>
			</div>
		</div>	
	</div>
</div>


<style>
    div#module-web-page-widget-list {
        position: absolute;
        background: white;
        padding: 10px;
        z-index: 10000;
    }

	div#module-web-page-widget-list hr.sep-1 {
		margin: 10px 0;
	}
	
    div#module-web-page-widget-list h3.header {
        margin-top: 0;
		cursor: move;
    }

    .frontend-container {
        border: 1px solid #D5E3EF;
    } 

    div.module-web-page-view-container .widget-header {
		padding-left: 2px;
    }

    div.module-web-page-view-container .widget-toolbar {
		padding-right: 2px;
    }

    div.module-web-page-view-container .row [class*="span"] {
		margin-left: 2px;
    }

    div.module-web-page-view-container .widget-placeholder {
		background: limegreen !important;
		color: limegreen !important;
    }

	div#menu-buffer-{{$uniqueId}} li a.dragabble i {
		color: #FFD9D5;
	}
</style>


<div id="module-web-page-widget-list" class="dropdown dropdown-preview">

    <h3 class="header smaller lighter blue">
        <i class="fa fa-th-large"></i>
        {{{$controller->LL('page.widget.title')}}}
    </h3>

    <script>
		var telenok_module_web_page_pid = 0;
	</script>

    <div class="clearfix">
        <select class="chosen" data-placeholder="{{{$controller->LL('page.select')}}}" id="module-web-page-widget-list-page-list" 
                onchange="telenok.getPresentationByKey('{{$presentation}}').addTabByURL({
								url:'{{\URL::route("cmf.module.web-page-constructor.view.page.container", ['id' => '--id--', 'languageId' => ':languageId:'])}}'
										.replace(/--id--/gi, parseInt(this.value, 10))
										.replace(/:languageId:/gi, parseInt(telenok_module_web_language_id, 10)),
								after: function() { updateContainer(); }
							});">
            <option value=""></option>
			<?php
			$pages = \Telenok\Web\Page::all();

			foreach ($pages as $page)
			{
				?>

				<option value="{{$page->id}}">{{{$page->translate('title')}}} [{{{$page->url_pattern}}}]</option>

				<?php
			}
			?>    
        </select>
    </div>

	<hr class="sep-1" />

	<div>
		<ul class="nav nav-tabs widget-choosing">
			<li class="active">
				<a data-toggle="tab" href="#menu-list-{{$uniqueId}}">
					{{{$controller->LL('tab.widget.list')}}}
				</a>
			</li>

			<li class="widget-buffer">
				<a data-toggle="tab" href="#menu-buffer-{{$uniqueId}}">
					{{{$controller->LL('tab.widget.buffer')}}}
					<span class="badge badge-important" id="menu-buffer-fa-{{$uniqueId}}">0</span>
				</a>
			</li>
			
			<li class="dropdown">
				<a data-toggle="tab" href="#menu-command-{{$uniqueId}}">
					{{{$controller->LL('tab.widget.command')}}}
				</a>
			</li>
		</ul>

		<div class="tab-content" style="overflow: visible; display: flex;">
			<div id="menu-list-{{$uniqueId}}" class="tab-pane active">
				<ul class="dropdown-menu" style="position: inherit; display: block;">
					<?php
					$group = \App::make('telenok.config')->getWidgetGroup();
					$widget = \App::make('telenok.config')->getWidget();

					foreach ($group as $g)
					{
						?>

						<li class="dropup dropdown-hover">

							<a href="javascript:void(0)" class="dropdown-toggle"><i class="{{$g->getIcon()}}"></i> {{{ $g->getName() }}}</a>

							<ul id="module-web-page-widget-list-item" class="dropdown-menu dropdown-success">

				<?php
				foreach ($widget as $w)
				{
					?>

								<li>
									<a href="javascript:void(0)" class="dragabble" data-widget-key="{{{$w->getKey()}}}" data-widget-id="0" data-widget-buffer-id="0" data-widget-buffer-key="0"><i class="{{$w->getIcon()}}"></i> {{{ $w->getName() }}}</a>
								</li>

					<?php
				}
				?>

							</ul>

						</li>

				<?php
			}
			?>
				</ul>
			</div>

			<div id="menu-buffer-{{$uniqueId}}" class="tab-pane">
				<ul class="dropdown-menu" style="position: inherit; display: block;" id="widget-menu-buffer">	
				<?php
				
					$widgetBufferedList = \Telenok\System\Buffer::with("sequence")->where(function($query) 
						{
							$query->where('user_id', \Auth::user()->getKey());
							$query->where('place', 'web-page');
						})->get(); 
					
					if (!$widgetBufferedList->isEmpty())
					{  
				?>
					@foreach($widgetBufferedList as $li) 
						<li>
							<a href="javascript:void(0)" class="dragabble" 
							   data-widget-key="{{{$li->sequence->model->key}}}" 
							   data-widget-buffer-id="{{{$li->getKey()}}}"
							   data-widget-buffer-key="{{{$li->key}}}"
							   data-widget-id="{{{$li->sequence->model->getKey()}}}"><i class="fa fa-times"></i> {{{$li->sequence->model->translate('title')}}}</a> 
						</li>
					@endforeach

				<?php

					}

				?>
				</ul>
			</div> 

			<div id="menu-command-{{$uniqueId}}" class="tab-pane">

				<select class="chosen" data-placeholder="{{{$controller->LL('language.select')}}}" id="module-web-page-widget-list-language-list"
						onchange="telenok_module_web_language_id = this.options[this.selectedIndex].value; reloadWebPageContainer();">
					<?php

					$localeDefault = \Config::get('app.localeDefault');
					$localeDefaultId = 0;

					$languages = \Telenok\System\Language::whereIn('locale', (array) \Config::get('app.locales'))
									->get()->sortBy(function($item) use ($localeDefault, &$localeDefaultId)
					{
						if ($item->locale == $localeDefault)
						{
							$localeDefaultId = $item->id;
							
							return 0;
						}
						else
						{
							return 1;
						}
					});

					foreach ($languages as $language)
					{
						?>

						<option value="{{$language->id}}">{{{$language->translate('title')}}}</option>

						<?php
					}
					?>    
				</select>

				
				<script>
					var telenok_module_web_language_id = "<?php echo $localeDefaultId; ?>";
				</script>
				
				<hr class="sep-1">
				
				<div class="clearfix">
					<span title="" data-placement="bottom" data-rel="tooltip" class="btn btn-info btn-sm tooltip-info" data-original-title="Reload page"
						  onclick="reloadWebPageContainer();">Reload page</span>
				</div>
			</div>
		</div>
	</div> 
</div>

<script>

	jQuery("div#module-web-page-widget-list").draggable({
		appendTo: "body",
		iframeFix: true,
		cancel: 'div#module-web-page-widget-list ul.dropdown-menu',
		cursor: "move",
		distance: 15,
	});

	function reloadWebPageContainer()
	{
		telenok.getPresentationByKey('{{$presentation}}').addTabByURL({
				url:'{{\URL::route("cmf.module.web-page-constructor.view.page.container", ['id' => '--id--', 'languageId' => ':languageId:'])}}'
						.replace(/--id--/gi, parseInt(telenok_module_web_page_pid, 10))
						.replace(/:languageId:/gi, parseInt(telenok_module_web_language_id, 10)),
				after: function() { updateContainer(); }
			}, true);
	}

	function updateContainer()
	{
		jQuery(".frontend-container:not(.container-me)")
			.addClass("container-me")
			.sortable({
				appendTo: 'body',
				opacity: 0.2,
				distance: 15,
				connectWith: '.frontend-container',
				revert: true,
				placeholder: "widget-placeholder",
				forceHelperSizeType: false,
				forcePlaceholderSize: false,
				handle: 'div.widget-header',
				stop: function(event, ui)
				{
					jQuery.ajax(
						{
							'url' : '{{\URL::route("cmf.module.web-page-constructor.view.page.insert.widget", ['languageId' => ':languageId:', 'key' => ':key:', 'bufferId' => ':bufferId:', 'container' => ':container:', 'id' => '--id--', 'pageId' => ':pageId:', 'order' => ':order:'])}}'
								.replace(/:container:/gi, jQuery(ui.item).closest(".frontend-container").data('container-id'))
								.replace(/:key:/gi, jQuery(ui.item).data('widget-key'))
								.replace(/:languageId:/gi, parseInt(telenok_module_web_language_id, 10))
								.replace(/:bufferId:/gi, jQuery(ui.item).data('widget-buffer-id'))
								.replace(/--id--/gi, parseInt(jQuery(ui.item).data('widget-id'), 10))
								.replace(/:pageId:/gi, parseInt(telenok_module_web_page_pid, 10))
								.replace(/:order:/gi, ui.item.index())
						})
						.done(function(data)
						{  
							var bufferKey = jQuery(ui.item).data('widget-buffer-key');
							
							if (bufferKey == 'cut')
							{
								var $container = jQuery('div.web-page-structure[data-web-page-id] div[data-container-id="' + data.container + '"]');
								var $el = jQuery('div.telenok-widget-box[data-widget-id="' + jQuery(ui.item).data('widget-id') + '"]');

								if ($container.size() && $el.size())
								{
									$container.append($el);
								}
								else
								{ 
									jQuery('div.telenok-widget-box[data-widget-id="' + jQuery(ui.item).data('widget-id') + '"]').remove();
								}

								jQuery('div#menu-buffer-{{$uniqueId}} ul li a[data-widget-buffer-id="' + jQuery(ui.item).data('widget-buffer-id') + '"]')
									.closest('li').remove();
							}

							if (bufferKey == 'cut' || bufferKey == 'copy')
							{
								reloadWebPageContainer();
							}

							jQuery(ui.item).replaceWith(data);

							updateContainer();
						});
				}
			})
			.droppable({
				accept: ".dragabble",
				greedy: true
			})
			.disableSelection();

		jQuery('.telenok-widget-box .widget-toolbar a[data-action="close"]:not(.remove-me)')
			.addClass("remove-me")
			.click(function(event)
			{
				event.preventDefault();
				event.stopPropagation();
				
				var this_ = this;

				jQuery.ajax({
					url: '{{\URL::route("cmf.module.web-page-constructor.view.page.remove.widget", ['id' => '--id--'])}}'
						.replace(/--id--/gi, jQuery(this).closest(".telenok-widget-box").data('widget-id')),
					context: document.body
				})
				.done(function(data)
				{
					if (data.success)
					{ 
						jQuery('div#menu-buffer-{{$uniqueId}} ul li a[data-widget-id="' + jQuery(this_).closest(".telenok-widget-box").data('widget-id') + '"] i').click();

						jQuery(this_).closest('.telenok-widget-box').fadeOut(function() {
							jQuery(this_).remove();
						});
					}
				});
			});

		jQuery('.telenok-widget-box .widget-toolbar a[data-action="settings"]:not(.settings-me)')
			.addClass("settings-me")
			.click(function(event)
			{	
				jQuery.ajax({
					url: '{{\URL::route("cmf.module.objects-lists.wizard.edit", ['id' => '--id--', 'chooseBtn' => 0, 'saveBtn' => 1])}}'
							.replace(/--id--/gi, jQuery(this).closest(".telenok-widget-box").data('widget-id')),					
					method: 'get',
					dataType: 'json'
				})
				.done(function(data) 
				{
					if (!jQuery('div#widget-setting-{{{$uniqueId}}}').size())
					{
						jQuery('body').append('<div id="widget-setting-{{{$uniqueId}}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
					}

					jQuery('div#widget-setting-{{{$uniqueId}}}').data('model-data', function(data) {})
					.html(data.tabContent)
					.modal('show')
					.on('hidden', function() 
					{ 
						jQuery(this).html(""); 
					});
				});
			});

		jQuery(
				'.telenok-widget-box .widget-toolbar a[data-action="cut"]:not(.copy-me),' +
				'.telenok-widget-box .widget-toolbar a[data-action="copy"]:not(.copy-me),' +
				'.telenok-widget-box .widget-toolbar a[data-action="copy-link"]:not(.copy-me)')
			.addClass("copy-me")
			.click(function(event)
			{
				event.preventDefault();
				event.stopPropagation();

				var this_ = this;

				jQuery.ajax({
					url: '{{\URL::route("cmf.module.web-page-constructor.view.buffer.add.widget", ['id' => '--id--', 'key' => ':key:'])}}'
						.replace(/--id--/gi, jQuery(this).closest(".telenok-widget-box").data('widget-id'))
						.replace(/:key:/gi, jQuery(this).data('action')),
					context: document.body
				})
				.success(function(data)
				{
					var $buffer = jQuery("ul#widget-menu-buffer"); 
					var $el = $buffer.find("li a[data-widget-id='" + data.widget.id + "']");

					jQuery('div#module-web-page-widget-list ul.widget-choosing li.widget-buffer a').click();

					if ($el.size())
					{
						$el.data('widget-buffer-key', jQuery(this_).data('action'));

						$el.closest('li').effect("highlight", {color: "#4F99C6"}, 1500 );
					}
					else
					{
						$buffer.append(
							'<li>' +
							'	<a href="javascript:void(0)" class="dragabble" data-widget-key="' + data.widget.key + '" data-widget-buffer-key="' + data.buffer.key + '" data-widget-buffer-id="' + data.buffer.id + '" data-widget-id="' + data.widget.id + '"><i class="fa fa-times"></i> ' + data.widget.title + '</a>' + 
							'</li>');
					} 

					updateContainer();
				});
			});

		jQuery("a.dragabble:not(.dragabble-me)", "div#module-web-page-widget-list")
			.addClass("dragabble-me")
			.draggable({
				appendTo: "body",
				iframeFix: true,
				connectToSortable: ".frontend-container",
				helper: "clone",
				revert: "invalid",
				cursor: "move",
				delay: 0
			});

		jQuery("a.dragabble i:not(.close-me)", "ul#widget-menu-buffer")
			.addClass("close-me")
			.click(function(event)
			{ 
				event.stopPropagation();
				event.preventDefault();

				this_ = this;

				jQuery.ajax({
					url: '{{\URL::route("cmf.module.web-page-constructor.view.buffer.delete.widget", ['id' => '--id--'])}}'
						.replace(/--id--/gi, jQuery(this).closest("a").data('widget-buffer-id')),
					context: document.body
				})
				.done(function(data)
				{ 
					jQuery(this_).closest('li').remove();
					updateContainer();
				});
			});

		var size = jQuery('li', "ul#widget-menu-buffer").size();

		jQuery('span#menu-buffer-fa-{{$uniqueId}}').text(parseInt(size, 10));
	}

	jQuery("#module-web-page-widget-list-page-list").ajaxChosen(
		{
			keepTypingMsg: "{{{$controller->LL('notice.typing')}}}",
			lookingForMsg: "{{{$controller->LL('notice.looking-for')}}}",
			type: "GET",
			url: "{{\URL::route("cmf.module.web-page-constructor.list.page")}}",
			dataType: "json",
			minTermLength: 1,
			afterTypeDelay: 1000
		},
		function (data)
		{
			var results = [];
				jQuery.each(data, function (i, val) {
					results.push({ value: val.id, text: val.title });
				});
			return results;
		},
		{
			width: "200px",
			no_results_text: "{{{$controller->LL('notice.not-found')}}}",
			allow_single_deselect: true
		});
		
		jQuery("#module-web-page-widget-list-language-list").chosen({disable_search_threshold: 10, width: "200px"});

		updateContainer();
</script>
