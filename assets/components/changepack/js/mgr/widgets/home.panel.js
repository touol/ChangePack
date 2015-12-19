ChangePack.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'changepack-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('changepack') + '</h2>',
			cls: '',
			style: {margin: '15px 0'}
		}, {
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: true,
			hideMode: 'offsets',
			items: [{
				title: _('changepack_commit'),
				layout: 'anchor',
				items: [{
					html: _('changepack_commit_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'changepack-grid-commits',
					cls: 'main-wrapper',
				},{
					html: _('changepack_log_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'changepack-grid-logs',
					cls: 'main-wrapper',
				}]
			},{
				title: _('changepack_backup'),
				layout: 'anchor',
				items: [{
					html: _('changepack_intro_msg'),
					cls: 'panel-desc',
				},{	
					xtype: 'changepack-form-create-import',
					cls: 'main-wrapper',
				}, {
					xtype: 'changepack-grid-backups',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	ChangePack.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(ChangePack.panel.Home, MODx.Panel);
Ext.reg('changepack-panel-home', ChangePack.panel.Home);
