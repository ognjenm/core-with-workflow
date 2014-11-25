<div class="container-table">

    <div class="table-header">{{ Lang::get("{$controller->getPackage()}::module/{$controller->getKey()}.list.name")}}</div>

    <div class="filter display-none">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title smaller">##### $controller->LL('table.filter.header') $$$$$$$$$$</h5>
                <span class="widget-toolbar no-border">
                    <a data-action="collapse" href="#">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </span>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" onsubmit="return false;">
                        
                        <button class="btn btn-info btn-sm" onclick="return false;">
                            <i class="fa fa-key bigger-110"></i>
                            ##### $controller->LL('btn.search') $$$$$$$$$$
                        </button>
                        <button class="btn btn-sm" type="reset">
                                <i class="fa fa-eraser bigger-110"></i>
                                ##### $controller->LL('btn.clear') $$$$$$$$$$
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <table class="table table-striped table-bordered table-hover" id="telenok-{{$controller->getPresentation()}}-presentation-grid-{{$gridId}}" role="grid"></table>



    <script type="text/javascript">

		var currentDirectory{{$uniqueId}} = '{{$currentDirectory}}';

        var presentation = telenok.getPresentation('{{$controller->getPresentationModuleKey()}}');
        var aoColumns = []; 
                aoColumns.push({ "mData": "tableCheckAll", "sTitle": '<label><input type="checkbox" name="checkHeader" class="ace ace-switch ace-switch-6" onclick="var tb=jQuery(\'#' + 
                            presentation.getPresentationDomId() + '-grid-{{$gridId}}\').dataTable();var chbx = jQuery(\'input[name=tableCheckAll\\\\[\\\\]]\', tb.fnGetNodes());chbx.prop(\'checked\', jQuery(\'input[name=checkHeader]\', tb).prop(\'checked\'));"><span class="lbl"></span></label>',
							"mDataProp": null, "sClass": "center", "sWidth": "20px", 
							"sDefaultContent": '<label><input type="checkbox" class="ace ace-switch ace-switch-6" name="tableCheckAll[]"><span class="lbl"></span></label>', 
							"bSortable": false});
                @foreach((array)$fields as $key => $field)
                    @if ($key==0)
                        aoColumns.push({ "mData": "{{ $field}}", "sTitle": "##### $controller->LL("field." . $field) $$$$$$$$$$", "bSortable": false });
                        aoColumns.push({ "mData": "tableManageItem", "sTitle": "##### $controller->LL('action') $$$$$$$$$$", "bSortable": false });
                    @else
                        aoColumns.push({ "mData": "{{ $field}}", "sTitle": "##### $controller->LL("field." . $field) $$$$$$$$$$", "bSortable": false });
                    @endif
                @endforeach

                presentation.addDataTable({
                    aoColumns : aoColumns,
					aaSorting: [],
                    sAjaxSource : '{{ $controller->getRouterList(['uniqueId' => $uniqueId]) }}',
                    domId: presentation.getPresentationDomId() + "-grid-{{$gridId}}", 
					tableListBtnCreate: {
							"sExtends": "collection",
							'sButtonClass': 'btn btn-sm btn-success',
							"sButtonText": "<i class='fa fa-plus smaller-90'></i> ##### $controller->LL('list.btn.create') $$$$$$$$$$",
							"aButtons": [ 
								{
									"sExtends": "text",
									"sButtonText": "<i class='fa fa-folder'></i> ##### $controller->LL('btn.create.directory') $$$$$$$$$$",
									"fnClick": function(nButton, oConfig, oFlash) 
									{ 
										telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
											url: '{{ $controller->getRouterCreate() }}',
											data: {
												currentDirectory: currentDirectory{{$uniqueId}},
												modelType : 'directory'
											}
										}); 
									}
								},
								{
									"sExtends": "text",
									'sButtonClass': '',
									"sButtonText": "<i class='fa fa-file'></i> ##### $controller->LL('btn.create.file') $$$$$$$$$$",
									"fnClick": function(nButton, oConfig, oFlash) {
										telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').addTabByURL({
											url: '{{ $controller->getRouterCreate() }}', 
											data: {
												currentDirectory: currentDirectory{{$uniqueId}},
												modelType : 'file'
											}
										}); 
									}
								}
							]
						}
                });
    </script>
</div>