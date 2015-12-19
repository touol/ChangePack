ChangePack.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'changepack-panel-home', renderTo: 'changepack-panel-home-div'
		}]
	});
	ChangePack.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(ChangePack.page.Home, MODx.Component);
Ext.reg('changepack-page-home', ChangePack.page.Home);