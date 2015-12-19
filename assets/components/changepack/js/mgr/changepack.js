var ChangePack = function (config) {
	config = config || {};
	ChangePack.superclass.constructor.call(this, config);
};
Ext.extend(ChangePack, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('changepack', ChangePack);

ChangePack = new ChangePack();