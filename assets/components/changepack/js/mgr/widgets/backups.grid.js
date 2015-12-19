ChangePack.grid.Backups = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'changepack-grid-backups';
	}
	Ext.applyIf(config, {
		url: ChangePack.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		pageSize:10,
		id: 'changepack-grid-backup',
		baseParams: {
			action: 'mgr/backup/getlist'
		},
		listeners: {
/* 			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateBackup(grid, e, row);
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
	ChangePack.grid.Backups.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(ChangePack.grid.Backups, MODx.grid.Grid, {

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = ChangePack.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},
	applyBackup: function (act, btn, e) {
		var topic = '/changepack/';
		var register = 'mgr';
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;
		this.console = MODx.load({
		   xtype: 'modx-console'
		   ,register: register
		   ,topic: topic
		   ,show_filename: 0
		   ,listeners: {
			 'shutdown': {fn:function() {
				 Ext.getCmp('changepack-grid-backups').refresh();
			 },scope:this}
		   }
		});
		this.console.show(Ext.getBody());
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/backup/applybackup'
				,register: register
				,topic: topic
				,id: id
			}
			,listeners: {
				'success':{fn:function() {
					this.console.fireEvent('complete');
				},scope:this}
			}
		});
	},
	applyCommit: function (act, btn, e) {
		var topic = '/changepack/';
		var register = 'mgr';
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;
		this.console = MODx.load({
		   xtype: 'modx-console'
		   ,register: register
		   ,topic: topic
		   ,show_filename: 0
		   ,listeners: {
			 'shutdown': {fn:function() {
				 Ext.getCmp('changepack-grid-backups').refresh();
			 },scope:this}
		   }
		});
		this.console.show(Ext.getBody());
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/backup/applycommit'
				,register: register
				,topic: topic
				,id: id
			}
			,listeners: {
				'success':{fn:function() {
					this.console.fireEvent('complete');
				},scope:this}
			}
		});
		
	},
	removeBackup: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('changepack_backups_remove')
				: _('changepack_backup_remove'),
			text: ids.length > 1
				? _('changepack_backups_remove_confirm')
				: _('changepack_backup_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/backup/remove',
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
		return ['id', 'name', 'description', 'change_count', 'user_id', 'customer', 'data', 'file_commit', 'file_backup', 'actions'];
		//`id`,  `name`,  LEFT(`description`, 256),  `change_count`,  `user_id`,  `data`,  LEFT(`file_commit`, 256),  LEFT(`file_backup`, 256)
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
			header: _('changepack_backup_file_commit'), //'file_commit', 'file_backup'
			dataIndex: 'file_commit',
			sortable: false,
			width: 120,
			renderer: ChangePack.utils.backupLink,
		}, {
			header: _('changepack_backup_file_backup'),
			dataIndex: 'file_backup',
			sortable: false,
			width: 120,
			renderer: ChangePack.utils.backupLink,
		}, {
			header: _('changepack_grid_actions'),
			dataIndex: 'actions',
			renderer: ChangePack.utils.renderActions,
			sortable: false,
			width: 100,
			id: 'actions'
		}];
	},

	getTopBar: function (config) {
		return [ '->', {
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
Ext.reg('changepack-grid-backups', ChangePack.grid.Backups);
