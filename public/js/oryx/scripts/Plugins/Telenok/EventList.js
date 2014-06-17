


Ext.ns('Telenok');

Telenok.GridDropZone = function(grid, config) {
    this.grid = grid;
    Telenok.GridDropZone.superclass.constructor.call(this, grid.view.scroller.dom, config);
};

Ext.extend(Telenok.GridDropZone, Ext.dd.DropZone, {
    onContainerOver: function(dd, e, data)
    {
        return dd.grid !== this.grid ? this.dropAllowed : this.dropNotAllowed;
    },
    onContainerDrop: function(dd, e, data) 
    {
        if (dd.grid !== this.grid) 
        {
            this.grid.store.add(data.selections);
            
            Ext.each(data.selections, function(r) {
                dd.grid.store.remove(r);
            });
            
            this.grid.onRecordsDrop(dd.grid, data.selections);
            
            return true;
        }
        else 
        {
            return false;
        }
    }, 
    containerScroll: true

});

Ext.ns('Telenok.Object');

Telenok.Object.Grid = Ext.extend(Ext.grid.GridPanel, {
    border: false,
    autoScroll: true,
    viewConfig: { forceFit: true },
    layout: 'fit',
    enableDragDrop: true,
    initComponent: function() 
    {
        var config = {
            columns:
            [{
                    id: 'id',
                    header: "№",
                    width: 40,
                    sortable: false,
                    dataIndex:'id'
                },
                {
                    id: 'title',
                    header: "Title",
                    width: 140,
                    sortable: false,
                    dataIndex: 'title'
                },
                {
                    id: 'object_type',
                    header: "Object type",
                    width: 140,
                    sortable: false,
                    dataIndex: 'object_type'
            }],
            viewConfig: {forceFit: true}
        };

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        Telenok.Object.Grid.superclass.initComponent.apply(this, arguments);
    },
    onRender: function()
    {
        Telenok.Object.Grid.superclass.onRender.apply(this, arguments);

        this.dz = new Telenok.GridDropZone(this, {ddGroup: this.ddGroup || 'GridDD'});
    },
    onRecordsDrop: Ext.emptyFn 
});
 

Telenok.Object.List = Ext.extend(Telenok.Object.Grid, {
    title: 'List of objects',
    plugins: [new Ext.ux.grid.Search({
        iconCls: 'icon-zoom',
        readonlyIndexes: [],
        minChars: 3,
        autoFocus: true,
        width: 300,
        showSelectAll: false,
        disableIndexes: ['object_type'],
        paramNames: { fields: 'fields', query: 'sSearch' }
    })],
    initComponent: function() 
    {
        var config = {
            store:  new Ext.data.JsonStore({
                totalProperty: 'iTotalRecords',
                root: 'aaData',
                url: 'cmf/module/objects-sequence/list',
                autoLoad: false,
                fields:[
                    {name:'id'},
                    {name:'title'},
                    {name:'object_type'}
                ]
            })
        };

        Ext.apply(this, Ext.apply(this.initialConfig, config)); 

        this.bbar = new Ext.Toolbar();

        Telenok.Object.List.superclass.initComponent.apply(this, arguments);
    }
});

Telenok.Object.Choosing = Ext.extend(Telenok.Object.Grid, {
    title: 'Selected object(s)', 
    initComponent: function()
    {
        var config = {
            store:  new Ext.data.JsonStore({
                totalProperty: 'iTotalRecords',
                root: 'aaData',
                url: 'cmf/module/objects-sequence/list',
                autoLoad: false,
                fields:[
                    {name:'id'},
                    {name:'title'},
                    {name:'object_type'}
                ]
            })
        }; 

        var toolbar = new Ext.Toolbar({
            items: [
                new Ext.Toolbar.Fill(),
                {xtype: 'textfield', value: 'Drag and drop records between tables', readOnly: true, disabled: true, width: 250}
            ]
        });

        this.bbar = toolbar;

        Ext.apply(this, Ext.apply(this.initialConfig, config));
 
        Telenok.Object.Choosing.superclass.initComponent.apply(this, arguments); 
    }
});



Ext.namespace("ORYX.Plugins.Telenok");

