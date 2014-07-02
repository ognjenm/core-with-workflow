<div class="widget-box">
    <div class="widget-header">
        <h4>
            <i class="fa fa-list-ol"></i>
            {{{ $controller->LL('field.rule') }}}
        </h4>
    </div> 

    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group">
                {{ Form::label("required", $controller->LL('property.required'), array('class'=>'control-label')) }}
                <div class="controls">
					{{ Form::hidden("required", 0) }}
                    {{ Form::checkbox("required", 1, $model->required, array('class'=>'ace ace-switch ace-switch-3')) }}
                    <span class="lbl"></span>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('upload_allow_ext', $controller->LL('property.upload_allow_ext'), array('class'=>'control-label')) }}
                <div class="controls">
                    <select multiple="multiple" class="form-control" data-placeholder="{{{$controller->LL('property.upload_allow_ext')}}}" id="upload_allow_ext{{$uniqueId}}" name="upload_allow_ext[]">
						
						<?php
							$allowedExt = $model->upload_allow_ext->all();
						?>
						
						@foreach(\Telenok\File\FileExtension::all()->sort(function($a, $b) { return strcmp($a->extension, $b->extension); }) as $extension)
 
						<option value="{{{$extension->extension}}}" @if (in_array($extension->extension, $allowedExt)) selected="selected" @endif >[{{{$extension->extension}}}] {{{$extension->translate('title')}}}</option>

						@endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('upload_allow_mime', $controller->LL('property.upload_allow_mime'), array('class'=>'control-label')) }}
                <div class="controls">
                    <select class="" multiple="multiple" data-placeholder="{{{$controller->LL('property.upload_allow_mime')}}}" id="upload_allow_mime{{$uniqueId}}" name="upload_allow_mime[]">
						
						<?php
							$allowedMime = $model->upload_allow_mime->all();
						?>
						
						@foreach(\Telenok\File\FileMimeType::all()->sort(function($a, $b) { return strcmp($a->mime_type, $b->mime_type); }) as $mimeType)
 
						<option value="{{{$mimeType->mime_type}}}" @if (in_array($mimeType->mime_type, $allowedMime)) selected="selected" @endif >[{{{$mimeType->mime_type}}}] {{{$mimeType->translate('title')}}}</option>

						@endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery("#upload_allow_ext{{$uniqueId}}").chosen({ 
        keepTypingMsg: "{{{$controller->LL('notice.typing')}}}",
        lookingForMsg: "{{{$controller->LL('notice.looking-for')}}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
    
    jQuery("#upload_allow_mime{{$uniqueId}}").chosen({ 
        keepTypingMsg: "{{{$controller->LL('notice.typing')}}}",
        lookingForMsg: "{{{$controller->LL('notice.looking-for')}}}", 
        minTermLength: 1,
        width: "400px",
        create_option: true,
        persistent_create_option: true,
        skip_no_results: true,
        search_contains: true
    });
</script>
