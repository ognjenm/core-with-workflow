    <?php 
    
        $domAttr = [];
        $disabled = false;
        $value = 0;
        $valueComment = '';

        if ($model->exists) 
        {
            $value = $model->workflow_status;
            $valueComment = $model->workflow_status_comment;
        }

        if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }
    ?> 

<div class="form-group">
    {{ Form::label("workflow_status", $controller->LL('title'), array('class'=>'control-label')) }}
    <div class="controls">
        @if ($disabled)
        {{ Form::hidden("workflow_status", $value) }}
        @endif
        {{ Form::select("workflow_status", \Telenok\Core\Model\Workflow\Status::lists('title', 'id'), $value, $domAttr) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label("workflow_status_comment", $controller->LL('comment'), array('class'=>'control-label')) }}
    <div class="controls">
        @if ($disabled)
        {{ Form::hidden("workflow_status_comment", $valueComment) }}
        @endif
        {{ Form::textarea("workflow_status_comment", $valueComment, $domAttr) }}
    </div>
</div>