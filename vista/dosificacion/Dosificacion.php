<?php
/**
*@package pXP
*@file gen-Dosificacion.php
*@author  (ada.torrico)
*@date 18-11-2014 19:17:08
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Dosificacion=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Dosificacion.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_dosificacion'
			},
			type:'Field',
			form:true 
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
				anchor: '60%',
				gwidth: 150,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['desc_sucursal']);
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
				name: 'id_lugar_pais',
				fieldLabel: 'Pais',
				allowBlank: true,
				emptyText:'Lugar...',
				store:new Ext.data.JsonStore(
				{
					url: '../../sis_parametros/control/Lugar/listarLugar',
					id: 'id_lugar',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_lugar','id_lugar_fk','codigo','nombre','tipo','sw_municipio','sw_impuesto','codigo_largo'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{tipos:"''pais''",par_filtro:'nombre'}
				}),
				valueField: 'id_lugar',
				displayField: 'nombre',
				gdisplayField:'desc_pais',
				hiddenName: 'id_lugar',
    			triggerAction: 'all',
    			lazyRender:true,
				mode:'remote',
				pageSize:50,
				queryDelay:500,
				anchor:"60%",
				gwidth:220,
				forceSelection:true,
				minChars:2,
				renderer:function (value, p, record){return String.format('{0}', record.data['desc_pais']);}
			},
			type:'ComboBox',
			filters:{pfiltro:'lug.nombre',type:'string'},
			id_grupo:0,
			grid:true,
			form:true
		},
		{
			config: {
				name: 'id_activida_economica',
				fieldLabel: 'Actividad Economica',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_facturacion/control/ActividadEconomica/listarActividadEconomica',
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
			config:{
				name: 'notificado',
				fieldLabel: 'Notificado',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.notificado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'llave',
				fieldLabel: 'Llave',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.llave',type:'string'},
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
				filters:{pfiltro:'dosi.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'nro_tramite',
				fieldLabel: 'Nro Tramite',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'NumberField',
				filters:{pfiltro:'dosi.nro_tramite',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo_autoimpresor',
				fieldLabel: 'Tipo AutoImpresor',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.tipo_autoimpresor',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'autoimpresor',
				fieldLabel: 'Autoimpresor',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.autoimpresor',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nroaut',
				fieldLabel: 'nro aut',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.nroaut',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'final',
				fieldLabel: 'Final',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.final',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'estacion',
				fieldLabel: 'Estacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'dosi.estacion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'inicial',
				fieldLabel: 'Inicial',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.inicial',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo',
				fieldLabel: 'Tipo',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'dosi.tipo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'glosa_consumidor',
				fieldLabel: 'Glosa Consumidor',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.glosa_consumidor',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'glosa_impuestos',
				fieldLabel: 'Glosa Impuestos',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.glosa_impuestos',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_dosificacion',
				fieldLabel: 'Fecha Dosificacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dosi.fecha_dosificacion',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		
		
		{
			config:{
				name: 'nombre_sisfac',
				fieldLabel: 'SISFAC',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.nombre_sisfac',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha_inicio_emi',
				fieldLabel: 'Inicio Emi',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dosi.fecha_inicio_emi',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_siguiente',
				fieldLabel: 'Nro Siguiente',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.nro_siguiente',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nro_resolucion',
				fieldLabel: 'Nro Resolucion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.nro_resolucion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'glosa_empresa',
				fieldLabel: 'Glosa Empresa',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:150
			},
				type:'TextField',
				filters:{pfiltro:'dosi.glosa_empresa',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
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
				filters:{pfiltro:'dosi.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha Creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'dosi.fecha_reg',type:'date'},
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
				name: 'id_usuario_ai',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'dosi.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'dosi.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Dosificacion',
	ActSave:'../../sis_facturacion/control/Dosificacion/insertarDosificacion',
	ActDel:'../../sis_facturacion/control/Dosificacion/eliminarDosificacion',
	ActList:'../../sis_facturacion/control/Dosificacion/listarDosificacion',
	id_store:'id_dosificacion',
	fields: [
		{name:'id_dosificacion', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_activida_economica', type: 'numeric'},
		{name:'notificado', type: 'string'},
		{name:'llave', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'nro_tramite', type: 'string'},
		{name:'tipo_autoimpresor', type: 'string'},
		{name:'nroaut', type: 'string'},
		{name:'final', type: 'string'},
		{name:'estacion', type: 'string'},
		{name:'inicial', type: 'string'},
		{name:'tipo', type: 'string'},
		{name:'glosa_consumidor', type: 'string'},
		{name:'glosa_impuestos', type: 'string'},
		{name:'fecha_dosificacion', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_lugar_pais', type: 'numeric'},
		{name:'autoimpresor', type: 'string'},
		{name:'nombre_sisfac', type: 'string'},
		{name:'fecha_inicio_emi', type: 'date',dateFormat:'Y-m-d'},
		{name:'nro_siguiente', type: 'string'},
		{name:'nro_resolucion', type: 'string'},
		{name:'glosa_empresa', type: 'string'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_sucursal', type: 'string'},
		{name:'desc_actividad', type: 'string'},
		{name:'desc_pais', type: 'string'},
	],
	sortInfo:{
		field: 'id_dosificacion',
		direction: 'ASC'
	},
	bdel:true,
	bsave:true
	}
)
</script>
		
		