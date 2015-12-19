ChangePack.grid.Logs = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'changepack-grid-logs';
	}
	Ext.applyIf(config, {
		url: ChangePack.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		pageSize:10,
		baseParams: {
			action: 'mgr/log/getlist'
		},
		listeners: {
			/* rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateLog(grid, e, row);
			} */
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
				return !rec.data.active
					? 'changepack-grid-row-disabled'
					: '';
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true,
	});
	ChangePack.grid.Logs.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(ChangePack.grid.Logs, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = ChangePack.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},

	createCommit: function (btn, e) {
		var w = MODx.load({
			xtype: 'changepack-commit-window-create',
			id: Ext.id(),
			listeners: {
				success: {
					fn: function () {
						this.refresh();
						Ext.getCmp('changepack-grid-commits').refresh();
					}, scope: this
				}
			}
		});
		w.reset();
		w.setValues({active: true});
		w.show(e.target);
	},

	removeLog: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('changepack_logs_remove')
				: _('changepack_log_remove'),
			text: ids.length > 1
				? _('changepack_logs_remove_confirm')
				: _('changepack_log_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/log/remove',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function (r) {
						this.refresh();
					}, scope: this
				}
			}
		});
		return true;
	},

	disableLog: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/log/disable',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},

	enableLog: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/log/enable',
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},

	getFields: function (config) {
		return ['id', 'commit_id', 'action', 'mod_class', 'mod_id', 'name', 'user_id', 'customer', 'last', 'data', 'actions'];
		//'id', 'commit_id', 'action', 'mod_class', 'mod_id', 'name', 'user_id', 'last', 'data'
	},

	getColumns: function (config) {
		return [{
			header: _('changepack_log_id'),
			dataIndex: 'id',
			sortable: true,
			width: 70
		}, {
			header: _('changepack_log_commit_id'),
			dataIndex: 'commit_id',
			sortable: true,
			width: 70,
		}, {
			header: _('changepack_log_action'),
			dataIndex: 'action',
			sortable: false,
			width: 70,
		}, {
			header: _('changepack_log_mod_class'),
			dataIndex: 'mod_class',
			sortable: false,
			width: 100,
		}, {
			header: _('changepack_log_mod_id'),
			dataIndex: 'mod_id',
			sortable: false,
			width: 70,
		}, {
			header: _('changepack_log_name'),
			dataIndex: 'name',
			sortable: false,
			width: 120,
		}, {
			header: _('changepack_grid_user'),
			dataIndex: 'customer',
			sortable: true,
			width: 100,
			renderer: ChangePack.utils.userLink,
		},{
			header: _('changepack_log_last'),
			dataIndex: 'last',
			renderer: ChangePack.utils.renderBoolean,
			sortable: true,
			width: 70,
		}, {
			header: _('changepack_log_data'),
			dataIndex: 'data',
			sortable: false,
			width: 120,
		}, {
			header: _('changepack_grid_actions'),
			dataIndex: 'actions',
			renderer: ChangePack.utils.renderActions,
			sortable: false,
			width: 60,
			id: 'actions'
		}];
	},

	getTopBar: function (config) {
		return [{
			text: '<i class="icon icon-plus"></i>&nbsp;' + _('changepack_commit_create'),
			handler: this.createCommit,
			scope: this
		}, '->', {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field-commit',
			emptyText: _('changepack_log_search_commit'),
			listeners: {
				render: {
					fn: function (tf) {
						tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
							this._doSearch(tf);
						}, this);
					}, scope: this
				}
			}
		}, {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field-last',
			emptyText: _('changepack_log_search_last'),
			listeners: {
				render: {
					fn: function (tf) {
						tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
							this._doSearch(tf);
						}, this);
					}, scope: this
				}
			}
		}, {
			xtype: 'button',
			id: config.id + '-search-clear',
			text: '<i class="icon icon-times"></i>',
			listeners: {
				click: {fn: this._clearSearch, scope: this}
			}
		}];
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	_getSelectedIds: function () {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

	_doSearch: function (tf, nv, ov) {
		this.getStore().baseParams.querycommit = Ext.getCmp(this.config.id + '-search-field-commit').getValue();
		this.getStore().baseParams.querylast = Ext.getCmp(this.config.id + '-search-field-last').getValue();
		//this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		//this.getStore().baseParams.query = '';
		//Ext.getCmp(this.config.id + '-search-field').setValue('');
		this.getStore().baseParams.querycommit = '';
		Ext.getCmp(this.config.id + '-search-field-commit').setValue('');
		this.getStore().baseParams.querylast = '';
		Ext.getCmp(this.config.id + '-search-field-last').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('changepack-grid-logs', ChangePack.grid.Logs);
