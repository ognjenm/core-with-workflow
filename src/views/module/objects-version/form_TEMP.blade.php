    <script type="text/javascript">
        jQuery('input[name=code]', '#model-ajax-{{$uniqueId}}').blur(function() {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');

            var pathModel = jQuery('input[name=namespace_path_class_model]', $form).val();
            var pathForm = jQuery('input[name=namespace_path_class_form]', $form).val();

            namespaceClassModelByPath{{$uniqueId}}(pathModel);

            if (jQuery.trim(pathForm))
            {
                namespaceClassFormByPath{{$uniqueId}}(pathForm);
            }
        });

        function class_model{{$uniqueId}}()
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.file-browser.wizard.list') }}',
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                jQuery('#modal-{{$uniqueId}}').data('path', function(path){ 
                    namespaceClassModelByPath{{$uniqueId}}(path);
                }).html(data.content).modal('show');
            });
        }

        function namespaceClassModelByPath{{$uniqueId}}(path)
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
            var code = jQuery('input[name=code]', $form).val();

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.objects-type.get.namespace-model-by-path') }}',
                data: {'path' : path, 'code' : code},
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                jQuery('input[name=namespace_path_class_model]', $form).val('');
                jQuery('div.alert-danger', $form).remove();

                if (data.error)
                {
                    if (data.error.length) 
                    {
                        $form.closest('div.tab-pane').prepend(jQuery('<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>').prepend(function(){return jQuery.each(data.error, function(i,v){ return '<li>'+v+'</li>'; })}))
                    }
                }
                else
                {
                    var code = jQuery('input[name=code]', $form).val() || '';

                    jQuery('input[name=class_model]', $form).val(data.class);
                    jQuery('input[name=namespace_path_class_model]', $form).val(path);
                }
            });
        } 

        function class_controller{{$uniqueId}}()
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
        
            jQuery.ajax({
                url: '{{ URL::route('cmf.module.file-browser.wizard.list') }}',
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                jQuery('#modal-{{$uniqueId}}').data('path', function(path){ 
                    namespaceClassFormByPath{{$uniqueId}}(path);
                }).html(data.content).modal('show');
            });
        }

        function namespaceClassFormByPath{{$uniqueId}}(path)
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
            var code = jQuery('input[name=code]', $form).val();

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.objects-type.get.namespace-form-by-path') }}',
                data: {'path' : path, 'code' : code},
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                jQuery('input[name=namespace_path_class_form]', $form).val('');
                jQuery('div.alert-danger', $form).remove();

                if (data.error)
                {
                    if (data.error.length) 
                    {
                        $form.closest('div.tab-pane').prepend(jQuery('<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>').prepend(function(){return jQuery.each(data.error, function(i,v){ return '<li>'+v+'</li>'; })}))
                    }
                }
                else
                {
                    var code = jQuery('input[name=code]', $form).val() || '';

                    jQuery('input[name=class_controller]', $form).val(data.class);
                    jQuery('input[name=namespace_path_class_form]', $form).val(path);
                }
            });
        }
    </script>

	<div class="row">
		<div class="col-xs-12">
			<div class="tabbable">
				<ul class="nav nav-tabs" id='form-nav-{{{$uniqueId}}}'>

					@foreach($type->tab()->get() as $tab) 

					<li>
						<a data-toggle="tab" href="#{{{$uniqueId}}}_{{{$tab->code}}}">
							@if ($tab->icon_class)
							<i class="{{{$tab->icon_class}}}"></i>
							@endif
							{{{$tab->translate('title')}}}
						</a>
					</li>

					@endforeach
				</ul>

				<script>
					jQuery("ul#form-nav-{{{$uniqueId}}} li:first a").click();
				</script>

				<div class="tab-content">

					@foreach($type->tab()->get()->sortBy('tab_order') as $tab) 

					<div id="{{{$uniqueId}}}_{{{$tab->code}}}" class="tab-pane in">

						@foreach($tab->field()->get()->filter(function($item) { return $item->show_in_form == 1; })->sortBy('field_order') as $field) 

							@if (!in_array($field->code, ['code', 'class_controller', 'class_model']))

								@if ($modelContent = \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId))
								{{$modelContent}}
								@endif

							@elseif ($field->code=='code')

								<div class="form-group">
									<?php 
										$code = ['class' => 'col-xs-5 col-sm-5'];

										if ($model->exists)
										{
											$code['readonly'] = 'readonly';
										}
									?>
									{{ Form::label('code', $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
									<div class="col-sm-9">
										{{ Form::text('code', null, $code) }}
									</div>
								</div> 

							@elseif ($field->code=='class_controller')

								<div class="form-group">
									{{ Form::label('class_controller', $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
									<div class="col-sm-9">
										{{ Form::text('class_controller', null, ['readonly' => 'readonly', 'class' => 'col-xs-5 col-sm-5']) }}
										{{ Form::hidden('namespace_path_class_form') }}
										<?php if (!class_exists($model->class_controller)) { ?>
										<span class="btn btn-sm" data-toggle="modal" onclick="class_controller{{$uniqueId}}();">
											<i class="fa fa-file"></i> Choose folder
										</span>
										<?php } ?>
									</div>
								</div> 

							@elseif ($field->code=='class_model')

								<div class="form-group">
									{{ Form::label('class_model', $field->translate('title'), array('class'=>'col-sm-3 control-label no-padding-right')) }}
									<div class="col-sm-9">
										{{ Form::text('class_model', null, ['readonly' => 'readonly', 'class' => 'col-xs-5 col-sm-5']) }}
										{{ Form::hidden('namespace_path_class_model') }}
										<?php if (!class_exists($model->class_model)) { ?>
										<span class="btn btn-sm" data-toggle="modal" onclick="class_model{{$uniqueId}}();">
											<i class="fa fa-file"></i> Choose folder
										</span>
										<?php } ?>
									</div>
								</div> 

							@endif

						@endforeach 

					</div>

					@endforeach

				</div>
			</div>
		</div>
	</div>
