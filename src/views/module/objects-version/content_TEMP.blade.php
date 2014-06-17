<div class="container-table">

    <div class="table-header">{{$type->translate('title_list')}}</div>
    
    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{{ $controller->LL('table.filter.header') }}}</h5>
                <span class="widget-toolbar no-border">
                    <a data-action="collapse" href="#">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal telenok-object-field-filter" onsubmit="return false;">
						
						<input type="hidden" name="filter_want_search" value="1" />
 
                        @foreach($fieldsFilter as $key => $field) 
								
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-1">{{{ $field->translate('title') }}}</label>
								<div class="col-sm-9">
									{{ \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFilterContent($field) }} 
								</div>
							</div> 
						
                        @endforeach
 
						<div class="form-group center">
							<div class="hr hr-8 dotted"></div>
							<button class="btn btn-sm btn-info" onclick="presentationTableFilter{{$uniqueId}}(this);">
								<i class="fa fa-search bigger-110"></i>
								{{{ $controller->LL('table.filter.btn') }}}
							</button>
							<button class="btn btn-sm" type="reset" onclick="presentationTableFilter{{$uniqueId}}(this, true);">
								<i class="fa fa-eraser bigger-110"></i>
								{{{ $controller->LL('btn.clear') }}}
							</button>
						</div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}" role="grid"></table>



    <script type="text/javascript">

        var presentation = telenok.getPresentationByKey('{{$controller->getPresentation()}}');
        var aoColumns = [];  
                @foreach($fields as $key => $field)
                        aoColumns.push({ "mData": "{{ $field->code }}", "sTitle": "{{{ $field->translate('title_list') }}}", "bSortable": ( {{$field->allow_sort}} ? true : false ) });
                    @if ($key==1)
                        aoColumns.push({ "mData": "tableManageItem", "sTitle": "{{{ $controller->LL('action') }}}", "bSortable": false }); 
                    @endif
                @endforeach

                presentation.addDataTable({
                    "oTableTools": {
                        "aButtons": [
                            {
                                "sExtends": "text",
                                "sButtonText": "<i class='fa fa-refresh smaller-90'></i> {{{ $controller->LL('list.btn.refresh') }}}",
                                'sButtonClass': 'btn-sm',
                                "fnClick": function(nButton, oConfig, oFlash) {
                                    jQuery('#' + presentation.getPresentationDomId() + "-grid-{{$gridId}}").dataTable().fnReloadAjax();
                                }
                            }
                        ]
                    },
                    aoColumns : aoColumns, 
					aaSorting: [],
                    sAjaxSource : '{{ $controller->getRouterList(['treePid' => $type->getKey()]) }}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}"
                });
                
        function presentationTableFilter{{$uniqueId}}(dom_obj, erase)
        {
            if (erase)
            {
                jQuery('.chosen', jQuery(dom_obj).closest('form')).val('').trigger('chosen:updated');
                jQuery('input[name="filter_want_search"]', jQuery(dom_obj).closest('form')).val(0);
            }
            else
			{
                jQuery('input[name="filter_want_search"]', jQuery(dom_obj).closest('form')).val(1);
			}

            
            jQuery('#telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}')
                .dataTable()
                .fnReloadAjax('{{ URL::route("cmf.module.{$controller->getKey()}.list") }}?' + (erase ? '' : jQuery.param(jQuery(dom_obj).closest('form').serializeArray())));
        }
    </script>