<?php

    $jsUnique = str_random();

?>

<div class="widget-box transparent">
	<div class="widget-header widget-header-small">
		<h4>
			<i class="fa fa-list-ul"></i>
			Parameters
		</h4> 
	</div>
	<div class="widget-body"> 
		<div class="widget-main">
            @if ($model->exists)

            <div class="row">
                <div class="col-xs-12 col-sm-8">

                    <div class="panel panel-default">
                        <div class="panel-heading"><button class="btn btn-success btn-sm" onclick="showModalParameter{{$jsUnique}}(); return false;">Add parameter</button></div>

                        <!-- Table -->
                        <table class="table">
                            <tr>
                                <td>sdasd</td>
                                <td>sdasd</td>
                                <td>sdasd</td>
                                <td>sdasd</td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
            </div>
            
            @else
            <p class="alert alert-info">Business process doesnt exists. Please, save it before.</p> 
            @endif

		</div>
	</div>
</div>

<script type="text/javascript">
    function showModalParameter{{$jsUnique}}(id)
    {
        jQuery.ajax({
            url: '{!! URL::route("cmf.workflow.parameter.show", ["id" => "__id__"]) !!}'.replace('__id__', id),
            method: 'get',
            dataType: 'json'
        }).done(function(data) 
        {
            if (!jQuery('#modal-{{$jsUnique}}').size())
            {
                jQuery('body').append('<div id="modal-{{$jsUnique}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
            }

            var $modal = jQuery('#modal-{{$jsUnique}}');
/*
            $modal.data('model-data', function(data)
            {
            });
*/
            $modal.html(data.tabContent);

            $modal.modal('show').on('hidden', function() 
            { 
                jQuery(this).html(""); 
            });
        }); 
    }
</script>