@extends('core::layout.backend')

@section('head')
<title>Installation</title>
@parent

<script type="text/javascript">
    jQuery(function() {
        var success = false;
        
        jQuery('#modal-wizard .modal-header').ace_wizard();
        jQuery('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');

        jQuery('#intall-ajax').submit(function(e) { 
            e.preventDefault();
        }); 
 
        jQuery('#modal-wizard .modal-header').on('change', function(e, data) {
            
            if (data.step===2 && data.direction==='next') 
            {
                jQuery('#modal-step3 i.fa fa-spinner').hide();
            } 
            
            if (data.step===3 && data.direction==='next') 
            {
                if (success) return true;
                
                e.preventDefault();
                
                jQuery('div.error-list').hide();
                
                jQuery('#modal-step3 i.fa fa-spinner').show();
                
                jQuery('.btn-prev, .btn-next', '#modal-wizard .wizard-actions').attr('disabled', 'disabled');

                jQuery.ajax({
                    method: 'post',
                    url: "{{ URL::route('cmf.install.process') }}",
                    context: document.body,
                    data: jQuery('#intall-ajax').serialize()
                }).done(function(data) {
                    
                    jQuery('#modal-step3 i.fa fa-spinner').hide();

                    if (data.error)
                    {
                        jQuery('div.error-common').show();

                        jQuery('.btn-prev, .btn-next', '#modal-wizard .wizard-actions').removeAttr('disabled');
                        
                        jQuery.each(data.error, function(k, v) {
                            jQuery('div.error-list.'+k).show();
                        });
                    }
                    else if (data.success)
                    {
                        success = true;
                        
                        jQuery('.btn-prev', '#modal-wizard .wizard-actions').remove();
                        jQuery('.btn-next', '#modal-wizard .wizard-actions').removeAttr('disabled').click();
                    }
                });

                return false;
            }
        });
    });
</script>
@stop

