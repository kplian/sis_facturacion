<?php
/**
*@package pXP
*@file gen-NotaDetalle.php
*@author  (ada.torrico)
*@date 18-11-2014 19:32:09
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.NotaDetalle=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.NotaDetalle.superclass.constructor.call(this,config);
		this.init();
		//this.load({params:{start:0, limit:this.tam_pag}})
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_nota_detalle'
			},
			type:'Field',
			form:true 
		},

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
			//configuracion del componente
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_nota'
			},
			type:'Field',
			form:true
		},







		{
			config:{
				name: 'concepto',
				fieldLabel: 'concepto',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'deno.concepto',type:'string'},
			id_grupo:1,
			grid:true,
			form:false
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
				filters:{pfiltro:'deno.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'importe',
				fieldLabel: 'importe',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'deno.importe',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
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
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'deno.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'deno.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'deno.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'deno.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Detalle Nota',
	ActSave:'../../sis_facturacion/control/NotaDetalle/insertarNotaDetalle',
	ActDel:'../../sis_facturacion/control/NotaDetalle/eliminarNotaDetalle',
	ActList:'../../sis_facturacion/control/NotaDetalle/listarNotaDetalle',
	id_store:'id_nota_detalle',
	fields: [
		{name:'id_nota_detalle', type: 'numeric'},
		{name:'id_factura_detalle', type: 'numeric'},
		{name:'id_nota', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'importe', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},

		{name:'concepto', type: 'string'},
		
	],
	sortInfo:{
		field: 'id_nota_detalle',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bdel:false,
	bsave:false,
	bedit:false,
	bnew:false,
		preparaMenu:function(tb){
			// llamada funcion clace padre
			Phx.vista.NotaDetalle.superclass.preparaMenu.call(this,tb)
		},
		onButtonNew:function(){
			Phx.vista.NotaDetalle.superclass.onButtonNew.call(this);
			this.getComponente('id_nota').setValue(this.maestro.id_nota);
		},
		onReloadPage:function(m){
			this.maestro=m;
			this.store.baseParams={id_nota:this.maestro.id_nota};
			this.load({params:{start:0, limit:50}})
		}
	}
)
</script>
		
		