ORYX.Plugins.Telenok.EventList = 
{
    construct: function(facade) 
    {
        arguments.callee.$.construct.apply(this, arguments);
        this.facade.registerOnEvent("property.telenok.eventlist", this.generatePositionReferenceEditor.bind(this)); 
    },
    onLoaded: function() {},
    generatePositionReferenceEditor: function(event) 
    {
        var QLField = new Ext.form.EventListLinkField({}); 

        event.result = {
            editor: QLField
        };
    }
};

ORYX.Plugins.Telenok.EventList = ORYX.Plugins.AbstractPlugin.extend(ORYX.Plugins.Telenok.EventList);


Ext.namespace("Ext.form");

Ext.form.EventListLinkField = function(config){
    config.validateOnBlur = false;
    config.editable = false;
    Ext.form.EventListLinkField.superclass.constructor.call(this, config);
};

Ext.form.EventListLinkField = Ext.extend(Ext.form.EventListLinkField, Ext.form.TriggerField,
{
    triggerClass: 'x-form-search-trigger',
    initComponent : function(){
        this.eventListData = null;
        this.window = undefined;
        Ext.form.EventListLinkField.superclass.initComponent.call(this);
    },
    validateBlur: function(e) 
    { 
        if (this.window !== undefined)
            return false;
        return true;
    },
    afterRender: function() 
    {
        Ext.form.EventListLinkField.superclass.afterRender.call(this);
        if (!this.editable) {
            this.el.dom.readOnly = true;
            this.el.addClass('x-trigger-noedit');
        }
    },
    onTriggerClick : function()
    {
        if (this.disabled){
            return;
        }
        
        if (this.window !== undefined)
            return false;
        
        var QLField = this;
        
        var dialog = new ORYX.Plugins.Telenok.QualificationWindow({
            choosed: this.eventListData,
            listeners: {
                beforeclose: function() {
                    delete QLField.window;
                    QLField.triggerBlur();
                },
                selected: function(window, selected) {
                    QLField.setValue(selected);
                }
            }
        });
        
        this.window = dialog;
        
        dialog.show();
    },
    /**
     * Returns the currently selected field value or empty string if no value is set.
     * @return {String} value The selected value
     */
    getValue : function() 
    { 
        return this.eventListData; 
    },
    /**
     * Sets the specified value into the field.  If the value finds a match, the corresponding record text
     * will be displayed in the field.  If the value does not match the data value of an existing item,
     * and the valueNotFoundText config option is defined, it will be displayed as the default field text.
     * Otherwise the field will be blank (although the value will still be set).
     * @param {String|Object} v The value to match
     */
    setValue : function(v) {
        this.eventListData = v;
        Ext.form.EventListLinkField.superclass.setValue.call(this, ( ( v instanceof Array ) ? v.join ( ',' ) : "" ));
    }
});

ORYX.Plugins.Telenok.QualificationWindow = function(config) {
    ORYX.Plugins.Telenok.QualificationWindow.superclass.constructor.call(this, config);
};

Ext.extend(ORYX.Plugins.Telenok.QualificationWindow, Ext.Window, {
    title: "Qualifications",
    modal: true,
    layout: 'border',
    width: 990,
    height: 540,
    choosed: [0],
    initComponent: function() 
    {
        var config = {
            buttons: [{
                text: "OK",
                scope: this,
                handler: function(button, e) { this.select(); }
            },
            {
                text: "Cancel",
                scope: this,
                handler: function(button, e) { this.cancel(); }
            }],
            tbar: [],
            items: [
                new Telenok.Object.List({
                    id: 'list',
                    region: 'west',
                    width: 495,
                    split: true
		}),
                new Telenok.Object.Choosing({
                    id: 'choosing',
                    region:'center'
		})
            ]
        };

        Ext.apply(this, Ext.apply(this.initialConfig, config));
        ORYX.Plugins.Telenok.QualificationWindow.superclass.initComponent.apply(this, arguments);
    
        this.addEvents('selected', 'cancelled');
        
        this.findById("list").addListener("render", function(cmp) {
            cmp.getStore().load();
        }, this);
        
        this.findById("choosing").addListener("render", function(cmp) {
            cmp.getStore().load({ "params": { "filter[id]": [this.choosed || 0] } });
        }, this);
    },
    select: function() 
    {
        var grid = this.findById("choosing"); 

        if (grid) {
            this.fireEvent('selected', this, grid.getStore().collect('id', false));
        }
        this.close();
    },
    cancel: function() 
    {
        this.fireEvent('cancelled', this);
        this.close();
    }

});
