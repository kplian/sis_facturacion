<?php
/**
*@package pXP
*@file gen-FacturaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:28:06
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FacturaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.FacturaDetalle.superclass.constructor.call(this,config);
		this.init();
		//this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_factura_detalle'
			},
			type:'Field',
			form:true 
		},
		{
			
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_factura'
			},
			type:'Field',
			form:true 
		},
		{
			config: {
				name: 'id_concepto_ingas',
				fieldLabel: 'Concepto Ingas',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_parametros/control/ConceptoIngas/listarConceptoIngas',
					id: 'id_concepto_ingas',
					root: 'datos',
					sortInfo: {
						field: 'desc_ingas',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_concepto_ingas', 'desc_ingas', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_concepto_ingas',
				displayField: 'desc_ingas',
				gdisplayField: 'desc_ingas',
				hiddenName: 'id_concepto_ingas',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_ingas']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.desc_ingas',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'precio_unitario',
				fieldLabel: 'Precio Unitario',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.precio_unitario',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'concepto',
				fieldLabel: 'concepto',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.concepto',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo_concepto',
				fieldLabel: 'Tipo Concepto',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.tipo_concepto',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'renglon',
				fieldLabel: 'Renglon',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.renglon',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'importe',
				fieldLabel: 'Importe',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.importe',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'defa.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'cantidad',
				fieldLabel: 'Cantidad',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'defa.cantidad',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'defa.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'defa.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'defa.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'defa.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Detalle Factura',
	ActSave:'../../sis_facturacion/control/FacturaDetalle/insertarFacturaDetalle',
	ActDel:'../../sis_facturacion/control/FacturaDetalle/eliminarFacturaDetalle',
	ActList:'../../sis_facturacion/control/FacturaDetalle/listarFacturaDetalle',
	id_store:'id_factura_detalle',
	fields: [
		{name:'id_factura_detalle', type: 'numeric'},
		{name:'id_factura', type: 'numeric'},
		{name:'id_concepto_ingas', type: 'numeric'},
		{name:'precio_unitario', type: 'string'},
		{name:'concepto', type: 'string'},
		{name:'tipo_concepto', type: 'string'},
		{name:'renglon', type: 'string'},
		{name:'importe', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'cantidad', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_ingas', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_factura_detalle',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	preparaMenu:function(tb){
		// llamada funcion clace padre
		Phx.vista.FacturaDetalle.superclass.preparaMenu.call(this,tb)
	},
    onButtonNew:function(){
		Phx.vista.FacturaDetalle.superclass.onButtonNew.call(this);
		this.getComponente('id_factura').setValue(this.maestro.id_factura);
	},
	onReloadPage:function(m){
		this.maestro=m;
		this.store.baseParams={id_factura:this.maestro.id_factura};
		this.load({params:{start:0, limit:50}})
	}
	
	}
	
	
)
</script>
		
		