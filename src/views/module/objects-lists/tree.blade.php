@extends('core::presentation.tree-tab-object.tree')

	@section('select_node') 
		
        //data.inst.toggle_node(data.rslt.obj);

		if (data.rslt.obj.data("module"))
		{
			telenok.addModule(data.rslt.obj.data("moduleKey"), data.rslt.obj.data("moduleRouterActionParam"), function(moduleKey) 
			{
                var param = telenok.getModule(moduleKey) || {};

                if (!param.preCallingPresentationFlag)
                {
                    telenok.preCallingPresentation(moduleKey);
                }

                if (telenok.hasPresentation(param.presentationModuleKey))
                {
					param.addTree = false;

                    telenok.getPresentation(param.presentationModuleKey).callMe(param);
                }

                telenok.postCallingPresentation(moduleKey); 

			});		
		}
		else
		{
			telenok.getPresentation('{{$controller->getPresentationModuleKey()}}')
                .addTabByURL({
                    url: '{!! URL::route("cmf.module.{$controller->getKey()}") !!}?' + jQuery.param({ "treePid": data.rslt.obj.data('id') }),
                    after: function() 
                    {
                        telenok.getPresentation('{{$controller->getPresentationModuleKey()}}').reloadDataTableOnClick({
                            "url": '{!! $controller->getRouterList() !!}', 
                            "data": { "treePid": data.rslt.obj.data("id") },
                            "gridId": data.rslt.obj.data("gridId")
                        });
                    }});
		}
		
	@stop 