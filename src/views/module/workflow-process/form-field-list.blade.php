	@if ($field->code == 'process')
		<div id='modal-business-{{$uniqueId}}' class="modal fade" role="dialog" aria-labelledby="label" style="overflow-y: hidden;">
			<div class="modal-dialog" style="width:100%;">
				<div class="modal-content">

					<div class="modal-header table-header" style="margin-bottom: 0;">
						<button data-dismiss="modal" class="close" type="button">×</button>
						<h4>Business process editor</h4>
					</div>
					<div class="modal-body" style="max-height: none; text-align: center; padding: 0;">
						<p>One fine body…</p>
					</div>
					<div class="modal-footer">
						<a href="#" class="btn" data-dismiss="modal">Close</a>
					</div>

				</div>
			</div>
		</div>

		<!-- Process field -->

		{{Form::hidden('process', $model->{$field->code})}}

		<div class="form-group">
			{{ Form::label('process', $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
			<div class="col-sm-3">
				<button type="button" class="btn" onclick="showProcessModal{{$uniqueId}}(); return false;">
					<i class="fa fa-floppy-o"></i>
					{{{ $controller->LL('btn.open-process-editor') }}}
				</button>
			</div>
		</div>

		<script type="text/javascript">
			function showProcessModal{{$uniqueId}}()
			{
				var modal = jQuery('#modal-business-{{$uniqueId}}').appendTo(document.body);
					modal
						.modal('show')
						.css({
							'width': function () { 
								return (jQuery(window).width() * .9) + 'px';  
							},
							'margin-left': function () { 
								return (jQuery(window).width() - $(this).outerWidth()) / 2;
							}
						});

				if (!jQuery("#frame-process-{{$uniqueId}}").size())
				{
					jQuery('div.modal-body', modal)
						.html(  '<iframe name="frame-process-{{$uniqueId}}" id="frame-process-{{$uniqueId}}" ' +
								' style="width: 100%; border: none;"' + 
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
						'height' : jQuery(window).height() - 200
					});

				frame.load(function(){
					window.frames['frame-process-{{$uniqueId}}'].importJSONFromTop = function() {
						return jQuery('input[name="process"]','#model-ajax-{{$uniqueId}}').val() 
					}
				});
			}
		</script>
	
	@else
		{{ \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) }}
	@endif