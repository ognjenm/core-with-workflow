
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

							@if (!in_array($field->code, ['key']))

								{{\App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId)}}

							@elseif ($field->code=='key')

								{{ Form::hidden('key') }}

								<div class="form-group">
									{{ Form::label('key', $field->translate('title'), array('class'=>'control-label')) }}
									<div class="controls">

										<?php 

										$key = ['onchange' => "onChangeType{$uniqueId}()"];

										if ($model->exists)
										{
											$key['disabled'] = 'disabled';
										}

										$selectFields = [];
										$multilanguageFields = [];

										\App::make('telenok.config')->getObjectFieldController()->each(function($field) use (&$selectFields, &$multilanguageFields) 
										{  
											$selectFields[$field->getKey()] = $field->getName(); 

											if ($field->allowMultilanguage())
											{
												$multilanguageFields[] = $field->getKey();
											}
										});

										?>
									{{ Form::select('key', $selectFields, null, $key) }}
									</div>
								</div>

								<script type="text/javascript">
									function onChangeType{{$uniqueId}}()
									{
										var $form = jQuery('#model-ajax-{{$uniqueId}}');

										var $key = jQuery('select[name="key"]', $form);

										@if (!$model->exists)

										if ( ['{{implode("','", $multilanguageFields)}}'].join(',').indexOf($key.val())>=0 )
										{
											jQuery('input[name="multilanguage"][type="checkbox"]', $form).removeAttr('disabled');
										}
										else
										{
											jQuery('input[name="multilanguage"][type="checkbox"]', $form).attr('disabled', 'disabled');
										}

										@endif
									}

									onChangeType{{$uniqueId}}();

								</script>

								@if ($model->exists) 

								<div class="form-group"> 
									{{\App::make('telenok.config')->getObjectFieldController()->get($model->key)->getFormFieldContent($model, $uniqueId)}}
								</div>        

								@endif

							@endif

						@endforeach 

					</div>

					@endforeach

				</div>
			</div>
		</div>
	</div>
 