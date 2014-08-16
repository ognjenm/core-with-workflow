<?php

    $userName = "";
    
    if ($model->exists)
    {
        $user = $model->createdByUser()->first();
        
        if ($user)
        {
            $userName = $user->username;
        }
    }
?>


<div class="form-group input-group">
    {{ Form::label(str_random(), $controller->LL('title'), array('class'=>'control-label')) }}
    <div class="controls">
        <span class="input-group-addon">
            <i class="fa fa-calendar bigger-110"></i>
        </span>
        <input type="text" disabled="disabled" value="@if ($model->exists){{{$model->created_at->setTimezone(\Config::get('app.timezone'))->format("d/m/Y H:i:s")}}}@endif" class="form-control date-picker" />
        <label class="inline">
            <span class="lbl">&nbsp;{{{ $controller->LL('by') }}}&nbsp;</span>
        </label>
        <input type="text" disabled="disabled" value="{{{$userName}}}" class="form-control date-picker">
    </div>
</div>
