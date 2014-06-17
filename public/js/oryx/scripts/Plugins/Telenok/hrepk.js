/* Copyright (c) 2011 Friedrich Röhrs
 * see the LICENSE file or http://www.opensource.org/licenses/mit-license.php
 */

/**
 * @namespace Plugin for HREPK support
 * @name ORYX.Plugins.HREPK
 */
Ext.namespace("ORYX.Plugins.HREPK");
ORYX.Plugins.HREPK.DataPlugin = /** @lends ORYX.Plugins.HREPK.DataPlugin */ {
    /**
     * @class
     * The DataPlugin initializes all datastores needed for a correct usage of HREPK data.
     * @extends ORYX.Plugins.AbstractPlugin
     * @constructs
     */
    construct: function(facade) {
        arguments.callee.$.construct.apply(this, arguments);
        this.createStore(
            "HRMOrganisation",
            ORYX.CONFIG.ROOT_PATH + 'HRM/position',
            [
                {name: "id", type:"int"},
                {name: 'parent', type:"int"},
                {name: 'name', type:"string"},
                {name: 'description', type:"string"}
            ]
        );
        this.createStore(
            "HRMPerson",
            ORYX.CONFIG.ROOT_PATH + 'HRM/employee',
            [
                {name: "id", type:"string"},
                {name: "gender", type:"string"},
                {name: "givenname", type:"string"},
                {name: "surname", type:"string"},
                {name: "streetaddress", type:"string"},
                {name: "city", type:"string"},
                {name: "state", type:"string"},
                {name: "zipcode", type:"string"},
                {name: "country", type:"string"},
                {name: "telephonenumber", type:"string"},
                {name: "birthday", type:"date", dateFormat:"n/j/Y"},
                {name: "centimeters", type:"string"},
                {name: "qualifications", type:"string", write:false },
                {name: "position", type:"number", write:false }
            ]
        );
        this.createStore(
            "HRMQualification",
            ORYX.CONFIG.ROOT_PATH + 'HRM/qualification',
            [
                {name: "id", type:"string"},
                {name:"name", type: "string"},
                {name:"description", type:"string"},
                {name:"parent", type:"string"},
                {name:"leaf", type:"boolean"}
            ]
        );
    },
    createStore: function(name,url,fields) {
        var proxy = new Ext.data.HttpProxy({
            api: {
                read    : url + '/read',
                create  : url + '/create',
                update  : url + '/update',
                destroy : url + '/destroy'
            }
        });
        var reader = new Ext.data.JsonReader({idProperty: 'id', root: 'data'}, fields);
        var writer = new Ext.ux.AdvJsonWriter({encode: true, writeAllFields: true});
        var facade = this.facade;
        var store = new Ext.data.Store({
            storeId: name,
            proxy: proxy,
            reader: reader,
            writer: writer,
            autoLoad: true,
            autoSave: true,
            listeners: {
                load: {
                    fn: function(/* Store */store, /*Ext.data.Record[]*/records, /*Object*/options) {
                        facade.getCanvas().update(true);
                    },
                    single: true
                }
            }
        });
        return store;
    }
}
ORYX.Plugins.HREPK.DataPlugin =
    ORYX.Plugins.AbstractPlugin.extend(ORYX.Plugins.HREPK.DataPlugin);
