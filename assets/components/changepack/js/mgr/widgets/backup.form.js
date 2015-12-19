/*
 * ChangePack
 *
 */
var topic = '/changepack/';
var register = 'mgr';

ChangePack.panel.createImport = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: ChangePack.config.connectorUrl
        ,baseParams: {
            action: 'importcommit',
            register: register,
            topic: topic
        }
        ,layout: 'fit'
        ,id: 'changepack-panel-import'
        ,buttonAlign: 'center'
        ,fileUpload: true
        ,width: '98%'
        ,items: [{
				html: '<p>'+_('changepack.tab.input.desc')+'</p>',
				border: false
			},{
				xtype: 'textfield',
				fieldLabel: _('changepack.jsonfile'),
				name: 'json-file',
				id: 'json-file',
				inputType: 'file'
			},{
				xtype: 'button',
				text: '<i class="icon icon-plus"></i>&nbsp;' + _('changepack_commit_import'),
				handler: this.importCommit,
				scope: this
			}]
    });
    Ext.Ajax.timeout = 0;
    ChangePack.panel.createImport.superclass.constructor.call(this,config);
};

Ext.extend(ChangePack.panel.createImport,MODx.FormPanel,{
	importCommit: function (act, btn, e) {
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
		Ext.getCmp('changepack-panel-import').form.submit({
			success:{fn:function() {
				this.console.fireEvent('complete');
			},scope:this},
			failure: function(f, a) {
				//alert(_('changepack.importfailure')+' '+a.result.message);
				//console.fireEvent('complete');
			}
		});
	}
});
Ext.reg('changepack-form-create-import',ChangePack.panel.createImport);
