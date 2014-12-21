@if ($field->code == "key")

    {!! Form::hidden('key', $model->{$field->code}) !!}

    <div class="form-group">
        {!! Form::label('key', $field->translate('title'), array('class' => 'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-9">

            <?php 

            $key = ['onchange' => "onChangeType{$uniqueId}()"];

            $selectFields = [];

            app('telenok.config')->getWorkflowParameter()
                    ->each(function($field) use (&$selectFields) 
            {  
                $selectFields[$field->getKey()] = $field->getName(); 
            });

            ?>
        {!! Form::select('key', $selectFields, $model->{$field->code}, $key) !!}
        </div>
    </div>

    <script type="text/javascript">
        function onChangeType{{$uniqueId}}()
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');

            var $key = jQuery('select[name="key"]', $form);
            
            jQuery("#div-parameter-{{$uniqueId}}").remove();
        }  
    </script>

    @if ($model->exists && ($parameter = app('telenok.config')->getWorkflowParameter()->get($model->key))) 
    <div id="div-parameter-{{$uniqueId}}">
    {!! $parameter->getFormFieldContent($controller, $model, $uniqueId) !!}
    </div>
    @endif

@else

    {!! app('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) !!}

@endif 