ORYX.Plugins.HREPK.LayoutPlugin = /** @lends ORYX.Plugins.HREPK.LayoutPlugin# */{
    /**
     * @class
     * The LayoutPlugin takes care of the layout for HREPK elements.
     * @extends ORYX.Plugins.AbstractPlugin
     * @constructs
     */
    construct: function(facade) {
        arguments.callee.$.construct.apply(this, arguments);
        this.facade.registerOnEvent("layout.hrepk.qualificationprofile", this.handleQualificationProfileLayout.bind(this));
        this.facade.registerOnEvent("property.hrepk.qualification", this.generateQualificationsEditor.bind(this));
        this.facade.registerOnEvent("property.hrepk.positionreference", this.generatePositionReferenceEditor.bind(this));
        this.facade.registerOnEvent("property.hrepk.employeereference", this.generateEmployeeReferenceEditor.bind(this));
    },
    onLoaded: function() {},
    /**
     * calculates the height of a text, taking line breaks into consideration
     *
     * @param {ORYX.Core.SVG.Label} labelElement the label
     * @param {String} [labelValue] the label value
     * @return {Number} the height of the text.
     */
    calculateLabelHeight : function (labelElement, labelValue) {
        var fontSize = labelElement.getFontSize();
        if (arguments.length == 1)
            labelValue = labelElement.text();
        var newlineOccurences = 1;
        labelValue.scan('\n', function() {
            newlineOccurences += 1;
        });
        // 0.75 account for padding around the label
        return newlineOccurences * fontSize + 0.75;
    },
    /**
     * generates the editor and renderer for the qualifications property of the
     * qualification profile element.
     * The result member of the event object is to be filled with an object of the format
     * {
     *  editor: Ext.Field,
     *  renderer: function( value, record) {}
     * }
     * @param event An object of the format {propertyItem: , propertyWindow:, result: }
     */
    generateQualificationsEditor: function(event) {
        var QLField = new Ext.form.QualificationLinkField({});
        var store = Ext.StoreMgr.get("HRMQualification");
        var propertyWindow = event.propertyWindow;
        var propertyItem = event.propertyItem;
        if (!(propertyItem instanceof ORYX.Core.StencilSet.ComplexPropertyItem))
            QLField.on("change",function(Field, newValue, oldValue){
                var position = store.find("id", newValue);
                    if (position < 0)
                        return;
                var record = store.getAt(position);
                propertyWindow.editDirectly(propertyItem.prefix() + "-title", record.get("name"), true);
            });
        event.result = {
            renderer: function(value){
                var position = store.find("id", value);
                if (position < 0)
                    return "unkown";
                var record = store.getAt(position);
                return record.get("name");
            },
            editor: QLField
        }
    },
    generateEmployeeReferenceEditor: function(event) {
        var propertyItem = event.propertyItem;
        var propertyWindow = event.propertyWindow;
        var storage =  Ext.StoreMgr.get("HRMPerson");
        var combobox = new Ext.form.ComboBox({
            store: storage,
            displayField: 'surname',
            valueField: 'id',
            typeAhead: true,
            forceSelection: true,
            mode: 'local',
            triggerAction: 'all',
            selectOnFocus:true,
            plugins: [Ext.ux.ComboListAutoSizer],
            listeners: {
                beforequery: function (){
                    console.log("a query is done");
                    console.dir(arguments);
                }
            }
        });
        combobox.on('select', function(combo, record, index) {
            propertyWindow.editDirectly(propertyItem.prefix() + "-" + propertyItem.id(), combo.getValue());
            propertyWindow.editDirectly(propertyItem.prefix() + "-title", record.get("surname"));
        }.bind(propertyWindow))
        // Assign result to editor and renderer.
        event.result = {
            renderer: function(value){
                var record = combobox.findRecord(combobox.valueField, value);
                return record ? (record.get("givenname") + " " + record.get("surname")) : combobox.valueNotFoundText;
            },
            editor: combobox
        }
    },
    generatePositionReferenceEditor: function(event) {
        var propertyItem = event.propertyItem;
        var propertyWindow = event.propertyWindow;
        var storage =  Ext.StoreMgr.get("HRMOrganisation");
        // Set the grid Editor
        var combobox = new Ext.form.ComboBox({
            store: storage,
            displayField: 'name',
            valueField: 'id',
            typeAhead: true,
            forceSelection: true,
            mode: 'local',
            triggerAction: 'all',
            selectOnFocus:true,
            plugins: [Ext.ux.ComboListAutoSizer],
            listeners: {
                beforequery: function (){
                    console.log("a query is done");
                    console.dir(arguments);
                }
            }
        });
        combobox.on('select', function(combo, record, index) {
            propertyWindow.editDirectly(propertyItem.prefix() + "-" + propertyItem.id(), combo.getValue());
            propertyWindow.editDirectly(propertyItem.prefix() + "-title", record.get("title"));
        }.bind(propertyWindow))
        // Assign result to editor and renderer.
        event.result = {
            renderer: function(value){
                var record = combobox.findRecord(combobox.valueField, value);
                return record ? record.get(combobox.displayField) : combobox.valueNotFoundText;
            },
            editor: combobox
        }
    }

};

ORYX.Plugins.HREPK.LayoutPlugin = 
    ORYX.Plugins.AbstractPlugin.extend(ORYX.Plugins.HREPK.LayoutPlugin);

Ext.namespace("Ext.form");
/**
 * Creates a new QualificationLinkField. Which shows a window where the qualification
 * associated with this element can be selected.
 * How does it work: There are two main parts.
 * The Datastore, synchroneusly loaded id=>title mapping of Qualification ID's
 * towards their displayname.
 * Tree structure to map the relationships between the qualifications.
 *
 * @class The QLF is used to display and edit links between elements and qualifications.
 */
