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
                aoColumns.push({ "mData": "tableCheckAll", "sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" onclick="var tb=jQuery(\'#' + 
                            presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>', 
						"mDataProp": null, "sClass": "center", "sWidth": "20px", 
						"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>',
						"bSortable": false});
                @foreach($fields as $key => $field)
                        aoColumns.push({ "mData": "{{ $field->code }}", "sTitle": "{{{ $field->translate('title_list') }}}", "bSortable": ( {{$field->allow_sort}} ? true : false ) });
                    @if ($key==1)
                        aoColumns.push({ "mData": "tableManageItem", "sTitle": "{{{ $controller->LL('action') }}}", "bSortable": false }); 
                    @endif
                @endforeach

                presentation.addDataTable({
                    aoColumns : aoColumns, 
					aaSorting: [],
                    sAjaxSource : '{{ $controller->getRouterList(['treePid' => $type->getKey()]) }}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}",
                    btnCreateUrl : '{{ $controller->getRouterCreate(['id' => $type->getKey()]) }}',
                    btnListEditUrl : '{{ $controller->getRouterListEdit(['id' => $type->getKey()]) }}',
                    btnListDeleteUrl : '{{ $controller->getRouterListDelete(['id' => $type->getKey()]) }}',
                    btnCreateDisabled : '{{ !\Auth::can('create', "object_type.{$type->code}") }}',
                    btnListDeleteDisabled : '{{ !\Auth::can('delete', "object_type.{$type->code}.%") }}'
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
                .fnReloadAjax('{{ $controller->getRouterList(['treePid' => $type->getKey()]) }}?' + (erase ? '' : jQuery.param(jQuery(dom_obj).closest('form').serializeArray())));
        }
    </script>
