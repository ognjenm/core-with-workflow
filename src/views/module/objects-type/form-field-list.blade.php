							@if (!in_array($field->code, ['code', 'class_controller', 'class_model']))

								{{\App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId)}}

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
