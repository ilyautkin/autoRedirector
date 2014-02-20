autoRedirector.grid.Items = function(config) {
	config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
	Ext.applyIf(config,{
		id: 'autoredirector-grid-items'
		,url: autoRedirector.config.connector_url
		,baseParams: {
			action: 'mgr/item/getlist'
		}
		,fields: ['id','uri','res_id','context_key']
		,autoHeight: true
		,paging: true
		,remoteSort: true
        ,sm: this.sm
		,columns: [
			{header: _('autoredirector_uri'),dataIndex: 'uri',sortable: true,width: 300}
			,{header: _('autoredirector_res_id'),dataIndex: 'res_id',sortable: true,width: 70}
			,{header: _('context'),dataIndex: 'context_key',sortable: true,width: 100}
		]
		,tbar: [{
			text: _('autoredirector_item_create')
			,handler: this.createItem
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}
	});
	autoRedirector.grid.Items.superclass.constructor.call(this,config);
};
Ext.extend(autoRedirector.grid.Items,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
        var cs = this.getSelectedAsList();
        var m = [];
        if (cs.split(',').length > 1) {
        	m.push({
    			text: _('autoredirector_items_remove')
    			,handler: this.removeSelected
    		});
        } else {
    		m.push({
    			text: _('autoredirector_item_update')
    			,handler: this.updateItem
    		});
    		m.push('-');
    		m.push({
    			text: _('autoredirector_item_remove')
    			,handler: this.removeItem
    		});
        }
		this.addContextMenuItem(m);
	}
	
	,createItem: function(btn,e) {
		if (!this.windows.createItem) {
			this.windows.createItem = MODx.load({
				xtype: 'autoredirector-window-item-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.createItem.fp.getForm().reset();
		this.windows.createItem.show(e.target);
	}

	,updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: autoRedirector.config.connector_url
			,params: {
				action: 'mgr/item/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'autoredirector-window-item-update'
							,record: r
							,listeners: {
								'success': {fn:function() { this.refresh(); },scope:this}
							}
						});
					}
					this.windows.updateItem.fp.getForm().reset();
					this.windows.updateItem.fp.getForm().setValues(r.object);
					this.windows.updateItem.show(e.target);
				},scope:this}
			}
		});
	}

	,removeItem: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('autoredirector_item_remove')
			,text: _('autoredirector_item_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/item/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
	
    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].data.id;
        }
        cs = cs.substr(1);
        return cs;
    }

    ,removeSelected: function(act,btn,e) {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

    	MODx.msg.confirm({
			title: _('autoredirector_items_remove')
			,text: _('autoredirector_items_remove_confirm')
			,url: this.config.url
			,params: {
                action: 'mgr/items/remove'
                ,items: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                       var t = Ext.getCmp('modx-resource-tree');
                       if (t) { t.refresh(); }
                },scope:this}
            }
        });
        return true;
    }
});
Ext.reg('autoredirector-grid-items',autoRedirector.grid.Items);




autoRedirector.window.CreateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('autoredirector_item_create')
		,id: this.ident
		,height: 200
		,width: 475
		,url: autoRedirector.config.connector_url
		,action: 'mgr/item/create'
		,fields: [
            {xtype: 'textfield',fieldLabel: _('autoredirector_uri'),name: 'uri',id: 'autoredirector-'+this.ident+'-uri',anchor: '99%'}
    		,{xtype: 'textfield',fieldLabel: _('autoredirector_res_id'),name: 'res_id',id: 'autoredirector-'+this.ident+'-res_id',anchor: '30%'}
			,{xtype: 'textfield',fieldLabel: _('context'),name: 'context_key',id: 'autoredirector-'+this.ident+'-context_key',anchor: '60%'        ,listeners: {
                afterrender: function() {
                   this.setValue('web');    
                }
            }}
		]

		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	autoRedirector.window.CreateItem.superclass.constructor.call(this,config);
};
Ext.extend(autoRedirector.window.CreateItem,MODx.Window);
Ext.reg('autoredirector-window-item-create',autoRedirector.window.CreateItem);


autoRedirector.window.UpdateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'meuitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('autoredirector_item_update')
		,id: this.ident
		,height: 200
		,width: 475
		,url: autoRedirector.config.connector_url
		,action: 'mgr/item/update'
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'autoredirector-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('autoredirector_uri'),name: 'uri',id: 'autoredirector-'+this.ident+'-uri',anchor: '99%'}
        	,{xtype: 'textfield',fieldLabel: _('autoredirector_res_id'),name: 'res_id',id: 'autoredirector-'+this.ident+'-res_id',anchor: '30%'}
			,{xtype: 'textfield',fieldLabel: _('context'),name: 'context_key',id: 'autoredirector-'+this.ident+'-context_key',anchor: '60%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	autoRedirector.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(autoRedirector.window.UpdateItem,MODx.Window);
Ext.reg('autoredirector-window-item-update',autoRedirector.window.UpdateItem);