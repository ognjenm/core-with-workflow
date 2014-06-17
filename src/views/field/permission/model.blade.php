<div class="form-group">
    <?php 
        $domAttr = ['class'=>'ace ace-switch ace-switch-3'];
        $disabled = false;
        $value = 1;
        /*
        if (!$model->exists) 
        {
            $value = $field->checkbox_default;
        }
        else
        {
            $value = $model->{$field->code};
        }

        if ( (!$model->exists && !$field->allow_create) || ($model->exists && !$field->allow_update) )
        {
            $domAttr['disabled'] = 'disabled';
            $disabled = true;
        }
        */
    ?>  
    
    <div class="widget-box transparent">
        <div class="widget-header widget-header-small">
            <h4>
                <i class="fa fa-list-ul"></i>
                {{{ $field->translate('title_list') }}}
            </h4> 
        </div>
        <div class="widget-body"> 
            <div class="widget-main">

                <ul class="nav nav-tabs" id="field-{{{ $model->{$field->code} . $uniqueId }}}-permission">
                    @foreach($permissions as $permission) 
                    <li><a href="#{{{$permission->code . $uniqueId}}}" data-toggle="tab">{{{$permission->translate('title')}}}</a></li>
                    @endforeach
                </ul>

                <div class="tab-content" style="overflow: visible;">
                    @foreach($permissions as $permission) 
                    <div class="tab-pane active" id="{{{$permission->code . $uniqueId}}}">
                        <div class="controls" style="margin-left: 0;">
                            <select class="chosen" multiple data-placeholder="{{{$controller->LL('notice.choose')}}}" id="permission-{{{$permission->code . $uniqueId}}}" name="permission[{{{$permission->code}}}][]">
                                <?php
                                
                                    $sequence = new \Telenok\Core\Model\Object\Sequence();
                                    $spr = new Telenok\Core\Model\Security\SubjectPermissionResource();
                                    $type = new Telenok\Core\Model\Object\Type();

                                    \Telenok\Core\Model\Object\Sequence::addMultilanguage('title_type');
                                    
                                    $subjects = \Telenok\Core\Model\Object\Sequence::select($sequence->getTable() . '.id', $sequence->getTable() . '.title', $type->getTable() . '.title as title_type')
                                    ->join($spr->getTable(), function($query) use ($spr, $sequence, $model) 
                                    {
                                        $query->on($sequence->getTable() . '.id', '=', $spr->getTable() . '.acl_subject_object_sequence');
                                    })
                                    ->join($type->getTable(), function($query) use ($sequence, $type) 
                                    {
                                        $query->on($sequence->getTable() . '.sequences_object_type', '=', $type->getTable() . '.id');
                                    })
                                    ->whereNotNull($spr->getTable() . '.active')
                                    ->where($spr->getTable() . '.acl_resource_object_sequence', $model->getKey())
                                    ->where($spr->getTable() . '.acl_permission_permission', $permission->getKey())
                                    ->get();
                                    
                                    foreach($subjects as $subject)
                                    {
                                        echo "<option value='{$subject->getKey()}' selected='selected'>[{$subject->translate('title_type')}#{$subject->id}] {$subject->translate('title')}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <script type="text/javascript">
                        jQuery('ul#field-{{{ $model->{$field->code} . $uniqueId}}}-permission a:first').tab('show'); 
                        
                        jQuery("#permission-{{{$permission->code . $uniqueId}}}").ajaxChosen({ 
                            keepTypingMsg: "{{{ $controller->LL('notice.typing') }}}",
                            lookingForMsg: "{{{ $controller->LL('notice.looking-for') }}}",
                            type: "GET",
                            url: "{{ URL::route("cmf.field.permission.list.title") }}", 
                            dataType: "json",
                            minTermLength: 1
                        }, 
                        function (data) 
                        {
                            var results = [];

                            jQuery.each(data, function (i, val) {
                                results.push({ value: val.value, text: val.text });
                            });

                            return results;
                        },
                        {
                            width: "100%",
                            no_results_text: "{{{ $controller->LL('notice.not-found') }}}"
                        });

                    </script>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
</div>