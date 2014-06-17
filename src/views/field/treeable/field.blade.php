<div class="form-group">
    {{ Form::hidden("treeable", 0) }}
    <?php 
        $domAttr = ['class'=>'ace ace-switch ace-switch-3'];
        $disabled = false;
        
        if ( $model->exists )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }
    ?> 
    {{ Form::label('treeable', $model->translate('title'), array('class'=>'control-label')) }}
    <div class="controls">
        @if ($disabled)
        {{ Form::hidden("treeable", null) }}
        @endif
        {{ Form::checkbox("treeable", 1, null, $domAttr) }}
        <span class="lbl"></span>
    </div>
</div>