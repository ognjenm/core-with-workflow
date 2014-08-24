
 

<script type="text/javascript">
    jQuery("#tree-{{$id}}").jstree({
		"themes" : {
			"theme": "proton",
			"url": "packages/telenok/core/js/jquery.jstree/themes/proton/style.css"
		},
        "core": {"initially_open": ["root-not-delete"]},
        "crrm": {
            "move": {
                "default_position": "first",
                "check_move": function(m) {
                    return (m.o[0].attr("rel") === "folder") ? true : false;
                }
            }
        },
        "types" : {
            "valid_children" : [ "root" ],
            "types" : {
                "root" : {
                    "icon" : { 
                        //"image" : "packages/telenok/core/css/jstree/root.png" 
                    },
                    "valid_children" : [ "default" ],
                    "hover_node" : false
                },
                "folder" : {
                    "icon" : { 
                        //"image" : "packages/telenok/core/css/jstree/folder.png" 
                    },
                    "valid_children" : [ "default" ],
                    "hover_node" : false
                },
                "default" : {
                    "valid_children" : [ "default" ]
                }
            }
        },
        "json_data": {
            "progressive_render": true,
            "ajax" : {
                "type": 'GET',
                "url": function (node) {
                    var nodeId = "", url = "";

                    if (!jQuery(node).attr('id')) {
                        url = '{{ URL::route("cmf.module.{$controller->getKey()}.list.tree") }}';
                    }
                    else
                    {
                        nodeId = jQuery(node).attr('id');
                        url = '{{ URL::route("cmf.module.{$controller->getKey()}.list.tree") }}?id=' + nodeId;
                    }

                    return url;
                }
            }
        },
        "search" : {
            "case_insensitive": true,
            "ajax": {
                "url": '{{ URL::route("cmf.module.{$controller->getKey()}.list.tree") }}'
            }
        },
		
        "plugins": ["themes", "json_data", "ui", "crrm", "search", "types", "state"]
    })
    .bind("select_node.jstree", function(event, data) 
    {
        data.inst.toggle_node(data.rslt.obj);

        telenok.getPresentationByKey('{{$controller->getPresentation()}}')
                .addTabByURL({
                    contentUrl:'{{ URL::route("cmf.module.{$controller->getKey()}") }}?' + jQuery.param({'treePid':data.rslt.obj.data('id')}),
                    after: function() {
                        telenok.getPresentationByKey('{{$controller->getPresentation()}}').ReloadDataTableOnClick({
                            url: '{{ $controller->getRouterList() }}', 
                            data: { treePid: data.rslt.obj.data("id") },
                            gridId: data.rslt.obj.data("gridId")
                        });
                    }});
    });
</script>

<div class="widget-box span">
    <div class="widget-header ">
        <h4 class="lighter widget-title smaller">{{{$treeChoose}}}</h4>
        <span class="widget-toolbar">

            
            <a data-action="settings" href="#" class="dropdown-toggle " data-toggle="dropdown">
                <i class="fa fa-search"></i>
            </a> 

            <div class="dropdown-menu dropdown-yellow dropdown-caret">
				<div class="input-group">
					<input type="text" class="input-medium search-query" onclick="event.stopPropagation();" 
							placeholder="{{{ $controller->LL('btn.search') }}}..." 
							onchange="jQuery('#tree-{{$id}}').jstree('search', jQuery(this).val());" 
						/>
					<span class="input-group-btn">
						<button class="btn btn-sm btn-info" type="button" onclick="jQuery('#tree-{{$id}}').jstree('search', jQuery(this).prev().val());return false;">
							<i class="fa fa-search  bigger-110"></i>
							{{{ $controller->LL('btn.search') }}}
						</button>
						<button class="btn btn-sm" type="button" onclick="jQuery('#tree-{{$id}}').jstree('clear_search');return false;">
							<i class="fa fa-eraser  bigger-110"></i>
							{{{ $controller->LL('btn.clear') }}}!
						</button>					</span>
			   </div>
            </div> 

            <a data-action="reload" href="#" onclick="jQuery('#tree-{{$id}}').jstree('refresh');return false;">
                <i class="fa fa-refresh"></i>
            </a>
        </span>
    </div>

    <div class="widget-body">
        <div class="widget-main padding-8">
            <div id="tree-{{$id}}"></div>
        </div>
    </div>
</div>