 

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>End point</h4>
		</div>
			
        <div class="modal-body" style="max-height: none; padding: 15px;">
            <div class="widget-main">
                <div class="row">
                    <div class="col-xs-12">
                        <form action="#" onsubmit="return false;" class="form-horizontal">

                            {{Form::hidden('sessionDiagramId', $sessionDiagramId)}}
                            {{Form::hidden('stencilId', $stencilId)}}

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[title]">{{{$controller->LL('title')}}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[title]" value="{{{array_get($property, 'title')}}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[bgcolor]">{{{$controller->LL('property.bgcolor')}}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[bgcolor]" value="{{{array_get($property, 'bgcolor')}}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[bordercolor]">{{{$controller->LL('property.bordercolor')}}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[bordercolor]" value="{{{array_get($property, 'bordercolor')}}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[description]">{{{$controller->LL('property.description')}}}</label>
                                <div class="col-sm-3">
                                    <input type="text" name="stencil[description]" value="{{{array_get($property, 'description')}}}" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="stencil[eventList]">{{{$controller->LL('property.eventList')}}}</label>
                                <div class="col-sm-3">
                                    <select class="chosen-select" multiple data-placeholder="{{{$controller->LL('notice.choose')}}}" id="input{{$uniqueId}}" name="stencil[event_list][]">
                                        <option value="workflow.store.before" @if (in_array('workflow.store.before', array_get($property, 'event_list', []))) selected @endif>Before creating new model</option>
                                        <option value="workflow.store.after" @if (in_array('workflow.store.after', array_get($property, 'event_list', []))) selected @endif>After creating new model</option>
                                        <option value="workflow.update.before" @if (in_array('workflow.update.before', array_get($property, 'event_list', []))) selected @endif>Before updating model</option>
                                        <option value="workflow.update.after" @if (in_array('workflow.update.after', array_get($property, 'event_list', []))) selected @endif>After updating model</option>
                                    </select> 
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery("#input{{$uniqueId}}").chosen({width: "300px"});
        </script>

        <div class="modal-footer">

            <div class="center no-margin">

                <button class="btn btn-success" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);
                    return false;">
                    <i class="fa fa-bullseye"></i>
                    {{{ $controller->LL('btn.apply') }}}
                </button>

                <button class="btn btn-success" data-dismiss="modal" onclick="
                    var $modal = jQuery(this).closest('div.modal');
                    var $form = jQuery('form', $modal);
                    $modal.data('model-data')($form);">
                    <i class="fa fa-bullseye"></i>
                    {{{ $controller->LL('btn.apply.close') }}}
                </button>

                <button class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-bullseye"></i>
                    {{{ $controller->LL('btn.close') }}}
                </button>

            </div>

        </div>

	</div>
</div>
