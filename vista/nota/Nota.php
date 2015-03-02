<?php
/**
*@package pXP
*@file gen-Nota.php
*@author  (ada.torrico)
*@date 18-11-2014 19:30:03
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Nota=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Nota.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}});
		this.addButton('imprimir',{argument: {imprimir: 'imprimir_nota'},text:'<i class="fa fa-print fa-3x"></i>  Imprimir Nota',/*iconCls:'' ,*/disabled:false,handler:this.reimprimir});
		this.addButton('anular',{argument: {imprimir: 'anular_nota'},text:'<i class="fa fa-file-excel-o fa-3x"></i> Anular Nota',/*iconCls:'' ,*/disabled:false,handler:this.anular});



	},

	Atributos:[
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
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_factura'
			},
			type:'Field',
			form:true
		},

		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_sucursal'
			},
			type:'Field',
			form:true
		},


		{
			config:{
				labelSeparator:'',
				inputType:'hidden',
				name: 'id_moneda'
			},
			type:'Field',
			form:true
		},

		{
			config:{
				name: 'nro_nota',
				fieldLabel: 'nro_nota',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
			type:'TextField',
			filters:{pfiltro:'not.nro_nota',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},


		{
			config:{
				name: 'nro_liquidacion',
				fieldLabel: 'nro_liquidacion',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
			type:'TextField',
			filters:{pfiltro:'not.nro_liquidacion',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},

		{
			config:{
				name: 'total_devuelto',
				fieldLabel: 'total_devuelto',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
			type:'NumberField',
			filters:{pfiltro:'not.total_devuelto',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},


		{
			config:{
				name: 'excento',
				fieldLabel: 'excento',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
			type:'NumberField',
			filters:{pfiltro:'not.excento',type:'numeric'},
			id_grupo:1,
			grid:true,
			form:true
		},


		{
			config:{
				name: 'estacion',
				fieldLabel: 'estacion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:20
			},
				type:'TextField',
				filters:{pfiltro:'not.estacion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'fecha',
				fieldLabel: 'fecha',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'not.fecha',type:'date'},
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
				filters:{pfiltro:'not.tcambio',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},

		{
			config:{
				name: 'nit',
				fieldLabel: 'nit',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'not.nit',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'razon',
				fieldLabel: 'razon',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
			type:'TextField',
			filters:{pfiltro:'not.razon',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'estado',
				fieldLabel: 'estado',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'not.estado',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'credfis',
				fieldLabel: 'credfis',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'not.credfis',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},

		{
			config:{
				name: 'monto_total',
				fieldLabel: 'monto_total',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:1179654
			},
				type:'NumberField',
				filters:{pfiltro:'not.monto_total',type:'numeric'},
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
				filters:{pfiltro:'not.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
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
				filters:{pfiltro:'not.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				filters:{pfiltro:'not.usuario_ai',type:'string'},
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
				filters:{pfiltro:'not.fecha_reg',type:'date'},
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
				filters:{pfiltro:'not.fecha_mod',type:'date'},
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
	title:'Notas',
	ActSave:'../../sis_facturacion/control/Nota/insertarNota',
	ActDel:'../../sis_facturacion/control/Nota/eliminarNota',
	ActList:'../../sis_facturacion/control/Nota/listarNota',
	id_store:'id_nota',
	fields: [
		{name:'id_nota', type: 'numeric'},
		{name:'id_factura', type: 'numeric'},
		{name:'id_sucursal', type: 'numeric'},
		{name:'id_moneda', type: 'numeric'},
		{name:'estacion', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'excento', type: 'numeric'},
		{name:'total_devuelto', type: 'numeric'},
		{name:'tcambio', type: 'numeric'},
		{name:'id_liquidacion', type: 'string'},
		{name:'nit', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'credfis', type: 'numeric'},
		{name:'nro_liquidacion', type: 'string'},
		{name:'monto_total', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'nro_nota', type: 'string'},
		{name:'razon', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},

	],
	sortInfo:{
		field: 'id_nota',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bedit:false,
	bnew:false,
		east:{
			url:'../../../sis_facturacion/vista/nota_detalle/NotaDetalle.php',
			title:'Columnas',
			width:300,
			cls:'NotaDetalle'
		},
		reimprimir:function(){

			var rec = this.sm.getSelected();

			Ext.Ajax.request({
				url:'../../sis_facturacion/control/Nota/generarNota',
				params:{'notas':rec.data['id_nota'],'reimpresion':'si'},
				success: this.successExport,
				failure: this.conexionFailure,
				timeout:this.timeout,
				scope:this
			});


		},
		successExport:function(resp){

			Phx.CP.loadingHide();

			var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

			console.log(objRes)

			var i=0;
			objRes.ROOT.datos.forEach( function( item ) {

				var texto = item;
				ifrm = document.createElement("IFRAME");
				ifrm.name = 'mifr'+i;
				ifrm.id = 'mifr'+i;
				document.body.appendChild(ifrm);
				var doc = window.frames['mifr'+i].document;
				doc.open();
				doc.write(texto);
				doc.close();
				i++;

			});


		},

		anular:function() {

			var rec = this.sm.getSelected();
			//console.log(this.confirmacion());


			Ext.MessageBox.confirm('Confirmación','¿Estas seguro de querer hacer esto?',function(btn){
				if(btn === 'yes'){

					Ext.Ajax.request({
						url:'../../sis_facturacion/control/Nota/anularNota',
						params:{'notas':rec.data['id_nota'],'nota_informix':rec.data['nro_nota']},
						success: this.successExport,
						failure: this.conexionFailure,
						timeout:this.timeout,
						scope:this
					});


				}else{
					//si el usuario canceló
					//alert('Decidiste Cancelar la Anulacion de la nota');
				}
			},this);





		}


	}
)
</script>

