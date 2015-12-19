ChangePack.grid.Commits = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'changepack-grid-commits';
	}
	Ext.applyIf(config, {
		url: ChangePack.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		pageSize:10,
		baseParams: {
			action: 'mgr/commit/getlist'
		},
		listeners: {
			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.searchCommit(grid, e, row);
			}
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
	ChangePack.grid.Commits.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(ChangePack.grid.Commits, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = ChangePack.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},
	
	searchCommit(btn, e, row){
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;
		Ext.getCmp('changepack-grid-logs' + '-search-field-commit').setValue(id);
		//Ext.getCmp('changepack-grid-logs' + '-search-field-last').setValue('');
		Ext.getCmp('changepack-grid-logs')._doSearch(btn);
	},
	updateCommit: function (btn, e, row) {
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/commit/get',
				id: id
			},
			listeners: {
				success: {
					fn: function (r) {
						var w = MODx.load({
							xtype: 'changepack-commit-window-update',
							id: Ext.id(),
							record: r,
							listeners: {
								success: {
									fn: function () {
										this.refresh();
									}, scope: this
								}
							}
						});
						w.reset();
						w.setValues(r.object);
						w.show(e.target);
					}, scope: this
				}
			}
		});
	},

	removeCommit: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('changepack_commits_remove')
				: _('changepack_commit_remove'),
			text: ids.length > 1
				? _('changepack_commits_remove_confirm')
				: _('changepack_commit_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/commit/remove',
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

	getFields: function (config) {
		return ['id', 'name', 'description', 'change_count', 'user_id', 'customer', 'data', 'filename', 'actions'];
		//`id`,  `name`,  LEFT(`description`, 256),  `change_count`,  `user_id`,  `data`,  LEFT(`filename`
	},

	getColumns: function (config) {
		return [{
			header: _('changepack_commit_id'),
			dataIndex: 'id',
			sortable: true,
			width: 70
		}, {
			header: _('changepack_commit_name'),
			dataIndex: 'name',
			sortable: true,
			width: 120,
		}, {
			header: _('changepack_commit_description'),
			dataIndex: 'description',
			sortable: false,
			width: 120,
		}, {
			header: _('changepack_commit_change_count'),
			dataIndex: 'change_count',
			sortable: false,
			width: 100,
		}, {
			header: _('changepack_grid_user'),
			dataIndex: 'customer',
			sortable: true,
			width: 100,
			renderer: ChangePack.utils.userLink,
		},{
			header: _('changepack_commit_data'),
			dataIndex: 'data',
			sortable: false,
			width: 120,
		}, {
			header: _('changepack_commit_filename'),
			dataIndex: 'filename',
			sortable: false,
			width: 120,
			renderer: ChangePack.utils.commitLink,
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
		return [/* {
			text: '<i class="icon icon-plus"></i>&nbsp;' + _('changepack_commit_create'),
			handler: this.createCommit,
			scope: this
		}, */ '->', {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field',
			emptyText: _('changepack_grid_search'),
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
		this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		this.getStore().baseParams.query = '';
		Ext.getCmp(this.config.id + '-search-field').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('changepack-grid-commits', ChangePack.grid.Commits);
