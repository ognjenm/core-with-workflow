<div class="form-group">
    {{ Form::label($field->code, $controller->LL('title'), ['class' => 'col-sm-3 control-label no-padding-right']) }}
    <div class="col-sm-9">
        {{ Form::select($field->code, Telenok\Core\Model\System\Language::all()->lists('title', 'locale'), $model->{$field->code}) }}
    </div>
</div> 