
    <div id='modal-business-{{$uniqueId}}' class="modal fade" style="max-height: 80%;overflow-y: auto;">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header table-header">
					<a class="close" data-dismiss="modal">×</a>
					<h3>Modal header</h3>
				</div>
				<div class="modal-body" style="max-height: none;text-align: center;">
					<p>One fine body…</p>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn">Close</a>
				</div>
				
			</div>
		</div>
    </div>



    @foreach($type->field()->get() as $field)
        @if (!in_array($field->code, ['process']))

            {{ \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) }}

        @elseif ($field->code=='process')

            {{Form::hidden('process')}}

            <div class="form-group">
                {{ Form::label('process', $field->translate('title'), array('class'=>'control-label')) }}
                <div class="controls">
                    <button type="button" class="btn" onclick="showProcessModal{{$uniqueId}}(); return false;">
                        <i class="fa fa-floppy-o"></i>
                        {{{ $controller->LL('btn.close') }}}
                    </button>
                </div>
            </div>

        @endif

        <hr />

    @endforeach

    <script type="text/javascript">
        function showProcessModal{{$uniqueId}}()
        {
            var modal = jQuery('#modal-business-{{$uniqueId}}').appendTo(document.body);

            modal.modal('show').css({
                'width': function () { 
                    return (jQuery(document).width() * .9) + 'px';  
                },
                'margin-left': function () { 
                    return -(jQuery(this).width() / 2); 
                }
            });
            
            if (!jQuery("#frame-process-{{$uniqueId}}").size())
            {
                jQuery('div.modal-body', modal)
                    .html(  '<iframe name="frame-process-{{$uniqueId}}" id="frame-process-{{$uniqueId}}" ' +
                            ' src="{{ URL::route("cmf.module.workflow-process.diagram.show", ['id' => intval($model->getKey())]) }}" />')
            }
            
            modal.on('hide', function(){
                if (window.frames['frame-process-{{$uniqueId}}'].oryxEditor)
                {
                    jQuery('input[name="process"]','#model-ajax-{{$uniqueId}}').val(window.frames['frame-process-{{$uniqueId}}'].oryxEditor.getSerializedJSON());
                }
            });

            var frame = jQuery('#frame-process-{{$uniqueId}}');
            frame.css({
                'width' : 1500,//parseInt(jQuery('div.modal-body', modal).css('width'), 10) - 20,
                'height' : 550
            });
            
            frame.load(function(){
                window.frames['frame-process-{{$uniqueId}}'].importJSONFromTop = function() {
                    return jQuery('input[name="process"]','#model-ajax-{{$uniqueId}}').val() 
                }
            });
        }
    </script>