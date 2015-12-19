ChangePack.window.CreateCommit = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'changepack-commit-window-create';
	}
	Ext.applyIf(config, {
		title: _('changepack_commit_create'),
		width: 550,
		autoHeight: true,
		url: ChangePack.config.connector_url,
		action: 'mgr/commit/create',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
	ChangePack.window.CreateCommit.superclass.constructor.call(this, config);
};
Ext.extend(ChangePack.window.CreateCommit, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'textfield',
			fieldLabel: _('changepack_commit_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false,
		}, {
			xtype: 'textarea',
			fieldLabel: _('changepack_commit_description'),
			name: 'description',
			id: config.id + '-description',
			height: 150,
			anchor: '99%'
		}/* , {
			xtype: 'xcheckbox',
			boxLabel: _('changepack_commit_active'),
			name: 'active',
			id: config.id + '-active',
			checked: true,
		} */];
	},

	loadDropZones: function() {
	}

});
Ext.reg('changepack-commit-window-create', ChangePack.window.CreateCommit);


ChangePack.window.UpdateCommit = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'changepack-commit-window-update';
	}
	Ext.applyIf(config, {
		title: _('changepack_commit_update'),
		width: 550,
		autoHeight: true,
		url: ChangePack.config.connector_url,
		action: 'mgr/commit/update',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
	ChangePack.window.UpdateCommit.superclass.constructor.call(this, config);
};
Ext.extend(ChangePack.window.UpdateCommit, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'hidden',
			name: 'id',
			id: config.id + '-id',
		}, {
			xtype: 'textfield',
			fieldLabel: _('changepack_commit_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false,
		}, {
			xtype: 'textarea',
			fieldLabel: _('changepack_commit_description'),
			name: 'description',
			id: config.id + '-description',
			anchor: '99%',
			height: 150,
		}/* , {
			xtype: 'xcheckbox',
			boxLabel: _('changepack_commit_active'),
			name: 'active',
			id: config.id + '-active',
		} */];
	},

	loadDropZones: function() {
	}

});
Ext.reg('changepack-commit-window-update', ChangePack.window.UpdateCommit);