<script type="text/javascript">
    if (!jQuery('#modal-template-marker-{{$uniqueId}}').size())
    {
        (function()
        {
            jQuery('body').append('<div id="modal-template-marker-{{$uniqueId}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');

            var $modal = jQuery('#modal-template-marker-{{$uniqueId}}');

            $modal.html(jQuery('#select-template-marker-{{$uniqueId}}').html());

            jQuery('option.chooseMeOnClick', $modal).dblclick(function()
            {
                jQuery('#{{$fieldId}}').val( jQuery('#{{$fieldId}}').val() + this.value );
            });
            
            $modal.on('hidden', function() 
            { 
                $modal.remove();
                jQuery('#select-template-marker-{{$uniqueId}}').remove();
            });
            
        })();
    }

    jQuery('#{{$buttonId}}').click(function()
    {
        jQuery('#modal-template-marker-{{$uniqueId}}').modal('show');
    });

</script>

<template id='select-template-marker-{{$uniqueId}}'>
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header table-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4>{{$controller->LL('modal-template-marker.title')}}</h4>
            </div>

            <div class="modal-body">
                <div class="widget-main">
                    <div id="accordion" class="accordion-style1 panel-group">

                        @foreach(app('telenok.config')->getWorkflowTemplateMarker()->all() as $c)

                            {!! $c->getBlockContent() !!}

                        @endforeach

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="center no-margin">
                    <button type="button" class="btn" data-dismiss="modal">
                        {{ $controller->LL('btn.close') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
    
</template>