Ext.form.QualificationLinkField = function(config){
    config.validateOnBlur = false;
    config.editable = false;
    Ext.form.QualificationLinkField.superclass.constructor.call(this, config);
};
Ext.form.QualificationLinkField = Ext.extend(Ext.form.QualificationLinkField, Ext.form.TriggerField,
/** @lends Ext.form.QualificationLinkField# */
{
    triggerClass: 'x-form-search-trigger',
    initComponent : function(){
        /** data of the qualification */
        this.qualificationData = null;
        /** the selection window when visible */
        this.window = undefined;
        Ext.form.QualificationLinkField.superclass.initComponent.call(this);
    },
    validateBlur: function(e) {
        // only let the field "blur" if the window is not visible anymore.
        if (this.window !== undefined)
            return false;
        return true;
    },
    afterRender: function() {
        Ext.form.QualificationLinkField.superclass.afterRender.call(this);
        if (!this.editable) {
            this.el.dom.readOnly = true;
            this.el.addClass('x-trigger-noedit');
        }
    },
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        var QLField = this;
        var path = "/0";
        if (this.qualificationData)
            path = this.qualificationData.path;
        var dialog = new ORYX.Plugins.HREPK.QualificationWindow({
            selectedPath: path,
            listeners: {
                beforeclose: function() {
                    //when being closed remove reference in QLField and blur it.
                    delete QLField.window;
                    QLField.triggerBlur();
                },
                selected: function(window,selectedNode) {
                    var value = {
                        id: selectedNode.id,
                        text: selectedNode.text,
                        path: selectedNode.getPath()
                    };
                    QLField.setValue(value);
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
    getValue : function(){
        if (this.qualificationData)
            return this.qualificationData.id;
        return Ext.form.QualificationLinkField.superclass.getValue.call(this);
    },
    /**
     * Sets the specified value into the field.  If the value finds a match, the corresponding record text
     * will be displayed in the field.  If the value does not match the data value of an existing item,
     * and the valueNotFoundText config option is defined, it will be displayed as the default field text.
     * Otherwise the field will be blank (although the value will still be set).
     * @param {String|Object} v The value to match
     */
    setValue : function(v){
        if (v.id) {
            this.qualificationData = v
            window.tree = this.tree;
            window.qd = v;
            Ext.form.QualificationLinkField.superclass.setValue.call(this, v.text);
        } else {
            var store = Ext.StoreMgr.get("HRMQualification");
            var record = store.getById(v);
            if (!record) { return }
            var path = "";
            var id = v;
            var text = record.get("name");
            while (record.get("parent") != 0) {
                path = "/" + record.id + path;
                record = store.getById(record.get("parent"));
            }
            path = "/0/" + record.id + path;
            this.setValue({
                id: id,
                text: text,
                path: path
            })
        }
    }
});
ORYX.Plugins.HREPK.QualificationWindow = function(config) {

    ORYX.Plugins.HREPK.QualificationWindow.superclass.constructor.call(this, config);
};
Ext.extend(ORYX.Plugins.HREPK.QualificationWindow,Ext.Window, /** @lends ORYX.Plugins.HREPK.QualificationWindow# */{
    // Defaults (overwritable)
    title: "Qualifications",
    modal: true,
    autoScroll: true,
    layout: "fit",
    width: 800,
    height: 500,
    selectedPath: "/0",
    initialNodeId: "0",
    bodyStyle: "background-color:#FFFFFF",
    initComponent:function() {
        // Settings (not overwriteable)
        var config = {
            buttons: [{
                text: "OK",
                ref: "../button_ok"
            }, {
                text: "Cancel",
                ref: "../button_cancel"
            }],
            tbar: [{
                text: 'Add Qualification',
                ref: "../button_add"
            }, {
                text: 'Remove Qualification',
                ref: "../button_remove"
            }],
            items:[
                new Ext.tree.TreePanel({
                    animate: true,
                    autoScroll: true,
                    nodeType: 'async',
                    enableDD: true,
                    root: new Ext.tree.AsyncTreeNode({
                        text: 'Qualifications',
                        id: this.initialNodeId
                    }),
                    loader: new Ext.ux.StoreTreeLoader({
                        store: Ext.StoreMgr.get("HRMQualification")
                    }),
                    containerScroll: true,
                    border: false,
                    ref: 'panel_qualificationtree',
                    listeners: {
                        movenode : function ( tree, node, oldParent, newParent, index ) {
                            var store = Ext.StoreMgr.get("HRMQualification");
                            var record = store.getById(node.id);
                            record.set("parent",newParent.id);
                            store.save();
                        }
                    }
                })
            ]
        };
        // apply config
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        ORYX.Plugins.HREPK.QualificationWindow.superclass.initComponent.apply(this, arguments);
        // add custom events
        this.addEvents('selected', 'cancelled');
        this.button_ok.setHandler(this.select,this);
        this.button_cancel.setHandler(this.cancel,this);
        this.panel_qualificationtree.addListener("afterrender",function(cmp) {
            if (this.selectedPath) {
                cmp.selectPath(this.selectedPath);
            }
        },this);
    },
    select: function() {
        // TODO finish me
        var selectedNode = this.panel_qualificationtree.getSelectionModel().getSelectedNode();
        if (selectedNode) {
            this.fireEvent('selected', this, selectedNode);
        }
        this.close();
    },
    cancel: function() {
        // TODO finish me
        this.fireEvent('cancelled', this);
        this.close();
    }
});