var autoRedirector = function(config) {
	config = config || {};
	autoRedirector.superclass.constructor.call(this,config);
};
Ext.extend(autoRedirector,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('autoredirector',autoRedirector);

autoRedirector = new autoRedirector();