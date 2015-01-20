autoRedirector.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'autoredirector-panel-home'
			,renderTo: 'autoredirector-panel-home-div'
		}]
	}); 
	autoRedirector.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(autoRedirector.page.Home,MODx.Component);
Ext.reg('autoredirector-page-home',autoRedirector.page.Home);