/**
 * Copyright (c) 2006
 * Martin Czuchra, Nicolas Peters, Daniel Polak, Willi Tscheschner
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 **/


if (!ORYX.Plugins) {
    ORYX.Plugins = new Object();
}

if (!ORYX.FieldEditors) {
    ORYX.FieldEditors = {};
}

if (!ORYX.LabelProviders) {
    ORYX.LabelProviders = {};
}



ORYX.Plugins.PropertyWindow = {
    facade: undefined,
    construct: function(facade) 
    {
        this.facade = facade;
        this.facade.registerOnEvent(ORYX.CONFIG.EVENT_DBLCLICK, this.actOnDBLClick.bind(this));
        this.init();
        this.selectDiagram();
    }, 
    actOnDBLClick: function(evt, shape) 
    {
        if (!(shape instanceof ORYX.Core.Shape) || !shape.getStencil().urlPropertyContent()) 
        {
            return;
        }

        var me = this;

        jQuery.ajax({
            url: shape.getStencil().urlPropertyContent(),
            method: 'get',
            dataType: 'json',
            data: {
                    'sessionProcessId': ORYX.Utils.getParamFromUrl('sessionProcessId'),
                    'processId': ORYX.Utils.getParamFromUrl('processId'),
                    'stencilId': shape.getPermanentId()
            }
        }).done(function(data) 
        {
            var $uniqueId = Math.floor(Math.random() * (1000000 - 1) + 1);

            if (!jQuery('#modal-' + $uniqueId, window.parent.document).size())
            {
                jQuery('body', window.parent.document).append('<div id="modal-' + $uniqueId + '" class="modal fade" role="dialog" aria-labelledby="label"></div>');
            }

            var $modal = parent.jQuery('#modal-' + $uniqueId);

            $modal.data('model-data', function($form)
            { 
                jQuery.ajax({
                    url: shape.getStencil().urlStoreProperty(),
                    method: 'get',
                    dataType: 'json',
                    data: $form.serialize()
                }).done(function(data) 
                { 
                    if (data)
                    {   
                        jQuery.each(data, function(k, v)
                        {
                            me.editDirectly(k, v);
                        });
                    }
                });
            });

            $modal.html(data.tabContent);

            $modal.modal('show').on('hidden.bs.modal', function()
            {
                jQuery(this).data('bs.modal', null);
                jQuery(this).remove();
            });
        });
    },
    init: function() 
    {
        // the properties array
        this.popularProperties = [];
        this.properties = [];

        /* The currently selected shapes whos properties will shown */
        this.shapeSelection = new Hash();
        this.shapeSelection.shapes = new Array();
        this.shapeSelection.commonProperties = new Array();
        this.shapeSelection.commonPropertiesValues = new Hash();

        this.updaterFlag = false;
    },
    // Select the Canvas when the editor is ready
    selectDiagram: function() 
    {
        this.shapeSelection.shapes = [this.facade.getCanvas()];
    },
    // Changes made in the property window will be shown directly
    editDirectly: function(key, value) 
    {
        this.shapeSelection.shapes.each(function(shape) 
        {
            shape.getStencil().properties().each(function(property)
            {
                if (key == property.id() && !shape.getStencil().property(property.prefix() + "-" + key).readonly())
                {
                    shape.setProperty(property.prefix() + "-" + key, value);
                }
            });
        }.bind(this));

        /* Propagate changed properties */
        var selectedElements = this.shapeSelection.shapes;

        this.facade.raiseEvent({
            type: ORYX.CONFIG.EVENT_PROPWINDOW_PROP_CHANGED,
            elements: selectedElements,
            key: key,
            value: value
        });

        this.facade.getCanvas().update();

    },
    onSelectionChanged: function(event) 
    {
        this.shapeSelection.shapes = event.elements;
    }
};

ORYX.Plugins.PropertyWindow = Clazz.extend(ORYX.Plugins.PropertyWindow);