@section('body')
<body>
    {{ Form::open(array('route' => 'cmf.install', 'id'=>"intall-ajax", 'class'=>'form-horizontal')) }}

        <?php $errors = null; ?>
        <div class="modal" id="modal-wizard" aria-hidden="false" style="width: 800px; margin-left: -370px;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div data-target="#modal-step-contents" class="modal-header table-header">
						<h3>{{\Lang::get('core::install/process.header')}}</h3>
						<hr/>
						<ul class="wizard-steps clearfix">
							<li class="active" data-target="#modal-step1" style="min-width: 25%; max-width: 25%;">
								<span class="step">1</span>
								<span class="title">{{{ \Lang::get('core::install/process.step.1.description.title') }}}</span>
							</li>

							<li data-target="#modal-step2" style="min-width: 25%; max-width: 25%;">
								<span class="step">2</span>
								<span class="title">{{{ \Lang::get('core::install/process.step.2.description.title') }}}</span>
							</li>

							<li data-target="#modal-step3" style="min-width: 25%; max-width: 25%;">
								<span class="step">3</span>
								<span class="title">{{{ \Lang::get('core::install/process.step.3.description.title') }}}</span>
							</li>

							<li data-target="#modal-step4" style="min-width: 25%; max-width: 25%;">
								<span class="step">4</span>
								<span class="title">Other Info</span>
							</li>
						</ul>
					</div>

					<div id="modal-step-contents" class="modal-body step-content">
						<div id="modal-step1" class="step-pane active">
							<div>
								<h4 class="blue center">{{{ \Lang::get('core::install/process.step.1.title') }}}</h4> 
							</div>
							<div>
								<p>{{\Lang::get('core::install/process.step.1.description')}}</p>
							</div>
						</div>

						<div id="modal-step2" class="step-pane">
							<div class="center">
								<h4 class="blue center">{{{ \Lang::get('core::install/process.step.2.title') }}}</h4> 
							</div>

							<div>
								<dl class="dl-horizontal">
									<dt style="width: 250px;">{{{ \Lang::get('core::install/process.os') }}}</dt>
									<dd style="margin-left: 270px;"><i class="fa fa-check green"></i> {{{ php_uname() }}}</dd>
									<dt style="width: 250px;">{{{ \Lang::get('core::install/process.php.version') }}}</dt>
										@if (version_compare(PHP_VERSION, '5.4.0') >= 0)
									<dd style="margin-left: 270px;">
										<i class="fa fa-check green"></i> {{{ phpversion() }}}
										@else
										<?php $errors = TRUE; ?>
									<dd style="margin-left: 270px;" class="text-warning red">
										<i class="fa fa-times red"></i> {{{ \Lang::get('core::install/process.php.version.error') }}}
										@endif
									</dd>
									<dt style="width: 250px;">{{{ \Lang::get('core::install/process.directory.install') }}}</dt>
									<dd style="margin-left: 270px;"><i class="fa fa-check green"></i> {{{ base_path() }}}</dd>
									<dt style="width: 250px;">{{{ \Lang::get('core::install/process.directory.writeable') }}}</dt>
									<dd style="margin-left: 270px;">
										@if (with(new Illuminate\Support\Collection(\File::directories(app_path())))->filter(function($dir){ return !\File::isWritable($dir); })->isEmpty())
										<div>
										<i class="fa fa-check green"></i> {{{ app_path() }}}
										@else
										<div class="text-warning red">
										<i class="fa fa-times red"></i> {{{ \Lang::get('core::install/process.directory.writeable.error', array('dir' => app_path())) }}}
										@endif
										</div>
										@if (with(new Illuminate\Support\Collection(\File::directories(storage_path())))->filter(function($dir){ return !\File::isWritable($dir); })->isEmpty())
										<div>
										<i class="fa fa-check green"></i> {{{ storage_path() }}}
										@else
										<div class="text-warning red">
										<i class="fa fa-times red"></i> {{{ \Lang::get('core::install/process.directory.writeable.error', array('dir' => storage_path())) }}}
										@endif
										</div>
										@if (with(new Illuminate\Support\Collection(\File::directories(public_path())))->filter(function($dir){ return !\File::isWritable($dir); })->isEmpty())
										<div>
										<i class="fa fa-check green"></i> {{{ public_path() }}}
										@else
										<div class="text-warning red">
										<i class="fa fa-times red"></i> {{{ \Lang::get('core::install/process.directory.writeable.error', array('dir' => public_path())) }}}
										@endif
										</div>
									</dd>
								</dl>
							</div>

						</div>

						<div id="modal-step3" class="step-pane">
							<div class="center">
								<h4 class="blue center">{{{ \Lang::get('core::install/process.step.3.title') }}} <i class="fa fa-spinner fa fa-spin orange bigger-125"></i></h4> 
							</div>

							<div>
								<p>{{\Lang::get('core::install/process.step.3.description')}}</p>
							</div> 

							<div class="center text-warning red error-list error-common hide">
								<h4 class="center"><i class="fa fa-exclamation-triangle"></i> {{{ \Lang::get('core::install/process.error.common') }}}</h4> 
							</div>

							<div class="form-group">
								{{ Form::label('domain', Lang::get('core::install/process.param.domain'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="domain" value="" placeholder="telenok.com"/>
									</span>
									<span class="help-block">{{{ Lang::get('core::install/process.param.domain.example') }}}</span>
									<div class="help-block red error-list domain hide">{{{ Lang::get('core::install/process.param.domain.error') }}}</div>
								</div>
							</div>

							<div class="form-group">
								{{ Form::label('locale', Lang::get('core::install/process.param.locale'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<select name="locale">
											<option value="en">English</option>
											<option value="ru">Русский</option>
											<option value="fr">French</option>
											<option value="de">German</option>
											<option value="it">Italian</option>
											<option value="pt">Portuguese</option>
											<option value="sr">Serbian</option>
										</select>
									</span> 
								</div>
							</div>

							<hr/>

							<div class="form-group">
								{{ Form::label('superuser_login', Lang::get('core::install/process.param.superuser_login'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="superuser_login" value="" placeholder="admin"/>
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.superuser_login.example') }}}</span>
									<div class="help-block red error-list superuser_login hide">{{{ \Lang::get('core::install/process.param.superuser_login.error') }}}</div>
								</div>
							</div>


							<div class="form-group">
								{{ Form::label('superuser_password', Lang::get('core::install/process.param.superuser_password'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="password" name="superuser_password" value="" />
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.superuser_password.example') }}}</span>
									<div class="help-block red error-list superuser_password hide">{{{ \Lang::get('core::install/process.param.superuser_password.error') }}}</div>
								</div>
							</div>

							<hr/>

							<div class="form-group">
								{{ Form::label('db_type', Lang::get('core::install/process.param.db_type'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<select name="db_driver" onchange="if (jQuery('option:selected', this).val()=='sqlite') jQuery('div.db').hide(); else jQuery('div.db').show();">
											<option value="mysql">MYSQL</option>
											<option value="sqlite">SQLite</option>
											<option value="pgsql">PostgreSQL</option>
											<option value="sqlsrv">MSSQL</option>
										</select>
									</span> 
								</div>
							</div>

							<div class="form-group db">
								{{ Form::label('db_host', Lang::get('core::install/process.param.db_host'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="db_host" value="" placeholder="192.168.0.100" />
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.db_host.example') }}}</span>
									<div class="help-block red error-list db_host hide">{{{ \Lang::get('core::install/process.param.db_host.error') }}}</div>
								</div>
							</div>

							<div class="form-group db">
								{{ Form::label('db_database', Lang::get('core::install/process.param.db_database'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="db_database" value="" placeholder="laravel" />
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.db_database.example') }}}</span>
									<div class="help-block red error-list db_database hide">{{{ \Lang::get('core::install/process.param.db_database.error') }}}</div>
								</div>
							</div>

							<div class="form-group db">
								{{ Form::label('db_username', Lang::get('core::install/process.param.db_username'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="db_username" value="" placeholder="root"/>
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.db_username.example') }}}</span>
									<div class="help-block red error-list db_username hide">{{{ \Lang::get('core::install/process.param.db_username.error') }}}</div>
								</div>
							</div>

							<div class="form-group db">
								{{ Form::label('db_password', Lang::get('core::install/process.param.db_password'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="password" name="db_password" />
									</span>
								</div>
							</div>

							<div class="form-group db">
								{{ Form::label('db_prefix', Lang::get('core::install/process.param.db_prefix'), array('class'=>'control-label')) }}
								<div class="controls">
									<span>
										<input type="text" name="db_prefix" value="" />
									</span>
									<span class="help-block">{{{ \Lang::get('core::install/process.param.db_prefix.example') }}}</span>
									<div class="help-block red error-list db_prefix hide">{{{ \Lang::get('core::install/process.param.db_prefix.error') }}}</div>
								</div>
							</div> 

						</div>

						<div id="modal-step4" class="step-pane">
							<div class="center">
								<h4 class="blue center">{{{ \Lang::get('core::install/process.step.4.title') }}}</h4> 
							</div>
						</div>
					</div>

					<div class="modal-footer wizard-actions">
						<button class="btn btn-sm btn-prev" disabled="disabled">
							<i class="fa fa-arrow-left"></i>
							{{{ \Lang::get('core::install/process.btn.prev') }}}
						</button>

						<button data-last="{{{ \Lang::get('core::install/process.btn.finish') }}} " class="btn btn-success btn-sm btn-next">
							{{{ \Lang::get('core::install/process.btn.next') }}}
							<i class="fa fa-arrow-right fa fa-on-right"></i>
						</button>

					</div>
								
				</div>
			</div>
        </div>

    {{ Form::close() }}

</body>
@stop
