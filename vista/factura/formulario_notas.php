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
                
                width:200,
                height:100,
                closeAction:'hide',
                title:'Vista Previa de Notas de credito y debito',
                preventBodyReset: true,            
				html: '<h1>This should be the way you expect it!</h1>',
                buttons: [
                
                {
                    text:'<i class="fa fa-check"></i> Aceptar',                    
                    handler: this.guardar,
                    
                    scope: this
                },{
                    text: '<i class="fa fa-times"></i> Cancelar',
                    handler: this.closeWin,
                    scope:this
                }]
            }); 
            
            
           this.tabs = new Ext.TabPanel({
				    
				    activeTab: 0,
				    
				    enableTabScroll:true,
				    listeners: {
				    	scope: this,
				    	
					    tabchange: function()  {
					    	
					    	
					       //console.log()
					      if(this.tabs.activeTab != undefined){
					      	var nrofac = this.tabs.activeTab.id
					       this.megrid.store.filter("nro_fac",nrofac);
					       
						      if(this.tabs.activeTab.name == 1){ // se fija si es boleto para bloquear agregar concepto
						      	this.megrid.addCon.disabled = true;
						      	this.megrid.removeBtn.disabled = true;
						      	
						      }else{
						      	this.megrid.addCon.disabled = false;
						      	this.megrid.removeBtn.disabled = false;
						      }
						      
					      
					      }
						     
					    },
					    remove:function(a,b){
					    	
					    	console.log(b.id)
					    	
					    	
	                       this.megrid.store.filter("nro_fac",b.id);
	                       
	                      
	                       
	                       var fil = this.megrid.getStore();
	                       console.log(fil.data);
	                       var count = fil.data.getCount();
	                      
	                       fil.each( function(rec){
	                       	 this.mestore.remove(rec);
	                       	
	                       }, this )
	                       
	                       this.megrid.store.clearFilter();
	                       if(this.tabs.activeTab != undefined){
					      	var nrofac = this.tabs.activeTab.id
					       this.megrid.store.filter("nro_fac",nrofac);
					      }
					    	
					    },
					    
					  },
				    plugins: new Ext.ux.TabCloseMenu(),
				    items: [/*{
				        title: 'Tab 1',
				        html: 'A simple tab'
				    },{
				        title: 'Tab 2',
				        html: 'Another one'
				    }*/]
				});
			         
        this.addTabsBtn =  new Ext.Button({
	        text: '<i class="fa fa-files-o fa-1g"></i> Agregar Nueva Nota',
	        handler: this.addTab,
	        height:200,
	         scope:this
	        //iconCls:'new-tab'
	    }); 
	    
	    
	  this.win_pop = new Ext.Window(
	    {
	        layout: 'fit',
	        width: 339,
	        height: 191,
	        modal: true,
	        closeAction: 'hide',
	        
	        items: new Ext.FormPanel({
		        labelWidth: 75, // label settings here cascade unless overridden
		        
		        frame:true,
		        title: 'Nueva Nota',
		        bodyStyle:'padding:5px 5px 0',
		        width: 339,
		        defaults: {width: 191},
		       // defaultType: 'textfield',
		
		        items: [{
		        		xtype:'combo',
		                fieldLabel: 'Tipo',
		                name: 'tipo_pop',
		               
		                allowBlank: true,
		                emptyText:'Tipo...',
		                typeAhead: true,
		                triggerAction: 'all',
		                lazyRender:true,
		                mode: 'local',
		                store:['FACTURA','BOLETO'],
		            },{
		            	xtype:'textfield',
		                fieldLabel: 'Autorizacion',
		                name: 'autorizacio_pop'
		            },{
		            	xtype:'textfield',
		                fieldLabel: 'Factura',
		                name: 'factura_pop'
		            }
		        ],
		
		        /*buttons: [{
		            text: 'Save'
		        },{
		            text: 'Cancel'
		        }]*/
		    }),
	        buttons: [
                
                {
                    text:'<i class="fa fa-check"></i> Aceptar',                    
                    handler: this.buscar_,
                    
                    scope: this
                },{
                    text: '<i class="fa fa-times"></i> Cancelar',
                    handler: this.closeWin_pop,
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
							'nro_fac','nro_aut','nro_nit','concepto_original'],
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
                    title:'DATOS DE LA DEVOLUCION O RESCICION DEL SERVICIO',
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
                        ref: '../addCon',
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
			                store:[/*'FACTURA','BOLETO',*/'FACTURA'],
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
                        
                        hidden: true,
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
                      
                        hidden: true,
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
        this.paddingForm = '0 0 5 0';
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
                                xtype:'panel',
                                //layout:'fit',
                                /*padding:'0 0 0 50',*/
                                margins:{top:0, right:0, bottom:0, left:0},
                               
                                border:false,
                                plain:true,
                                width:'100%',
                                activeTab: 0,
                                defaults:{autoHeight: true},
                                items:[
                                		this.addTabsBtn,
                                        this.tabs
                                       ]
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
          
                
      
       
        
        this.init();  
        this.iniciarEventos();  
        this.loadValoresIniciales();
        
    
    },
    
    iniciarEventos : function () {
    	
    	
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
            	
    	
           
           
           
           
           
          
           
           this.Cmp.tipo_id.on('select',function(rec){ 
    		
            if(this.Cmp.tipo_id.getValue() == 'FACTURA'){
            	
            	
            	this.resetearPanels();
            	this.addTabsBtn.disable();
            
            	
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
            	
            	

            	
            	this.megrid.initialConfig.columns[1].hidden = false;
            	this.megrid.getView().refresh(true);
            	
            	Ext.getCmp('input_pu').disabled=false;
            	
            	Ext.getCmp('input_pu').enable(true);
            	
            	
            	
            	
            }
            else if(this.Cmp.tipo_id.getValue() == 'BOLETO'){
            	
            	
            	this.resetearPanels();
            	this.addTabsBtn.disable();
            	
            	
            	
            	this.megrid.initialConfig.columns[1].hidden = true;
            	
            	this.megrid.getView().refresh(true);
            	
            	
            	
            	Ext.getCmp('input_pu').disabled=true;
            	Ext.getCmp('input_pu').addClass('x-item-disabled');
            	Ext.getCmp('input_pu').enable(false);
            	Ext.getCmp('input_pu').disable(false);
            	
            
            	
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
        		
        		this.Cmp.autorizacion.disable();
        		
        		this.Cmp.id_moneda.hide();
        		this.Cmp.pasajero.hide();
        		this.Cmp.boleto.hide();
        		this.Cmp.moneda.hide();
        		this.Cmp.tcambio.hide();
        		
        		this.Cmp.autorizacion.setValue(1);
        		
        		this.megrid.enable();
            	
            	


            }
            
            else if(this.Cmp.tipo_id.getValue() == 'LIQUIDACION'){
            	
            	
            	this.resetearPanels();
            	this.addTabsBtn.enable();
		            	
            	this.megrid.initialConfig.columns[1].hidden = true;
            	this.megrid.getView().refresh(true);
            	
            	Ext.getCmp('input_pu').disabled=true;
            	Ext.getCmp('input_pu').addClass('x-item-disabled');
            	Ext.getCmp('input_pu').enable(false);
            	Ext.getCmp('input_pu').disable(false);
            	
            	
            	this.resetear();
            	
            	this.Cmp.liquidevolu.enable();  
            	
            }
                  
            
           },this);
           
           
           
           this.Cmp.liquidevolu.on('keyup',function(combo,record){
           	
	           	 console.log(this.Cmp.liquidevolu.store)
           	
           },this);
           
           
          this.Cmp.liquidevolu.on('select',function(combo,record){ 
    		
    		

    		
    		this.tabs.removeAll();
    		this.resetGroup(10);
    		this.megrid.enable();
    		
    		this.megrid.remove();
    		this.megrid.store.removeAll(); 
    		
    		
    	
    		this.megrid.store.baseParams = {}; // limpio los parametro enviados
            this.megrid.store.baseParams.nroliqui=this.Cmp.liquidevolu.getValue();            
            //this.Cmp.id_factura.modificado=true;
            
            
           
           	this.megrid.store.load({params:{start:0,limit:20}, 
		       callback : function (r,a) {	
		       		console.log(r.length)    	
		       		if(r[0].data['tipo'] == 'FACTURA'){
		       			
		       			var arra = new Array();
				    	for (var i = 0; i < r.length; i++) {
							arra[i] = r[i].data;
					    }
						this.tabsFactura(arra);
		       		}else{
		       			var concepto = r[0].data['billcupon'];
			    		var importe_original = r[0].data['importe_original'];
			    		var billete = r[0].data['nro_billete'];
			    		var concepto_original = r[0].data['concepto_original'];
			        	//aca va la agregacion del los datos originales
			        	//if(r[])
			        	this.tabsBoleto(concepto_original,importe_original,billete);
			        	//termina agregacion de los datos originales
			        	this.agregarDatosCampo(r[0].data['nro_fac'],r[0].data['razon'],r[0].data['nro_nit'],r[0].data['fecha'],r[0].data['importe_original'],r[0].data['nro_aut']);
		       		}
		    	},scope : this
		    });
           },this);


          this.megrid.initialConfig.columns[3].editor.on('blur',function(){   
          	//aca
          	var nroaut = this.tabs.activeTab.name;
          	var nrofac = this.tabs.activeTab.id;
          	 this.megrid.initialConfig.columns[4].editor.setValue(nroaut);
          	 this.megrid.initialConfig.columns[5].editor.setValue(nrofac);
          	 
          	 
          	 this.megrid.store.data.each( function(rec){
	           this.megrid.initialConfig.columns[14].editor.setValue(rec.data.nro_nit) ;         	 
	           this.megrid.initialConfig.columns[15].editor.setValue(rec.data.razon)  ;          	
          	}, this )
          
          },this);
          
          
           this.megrid.initialConfig.columns[2].editor.on('select',function(){ 
           	
        	 
        	 if(this.megrid.initialConfig.columns[2].editor.getValue() == 'BOLETO'){
        	 	
        	 	//si escoge boleto en la grilla
        	 	
        	 	
            	Ext.getCmp('input_fac').addClass('x-item-disabled');
            	Ext.getCmp('input_fac').disable(true);
            	
            	Ext.getCmp('input_aut').addClass('x-item-disabled');
            	Ext.getCmp('input_aut').disable(true);	
            	
            	
            	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
            	
            
            	
            	
        	 }else if(this.megrid.initialConfig.columns[2].editor.getValue() == 'FACTURA'){
        	 	
        	 	Ext.getCmp('input_fac').addClass('x-item-disabled');
            	Ext.getCmp('input_fac').disable(true);
            	
            	Ext.getCmp('input_aut').addClass('x-item-disabled');
            	Ext.getCmp('input_aut').disable(true);	
        	 	
        	 	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
        	 	
        	 	
        	 	
        	 	
        	 	
        	 }
        	 /*else if(this.megrid.initialConfig.columns[2].editor.getValue() == 'CONCEPTO'){
        	 	
        	 	
        	 	Ext.getCmp('input_fac').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_fac').enable(true);
        	 	
        	 	Ext.getCmp('input_aut').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_aut').enable(true);
        	 	
        	 	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
        	 }*/
           
           },this);
           
           
           
           //factura
           // this.megrid.initialConfig.columns[5].editor.on('blur',function(){ 
           this.Cmp.nro_factura.on('blur',function(){   
          		

          		if(this.Cmp.autorizacion.getValue() != 1 ){
       			Phx.CP.loadingShow();
       			
       			this.megrid.remove();
				this.megrid.store.removeAll();
						
       			var arra = this.datosPermitidosFactura();
		    	
		    	this.tabs.removeAll();	
       			this.ajaxFactura(this.Cmp.nro_factura.getValue(), this.Cmp.autorizacion.getValue(),arra);
       			    
			    Phx.CP.loadingHide();
	       	
	       		}//end if si es factura	
	       		else if(this.Cmp.autorizacion.getValue() == 1){
	       			
	       				this.megrid.remove();
						this.megrid.store.removeAll();
				
	       				Phx.CP.loadingShow();
       			
       			
		       			//permitidos boletos
		       			var arra = this.datosPermitidosBoletos();
		       		
			       			//comienzo ajax
			       		this.tabs.removeAll();
				        this.ajaxBoletos(this.Cmp.nro_factura.getValue(),arra)
			    			
	    			
		       			
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
     			
           },this);
           
           
           this.megrid.initialConfig.columns[15].editor.on('blur',function(){
     		
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
     			
           },this);
           
           
            this.tabs.on('change',function(){
            	alert('ohh');
            },this);
          	
          	this.tabs.on('close',function(obj){
			     alert('cerrado')
			});
           
          
           this.megrid.initialConfig.columns[9].editor.on('keyup',function(){ 
           	
           var devol = this.megrid.initialConfig.columns[9].editor.getValue() - this.megrid.initialConfig.columns[10].editor.getValue(); 
          
	       this.megrid.initialConfig.columns[11].editor.setValue(devol);
	                         
           
           },this);
           
           
          this.megrid.initialConfig.columns[10].editor.on('keyup',function(){ 
           	
           	var devol = this.megrid.initialConfig.columns[9].editor.getValue() - this.megrid.initialConfig.columns[10].editor.getValue(); 
          
	       this.megrid.initialConfig.columns[11].editor.setValue(devol);
          },this);
          
          
          
          
          this.win_pop.items.items[0].form.items.items[0].on('select',function(){
          	
          	if(this.win_pop.items.items[0].form.items.items[0].getValue() == 'BOLETO'){
          		
          		this.win_pop.items.items[0].form.items.items[1].setValue('1');
          		this.win_pop.items.items[0].form.items.items[2].setValue('');
          		this.win_pop.items.items[0].form.items.items[1].disable();
          		
          		
          		
          	}else{
          		
          		this.win_pop.items.items[0].form.items.items[1].setValue('');
          		this.win_pop.items.items[0].form.items.items[2].setValue('');
          		this.win_pop.items.items[0].form.items.items[1].enable();
          		
          	}
          	
			          	
          },this);
          
          
          /*
          
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
          
          
          
          
           
           
          
           
           
           
           

    },
    
    
    loadValoresIniciales:function()
    { 
    	
    	 		Ext.getCmp('input_fac').addClass('x-item-disabled');
            	Ext.getCmp('input_fac').disable(true);
            	
            	Ext.getCmp('input_aut').addClass('x-item-disabled');
            	Ext.getCmp('input_aut').disable(true);	
        	 	
        	 	Ext.getCmp('input_concepto').removeClass('x-item-disabled');
        	 	Ext.getCmp('input_concepto').enable(true);
        	 	
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
          
        
     
     ],
    title:'Formulario de Recepción',
    
    getVistaPreviaHtml : function () {
    	
    	
    	var html = 'Estas seguro de Seguir Con la nota';    	
    	
    	
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
    otro : function(){
    	alert('llega');
    },
    guardar : function () {
    
    		
    	
    	
    	
    	this.win.hide();
    	this.megrid.store.filter();
    	
    	var cantidad_registros = this.megrid.store.getCount();
    	var record;
    	
    	var arra = new Array();
    	
    	
    	for (var i = 0; i < cantidad_registros; i++) {
    		
    		record = this.megrid.store.getAt(i);
    		
    				
			arra[i] = new Object();
	        arra[i].nroliqui = this.Cmp.liquidevolu.getValue();
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
    	this.tabs.removeAll();
    	//console.log(arra);
    	
    	
        this.argumentExtraSubmit = {'newRecords':Ext.encode(arra)};
        Phx.vista.FormNota.superclass.onSubmit.call(this,this.o); 
        
        //para limpiar despues de guardar  
    },
    closeWin : function () {
    	this.win.hide();
    },
    
    
    buscar_ : function () {
    	
    	this.megrid.store.clearFilter();
	                      
        if(this.win_pop.items.items[0].form.items.items[1].getValue() == 1){
        	
        	
        	var arra = this.datosPermitidosBoletos();
	        this.ajaxBoletos(this.win_pop.items.items[0].form.items.items[2].getValue(),arra)
				        
        }
        else{
        	this.megrid.store.baseParams.factura = this.win_pop.items.items[0].form.items.items[2].getValue() ; 
        	var arra = this.datosPermitidosFactura();
		    this.ajaxFactura(this.win_pop.items.items[0].form.items.items[2].getValue(), this.win_pop.items.items[0].form.items.items[1].getValue(),arra);
        }
        
         if(this.tabs.activeTab != undefined){
	      	var nrofac = this.tabs.activeTab.id
	       this.megrid.store.filter("nro_fac",nrofac);
	      }
        
  
       this.win_pop.hide();
		    
    	
    	
    	
    },
    
    closeWin_pop : function () {
    	this.win_pop.hide();
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
     addTab:function(){
     	
     	
   	 this.win_pop.show();
             
    
    
     },
    
     agregarDatosCampo:function(nro_fac,razon,nro_nit,fecha,importe_original,nro_aut){
     	
     	this.resetear();
     	
     	
     	
     	this.Cmp.nro_factura.setValue(nro_fac);
		this.Cmp.razon.setValue(razon);
		this.Cmp.nit.setValue(nro_nit);
		this.Cmp.fecha.setValue(fecha);
		this.Cmp.importe.setValue(importe_original);
		this.Cmp.autorizacion.setValue(nro_aut);	
     },
     
     datosPermitidosFactura:function(){
     	
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
		return arra;
				
     },
     datosPermitidosBoletos:function(){
     	
     	var cantidad_registros = this.megrid.store.getCount();
    	var record;
    	
    	var arra = new Array();
    	
    	
    	for (var i = 0; i < cantidad_registros; i++) {
    		
    		record = this.megrid.store.getAt(i);
    		
			if(record.data.tipo == 'BOLETO'){
				
			
			arra[i] = new Object();
	        arra[i].billete = record.data.nro_billete
	        arra[i].tipo = record.data.tipo;
	        
	       }
			       
		}
		return arra;
     },
     
     ajaxFactura:function(nro_factura,autorizacion,arra){
     	Ext.Ajax.request({
		         	
	         		
	                url : '../../sis_facturacion/control/Liquidevolu/listarFacturaDevolucion',
	                params:{'nrofac':nro_factura,
	                		'nroaut':autorizacion,
	                		'datos_no_permitidos':Ext.encode(arra),
	                		'start':0,'limit':1},
	                success : function(resp){ 
	                	
	                	
						
	                	Phx.CP.loadingHide();
	                	var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
	                	
	                	if(reg.datos == "DUPLICADO"){
	                		this.mensaje_('DUPLICIDAD','Lo requerido ya se encuentra en vista','ERROR');
				                			 
	                	}
	                	if(reg.datos){
	                		
	                		if(reg.datos == 'esta factura ya se devolvio'){
	                			this.mensaje_('Nota',reg.datos,'ERROR');
	                		}
	                		this.resetear();
	                		
        					
	                		
	                		this.tabsFactura(reg.countData);
							this.agregarDatosCampo(reg.datos[0].nrofac,reg.datos[0].razon,reg.datos[0].nit,reg.datos[0].fecha,reg.datos[0].importe,reg.datos[0].nroaut);				    
										
	                		
			            	
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
	                            
	                            /*for(var i = 0, r; r = se[i]; i++){
	                            	
	                            	 
	                                this.mestore.remove(r);
	                            }*/
                    			
                    			}
	                	}
      	
			    
	                },
	                failure : this.conexionFailure,
	                timeout : this.timeout,
	                scope : this
	           })
     },
     ajaxBoletos:function(billete,arra){
     	 Ext.Ajax.request({
				         	
			         		
			                url : '../../sis_facturacion/control/Liquidevolu/listarBoletosExistente',
			                params:{'billete':billete,
			                		'datos_no_permitidos':Ext.encode(arra),
			                		'start':0,'limit':1},
			                success : function(resp){ 
			                	
			                	
			                	Phx.CP.loadingHide();
			                	var reg_new = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
			                	
			                	
			                	
			                	
			                		if(reg_new.datos){
			                			
			                			//editor.stopEditing();
			                			
			                			if(reg_new.datos == "PERTENECE A UNA LIQUIDACION"){
			                				
			                				this.mensaje_('Error','El numero de billete pertenece a una liquidacion','ERROR');

			                				
			                			}else if(reg_new.datos == "PERTENECE A UNA NOTA"){
			                				
			                				
			                				this.mensaje_('Error',reg_new.datos +'se encuentra en la nota '+reg_new.total,'ERROR');
			                			
			                			}else{
			                				
			                			
			                				
			                			
			                		
			                		
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
					                                tipo:'BOLETO',
					                                
					                                concepto:reg_new.datos[0].CONCEPTO_ORIGINAL,
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
					    				
	
										this.tabsBoleto(reg_new.datos[0].CONCEPTO_ORIGINAL,reg_new.datos[0].MONTO,reg_new.datos[0].BILLETE);
										this.agregarDatosCampo(reg_new.datos[0].BILLETE,reg_new.datos[0].RAZON,reg_new.datos[0].NIT,reg_new.datos[0].FECHA_FAC,reg_new.datos[0].MONTO,reg_new.datos[0].NROAUT);				    
														    
										var se = this.megrid.getSelectionModel().getSelections();
			                            				 
									    this.mestore.insert(0, es);
		                    			this.megrid.getView().refresh();
					                            				
			                            
			                            /*for(var i = 0, r; r = se[i]; i++){
			                            	
			                            	 
			                                this.mestore.remove(r);
			                            }*/
			                	
				                		}else{
				                			
				                			this.mensaje_('DUPLICIDAD','Lo requerido ya se encuentra en vista','ERROR');

				                		}
				                		
				                		}//fin pertenece
			                		       		  	               
						            } else {		                
						                alert('Ocurrio un error al obtener el billete de esta liquidacion')
						            }  
					                       	
			                },
			                failure : this.conexionFailure,
			                timeout : this.timeout,
			                scope : this
			         });
     },
     
     tabsFactura:function(countData){
     	
     	
     	var m = '';
		    		m +='<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">';
					m +='<thead>';
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Cant.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Conceptos.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Precio Unitario.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Importe.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					var total = 0;
					
					//detalle 
					for (var i = 0; i <countData.length; i++) {

     
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>1.<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+countData[i].concepto+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+countData[i].importe_original+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+countData[i].importe_original+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					
					total = parseFloat(total)+parseFloat(countData[i].importe_original);
					
					
					}
					
					
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " colspan="2" style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>TOTAL GENERAL Bs</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>'+total+'</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					
					m +='</thead>';
					m +='</table>';
					
					
					
					
		    		this.tabs.add({
		            title: '<i class="fa fa-file"></i> DATOS DE LA TRANSACCION : '+countData[0].nrofac,
		            //iconCls: 'tabs',
		            id:countData[0].nrofac,
		             name:countData[0].nroaut,
		            html: m ,
		            closable:true
		        	}).show();
		        	
     	
     	
     },
     
     tabsBoleto:function(concepto,importe_original,billete){
     	
     	var m = '';
		    		m +='<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">';
					m +='<thead>';
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Cant.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Conceptos.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Precio Unitario.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>Importe.</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					
					
					//detalle 
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>1.<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+concepto+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+importe_original+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a>'+importe_original+'<img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					
					
					m +='<tr class="x-grid3-hd-row">';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " colspan="2" style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					
					
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>TOTAL GENERAL Bs</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='<td class="x-grid3-hd x-grid3-cell x-grid3-td-1 " style="width: 58px;">';
					m +='<div class="x-grid3-hd-inner x-grid3-hd-1" unselectable="on" style="">';
					m +='<a class="x-grid3-hd-btn" href="#"></a><h1>'+importe_original+'</h1><img alt="" class="x-grid3-sort-icon" src="resources/s.gif">';
					m +='</div>';
					m +='</td>';
					
					m +='</tr>';
					
					
					m +='</thead>';
					m +='</table>';
					
					
					
					
		    		this.tabs.add({
		            title: '<i class="fa fa-ticket"></i> DATOS DE LA TRANSACCION Original: '+billete,
		            //iconCls: 'tabs',
		            id:billete,
		            name:1,
		            html: m ,
		            closable:true
		        	}).show();
		        	
     	
     },
     
     resetearPanels: function(){
     	this.tabs.removeAll();
	 	this.megrid.remove();
		this.megrid.store.removeAll();
     },
     
     
     mensaje_ : function(titulo,mensaje,icono){
     	
     
     	var tipo = '';
     	switch (icono) {
			    case 'ERROR':
			        tipo = 'ext-mb-error';
			        break;
			    
			    case 'PELIGRO':
			        tipo = 'ext-mb-warning';
			        break;
			    case 'INFO' :
			        tipo = 'ext-mb-info';
			        break;
			}

     	Ext.MessageBox.show({
            title: titulo,
            msg: mensaje,
            buttons: Ext.MessageBox.OK,
            icon:tipo
        })
				                                
     }
    
    
}
)    
</script>