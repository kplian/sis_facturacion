<?php
/**
*@package pXP
*@file    SubirArchivo.php
*@author  Favio Figueroa
*@date    21/11/2014
*@description permites subir archivos a la tabla de documento_sol
*/
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.FormNota=Ext.extend(Phx.frmInterfaz,{
    ActSave: '../../sis_facturacion/control/Nota/saveForm',
    botones: false,
    ciudadOrigen : '',
    sucursalOrigen: '',
    tam_pag: 20, 
    layout: 'fit',   
     
    constructor:function(config)
    {
    	
    	this.detalle = '';  
    	
    	this.montototal = 0;
    	this.win = new Ext.Window({
                layout:'fit',
                
                width:900,
                height:600,
                closeAction:'hide',
                title:'Vista Previa de Notas de credito y debito',
                preventBodyReset: true,            
				html: '<h1>This should be the way you expect it!</h1>',
                buttons: [{
                    text:'<i class="fa fa-check"></i> Aceptar',                    
                    handler: this.guardar,
                    
                    scope: this
                },{
                    text: '<i class="fa fa-times"></i> Cancelar',
                    handler: this.closeWin,
                    scope:this
                }]
            });    	
    	//cantidad,detalle,peso,totalo
        var Items = Ext.data.Record.create([{
                            name: 'cantidad',
	                        type: 'int'
	                    }, {
	                        name: 'Concepto',
	                        type: 'string'
	                    },{
	                        name: 'p/Unit',
	                        type: 'float'
	                    },{
	                        name: 'Importe Original',
	                        type: 'float'
	                    },{
	                        name: 'Importe a Devolver',
	                        type: 'float'
	                    },{
	                        name: 'Exento',
	                        type: 'float'
	                    },{
	                        name: 'Total Devuelto',
	                        type: 'float'
	                    },{
	                        name: 'nro_liqui',
	                        type: 'string'
	                    },{
	                        name: 'nro_billete',
	                        type: 'string'
	                    },{
	                        name: 'nro_nit',
	                        type: 'string'
	                    },{
	                        name: 'razon',
	                        type: 'string'
	                    },{
	                        name: 'fecha_fac',
	                        type: 'string'
	                    },{
	                        name: 'nro_fac',
	                        type: 'string'
	                    },{
	                        name: 'nro_aut',
	                        type: 'string'
	                    }
	                    ]);
                    
                    
                    
                    
                   
        this.mestore = new Ext.data.JsonStore({
        	
					url: '../../sis_facturacion/control/FacturaDetalle/listarFacturaDetalle',
					id: 'id_factura_detalle',
					root: 'datos',
					totalProperty: 'total',
					fields: ['concepto','importe_original','cantidad',
							'nroliqui','billcupon','razon','nit','exento',
							'nrofac','nroaut','fecha_fac','precio_unitario',
							'importe_devolver','total_devuelto','tipo','nro_billete',
							'nro_fac','nro_aut','nro_nit'],
					remoteSort: true,
					baseParams: {dir:'ASC',sort:'nroliqui',limit:'50',start:'0'}
					
				});
        
         var editor = new Ext.ux.grid.RowEditor({
                saveText: 'Aceptar',
                name:'btn_editor'
               
            });
            
            
            
       
        
        
        // utilize custom extension for Group Summary
        //var summary = new Ext.ux.grid.Summary();
        var summary = new Ext.ux.grid.GridSummary();
        
       //cantidad,detalle,peso,total
       
       

        
        this.megrid = new Ext.grid.GridPanel({
        	padding: '0 0 0 0',
                    title:'Detalle',
                    store:  this.mestore,
                  
                   style: 'margin:0 auto;margin-top:0; width:1200px;',
                    disabled:true,
                    
                    
                    //margins: '0 5 5 60',
                    //autoExpandColumn: 'name',
                    plugins: [editor,summary],
                    stripeRows: true,
                    //plugins: summary,
                   /* view: new Ext.grid.GroupingView({
                        markDirty: false
                    }),*/
                   
                    tbar: [{
                        /*iconCls: 'badd',*/
                        text: '<i class="fa fa-plus-circle fa-lg"></i> Agregar',
                        scope:this,
                        width: '100',
                       
                        handler: function(){
                            var e = new Items({
                                cantidad:1,
                                detalle:'',
                                peso:0,
                                total:1
                            });
                            editor.stopEditing();
                            this.mestore.insert(0, e);
                            this.megrid.getView().refresh();
                            this.megrid.getSelectionModel().selectRow(0);
                            editor.startEditing(0);
                        }
                    },{
                        ref: '../removeBtn',
                        /*iconCls: 'bdelete',*/
                        text: '<i class="fa fa-trash fa-lg"></i> Eliminar',
                        //disabled: true,
                        scope:this,
                        handler: function(){
                            editor.stopEditing();
                            var s = this.megrid.getSelectionModel().getSelections();
                            for(var i = 0, r; r = s[i]; i++){
                                this.mestore.remove(r);
                            }
                        }
                    }],
            
                    columns: [
                    new Ext.grid.RowNumberer(),
                    {
                    	
                       // id: 'cantidad',
                        header: 'Cant.',
                        dataIndex: 'cantidad',
                        width: 60,
                        sortable: true,
                        hidden: true,
						hideable: false,
                        editor: {
                            xtype: 'numberfield',
                            allowBlank: true,
                            enable:false,
                            enableKeyEvents: true,
                        },
                        summaryType: 'count',
                        
                        summaryRenderer: function(v, params, data){
                            return ((v === 0 || v > 1) ? '(' + v +' items)' : '(1 item)');
                        },
                    },
                    {
                        
                        header: 'tipo',
                        dataIndex: 'tipo',
                        
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                        
                        editor: {
                        	
                        	 xtype: 'combo',
                        	name: 'tipo',
			                fieldLabel: 'Tipo',
			                allowBlank: true,
			                emptyText:'Tipo...',
			                typeAhead: true,
			                triggerAction: 'all',
			                lazyRender:true,
			                mode: 'local',
			                store:['FACTURA','BOLETO','CONCEPTO'],
			                width:200 ,
			                enableKeyEvents: true,
				            
				        }
						
                        
                    },
                   
                    {
                        header: 'Concepto',
                        dataIndex: 'concepto',
                        width: 200,
                        sortable: false,
                        
                       
                       
                         editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						    name:'billete_text',
						    allowBlank: true,
						    id:'input_concepto',
						    
						    
						    
						})
                       
                       
                            
                    }
                    ,{
                        
                        header: 'nro_aut',
                        dataIndex: 'nro_aut',
                        
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                         editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						   
						    allowBlank: true,
						    id:'input_aut',
						   
						    
						    
						})
                        
                    },
                    {
                        
                        header: 'nro_fac',
                        dataIndex: 'nro_fac',
                        
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                         editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						  
						    allowBlank: true,
						    id:'input_fac',
						 
						    
						    
						})
                        
                    },
                    {
                        
                        header: 'fecha_fac',
                        dataIndex: 'fecha_fac',
                        
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                        editor: {
			                xtype: 'datefield',
			                allowBlank: true,
			                minValue: '01/01/2006',
			                minText: 'Can\'t have a start date before the company existed!',
			                maxValue: (new Date()).format('m/d/Y')
			            }
                        
                    },
                    
                    {
                        xtype: 'numbercolumn',
                        header: 'P/Unit',
                        dataIndex: 'precio_unitario',
                        align: 'center',
                        width: 50,
                        trueText: 'Yes',
                        falseText: 'No',
                        summaryType: 'sum',
                        editor: {
                            xtype: 'numberfield',
                            allowBlank: true,
                           disabled :true,
                           //readOnly:true,
                           id:'input_pu',
                           enableKeyEvents: true,
                            
                        }
                       
                       
                        
                    },{
                        xtype: 'numbercolumn',
                        header: 'Importe Original',
                        dataIndex: 'importe_original',
                        
                        format: '$0,0.00',
                        width: 100,
                        sortable: false,
                        summaryType: 'sum',
                        editor: {
                            xtype: 'numberfield',
                            allowBlank: true,
                             disabled :true,
                             id:'input_importe_original'
                           
                        }
                    },{
                        xtype: 'numbercolumn',
                        header: 'Importe a Devolver',
                        dataIndex: 'importe_devolver',
                        
                        format: '$0,0.00',
                        width: 100,
                        sortable: false,
                        summaryType: 'sum',
                        editor: {
                        	 enableKeyEvents: true,
                            xtype: 'numberfield',
                            allowBlank: true,
                           
                        }
                    }
                    ,{
                        xtype: 'numbercolumn',
                        header: 'Exento',
                        dataIndex: 'exento',
                       	css: {
                    		background: "#ccc",
            			},
                        format: '$0,0.00',
                        width: 100,
                        sortable: false,
                        summaryType: 'sum',
                        
                        editor: {
                        	 enableKeyEvents: true,
                            xtype: 'numberfield',
                            allowBlank: true,
                            minValue: 0
                        }
                    },{
                        xtype: 'numbercolumn',
                        header: 'total Devuelto',
                        dataIndex: 'total_devuelto',
                        
                        format: '$0,0.00',
                        width: 100,
                        sortable: false,
                        summaryType: 'sum',
                         editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						    name:'t_dev',
						    disabled :true,
						    allowBlank: true
						    
						    
						})
                    },{
                        
                        header: 'nro_liqui',
                        dataIndex: 'nro_liqui',
                         hidden: true,
						hideable: false,
                       
                        width: 100,
                        sortable: false
                        
                    },{
                        
                        header: 'nro_billete',
                        dataIndex: 'nro_billete',
                      
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false
                        
                    },{
                        
                        header: 'nro_nit',
                        dataIndex: 'nro_nit',
                         hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                        editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						   id:'nro_nit',
						   name:'nro_nit',	
						    allowBlank: true,
						    
						    
						    
						    
						})
                        
                    },{
                        
                        header: 'razon',
                        dataIndex: 'razon',
                        
                        hidden: false,
						hideable: false,
                        width: 100,
                        sortable: false,
                        editor:new Ext.form.TextField({

						    enableKeyEvents: true,
						    id:'razon',
						   name:'razon',	
						    allowBlank: true,
						    
						    
						    
						    
						})
                        
                    }
            
            ]
                });
        
        //prepara barra de tareas
        this.iniciarArrayBotones();
        
    
        
        this.borderForm = true;
        
        this.frameForm = false; 
        this.paddingForm = '5 40 5 0';
        this.bodyStyle ='padding:0px 5px 0',
        this.Grupos = [
            {
            	
                layout: 'form',
                autoScroll: true,
                xtype: 'panel',
                bbar: this.toolBar,
                 
                width: 850,
                title: 'Formulario de FormNotas',
                border: false,
                frame: true,
                padding: '5 0 20 0',
                
                
                margins:{top:0, right:0, bottom:0, left:0},
                
                defaults: {
                   border: false
                   
                },            
                items: [{
                            xtype: 'fieldset',
                            margins:{top:0, right:0, bottom:0, left:50},
                            layout: 'column',
                            title: '<h1 style="color:111; font-size:12px;">Tipo de Devolución ...</h1>',
                            width: 850,
                            
                            style: {
					            background: '#c5d6ec'
					            
					        },
                            autoHeight: true,
                            padding: '0 0 0 0',
                            
                            items: [
                                {
                                    layout: 'form',
                                    
                                    border: false,
                                    itemId:'origen_destino',
                                    items: [],
                                    padding: '0 10 0 0',
                                    labelWidth:50,
                                    id_grupo:1
                                    
                                },{
                                    layout: 'form',
                                    border: false,
                                    items: [],
                                    padding: '0 10 0 0',
                                    labelWidth:75,
                                    id_grupo:2
                                },
                                {
                                    layout: 'form',
                                    border: false,
                                    items: [],
                                    padding: '0 10 0 0',
                                    id_grupo:22
                                }
                              ],
                                        
                           },
                         
                         
                       
                           
                           
                            {
                            xtype: 'fieldset',
                            margins:{top:0, right:0, bottom:0, left:50},
                            layout: 'column',
                            title: ' <h1 style="color:111; font-size:12px;">Datos ...</h1>',
                            width:900,
                            name:'datos_factura ',
                            autoHeight: true,
                            padding: '0 0 0 0',
                            items: [
                                {
                                    layout: 'form',
                                    border: false,
                                    itemId:'origen_destino',
                                    items: [],
                                    labelWidth:70,
                                    padding: '0 10 0 0',
                                    id_grupo:6                                   
                                },{
                                    layout: 'form',
                                    border: false,
                                    items: [],
                                    padding: '0 10 0 0',
                                      labelWidth:60,
                                    id_grupo:7
                                },{
                                    layout: 'form',
                                    border: false,
                                    items: [],
                                    padding: '0 10 0 0',
                                      labelWidth:75,
                                    id_grupo:8
                                }
                                
                              ],
                                        
                           },
                           
                           
                           
                           
                           
  
                         
                         {
                                xtype:'tabpanel',
                                //layout:'fit',
                                /*padding:'0 0 0 50',*/
                                margins:{top:0, right:0, bottom:0, left:0},
                               
                                border:false,
                                plain:true,
                                width:'100%',
                                activeTab: 0,
                                height:235,
                                items:[
                                        this.megrid
                                       ]
                       },
                      
                    ]
            }
        ];
        
        Phx.vista.FormNota.superclass.constructor.call(this,config);
          
                
         var fieldset = this.form.items.items[0].items.items[2];
         
        /*fieldset.add({
         	xtype:'button',
         	text:'Copiar Datos',
         	scope:this,
         	handler:function(){
         	
         	//alert(this.Cmp.remitente.getValue())
         	//this.form.remitente.setValue('NORMAL');
         	this.Cmp.destinatario.setValue(this.Cmp.remitente.getValue());
         	this.Cmp.telf_destinatario.setValue(this.Cmp.telf_remitente.getValue());
         	//this.form.destinatario.value = this.form.remitente.getValue()
         	
         	}
         	
         });*/
         fieldset.doLayout();       
       
        
        this.init();  
        this.iniciarEventos();  
        this.loadValoresIniciales();
        
    
    },
    
    iniciarEventos : function () {
    	/*this.Cmp.sucursal_id.on('select',function(rec){ 
    		
    		
    		console.log('a');
    		
    		this.Cmp.id_factura.reset();
    		this.Cmp.liquidevolu.reset();
            this.Cmp.id_factura.store.baseParams.id_sucursal=this.Cmp.sucursal_id.getValue();            
            this.Cmp.id_factura.modificado=true;
            
            
            
            
            this.Cmp.id_factura.store.load({params:{start:0,limit:20}, 
		       callback : function (r) {	       				
		    		if (r.length > 0 ) {	       				
	    				this.Cmp.id_factura.setValue(r[0].data.sucursal_id);
	    			}     
		    			    		
		    	}, scope : this
		    });
            
            this.Cmp.id_factura.enable();            
            
           },this);*/
           
           
           
           
           /*this.Cmp.id_factura.on('select',function(combo,record){ 
    		
    		
    		
    		this.Cmp.autorizacion.setValue(record.data.desc_numero_dosificacion);
    		this.Cmp.fecha.setValue(record.data.fecha);
    		this.Cmp.nro_factura.setValue(record.data.nro_factura);
    		this.Cmp.nit.setValue(record.data.nit);
    		this.Cmp.tcambio.setValue(record.data.tcambio);
    		this.Cmp.moneda.setValue(record.data.desc_moneda);
    		this.Cmp.id_moneda.setValue(record.data.id_moneda);
    		this.Cmp.razon.setValue(record.data.razon);
            
    		this.megrid.enable();
    		
    		this.megrid.remove();
    		this.megrid.store.removeAll();  

    		
    		this.megrid.store.baseParams = {}; // limpio los parametro enviados
    		
            this.megrid.store.baseParams.id_factura=this.Cmp.id_factura.getValue(); 
            
             
           	this.megrid.store.load({params:{start:0,limit:20}, 
		       scope : this
		    });
            
            
           },this);*/
           
           
          
           
           this.Cmp.tipo_id.on('select',function(rec){ 
    		
            if(this.Cmp.tipo_id.getValue() == 'FACTURA'){
            	
            	console.log('llega')
            	//console.log(Ext.getCmp('input_pu'));
            	//console.log(Ext.getCmp('input_concepto'));
            	
            	
            	
            	this.resetear();
            	this.Cmp.liquidevolu.disable();
            	
            	
            	this.Cmp.nro_factura.show();
        		this.Cmp.autorizacion.show();
        		this.Cmp.nit.show();
        		this.Cmp.razon.show();
        		
        		this.Cmp.nro_factura.enable();
        		this.Cmp.razon.enable();
        		this.Cmp.nit.enable();
        		this.Cmp.fecha.enable();
        		this.Cmp.importe.enable();
        		this.Cmp.autorizacion.enable();
        		
        		this.Cmp.id_moneda.hide();
        		this.Cmp.pasajero.hide();
        		this.Cmp.boleto.hide();
        		this.Cmp.moneda.hide();
        		this.Cmp.tcambio.hide();
        		
        		
            	
            	this.megrid.enable();
            	
            	

            	//this.megrid.initialConfig.columns[3].editor.disabled = false;
            	//this.megrid.initialConfig.columns[3].editor.enable = true;
            	//console.log(this.megrid.initialConfig.columns[3].editor.disabled);
            	
            	this.megrid.initialConfig.columns[1].hidden = false;
            	this.megrid.getView().refresh(true);
            	
            	Ext.getCmp('input_pu').disabled=false;
            	
            	Ext.getCmp('input_pu').enable(true);
            	
            	
            	
            	
            }
            else if(this.Cmp.tipo_id.getValue() == 'BOLETO'){
            	
            	
            	
            	
            	console.log(this.megrid);
            	//this.megrid.initialConfig.columns[3].editor.disabled = true;
            	//this.megrid.initialConfig.columns[3].editor.enable = false;
            	//console.log(this.megrid.initialConfig.columns[3].editor.disabled);
            	
            	this.megrid.initialConfig.columns[1].hidden = true;
            	
            	this.megrid.getView().refresh(true);
            	
            	
            	
            	Ext.getCmp('input_pu').disabled=true;
            	Ext.getCmp('input_pu').addClass('x-item-disabled');
            	Ext.getCmp('input_pu').enable(false);
            	Ext.getCmp('input_pu').disable(false);
            	
            	//this.Cmp.boletos_id.enable();  
            	//this.Cmp.id_factura.disable(); 
            	
            	
            	this.Cmp.liquidevolu.disable();
            	
            	
            	this.resetear();
            	this.Cmp.nro_factura.show();
        		this.Cmp.autorizacion.show();
        		this.Cmp.nit.show();
        		this.Cmp.razon.show();
        		
        		this.Cmp.nro_factura.enable();
        		this.Cmp.razon.enable();
        		this.Cmp.nit.enable();
        		this.Cmp.fecha.enable();
        		this.Cmp.importe.enable();
        		
        		this.Cmp.id_moneda.hide();
        		this.Cmp.pasajero.hide();
        		this.Cmp.boleto.hide();
        		this.Cmp.moneda.hide();
        		this.Cmp.tcambio.hide();
        		this.Cmp.autorizacion.hide();
        		
        		
        		this.megrid.enable();
            	
            	


            }
            
            else if(this.Cmp.tipo_id.getValue() == 'LIQUIDACION'){
            	
            	
            	//this.megrid.initialConfig.columns[3].editor.disabled = true;
            	//this.megrid.initialConfig.columns[3].editor.enable = false;
            	//console.log(this.megrid.initialConfig.columns[3].editor.disabled);
            	
            	
            	
            	this.megrid.initialConfig.columns[1].hidden = true;
            	this.megrid.getView().refresh(true);
            	
            	Ext.getCmp('input_pu').disabled=true;
            	Ext.getCmp('input_pu').addClass('x-item-disabled');
            	Ext.getCmp('input_pu').enable(false);
            	Ext.getCmp('input_pu').disable(false);
            	
            	
            	this.resetear();
            	
            	this.Cmp.liquidevolu.enable();  
            	//this.Cmp.boletos_id.disable();  
            	//this.Cmp.id_factura.disable();  
            	
            	 //this.mostrarGrupo(9);
            	//this.mostrarGrupo(10);
            	
            	this.Cmp.nro_factura.hide();
        		this.Cmp.autorizacion.hide();
        		this.Cmp.nit.hide();
        		this.Cmp.razon.hide();
        		this.Cmp.id_moneda.hide();
        		
        		this.Cmp.pasajero.show();
        		this.Cmp.boleto.show();
        		this.Cmp.importe.show();
            }
                  
            
           },this);
           
           
           
          this.Cmp.liquidevolu.on('select',function(combo,record){ 
    		
    		
    		this.resetGroup(10);
    		this.megrid.enable();
    		//this.megrid.colModel.config[5].editor.disabled =true;
    		//console.log(this.megrid.colModel.config[5].isColumn);
    		
    		///this.megrid.colModel.config[5].isColumn = false;
    		this.megrid.remove();
    		this.megrid.store.removeAll(); 
    		
    		
    	
    		this.megrid.store.baseParams = {}; // limpio los parametro enviados
            this.megrid.store.baseParams.nroliqui=this.Cmp.liquidevolu.getValue();            
            //this.Cmp.id_factura.modificado=true;
            
            
           
           	this.megrid.store.load({params:{start:0,limit:20}, 
		       callback : function (r) {	       				
		    		console.log(r); 
		    		
		    			    		
		    	},scope : this
		    });
            

            
           },this);
           
           
           
           
          this.megrid.initialConfig.columns[3].editor.on('keyup',function(){   
          	
          	if(this.megrid.initialConfig.columns[2].editor.getValue() == 'BOLETO'){
          		
          	if(this.megrid.initialConfig.columns[3].editor.getValue().length == 13 && this.Cmp.tipo_id.getValue() != 'FACTURA MANUAL' )
       		{
       			
       			Phx.CP.loadingShow();
       			
       			
       			var cantidad_registros = this.megrid.store.getCount();
		    	var record;
		    	
		    	var arra = new Array();
		    	
		    	
		    	for (var i = 0; i < cantidad_registros; i++) {
		    		
		    		record = this.megrid.store.getAt(i);
		    		console.log(record)
					if(record.data.tipo == 'BOLETO'){
						
					
					arra[i] = new Object();
			        arra[i].billete = record.data.nro_billete
			        arra[i].tipo = record.data.tipo;
			        
			       }
					       
				}
       		
	       	
	       			//comienzo ajax
		         Ext.Ajax.request({
		         	
	         		
	                url : '../../sis_facturacion/control/Liquidevolu/listarBoletosExistente',
	                params:{'billete':this.megrid.initialConfig.columns[3].editor.getValue(),
	                		'datos_no_permitidos':Ext.encode(arra),
	                		'start':0,'limit':1},
	                success : function(resp){ 
	                	
	                	
	                	Phx.CP.loadingHide();
	                	var reg_new = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
	                	
	                	
	                	
	                	
	                		if(reg_new.datos){
	                			
	                			//editor.stopEditing();
	                			
	                			
	                		
	                		
	                			if(reg_new.datos != "DUPLICADO"){
	                	
	                			var Items = Ext.data.Record.create([{
							                        name: 'cantidad',
							                        type: 'int'
							                    },{
							                        name: 'tipo',
							                        type: 'string'
							                    },
							                    {
							                        name: 'Concepto',
							                        type: 'string'
							                    },{
							                        name: 'p/Unit',
							                        type: 'float'
							                    },{
							                        name: 'Importe Original',
							                        type: 'float'
							                    },{
							                        name: 'Importe a Devolver',
							                        type: 'float'
							                    },{
							                        name: 'Exento',
							                        type: 'float'
							                    },{
							                        name: 'Total Devuelto',
							                        type: 'float'
							                    },{
							                        name: 'nro_billete',
							                        type: 'string'
							                    },{
							                        name: 'nro_nit',
							                        type: 'string'
							                    },{
							                        name: 'razon',
							                        type: 'string'
							                    },{
							                        name: 'fecha_fac',
							                        type: 'string'
							                    },{
							                        name: 'nro_fac',
							                        type: 'string'
							                    },{
							                        name: 'nro_aut',
							                        type: 'string'
							                    }
							                    ]);
															                    
								  var es = new Items();
												
											
												  
									var total_de = reg_new.datos[0].MONTO - reg_new.datos[0].EXENTO;			    
													  
			    				es = new Items({
			                                cantidad:1,
			                                tipo:this.megrid.initialConfig.columns[2].editor.getValue(),
			                                
			                                concepto:reg_new.datos[0].BILLETE,
			                                precio_unitario:reg_new.datos[0].MONTO,
			                                importe_original:reg_new.datos[0].MONTO,
			                                importe_devolver:reg_new.datos[0].MONTO,
			                                exento:reg_new.datos[0].EXENTO,
			                                total_devuelto:total_de,
			                                nro_billete:reg_new.datos[0].BILLETE,
			                                nro_nit:reg_new.datos[0].NIT,
			                                razon:reg_new.datos[0].RAZON,
			                                fecha_fac:reg_new.datos[0].FECHA_FAC,
			                                nro_fac:reg_new.datos[0].BILLETE,
			                                nro_aut:1
			                                
			                            });					
			    										
												    
												    
								var se = this.megrid.getSelectionModel().getSelections();
	                            console.log(se);
	                            				 
							    this.mestore.insert(0, es);
                    			this.megrid.getView().refresh();
			                            				
	                            
	                            for(var i = 0, r; r = se[i]; i++){
	                            	
	                            	 
	                                this.mestore.remove(r);
	                            }
	                	
		                		}else{
		                			alert("Billete Duplicado en la vista");
		                		}
	                		       		  	               
				            } else {		                
				                alert('Ocurrio un error al obtener el billete de esta liquidacion')
				            }  
			                       	
	                },
	                failure : this.conexionFailure,
	                timeout : this.timeout,
	                scope : this
	         });
	         //fin ajax 2
       			
       			
       		}
       		}
          	
          },this);
          
          
           this.megrid.initialConfig.columns[2].editor.on('select',function(){ 
           	
        	 console.log('llega');
        	 
        	 if(this.megrid.initialConfig.columns[2].editor.getValue() == 'BOLETO'){
        	 	
        	 	//si escoge boleto en la grilla
        	 	
        	 	
            	Ext.getCmp('input_fac').addClass('x-item-disabled');
            	Ext.getCmp('input_fac').disable(true);
            	
            	Ext.getCmp('input_aut').addClass('x-item-disabled');
            	Ext.getCmp('input_aut').disable(true);	
            	
            	
            	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
            	
            
            	
            	
        	 }else if(this.megrid.initialConfig.columns[2].editor.getValue() == 'FACTURA'){
        	 	
        	 	Ext.getCmp('input_concepto').addClass('x-item-disabled');
            	Ext.getCmp('input_concepto').disable(true);	
        	 	
        	 	Ext.getCmp('input_fac').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_fac').enable(true);
        	 	
        	 	Ext.getCmp('input_aut').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_aut').enable(true);
        	 	
        	 	
        	 	
        	 }else if(this.megrid.initialConfig.columns[2].editor.getValue() == 'CONCEPTO'){
        	 	
        	 	
        	 	Ext.getCmp('input_fac').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_fac').enable(true);
        	 	
        	 	Ext.getCmp('input_aut').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_aut').enable(true);
        	 	
        	 	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
        	 }
           
           },this);
           
           
           
           //factura
           this.megrid.initialConfig.columns[5].editor.on('blur',function(){   
          		
          		
          		if(this.megrid.initialConfig.columns[2].editor.getValue() == 'FACTURA'){
       			Phx.CP.loadingShow();
       			
       			var cantidad_registros = this.megrid.store.getCount();
		    	var record;
		    	
		    	var arra = new Array();
		    	
		    	
		    	for (var i = 0; i < cantidad_registros; i++) {
		    		
		    		record = this.megrid.store.getAt(i);
					if(record.data.tipo == 'FACTURA'){
						
					
					arra[i] = new Object();
			        arra[i].nrofac = record.data.nro_fac;
			        arra[i].nroaut = record.data.nro_aut;
			        arra[i].tipo = record.data.tipo;
			        
			       }
					       
				}
		    		
				   
    			
       			
       			Ext.Ajax.request({
		         	
	         		
	                url : '../../sis_facturacion/control/Liquidevolu/listarFacturaDevolucion',
	                params:{'nrofac':this.megrid.initialConfig.columns[5].editor.getValue(),
	                		'nroaut':this.megrid.initialConfig.columns[4].editor.getValue(),
	                		'datos_no_permitidos':Ext.encode(arra),
	                		'start':0,'limit':1},
	                success : function(resp){ 
	                	
	                	
	                	Phx.CP.loadingHide();
	                	var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
	                	
	                	if(reg.datos == "DUPLICADO"){
	                		alert("datos duplicados en la vista intente con otra factura");
	                	}
	                	if(reg.datos){
	                		
	                		
	                		this.resetear();
	                		
        					this.Cmp.nit.setValue(reg.datos[0].nit);
        					this.Cmp.nro_factura.setValue(reg.datos[0].nrofac);
        					this.Cmp.autorizacion.setValue(reg.datos[0].nroaut);
        					this.Cmp.razon.setValue(reg.datos[0].razon);
        					this.Cmp.fecha.setValue(reg.datos[0].fecha);
	                		
	                		
	                		
			            	
			            	this.Cmp.nro_factura.show();
			        		this.Cmp.autorizacion.show();
			        		this.Cmp.nit.show();
			        		this.Cmp.razon.show();
			        		
			        		this.Cmp.nro_factura.enable();
			        		this.Cmp.razon.enable();
			        		this.Cmp.nit.enable();
			        		this.Cmp.fecha.enable();
			        		this.Cmp.importe.enable();
			        		this.Cmp.autorizacion.enable();
			        		
			        		this.Cmp.id_moneda.hide();
			        		this.Cmp.pasajero.hide();
			        		this.Cmp.boleto.hide();
			        		this.Cmp.moneda.hide();
			        		this.Cmp.tcambio.hide();
			        		
			        		
			        		if(reg.countData){
			        			
			        		
			        		var Items = Ext.data.Record.create([{
							                        name: 'cantidad',
							                        type: 'int'
							                    },{
							                        name: 'tipo',
							                        type: 'string'
							                    },
							                    {
							                        name: 'Concepto',
							                        type: 'string'
							                    },{
							                        name: 'p/Unit',
							                        type: 'float'
							                    },{
							                        name: 'Importe Original',
							                        type: 'float'
							                    },{
							                        name: 'Importe a Devolver',
							                        type: 'float'
							                    },{
							                        name: 'Exento',
							                        type: 'float'
							                    },{
							                        name: 'Total Devuelto',
							                        type: 'float'
							                    },{
							                        name: 'nro_billete',
							                        type: 'string'
							                    },{
							                        name: 'nro_nit',
							                        type: 'string'
							                    },{
							                        name: 'razon',
							                        type: 'string'
							                    },{
							                        name: 'fecha_fac',
							                        type: 'string'
							                    },{
							                        name: 'nro_fac',
							                        type: 'string'
							                    },{
							                        name: 'nro_aut',
							                        type: 'string'
							                    }
							                    ]);
															                    
								  var es = new Items();
												
											
												  
										    
								
								for (var i = 0; i <reg.countData.length; i++) {				  
			    				es = new Items({
			                                cantidad:1,
			                                tipo:'FACTURA',
			                                
			                                concepto:reg.countData[i].concepto,
			                                precio_unitario:reg.countData[i].precio_unitario,
			                                importe_original:reg.countData[i].importe_original,
			                                importe_devolver:reg.countData[i].importe_original,
			                                exento:0,
			                                total_devuelto:0,
			                                nro_billete:reg.countData[i].concepto,
			                                nro_nit:reg.countData[i].nit,
			                                razon:reg.countData[i].razon,
			                                fecha_fac:reg.countData[i].fecha,
			                                nro_fac:reg.datos[0].nrofac,
			                                nro_aut:reg.datos[0].nroaut
			                                
			                            });	
			                            
			                             this.mestore.insert(0, es);
			                             this.megrid.getView().refresh();
                    			
							    
								}				    
												    
								var se = this.megrid.getSelectionModel().getSelections();
	                            console.log(se);
	                            
							   
							    
                    			
                    			
	                       
			                            				
	                            
	                            for(var i = 0, r; r = se[i]; i++){
	                            	
	                            	 
	                                this.mestore.remove(r);
	                            }
	                            
	                            
                    			
                    			}
			        		
			        		
	                	}
	                	
	                	
	                	
			    
	                },
	                failure : this.conexionFailure,
	                timeout : this.timeout,
	                scope : this
	           })
       			
	       		
			    
			    Phx.CP.loadingHide();
	       	
	       		}//end if si es factura	
	       		else if(this.megrid.initialConfig.columns[2].editor.getValue() == 'CONCEPTO'){
	       			
	       			//relaciono su nit y razon dependiendo su nroaut y nrofac para que se llene automarico esos campos nuevos
	       			var cantidad_registros = this.megrid.store.getCount();                
		           for (var i = 0; i < cantidad_registros; i++) {
	    		
		    		record = this.megrid.store.getAt(i);
					
					
					
					if(record.data.nro_fac == this.megrid.initialConfig.columns[5].editor.getValue())
					
							if(record.data.nro_aut == this.megrid.initialConfig.columns[4].editor.getValue()){
								this.megrid.initialConfig.columns[14].editor.setValue(record.data.nro_nit);
								this.megrid.initialConfig.columns[15].editor.setValue(record.data.razon);
							}
					       
		        
	    			}
	    			
	    			
		       			
		       	}
          	
          },this);
          
          
          
          
          //para cambiar el nit y razon de las columnas
           this.megrid.initialConfig.columns[14].editor.on('blur',function(){
     			
     			var se = this.megrid.getSelectionModel().getSelections();
	                       
	                            
	            var cantidad_registros = this.megrid.store.getCount();                
	           for (var i = 0; i < cantidad_registros; i++) {
    		
	    		record = this.megrid.store.getAt(i);
				
				
				if((record.data.nro_fac == se[0].data['nro_fac'])  && (record.data.nro_aut = se[0].data['nro_aut'])){
					record.data.nro_nit = this.megrid.initialConfig.columns[14].editor.getValue();
					record.data.razon = this.megrid.initialConfig.columns[15].editor.getValue();
				}
			
				       
	        
    			}
    			this.megrid.getView().refresh();
    			console.log(this.megrid);
     			
           },this);
           
           
           this.megrid.initialConfig.columns[15].editor.on('blur',function(){
     		
     			var se = this.megrid.getSelectionModel().getSelections();
	                            console.log(se[0].data['nro_aut']);
	                            console.log(se[0].data['nro_fac']);
	                            
	            var cantidad_registros = this.megrid.store.getCount();                
	           for (var i = 0; i < cantidad_registros; i++) {
    		
	    		record = this.megrid.store.getAt(i);
			
				if((record.data.nro_fac == se[0].data['nro_fac'])  && (record.data.nro_aut = se[0].data['nro_aut'])){
					record.data.nro_nit = this.megrid.initialConfig.columns[14].editor.getValue();
					record.data.razon = this.megrid.initialConfig.columns[15].editor.getValue();
				}
			  
	        
    			}
    			this.megrid.getView().refresh();
    			console.log(this.megrid);
     			
           },this);
           
           
           
          
           
          /*
           this.megrid.initialConfig.columns[5].editor.on('keyup',function(){ 
           	
           var devol = this.megrid.initialConfig.columns[5].editor.getValue() - this.megrid.initialConfig.columns[6].editor.getValue(); 
           console.log(devol);
          
	       this.megrid.initialConfig.columns[7].editor.setValue(devol);
	                         
           
           },this);
           
           
          this.megrid.initialConfig.columns[6].editor.on('keyup',function(){ 
           	
           	var devol = this.megrid.initialConfig.columns[5].editor.getValue() - this.megrid.initialConfig.columns[6].editor.getValue(); 
           console.log(devol);
          
	       this.megrid.initialConfig.columns[7].editor.setValue(devol);
          },this);
          
          
          
          
          this.megrid.initialConfig.columns[3].editor.on('keyup',function(){ 
           	
           		var total = this.megrid.initialConfig.columns[1].editor.getValue() * this.megrid.initialConfig.columns[3].editor.getValue(); 
           console.log(total);
           
           this.megrid.initialConfig.columns[4].editor.setValue(total);
          	
          },this);
          */
          
          /*this.megrid.initialConfig.columns[1].editor.on('keyup',function(){ 
           	
           		var total = this.megrid.initialConfig.columns[1].editor.getValue() * this.megrid.initialConfig.columns[3].editor.getValue(); 
           console.log(total);
           
           this.megrid.initialConfig.columns[4].editor.setValue(total);
          	
          },this);
          */
          
          
          
          
           
           
          
           
           
           
           
       /*this.Cmp.tipo_factura.on('select',function(){        	
       		if (this.Cmp.tipo_factura.getValue() == 'AUTOMATICO') {
       			this.ocultarGrupo(7);
		        this.Cmp.id_dosificacion.allowBlank = true;
		        this.Cmp.nro_factura.allowBlank = true;
		        this.Cmp.fecha_factura.allowBlank = true;
       		} else {
       			this.mostrarGrupo(7);
		        this.Cmp.id_dosificacion.allowBlank = false;
		        this.Cmp.nro_factura.allowBlank = false;
		        this.Cmp.fecha_factura.allowBlank = false;
		       
	            this.Cmp.id_dosificacion.store.load({params:{start:0,limit:20}, 
			       callback : function (r) {	       				
			    		if (r.length > 0 ) {	
			    			       				
		    				this.Cmp.id_dosificacion.setValue(r[0].data.id_dosificacion);
		    			}     
			    			    		
			    	}, scope : this
			    });	
       		} 		            
            
           },this);*/
           
       /*this.Cmp.tipo.on('select',function(){        	
       		if (this.Cmp.tipo.getValue() == 'NORMAL') {
       			this.mostrarGrupo(6);
		        this.Cmp.tipo_factura.allowBlank = false;
		        this.Cmp.nit.allowBlank = false;
		        this.Cmp.nombre.allowBlank = false;
       		} else {       			
       			this.ocultarGrupo(6);
		        this.Cmp.tipo_factura.allowBlank = true;
		        this.Cmp.nit.allowBlank = true;
		        this.Cmp.nombre.allowBlank = true;	
		        this.Cmp.nit.reset();
		        this.Cmp.nombre.reset();	            
       		} 		            
            
           },this);*/
    },
    
    
    loadValoresIniciales:function()
    { 
    	console.log(this.megrid);
    	
    	//this.megrid.topToolbar.disabled = true;
    	//this.megrid.toolbars.disabled = true;
        /*var dom = Ext.select('.x-tab-panel');
    		console.log(dom.elements[1]);
    		//dom.elements[1]
    		Ext.get(dom.elements[1].id).setStyle('margin-left','50px');
        
        */	            
    },
    

    
                  
    
    Atributos:[     
       
        
        {
            config:{
                name: 'tipo_id',
                fieldLabel: 'Tipo',
                allowBlank: true,
                emptyText:'Tipo...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                store:['FACTURA','LIQUIDACION','BOLETO'],
                width:200 
            },
                type:'ComboBox',
                id_grupo:1,
                form:true
        },
        /*
        {
			config: {
				name: 'sucursal_id',
				fieldLabel: 'Sucursal',
				allowBlank: true,
				emptyText: 'Sucursal...',
				store: new Ext.data.JsonStore({
					url: '../../sis_ventas/control/Sucursal/listarSucursal',
					id: 'id_sucursal',
					root: 'datos',
					sortInfo: {
						field: 'estacion',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_sucursal', 'estacion'],
					remoteSort: true,
					baseParams: {par_filtro: 'estacion'}
				}),
				valueField: 'id_sucursal',
				displayField: 'estacion',
				hiddenName: 'id_sucursal',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				width:150,				
				minChars: 2
			},
			type: 'ComboBox',
			id_grupo: 1,			
			form: true
		},*/
        
       /* {
	       			config:{
	       				name:'id_factura',
	       				
	       				fieldLabel:'factura',
	       				allowBlank:true,
	       				
	       				emptyText:'Persona...',
	       				store: new Ext.data.JsonStore({

	    					url:'../../sis_facturacion/control/Factura/listarFactura',
	    					id: 'id_factura',
	    					root: 'datos',
	    					
	    					sortInfo:{
	    						field: 'nro_factura',
	    						direction: 'ASC'
	    					},
	    					totalProperty: 'total',
	    					fields: ['id_factura','nro_factura','fecha','nit','razon','desc_numero_dosificacion','desc_moneda','tcambio','id_moneda','razon'],
	    					// turn on remote sorting
	    					remoteSort: true,
	    					baseParams:{par_filtro:'nro_factura#fecha'}
	    				}),
	       				valueField: 'id_factura',
	       				displayField: 'nro_factura',
	       				
	       				tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>NIT:</b>{nro_factura}</p><p><b>Fecha:</b>{fecha}</p> </div></tpl>',
	       				hiddenName: 'id_factura',
	       				forceSelection:true,
	       				typeAhead: true,
	           			triggerAction: 'all',
	           			lazyRender:true,
	       				mode:'remote',
	       				pageSize:10,
	       				queryDelay:1000,
	       				width:200,
	       				gwidth:280,
	       				minChars:2,
	       				disabled:true,
	       				turl:'../../../sis_seguridad/vista/persona/Persona.php',
	       			    ttitle:'Personas',
	       			   // tconfig:{width:1800,height:500},
	       			    tdata:{},
	       			    tcls:'persona',
	       			    pid:this.idContenedor,
	       			
	       				renderer:function (value, p, record){return String.format('{0}', record.data['nro_factura']);}
	       			},
	       			type: 'ComboBox',
			id_grupo: 3,			
			form: true
	    },*/
	    
        /*{
			config: {
				name: 'id_factura',
				fieldLabel: 'Facturas',
				allowBlank: false,
				emptyText: 'Factura...',
				store: new Ext.data.JsonStore({
					url: '../../sis_facturacion/control/Factura/listarFactura',
					id: 'id_factura',
					root: 'datos',
					sortInfo: {
						field: 'nro_factura',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_factura', 'nro_factura'],
					remoteSort: true,
					baseParams: {par_filtro: 'nro_factura'}
				}),
				valueField: 'id_factura',
				displayField: 'nro_factura',
				hiddenName: 'id_factura',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				width:100,				
				minChars: 2,
				disabled:true
			},
			type: 'ComboBox',
			id_grupo: 3,			
			form: true
		},*/
		/*{
            config:{
                name: 'boletos_id',
                fieldLabel: 'Boletos',
                allowBlank: true,
                emptyText:'Boleto...',
                typeAhead: true,
                triggerAction: 'all',
                lazyRender:true,
                mode: 'local',
                store:['AUTOMATICO','MANUAL'],
                width:150,
                disabled:true
            },
                type:'ComboBox',
                id_grupo:4,
                form:true
        },*/
        
       {
	       			config:{
	       				name:'liquidevolu',
	       				
	       				fieldLabel:'LiquiDevolu',
	       				allowBlank:true,
	       				
	       				emptyText:'Liquidacion...',
	       				store: new Ext.data.JsonStore({
	    					url:'../../sis_facturacion/control/Liquidevolu/listarLiquidevolu',
	    					id: 'nroliqui',
	    					root: 'datos',
	    					
	    					sortInfo:{
	    						field: 'nroliqui',
	    						direction: 'ASC'
	    					},
	    					totalProperty: 'total',
	    					fields: ['nroliqui','estacion','pais'],
	    					// turn on remote sorting
	    					remoteSort: true,
	    					baseParams:{par_filtro:'nroliqui#nroliqui'}
	    				}),
	       				valueField: 'nroliqui',
	       				displayField: 'nroliqui',
	       				
	       				tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>Nro Liqui:</b>{nroliqui}</p><p><b><i class="fa fa-university"></i>Estacion:</b>{estacion}</p> </div></tpl>',
	       				hiddenName: 'nroliqui',
	       				forceSelection:true,
	       				typeAhead: true,
	           			triggerAction: 'all',
	           			lazyRender:true,
	       				mode:'remote',
	       				pageSize:10,
	       				queryDelay:1000,
	       				width:220,
	       				gwidth:280,
	       				minChars:2,
	       				
	       				disabled:true,
	       				//turl:'../../../sis_seguridad/vista/persona/Persona.php',
	       			    //ttitle:'Personas',
	       			   // tconfig:{width:1800,height:500},
	       			    tdata:{},
	       			    tcls:'persona',
	       			    pid:this.idContenedor,
	       			
	       				renderer:function (value, p, record){return String.format('{0}', record.data['nroliqui']);}
	       			},
	       			type: 'ComboBox',
			id_grupo: 2,			
			form: true
	    },
        
        
       
       
       
       
		{
            config:{
                fieldLabel: "Autorizacion",
                name: 'autorizacion', 
                allowBlank: true,               
                maxLength:150,
                width:200,
                disabled:true                   
            },
            type:'TextField',
            id_grupo: 8,
            form:true 
        },
        {
            config:{
                fieldLabel: '<i class="fa fa-barcode"></i> Factura',
                name: 'nro_factura',
                maxLength:150,
                width:200 ,
                disabled:true,
                allowBlank:true             
            },
            type:'NumberField',
            id_grupo: 6,
            form:true 
        }
        
        ,{
			config:{
				name: 'fecha',
				fieldLabel: '<i class="fa fa-calendar"></i> Fecha',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'factu.fecha_reg',type:'date'},
				id_grupo:7,
				form:true 
		}
        
        
        
       
        ,{
            config:{
                fieldLabel: '<i class="fa fa-user"></i> Moneda ',
                name: 'moneda',
                width:200,
                disabled:true                
            },
            type:'TextField',
            id_grupo: 6,
            form:true 
        }
        ,{
            config:{
                fieldLabel: "id_moneda",
                name: 'id_moneda',
                width:200,
                disabled:true                
            },
            type:'TextField',
            id_grupo: 8,
            form:true 
        }
        
         ,{
            config:{
                fieldLabel: "TCambio",
                name: 'tcambio',
                width:200,
                disabled:true                
            },
            type:'TextField',
            id_grupo: 8,
            form:true 
        }
         ,{
            config:{
                fieldLabel: "NIT",
                name: 'nit',
                width:200,
                disabled:true,
                allowBlank: true,                
            },
            type:'TextField',
            id_grupo: 6,
            form:true 
        }
        ,{
            config:{
                fieldLabel: '<i class="fa fa-user"></i> Razon',
                name: 'razon',
                width:200,
                disabled:true ,
                allowBlank: true,               
            },
            type:'TextField',
            id_grupo: 7,
            form:true 
        }
        
        
        
        
        //para la liquidacion form
        
         ,{
            config:{
                fieldLabel: '<i class="fa fa-user"></i> Pasajero',
                name: 'pasajero',
                width:200,
                disabled:true            
            },
            type:'TextField',
            id_grupo: 6,
            form:true 
        }
        ,{
            config:{
                fieldLabel: "Boleto",
                name: 'boleto',
                width:200,
                disabled:true                
            },
            type:'TextField',
            id_grupo: 7,
            form:true 
        }
        ,{
            config:{
                fieldLabel: '<i class="fa fa-money"></i> Importe',
                name: 'importe',
                width:200,
                disabled:true,
                allowBlank: true             
            },
            type:'TextField',
            id_grupo: 8,
            form:true 
        }
        
        
        
        
       /* ,{
            config:{
                fieldLabel: "Liqui",
                name: 'liqui_concepto',
                width:500,
                disabled:true                
            },
            type:'TextField',
            id_grupo: 9,
            form:true 
        }
        
         ,{
            config:{
                fieldLabel: "Exen",
                name: 'exe',
                width:150,
                disabled:false                
            },
            type:'TextField',
            id_grupo: 10,
            form:true 
        }*/
        
        
     
     ],
    title:'Formulario de Recepción',
    
    getVistaPreviaHtml : function () {
    	
    	var th = '"background-color:#c5d6ec;font-weight:bold;border:1px solid #ffffff; border-width:0px 1px 1px 0px; text-align:left;padding:5px;font-family:Arial;font-weight:normal;;  font-size:12px;font-weight:bold;"';
    	var td = '"border:1px solid #ffffff; border-width:0px 1px 1px 0px; text-align:left;padding:3px;font-family:Arial;font-weight:normal;  font-size:12px;"';    	
		
    	
    	if (this.Cmp.tipo_id.getValue() == 'FACTURA') {
    		
			var table = '"border-collapse: collapse;border-spacing: 0;width:100%;height:100%;margin:0px;padding:0px;-moz-border-radius-topright:11px;-webkit-border-top-right-radius:11px;border-top-right-radius:11px;"';
    		var html = '<table align="right" style =' + table + '>';    	
	    	html += '<tr><th style =' + th + '>Tipo</th><td style =' + td + '>' + this.Cmp.tipo_id.getValue() + '</td><th style =' + th + '>Destino</th><td style =' + td + '>HOLA</td></tr>';
	    	html += '<tr><th style =' + th + '>Numero</th><td style =' + td + '>' + this.Cmp.nro_factura.getValue() + '</td><th style =' + th + '>Suc. Destino</th><td style =' + td + '>HOLA</td></tr>';
	    	html += '<tr><th style =' + th + '>Fecha</th><td style =' + td + '>' + this.Cmp.fecha.getValue() + '</td><td></td><td style =' + td + '></td></tr>';
   
    	} 
    	else if(this.Cmp.tipo_id.getValue() == 'LIQUIDACION' || this.Cmp.tipo_id.getValue() == 'BOLETO MANUAL' || this.Cmp.tipo_id.getValue() == 'FACTURA MANUAL') {
    		var table = '"border-collapse: collapse;border-spacing: 0;width:100%;height:100%;margin:0px;padding:0px;-moz-border-radius-topright:11px;-webkit-border-top-right-radius:11px;border-top-right-radius:11px;"';
    		var html = '<table align="right" style =' + table + '>';    	
	    	html += '<tr><th style =' + th + '>Tipo</th><td style =' + td + '>' + this.Cmp.tipo_id.getValue() + '</td><th style =' + th + '>Boleto</th><td style =' + td + '>'+ this.Cmp.boleto.getValue() +'</td></tr>';
	    	html += '<tr><th style =' + th + '>Numero Liquidacion</th><td style =' + td + '>' + this.Cmp.liquidevolu.getValue() + '</td><th style =' + th + '>Importe</th><td style =' + td + '>'+ this.Cmp.importe.getValue() +'</td></tr>';
	    	html += '<tr><th style =' + th + '>Fecha</th><td style =' + td + '>' + this.Cmp.fecha.getValue() + '</td><th style =' + th + '>Pasajero</th><td style =' + td + '>'+ this.Cmp.pasajero.getValue() +'</td></tr>';
	   		
	   		html += '<tr align="right"><th style =' + th + ' colspan="5" align="right"><center>DATOS DE LA TRANSACCIÓN ORIGINAL</center></th></tr>';
	   		
	   		html += '<tr><th style =' + th + 'colspan="2">Concepto</th><th style =' + th + 'colspan="1">P/UNIT</th><th style =' + th + 'colspan="1">IMPORTE</th><th style =' + th + ' colspan="1">Total General</th></tr>';
	   		
    		
    	}
    	else {
    		var table = '"border-collapse: collapse;border-spacing: 0;width:100%;height:100%;margin:0px;padding:0px;-moz-border-radius-topright:11px;-webkit-border-top-right-radius:11px;border-top-right-radius:11px;"';
    	}
    	
    	
		
    	 	//html += '<tr><th style =' + th + '>NIT</th><td style =' + td + '>' + this.Cmp.nit.getValue() + '</td><th style =' + th + '>NOMBRE</th><td style =' + td + '>' + this.Cmp.nombre.getValue() + '</td></tr>';
    	//html += '<tr><th style =' + th + '>REMITENTE</th><td style =' + td + '>' + this.Cmp.remitente.getValue() + '</td><th style =' + th + '>TELEFONO</th><td style =' + td + '>' + this.Cmp.telf_remitente.getValue() + '</td></tr>';
    	//html += '<tr><th style =' + th + '>DESTINATARIO</th><td style =' + td + '>' + this.Cmp.destinatario.getValue() + '</td><th style =' + th + '>TELEFONO</th><td style =' + td + '>' + this.Cmp.telf_destinatario.getValue() + '</td></tr>';
    	//detalle
    		
    	
    	var cantidad_registros = this.megrid.store.getCount();
    	var record;
    	this.montototal=0;
    	var pesototal=0;
    	this.detalle = '';
    	for (var i = 0; i < cantidad_registros; i++) {
    		
    		record = this.megrid.store.getAt(i);
    		//this.montototal = this.montototal + record.data.total;
    		//pesototal =  pesototal + record.data.peso;
    		if (this.Cmp.tipo_id.getValue() == 'FACTURA') {
	    		if(record.data.exento != undefined){
	    		html += '<tr align="right"><th style =' + th + ' colspan="4" align="right"><center>DETALLE DE LA DEVOLUCION O RESCICION DEL SERVICIO</center></th></tr>';
    			html += '<tr><th style =' + th + '>CANT</th><th style =' + th + '>CONCEPTO</th><th style =' + th + '>P/UNIT</th><th style =' + th + '>IMPORTE</th><th style =' + th + '>DEVUELTO</th></tr>';
    
	    		html += '<tr><td style =' + td + '>' + record.data.cantidad + '</td><td style =' + td + '>' + record.data.concepto + '</td><td style =' + td + '>' + record.data.precio_unitario + '</td><td style =' + td + '>'+ record.data.importe + '</td><td style =' + td + '>'+ record.data.exento + '</td>';
	            //this.detalle = ' ' + record.data.total + ' ' + record.data.detalle;
	           	}
           }
           else if(this.Cmp.tipo_id.getValue() == 'LIQUIDACION' || this.Cmp.tipo_id.getValue() == 'BOLETO MANUAL' || this.Cmp.tipo_id.getValue() == 'FACTURA MANUAL') {
           	
           		if(record.data.exento != undefined || record.data.exente != ""){

           		html += '<tr><td style =' + td + ' colspan="2" align="right"><center>' + record.data.concepto + '</center></td><td style =' + td + ' colspan="1" align="right"><center>' + record.data.precio_unitario + '</center></td><td style =' + td + ' colspan="1">' + record.data.importe_original + '</td><td style =' + td + ' colspan="1">' + record.data.importe_original + '</td></tr>';
	            
	            }
           }
    	} 
    	
    	html += '<tr align="right"><th style =' + th + ' colspan="5" align="right"><center>DETALLE DE LA DEVOLUCION O RESCICION DEL SERVICIO</center></th></tr>';
	   		
	   	html += '<tr><th style =' + th + 'colspan="2">Concepto</th><th style =' + th + 'colspan="1">Monto Total</th><th style =' + th + 'colspan="1">EXENTO</th><th style =' + th + ' colspan="1">Total Devuelto</th></tr>';
	   		   	
    	
    	for (var i = 0; i < cantidad_registros; i++) {
    		
    		record = this.megrid.store.getAt(i);
    		//this.montototal = this.montototal + record.data.total;
    		//pesototal =  pesototal + record.data.peso;
    		if (this.Cmp.tipo_id.getValue() == 'FACTURA') {
	    		if(record.data.exento != undefined){
	    		html += '<tr align="right"><th style =' + th + ' colspan="4" align="right"><center>DETALLE DE LA DEVOLUCION O RESCICION DEL SERVICIO</center></th></tr>';
    			html += '<tr><th style =' + th + '>CANT</th><th style =' + th + '>CONCEPTO</th><th style =' + th + '>P/UNIT</th><th style =' + th + '>IMPORTE</th><th style =' + th + '>DEVUELTO</th></tr>';
    
	    		html += '<tr><td style =' + td + '>' + record.data.cantidad + '</td><td style =' + td + '>' + record.data.concepto + '</td><td style =' + td + '>' + record.data.precio_unitario + '</td><td style =' + td + '>'+ record.data.importe + '</td><td style =' + td + '>'+ record.data.exento + '</td>';
	            //this.detalle = ' ' + record.data.total + ' ' + record.data.detalle;
	           	}
           }
           else if(this.Cmp.tipo_id.getValue() == 'LIQUIDACION' || this.Cmp.tipo_id.getValue() == 'BOLETO MANUAL' || this.Cmp.tipo_id.getValue() == 'FACTURA MANUAL') {
           	
           		if(record.data.exento != undefined || record.data.exente != ""){

           		html += '<tr><td style =' + td + ' colspan="2" align="right"><center>' + record.data.concepto + '</center></td><td style =' + td + ' colspan="1" align="right"><center>' + record.data.importe_devolver+ '</center></td><td style =' + td + ' colspan="1">' + record.data.exento + '</td><td style =' + td + ' colspan="1">' + record.data.total_devuelto + '</td></tr>';
	            
	            }
           }
    	} 
    	
    	//html += '<tr><td style =' + th + '>	Total</td><td style =' + th + '></td><td style =' + th + '>'+ pesototal+ '</td><td style =' + th + '>'+ this.montototal  + '</td>';
    	html += '</table>';
    	return html;
    },
    
    
    onSubmit: function(o) {
    	//this.win.html = this.getVistaPreviaHtml();    	
    	this.win.show();
    	this.win.body.update(this.getVistaPreviaHtml());
    	this.o = o;
    
         
        
    },
    successSave:function(resp) { 
    		
    	Phx.CP.loadingHide();		
        var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        
        console.log(objRes.datos)
        //this.generarReportesApplet(objRes); 
        this.onReset();     
        
     
        Ext.Ajax.request({
		        url:'../../sis_facturacion/control/Nota/generarNota',
		        params:{'notas':objRes.datos},
		        success: this.successExport,
		        failure: this.conexionFailure,
		        timeout:this.timeout,
		        scope:this
		    });
		
    },
    
    
	successExport:function(resp){
		
		Phx.CP.loadingHide();
		
		
		
		//doc.write(texto);
        var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        
         //console.log(objRes.ROOT.datos[0].length)
         
        
        
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
	        console.log(ifrm);
	        i++;
        
        });
        
        
        this.Cmp.liquidevolu.disable();  
    	//this.Cmp.boletos_id.disable();  
    	this.Cmp.id_factura.disable();  
    	this.megrid.disable();
		//window.open(objRes.ROOT.datos);
		

    
    	//creacion de una pagina para imprimir el resultado de la nota
    	//este documento mostrara en pantalla la nota
    	
		/*var win = window.open("", "win", "width=300,height=200");
         win.document.open("text/html", "replace");
         win.document.write(texto);
        win.document.close();
        */
       
       //creacion de un iframe oculto que no mostrara el codigo html simplemente ejecutara
       
        /*ifrm = document.createElement("IFRAME");
        ifrm.name = 'mifr';
        ifrm.id = 'mifr';
        document.body.appendChild(ifrm);
       	var doc = window.frames['mifr'].document;
		doc.open();
		doc.write(texto);
		doc.close();
        console.log(ifrm);*/
        
      
		
	},
    
    guardar : function () {
    	this.win.hide();
    	
    	console.log(this.mestore);
    	console.log(this.megrid.store)
    	var cantidad_registros = this.megrid.store.getCount();
    	var record;
    	
    	var arra = new Array();
    	
    	
    	for (var i = 0; i < cantidad_registros; i++) {
    		
    		record = this.megrid.store.getAt(i);
    		
    				
			arra[i] = new Object();
	        arra[i].nroliqui = record.data.nro_liqui;
	        arra[i].concepto = record.data.concepto;
	        arra[i].precio_unitario = record.data.precio_unitario;
	        arra[i].importe_original = record.data.importe_original;
	        arra[i].importe_devolver = record.data.importe_devolver;
	        arra[i].exento = record.data.exento;
	        arra[i].total_devuelto = record.data.total_devuelto;
	        arra[i].nro_billete = record.data.nro_billete;
	        arra[i].nro_nit = record.data.nro_nit;
	        arra[i].razon = record.data.razon;
	        
	        arra[i].nrofac = record.data.nro_fac;
	         arra[i].nroaut = record.data.nro_aut;
	          arra[i].tipo = record.data.tipo;
			       
    			
		        
    		
	        
    	}
    	
    	//console.log(arra);
    	
    	
        this.argumentExtraSubmit = {'newRecords':Ext.encode(arra)};
        Phx.vista.FormNota.superclass.onSubmit.call(this,this.o); 
        
        //para limpiar despues de guardar  
    },
    closeWin : function () {
    	this.win.hide();
    },
    
    onReset:function(){
    	//this.Cmp.idd_sucursal.setDisabled(true);
        Phx.vista.FormNota.superclass.onReset.call(this);  
        this.mestore.rejectChanges();     
        this.mestore.removeAll(false);  
    },
    
     resetear:function(){
           	
   		this.resetGroup(6);
    	this.resetGroup(7);
    	this.resetGroup(8);
    	//this.resetGroup(9);
    	//this.resetGroup(10);
    	//this.megrid.remove();
		//this.megrid.store.removeAll();
     },
    
    
}
)    
</script>