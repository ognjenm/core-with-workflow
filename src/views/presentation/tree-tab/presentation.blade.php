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
                jQuery('div.page-header', '#'+_this.presentationDomId).html('<h1>' 
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

            this.addTab = function(param)
            {
                if (!param.tabKey) return _this;

                var id = _this.presentationDomId + '-tab-' + param.tabKey;
                var tabs = jQuery('div.telenok-presentation-tabs', '#' + _this.presentationDomId);

                if (jQuery('div#'+id, tabs).length)
                {
                    jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a[href="#'+id+'"]', tabs).tab('show');
                    return _this;
                }

                var tabTemplate = "<li><a href='#" + id + "' data-toggle='tab'><i class='green fa fa-home bigger-110'></i>&nbsp;" + param.tabLabel + "&nbsp;<i class='fa fa-times red' style='cursor:pointer;'></i></a></li>";

                var $li = jQuery(tabTemplate);

                jQuery('ul.nav-tabs#nav-tabs-{{$presentation}}', tabs).append($li);
				
				$li.on('mousedown', function(event)
				{
					if (event.which == 2)
					{
						event.stopPropagation();
						event.preventDefault();
						jQuery('i.fa.fa-times', this).click();
					}
				});
	
				jQuery('div.tab-content#tab-content-{{$presentation}}', tabs).append("<div class='tab-pane' id='" + id + "'>" + param.tabContent + "</div>");
                jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).tab('show');

                jQuery('a i.fa.fa-times', $li).click(function()
                {
                    var tabId = jQuery('a', $li).attr('href');
                    jQuery(tabId).remove();
                    $li.remove();
                    jQuery('ul.nav-tabs#nav-tabs-{{$presentation}} a:last', tabs).tab('show');
                });
				 
                return _this;
            }

            this.addTabByURL = function(param)
            {
                jQuery.ajax({
                    url: param.contentUrl,
                    method: 'get',
                    dataType: 'json',
                    data: param.data || {}
                })
                .success(function(data)
                {
                    _this.addTab({tabKey: data.tabKey, tabLabel : data.tabLabel, tabContent : data.tabContent});
                    
                    if(jQuery.isFunction(param.after))
                    {
                        param.after();
                    }
                });

                return _this;
            }

            this.addTree = function() 
            {
 
                var key = 'telenok-presentation-' + _this.presentationParam.key + '-tree';

                if (!_this.presentationParam.treeContent) 
                {
                    return _this;
                }

                jQuery('div.telenok-presentation-tabs', '#'+_this.getPresentationDomId()).removeClass('col-xs-12').addClass('col-xs-9');
                jQuery('div.telenok-presentation-tree', '#'+_this.getPresentationDomId()).show();

				//jQuery('table.dataTable').each(function(i, v)
				//{
				//	jQuery(v).css({ width: jQuery(v).parent().parent().parent().parent().width() });
				//});

                if (jQuery('#' + key).size()) 
                {
                    jQuery('div.telenok-tree', '#'+_this.presentationDomId).hide();
                    jQuery('#' + key, '#'+_this.presentationDomId).show();

                    return _this;
                }

                jQuery('div.telenok-tree', '#'+_this.presentationDomId).hide();

                jQuery('div.telenok-presentation-tree', '#'+_this.presentationDomId).append(
                        '<div id="' + key + '" class="telenok-tree">'
                        + _this.presentationParam.treeContent
                        + '</div>'
                    );

                return _this;
            }

            this.addDataTable = function(param)
            {
                param = jQuery.extend({}, {
                    "multipleSelection": true,
                    "aoColumns": [],
					"autoWidth": false,
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
                                    else _this.addTabByURL({contentUrl: param.btnCreateUrl});
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
                                                        contentUrl: param.btnListEditUrl, 
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
                                                    else {
                                                        //
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
                                "fnClick": function(nButton, oConfig, oFlash) {
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
                        "sSearch": "{{{ \Lang::get('core::default.table.search') }}} ",
                        "sInfo": "{{{ \Lang::get('core::default.table.showed') }}}",
                        "sInfoEmpty": "{{{ \Lang::get('core::default.table.empty.showed') }}}",
                        "sZeroRecords": "{{{ \Lang::get('core::default.table.empty.filtered') }}}",
                        "sInfoFiltered": "",
                    }
                }, param);

                jQuery('#' + param.domId).dataTable(param);

                return _this;
            }

            this.ReloadDataTableOnClick = function(param)
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
            presentation.setParam(param)
                .setPageHeader()
                .addTree()
                .addTabByURL(param); 
  
        return presentation;
    });
</script>

<div>
    <div class="page-header position-relativee"></div>
	<div>
		<div class="telenok-presentation-tree col-xs-3" style="display: none;"></div>
		<div class="col-xs-12 telenok-presentation-tabs"> 
			<div class="tabbable">
				<ul class="nav nav-tabs" id="nav-tabs-{{$presentation}}"></ul>
				<div class="tab-content" id="tab-content-{{$presentation}}"></div>
			</div>
		</div>	
	</div>
</div>
 