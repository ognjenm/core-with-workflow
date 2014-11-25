@extends('core::presentation.tree-tab.tree')

	@section('select_node')

	data.inst.toggle_node(data.rslt.obj);

	telenok.getPresentation('{{$controller->getPresentationModuleKey()}}')
			.addTabByURL({
				url:'{!! URL::route("cmf.module.{$controller->getKey()}") !!}?' + jQuery.param({'treePid':data.rslt.obj.data('id')}),
				after: function() {
					telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').reloadDataTableOnClick({
						url: '{!! $controller->getRouterList() !!}', 
						data: { treePid: data.rslt.obj.data("id") },
						gridId: data.rslt.obj.data("gridId")
					});
				}});
	@stop