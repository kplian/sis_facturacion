<?php
/**
 *@package pXP
 *@file    Envio.php
 *@author  RCM
 *@date    08/03/2014
 *@description Interfaz para envio de las encomiendas
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.FormularioDos = Ext.extend(Phx.frmInterfaz, {
		ActSave:'../../sis_encomiendas/control/Encomienda/guardarManifiesto',
		guardar:false,
		breset:true,
		bcancel:false,
		labelSubmit:'Finalizar',
		labelReset:'Imprimir Manifiesto',
		
		constructor : function(config) {
			
			
			this.gridCentral = this.crearGridCentralPanel()
			this.Grupos = [{ 
				layout: 'form',
				xtype: 'panel',
				border: false,
				
				items:[
					{
						xtype:'fieldset',
						//region: 'north',
						layout: 'form',
		                border: true,
		                title: 'Datos del Viaje',
		                bodyStyle: 'padding:0 10px 0;',
		                columnWidth: 0.5,
		                items:[],
				        id_grupo:0,
				        collapsible:true
					},{
						xtype:'panel',
						layout: 'fit',
						//region: 'center',
		                border: true,
		                title: 'Manifiesto',
		                //bodyStyle: 'padding:0 10px 0;',
		                columnWidth: 0.5,
		                items:[this.gridCentral],
				        //autoScroll:true,
				        collapsible:false
					}
					]
				}];
				
				this.leftArrowButton = new Ext.Button({
				  text    : 'Añadir',				  
				  handler : this.insertaItems,
				  iconCls: 'batras',
                	// specified inline
                  tooltip: '<b>Añadir a Manifiesto</b>',
                  scope: this
				    			   
				});
				
				this.rightArrowButton= new Ext.Button({
				  text    : 'Quitar',
				  scope : this,
				  handler : this.eliminaItems,
				  iconCls: 'badelante',
                	// specified inline
                  tooltip: '<b>Quitar de Manifiesto</b>',				  					   
				}),
				
				this.rightActButton= new Ext.Button({
				  text    : 'Actualizar',
				  scope : this,
				  handler : this.actualizaEncomiendas,
				  iconCls: 'bact',
                	// specified inline
                  tooltip: '<b>Actualizar Encomiendas</b>',				  					   
				}),
				
				
				this.ctxMenuLeft = new Ext.menu.Menu({
					//id:'copyCtx_'+this.idContenedor,
					items : [{
						//id:'reload_'+this.idContenedor,
						handler : this.actualizarNodoLeft,
						icon : '../../../lib/imagenes/act.png',
						text : 'Actualizar Viajes',
						scope : this
		
					}]
				});
				
				this.ctxMenuRight = new Ext.menu.Menu({
					//id:'copyCtx_'+this.idContenedor,
					items : [{
						//id:'reload_'+this.idContenedor,
						handler : this.actualizarNodoRight,
						icon : '../../../lib/imagenes/act.png',
						text : 'Actualizar Encomiendas',
						scope : this
		
					}]
				});
				
				
			  this.chkCiudad=new Ext.form.Checkbox({
			        name: 'chk_ciudad',  
			        fieldLabel: 'Ciudad',
			        check:true
			   });
				
			Phx.vista.FormularioDos.superclass.constructor.call(this, config);	
					
			this.lastSelectionleftTree = '1';
			this.recordConstructor = Ext.data.Record.create(this.fieldsCentralPanel);
			this.init();
			this.iniciarEventos();
			this.loadValoresIniciales();
					
		},
		
		leftPanel : new Ext.Panel({
			region : 'west',
			//margins : '5 0 5 5',
			//split : true,
			width : '28%',
			layout : 'border',
			title: 'Factura'
		}),	
		
		rightPanel : new Ext.Panel({
			region : 'east', 
			//margins : '5 0 5 5',
			//split : true,
			width : '33%',
			layout : 'border',
			height: 300
		}),
		
		rightTbar: new Ext.Toolbar({
			height:'35',
        	region : 'north',        	
            defaults: {
                scale: 'large'
            }
      	}),
		
		
		rightTree : new Ext.ux.tree.TreeGrid({
			region : 'center',
			height : 300,
	        width: '90%',	        
	        title:'Encomiendas para Enviar',
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        enableDD:true,
	        containerScroll: true,
	        enableSort:false,
	        rootVisible: false,
	        singleExpand:true,
	        root: new Ext.tree.AsyncTreeNode({			
						collapsed : false,
						expanded : false,			
						hidden : false,
						id : '1',
						text: 'prueba'
					}),		   	        
	        listeners: {
	            'checkchange': function(node, checked){
	            	var existeOtro = false;
	                if (node.attributes['tipo'] == 'rama' && checked) {
	                	node.parentNode.eachChild(function(n) {
						    if (node.attributes['id'] != n.attributes['id'] && n.attributes['checked']) {
						    	alert('Existe otro destino marcado');
						    	node.getUI().toggleCheck(false);
						    	existeOtro = true;
						    }
						}, this);
	                }
	                if (!existeOtro) {
		                node.eachChild(function(n) {
						    n.getUI().toggleCheck(checked);
						}, this);
					}
	            }
	        },
	       columns:[{
	            header:'Destino/Hora',
	            width:100,
	            dataIndex:'id_factura'
	        },{
	            header:'Bus',
	            width:60,
	            dataIndex:'cantidad'
	        },{
	            header:'Tipo',
	            width:100,
	            dataIndex:'precio_unitario'
	        }],
	            
	        
	        singleExpand : false,
	        loader : new Ext.tree.TreeLoader({
						url : '../../sis_facturacion/control/FacturaDetalle/listarFacturaDetalle',
						baseParams : {},						 
						clearOnLoad : true,						
						nodeParameter : 'id_factura'/*,
						uiProviders:{
			                'col': Ext.ux.tree.ColumnNodeUI
			            }*/
					})			
	    }),
	    
	    leftTree : new Ext.ux.tree.TreeGrid({	
	    	region : 'center', 
	        height: 300,	        
	        useArrows:true,
	        autoScroll:true,
	        animate:true,
	        enableDD:true,
	        enableSort:false,
	        header:true,
	        containerScroll: true,
	        rootVisible: false,
	        root: new Ext.tree.AsyncTreeNode({			
						collapsed : false,
						expanded : false,			
						hidden : false,
						id : '1'
					}),	        
	        columns:[{
	            header:'Destino/Hora',
	            width:100,
	            dataIndex:'id_factura'
	        },{
	            header:'Bus',
	            width:60,
	            dataIndex:'cantidad'
	        },{
	            header:'Tipo',
	            width:100,
	            dataIndex:'precio_unitario'
	        }],
	        
	        singleExpand : false,
	        loader : new Ext.tree.TreeLoader({
						url : '../../sis_facturacion/control/FacturaDetalle/listarFacturaDetalle',
						baseParams : {},
						clearOnLoad : true,
						nodeParameter : 'id_factura'
					})			
	    }),
		
	
		
		facturaNota : new Ext.form.ComboBox({
			region : 'north',
			fieldLabel : 'Factura',
			store: new Ext.data.JsonStore({
					url: '../../sis_facturacion/control/Factura/ListarFactura',
					id: 'id_factura',
					root: 'datos',
					sortInfo: {
						field: 'nro_factura',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_factura', 'nro_factura', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nro_factura#movtip.codigo'}
				}),
				valueField: 'id_factura',
				displayField: 'nro_factura',
				gdisplayField: 'desc_sucursal',
				hiddenName: 'id_factura',
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
			
		}),
		Atributos : [
		
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'idd_viaje'
			},
			type:'Field',
			form:true 
		},{
			config : {
				name : 'destino',
				fieldLabel : "Destino",
				width : '100%',				
				readOnly: true
			},
			type : 'Field',
			id_grupo : 0,
			form : true
		},{
			config : {
				name : 'bus',
				fieldLabel : "Bus y Hora",
				readOnly : true,
				width : '100%'				
			},
			type : 'Field',
			id_grupo : 0,
			form : true
		},{
			config : {
				name : 'chofer1',
				fieldLabel : "Chofer A",
				readOnly : true,
				width : '100%'
			},
			type : 'Field',
			id_grupo : 0,
			form : true
		},{
			config : {
				name : 'chofer2',
				fieldLabel : "Chofer B",
				readOnly : true,
				width : '100%'
			},
			type : 'Field',
			id_grupo : 0,
			form : true
		},{
			config : {
				name : 'chofer3',
				fieldLabel : "Chofer C",
				readOnly : true,
				width : '100%'
			},
			type : 'Field',
			id_grupo : 0,
			form : true
			}
		],
		prepararRegion : function() {
			
			//Panel principal
			this.viewport = new Ext.Panel({
				layout : 'border',
				region : 'center',
				items : [this.leftPanel, this.form, this.rightPanel]
			});
			
			this.leftPanel.add(this.facturaNota);
			this.leftPanel.add(this.leftTree);
			
			this.rightPanel.add(this.rightTree);
			this.rightPanel.add(this.rightTbar);
			this.rightTbar.add(this.leftArrowButton);
			this.rightTbar.add(this.rightArrowButton);
			this.rightTbar.add(this.rightActButton);
			this.rightTbar.add(this.chkCiudad,'Todos');
			//Preparamos regiones
			this.regiones = new Array();
			this.regiones.push(this.viewport);
			this.definirRegiones();
		},			
		
		fieldsCentralPanel : [{
				name : 'id_item_encomienda'
			},{
				name : 'idd_destino'
			}, {
				name : 'guia'
			}, {
				name : 'detalle'
			}, {
				name : 'cantidad',
				type : 'float'
			}, {
				name : 'remitente'
			},{
				name : 'monto',
				type : 'float'
			}],
		crearGridCentralPanel : function() {
			var url = '../../sis_facturacion/control/Factura/listarFactura';
			var store = new Ext.data.JsonStore({
				url : url,
				id : 'id_factura',
				root : 'datos',
				sortInfo : {
					field : 'nit',
					direction : 'ASC'
				},
				totalProperty : 'total',
				fields : this.fieldsCentralPanel,
				remoteSort : true,
				baseParams : {
					start : 0,
					limit : 50,
					idd_viaje : -1
				}
			});
			store.on('loadexception', this.conexionFailure);
			//store.load();
			this.selectionModelGridCentralPanel = new Ext.grid.CheckboxSelectionModel({
												singleSelect:false});
												
			return new Ext.grid.GridPanel({
				store : store,
				cm :  new Ext.grid.ColumnModel(
					{columns : 
						[this.selectionModelGridCentralPanel,
						{
							id : 'idd_destino',
							header : 'id',
							width : 160,
							sortable : true,
							dataIndex : 'idd_destino',
							hidden : true
						}, {
							id : 'id_item_encomienda',
							header : 'id',
							width : 160,
							sortable : true,
							dataIndex : 'id_item_encomienda',
							hidden : true
						},{
							header : 'Guia',
							width : 75,
							sortable : true,
							dataIndex : 'guia'
						}, {
							header : 'Detalle',
							width : 100,
							sortable : true,
							dataIndex : 'detalle'
						}, {
							header : 'Cantidad',
							width : 85,
							sortable : true,
							dataIndex : 'cantidad'
						},{
							header : 'Remitente',
							width : 100,
							sortable : true,
							dataIndex : 'remitente',
							hidden : true
						}]
					}),
				stripeRows : true,
				//autoExpandColumn: 'remitente',
				height : 350,
				//layout : 'fit',
				sm : this.selectionModelGridCentralPanel,
				//width : 390,
				//title: 'Items encomiendas',
				// config options for stateful behavior
				stateful : true,
				stateId : 'grid',
				bbar : new Ext.PagingToolbar({
					store : store,
					pageSize : 50
				}),
				autoWidth:true
				
			});
		},
		iniciarEventos : function () {
			// para capturar un error
			this.leftTree.on('click', this.onLeftTreeClick, this);
			this.leftTree.loader.on('loadexception', this.conexionFailure);
			this.leftTree.loader.on('beforeload', this.onLeftTreeBeforeLoad, this);
			this.facturaNota.on('select', this.onFactura, this);
			this.chkCiudad.on('check', this.onCheck, this);
			
			this.leftTree.on('contextmenu', function(node, e) {				
				node.select();
				this.ctxMenuLeft.showAt(e.getXY())
			}, this);
			
			this.rightTree.on('contextmenu', function(node, e) {				
				node.select();
				this.ctxMenuRight.showAt(e.getXY())
			}, this);
			
			/*this.chkCiudad.on('check',function(chk,a){				
					this.rightTree.loader.baseParams['check_ciudad'] = a;					
					this.rightTree.root.reload();
			},this);*/
		},
		
		onCheck : function (chk,a){
			this.rightTree.loader.baseParams['check_ciudad'] = a;	
			this.rightTree.root.reload();
			
			this.gridCentral.store.baseParams.check_ciudad = a;
			this.gridCentral.store.load();
				
		},
		actualizarNodoLeft : function() {
			this.ctxMenuLeft.hide();
			var n = this.leftTree.getSelectionModel().getSelectedNode();
			setTimeout(function() {
				if (!n.leaf) {
					n.reload()
				}
			}, 10);
		},
		
		actualizarNodoRight : function() {
			this.ctxMenuRight.hide();
			var n = this.rightTree.getSelectionModel().getSelectedNode();
			setTimeout(function() {
				if (!n.leaf) {
					n.reload()
				}
			}, 10);
		},
		onLeftTreeBeforeLoad : function(treeLoader, node) {
			//if (node.attributes['idd_destino'] != undefined && node.attributes['idd_destino'] != '') {
				treeLoader.baseParams['id_factura'] = this.facturaNota.getValue();				
			//} 
		},
		onFactura : function() {
			this.leftTree.root.reload();
		},
		onLeftTreeClick : function(node, e) {				
			this.lastSelectionleftTree = '2';
			//reload manifiesto
			if (node.attributes['tipo'] == 'hoja') {
				//actualizar grilla
				this.gridCentral.store.baseParams.idd_viaje = node.attributes['id_factura'];
				this.gridCentral.store.load();
				//actualizar arbol derecho
				this.Cmp.idd_viaje.setValue(node.attributes['idd_viaje']);
				this.Cmp.destino.setValue(node.attributes['destino']);
				this.Cmp.bus.setValue(node.attributes['hora'] + '  [' + node.attributes['numero_bus'] + ']  ' + node.attributes['bus']);
				var txtChoferes = new String(node.attributes['choferes']); 
				var arrChoferes = txtChoferes.split(",");
				this.Cmp.chofer1.setValue(arrChoferes[0]);
				this.Cmp.chofer2.setValue(arrChoferes[1]);
				this.Cmp.chofer3.setValue(arrChoferes[2]);
			} else {
				///actualizar grilla
				this.gridCentral.store.baseParams.idd_viaje = -1;
				this.gridCentral.store.load();
				//actualizar arbol derecho
				this.Cmp.idd_viaje.reset();
				this.Cmp.destino.reset();
				this.Cmp.bus.reset();				
				this.Cmp.chofer1.reset();
				this.Cmp.chofer2.reset();
				this.Cmp.chofer3.reset();
			}
			this.lastSelectionleftTree = node.attributes['id_factura'];
		},
		insertaItems : function () {
			var cantidad_check = 0;
			if (this.Cmp.idd_viaje.getValue() != '') {
				this.rightTree.root.eachChild(function(destino) {
					item = destino.findChild('checked',true);
					
					while (item != null) {
						console.log(item);
			    		cantidad_check ++;
			    		//inserta un elemento en la grilla del store
			    		this.gridCentral.store.add( new this.recordConstructor ({
			    		   idd_destino : item.attributes['id_p'],
						   id_item_encomienda : item.attributes['id_item_encomienda'],						
						   guia:  item.attributes['guia'],						
						   detalle:  item.attributes['detalle'],						
						   cantidad:  item.attributes['cantidad'],						
						   remitente:  item.attributes['remitente']				
						}));
			    		//alert('inserta : ' + item.attributes['text']);
			    		//elimina nodo del arbol
			    		destino.removeChild(item);	
			    		item = destino.findChild('checked',true);		    		
			    		
					}
					
	                
				    
				}, this);
			} else {
				alert('Debe seleccionar un viaje para asignar encomiendas');
				return;
			}
			
			if (cantidad_check == 0) {
				alert('Debe seleccionar items para añadir al manifiesto');
			} else {
				this.guardar = true;
				this.onSubmit({
                    'news': true,
                    def: 'reset'
                });
			}
		},
		eliminaItems : function () {
			var s = this.gridCentral.getSelectionModel().getSelections();			 			
			if (s.length > 0) {
				Ext.each(s, function (item) {
					//quita del grid
					this.gridCentral.store.remove(item);
										
					//buscar el nodo con el id del destino
					var parentNode = this.rightTree.root.findChild('2', item.data.idd_destino);
					
					//crea un nuevo nodo con los atributos correspondientes
					var newNode = new Ext.tree.TreeNode({
										id_item_encomienda : item.data.id_item_encomienda,
										guia : item.data.guia,
										detalle : item.data.detalle,
										cantidad : item.data.cantidad,									
										tipo : "hoja",
										remitente : item.data.remitente,
										id_p : item.data.idd_destino,
										checked : false,
										leaf : true,
										allowDelete : false,
										allowEdit : false,
										icon : "..\/..\/..\/lib\/imagenes\/a_form.png",
										id : item.data.id_item_encomienda,
										text : item.data.guia,
										qtip : item.data.remitente});
				 	
				 	//append el nuevo nodo en el destino
				 	if (parentNode.isExpanded()) {
				 		parentNode.appendChild(newNode);
				 	} else {
				 		parentNode.expand(false, true, function() {
					 		parentNode.appendChild(newNode);
					 	}, this);
				 	}
				 	
	                
				}, this);
				
				this.guardar = true;
				 	this.onSubmit( {
	                    'news': true,
	                    def: 'reset'
	                });
	                
	                
			} else {
				alert('No existe ningun item seleccionado')
			}
		},
		
		onSubmit: function(o) {			   	
	    	var newRecords = this.gridCentral.store.data.items;        
	        var newRec = [];	              
	        Ext.each(newRecords,function(record){
	           newRec.push(record.data);
	        });
	        if (this.guardar) {
	        	this.argumentExtraSubmit = {'accion': 'guardar','newRecords':Ext.encode(newRec)};
	        } else {
	        	this.argumentExtraSubmit = {'accion': 'cambiar_estado','newRecords':Ext.encode(newRec)};
	        }
	        Phx.vista.FormularioDos.superclass.onSubmit.call(this,o);
	        	        
		},
		actualizaEncomiendas : function () {
			this.rightTree.root.reload();
		},
		
		
    
		successSave:function(resp){
			if (this.guardar) {
				Phx.CP.loadingHide();
				
			} else {
				Phx.CP.loadingHide();
				Ext.Msg.alert('Información','El manifiesto ha sido finalizado');
			}
			
			//this.onPrintManifiesto();
			
			var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
				
		        //this.generarReportesApplet(objRes);       
		        //this.onReset();         
		        Ext.Ajax.request({
				        url:'../../sis_encomiendas/control/Manifiesto/generarManifiesto',
				        params:{'id_manifiesto':objRes.ROOT.datos.id_manifiesto},
				        success: this.successExport,
				        failure: this.conexionFailure,
				        timeout:this.timeout,
				        scope:this
				    });     
			
			this.guardar = false;
			//alert('entra');
			//this.rightTree.root.reload();
			//alert('entra1');
			this.gridCentral.store.load();
			//alert('entra2');
		},
		conexionFailure : function (resp) {
			this.guardar = false;
			Phx.vista.FormularioDos.superclass.conexionFailure.call(this,resp);
			
		},
		onReset : function () {
			this.onPrintManifiesto();
		},
		onPrintManifiesto : function () {
			var printer = document.printerSystem;
			var nro_bus, hora_bus;
			var aux_array = this.Cmp.bus.getValue().split("  ");
			nro_bus = aux_array[1];
			hora_bus = aux_array[0];
			
			alert('favio')
    		printer.typeDocument="lista";
    		
			printer.setCabecera("-", "-", " ENVIO ","-", "-", "-", "--","", "", "00");
					
			printer.setManifiesto(this.Cmp.chofer1.getValue(),this.facturaNota.getValue().dateFormat('Y-m-d'),hora_bus,this.Cmp.destino.getValue(),nro_bus);
			
			
		    var newRecords = this.gridCentral.store.data.items;        
	                      
	        Ext.each(newRecords,function(record){	           
	           
	           printer.addEncomienda(record.data.guia,record.data.cantidad.toString() + ' ' + record.data.detalle,record.data.monto.toString(),'ITEM');
	        });
	        
		    try{
		        printer.imprimir();
		        printer.clean();
		    }catch(ex ){
		        console.log("error en el applet de impresion",ex);
		    }
		},
		// Abre ventana con reporte en pdf
		successExport:function(resp){
			Phx.CP.loadingHide();
	        var objRes = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
	        var nomRep = objRes.ROOT.detalle.archivo_generado;
	        if(Phx.CP.config_ini.x==1){  			
	        	nomRep = Phx.CP.CRIPT.Encriptar(nomRep);
	        }
	        
	         
	    
	       window.open('../../../lib/lib_control/Intermediario.php?r='+nomRep+'&t='+new Date().toLocaleTimeString())
	       
	       var ifrm = document.createElement("iframe");
	 
	       ifrm.style.width = 0+"px"; 
		   ifrm.style.height = 0+"px"; 
		   
 
	       
		   ifrm.setAttribute("src", '../../../lib/lib_control/Intermediario.php?r='+nomRep+'&t='+new Date().toLocaleTimeString()); 
		   	   
		  
		   
		   document.body.appendChild(ifrm);
		   
		   ifrm.onload = function() {	
		   	  //window.print()   	
			  setTimeout(function(){ ifrm.parentNode.removeChild(ifrm);},2000);
			};  
			
			
			
			
		},
	})
</script>