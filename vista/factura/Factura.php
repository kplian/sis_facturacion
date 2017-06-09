<?php
/**
*@package pXP
*@file gen-Factura.php
*@author  (ada.torrico)
*@date 18-11-2014 19:26:15
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Factura=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Factura.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
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
				name: 'id_agencia',
				fieldLabel: 'Agencia',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas/control/Agencia/ListarAgencia',
					id: 'id_agencia',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_agencia', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_agencia',
				displayField: 'nombre',
				gdisplayField: 'desc_agencia',
				hiddenName: 'id_agencia',
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
					return String.format('{0}', record.data['desc_agencia']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_sucursal',
				fieldLabel: 'Sucursal',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas/control/Sucursal/ListarSucursal',
					id: 'id_sucursal',
					root: 'datos',
					sortInfo: {
						field: 'estacion',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_sucursal', 'estacion', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.estacion#movtip.codigo'}
				}),
				valueField: 'id_sucursal',
				displayField: 'estacion',
				gdisplayField: 'desc_sucursal',
				hiddenName: 'id_sucursal',
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
					return String.format('{0}', record.data['desc_sucursal']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.estacion',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_dosificacion',
				fieldLabel: 'Dosificacion',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_facturacion/control/Dosificacion/ListarDosificacion',
					id: 'id_dosificacion',
					root: 'datos',
					sortInfo: {
						field: 'nroaut',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_dosificacion', 'nroaut'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nroaut#movtip.codigo'}
				}),
				valueField: 'id_dosificacion',
				displayField: 'nroaut',
				gdisplayField: 'desc_numero_dosificacion',
				hiddenName: 'id_dosificacion',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '60%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_numero_dosificacion']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nroaut',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_actividad_economica',
				fieldLabel: 'Actividad Economica',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_facturacion/control/ActividadEconomica/ListarActividadEconomica',
					id: 'id_actividad_economica',
					root: 'datos',
					sortInfo: {
						field: 'nombre_actividad',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_actividad_economica', 'nombre_actividad', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre_actividad#movtip.codigo'}
				}),
				valueField: 'id_actividad_economica',
				displayField: 'nombre_actividad',
				gdisplayField: 'desc_actividad',
				hiddenName: 'id_actividad_economica',
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
					return String.format('{0}', record.data['desc_actividad']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre_actividad',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_moneda',
				fieldLabel: 'Moneda',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_parametros/control/Moneda/listarMoneda',
					id: 'id_moneda',
					root: 'datos',
					sortInfo: {
						field: 'moneda',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_moneda', 'moneda', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.moneda#movtip.codigo'}
				}),
				valueField: 'id_moneda',
				displayField: 'moneda',
				gdisplayField: 'desc_moneda',
				hiddenName: 'id_moneda',
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
					return String.format('{0}', record.data['desc_moneda']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'nit',
				fieldLabel: 'NIT',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.nit',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'por_comis',
				fieldLabel: 'Por Comis',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'factu.por_comis',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tcambio',
				fieldLabel: 'tcambio',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'factu.tcambio',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'importe_comis',
				fieldLabel: 'Importe Comis',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'factu.importe_comis',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'codigo_control',
				fieldLabel: 'Codigo de Control',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.codigo_control',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_factura',
				fieldLabel: 'Nro Factura',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'factu.nro_factura',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'contabilizado',
				fieldLabel: 'Contabilizado',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.contabilizado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha',
				fieldLabel: 'Fecha',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'factu.fecha',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'observacion',
				fieldLabel: 'Observacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.observacion',type:'string'},
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
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.renglon',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'monto',
				fieldLabel: 'Monto',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.monto',type:'string'},
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
				filters:{pfiltro:'factu.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'comision',
				fieldLabel: 'Comision',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'factu.comision',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'razon',
				fieldLabel: 'Razon',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'factu.razon',type:'string'},
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
				filters:{pfiltro:'factu.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'factu.fecha_reg',type:'date'},
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
				filters:{pfiltro:'factu.usuario_ai',type:'string'},
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
				filters:{pfiltro:'factu.fecha_mod',type:'date'},
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
	title:'Factura',
	ActSave:'../../sis_facturacion/control/Factura/insertarFactura',
	ActDel:'../../sis_facturacion/control/Factura/eliminarFactura',
	ActList:'../../sis_facturacion/control/Factura/listarFactura',
	id_store:'id_factura',
	fields: [
		{name:'id_factura', type: 'numeric'},
		{name:'id_agencia', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_actividad_economica', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'id_dosificacion', type: 'numeric'},
		{name:'nit', type: 'string'},
		{name:'por_comis', type: 'numeric'},
		{name:'tcambio', type: 'numeric'},
		{name:'importe_comis', type: 'numeric'},
		{name:'codigo_control', type: 'string'},
		{name:'nro_factura', type: 'string'},
		{name:'contabilizado', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'observacion', type: 'string'},
		{name:'renglon', type: 'string'},
		{name:'monto', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'comision', type: 'numeric'},
		{name:'razon', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_sucursal', type: 'string'},
		{name:'desc_agencia', type: 'string'},
		{name:'desc_actividad', type: 'string'},
		{name:'desc_moneda', type: 'string'},
		{name:'desc_numero_dosificacion', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_factura',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true,
	east:{
		  url:'../../../sis_facturacion/vista/factura_detalle/FacturaDetalle.php',
		  title:'Columnas', 
		  width:400,
		  cls:'FacturaDetalle'
		 }
	}
)
</script>
		
		