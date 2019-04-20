
function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
function resetForm(){
  $('body').css('overflow', 'initial'),
  $('form')[0].reset();
  $('form').find('input').attr('disabled', false),
  $('.loader').css('display', 'none');  
}
function addSimpleInput(e){
    var el = $(e).parent().prev().children(),
        el_name = $(e).parent().prev().children().find('*').attr('name');
    el.eq(0).clone(true)
    .append('<a href="javascript:void(0)" class="removeElement" onclick="remove(this)"><i class="fal fa-close"></i></a>')
    .appendTo($(e).parent().prev());   
}
function remove(e){
  $(e).closest('span').remove();
}

function changeMessage(message){
    if(!$('.error-message').length){
      $('<p class="error-message">'+message+'</p>').appendTo('form');
    } else {
      $('.error-message').html(message);
    }              
}
function closeMessage(e){
    $(e).closest('.messageBar').remove();
    var url = window.location.href.split('?')[0];
    window.location = url;
}

function collapse(e){
	$(e).closest('li').toggleClass('collapsed');
}

var nivel_rodada = (!$('.rodadas .columns').children().length) ? 0 : parseInt($('.rodadas .columns').children().last().find('.nivel_rodada').val());

function removeRodada(e){
	if($(e).closest('li').is(':last-of-type')){
	    if($('.rodadas .columns').children().length % 2 !== 0){
	    	nivel_rodada -=1;
	    }	

	    if($(e).closest('li').attr('data-origin')){
	      var vars = window.location.pathname,
	          id = vars.split('/')[3],
	          uid = vars.split('/')[2],
	          position = parseInt($('.rodadas .columns').children().last().index());

	        $.ajax({
	            type: 'POST',
	            async: true,
	            url: window.location.origin + '/functions/delete.php',
	            data: {
	              'table' : 'rodadas',
	              'pid' : id,
	              'position' : position,
	            },
	            datatype: 'json',
	            cache: true,
	            global: false,
	            beforeSend: function() { 
	                $("#loader").css('display', 'flex'),
	                $(e).closest('li').remove();
	            },
	            success: function(data) {
	                console.log(data);
	            },
	            complete: function() { 
	                $("#loader").css('display', 'none');
	            }
	        });  
	    } else {
	    	$(e).closest('li').remove();
	    }
	}	
}

function addMultipleInput(e){
    if($('.rodadas .columns').children().length % 2 === 0){
    	nivel_rodada +=1;
    }

    var required, 
    	tipo_processo = $('[name="tipo_processo"]').val();

    if(tipo_processo == 'Licitação' || tipo_processo == 'Adj. Direta' || tipo_processo == 'RFI'){
    	required = true;
    } else {
    	required = false;
    }

	var html = '<li>';
		html += '<span class="fieldset">';
			html += '<label for="nivel-rodada">Nível</label>';
			html += '<span>';
				html += '<input required="'+required+'" value="'+nivel_rodada+'"  readonly="readonly" name="nivel-rodada[]" class="nivel_rodada" type="text">';
			html += '</span>';
		html += '</span>';
		html += '<span class="fieldset">';
			html += '<label for="rodada_tipo">Tipo</label>';
			html += '<span class="custom-combobox">';
				html += '<i class="fal fa-angle-down"></i>';
				html += '<select required="'+required+'" name="rodada-tipo[]">';
					html += '<option value="Rodada Comercial">Rodada Comercial</option>';
					html += '<option value="Rodada Técnica">Rodada Técnica</option>';
				html += '</select>';
			html += '</span>';
		html += '</span>	';
		html += '<span class="fieldset">';
			html += '<label for="data-inicial-rodada">Início</label>';
			html += '<span>';
				html += '<input required="'+required+'" name="data-inicial-rodada[]" type="date">';
			html += '</span>';
		html += '</span>  ';
		html += '<span class="fieldset">';
			html += '<label for="data-final-rodada">Fim</label>';
			html += '<span>';
				html += '<input required="'+required+'" name="data-final-rodada[]" type="date">';
			html += '</span>';
		html += '</span> ';
		html += '<span class="fieldset rodadas-footer">';
 			// html += '<span><a class="collapse" onclick="collapse(this)" href="javascript:void(0)"><i class="fa fa-angle-up"></i></a></span>';		
		html += '<span> ';
			html += '<a href="javascript:void(0)" onclick="removeRodada(this)" class="remove-rodada btn btn-2">Excluir</a>';
		html += '</span>';
	html += '</span>';
	html += '</li>';	    

    $(html).appendTo($(e).closest('.rodadas').find('.columns'));
}

$(document).ready(function () {

	$(".assets").submit(function( event ) {
		event.preventDefault();
		
		var dataparam = $(this).serialize();

        $.ajax({
            type: 'POST',
            async: true,
            url: window.location.origin + '/functions/insert.php',
            data: {
              'data' : decodeURIComponent(dataparam)
            },
            datatype: 'json',
            cache: true,
            global: false,
            beforeSend: function() { 
                $("#loader").css('display', 'flex');
            },
            success: function(data) {
            	console.log(data);
            	window.location = window.location.origin + '/cadastro/'+ (($('[name="table"]').val() == 'subfamilia') ? $('[name="table"]').val() : 'tipos-de-processos') +'/?'+ ((data == '' || !data) ? 'exist' : 'registered') +'=true';
            },
            complete: function() { 
                $("#loader").css('display', 'none');
            }
        }); 
	});

	$(".ajax").click(function( event ) {
	    event.preventDefault();

        $(this).closest('tr').remove();

	    var table, id, i = 0;

		$.each( $(this).attr('href').split('?')[1].split('&'), function( key, value ) {
			value = value.split('=')[1];

			if(key == 0){
				table = value;
			} else {
				id = value;
			}
		});
		
        $.ajax({
            type: 'POST',
            async: true,
            url: window.location.origin + '/functions/delete.php',
            data: {
              'table' : table,
              'id' : id,
            },
            datatype: 'json',
            cache: true,
            global: false,
            beforeSend: function() { 
                $("#loader").css('display', 'flex');
            },
            success: function(data) {
                console.log(data);
            },
            complete: function() { 
                $("#loader").css('display', 'none');
            }
        }); 	    
	}); 
	
	$('#senha').on('keypress', function() {
	  $(this).attr('type', 'text');
	}).on('keyup blur', function() {
	  setTimeout(function(){ 
	    $(this).attr('type', 'password');
	  }, 300);
	});

	$('.telefone').mask('(00) 00000-0000');
	$('.money').maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
	
	if($('.progressBar').length){
	  $(".progressBar").before($(".progressBar").clone(true).addClass("-sticky")),
	  $(".forms-footer").before($(".forms-footer").clone(true).addClass("-sticky")), 
	  $(".forms-footer.-sticky").find('input').remove();
	}

	$(window).scroll(function() {
	    var t = $(this).scrollTop();
	    if (t > $('.progressBar:not(.-sticky)').offset().top){
	        $(".progressBar.-sticky").addClass("-stuck");
	        $(".forms-footer.-sticky").addClass("-stuck");
	    } else {
	        $(".progressBar.-sticky").removeClass("-stuck");
	        $(".forms-footer.-sticky").removeClass("-stuck");
	    }
	    if(t > ($('.forms-footer:not(.-sticky)').offset().top - 1000)) {
	        $(".forms-footer.-sticky").removeClass("-stuck");
	    }
	});   

	// - Controle do form	

	var fields = [],
		default_fields = [
			'nome_processo',
			'tipo_processo',
			'comprador',
			'subfamilia',
			'grupo_de_compras',
			'data_disp_compras',
			'responsavel',
			'responsavel[]',
			'cod_material'
		],
		filtered_fields = [];	

	// Subfamilias

	$( "#subfamilia" ).find('select').on( "change", function() {
	    if(!$(this).closest('.fieldset').find($('.subfamilia_result')).length){
	        $('<div class="subfamilia_result"/>').appendTo($(this).closest('.fieldset'));
	    }
	    $('.subfamilia_result').html($(this).val());
	});	

	// Sociedades

	$( "#sociedades input[type='checkbox']" ).each(function() {
	    $(this).change(function() {
	      if(!$(this).is( ":checked" )){
	        $(this).closest('li').find('.money, .moeda').val('').attr('required', false);
	      } else {
	        $(this).closest('li').find('.money, .moeda').attr('required', true);
	      }
	    });        
	});     

	$( "#sociedades .moeda" ).each(function() {
	    $(this).on('change', function (e) {
	      if($(this).val()){
	        if(!$(this).closest('li').find('input[type="checkbox"]').is(":checked")){
	          $(this).closest('li').find('input[type="checkbox"]').trigger('click');
	        }
	      } else {
	        if($(this).closest('li').find('input[type="checkbox"]').is(":checked")){
	          $(this).closest('li').find('input[type="checkbox"]').trigger('click');
	        }
	      }
	    });
	});

	$( "#sociedades .money" ).each(function() {
	    $(this).on('blur keypress', function (e) {
	      if($(this).val()){
	        if(!$(this).parent().prev().find('input').is(":checked")){
	          $(this).parent().prev().find('input').trigger('click');
	        }
	      } else {
	        if($(this).parent().prev().find('input').is(":checked")){
	          $(this).parent().prev().find('input').trigger('click');
	        }
	      }
	    });
	});

	// Pega todos os campos

	$( "#processo-form" ).children('div.fieldset').each(function() {
		var field = $(this).attr('class').split('fieldset')[1].trim();

		if(jQuery.inArray(field, fields) === -1){
			var name = field.replace('field_id_','').trim();
			fields.push(name);
		}			
	});		

	// Define required pros campos default

	$.each( default_fields, function( key, value ) {
		jQuery('[name="'+value+'"]').attr('required', true);
	});

	// Pega os campos por tipo de processo

	$('[name="tipo_processo"]').on('change', function() {
		$('.rodadas input, .rodadas select').attr('required', false).attr('placeholder', '').removeClass('obrigatorio');

		switch($(this).val()){
			case 'Licitação':
			case 'Adj. Direta':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'dados_bravo_sav',
					'dados_bravo_rqf',
					'elaboracao_pa_data_inicial',
					'elaboracao_pa_data_final',
					'adjudicacao_num_pa',
					'workflow_data_inicial',
					'workflow_data_final',
					'numero_pedido',
					'criacao_de_pedido_data_inicial',
					'criacao_de_pedido_data_final',
					'disponivel_sap'
				];
				$('.rodadas input, .rodadas select').attr('required', true).attr('placeholder', 'campo obrigatório *').addClass('obrigatorio').on('blur change', function() {
					if($(this).is('.obrigatorio') && $(this).val()){
						$(this).removeClass('obrigatorio').attr('placeholder', '');
					} else if(!$(this).val()){
						$(this).addClass('obrigatorio').attr('placeholder', 'campo obrigatório *');
					}
				});
			break;	
			case 'Amp. Verba':
			case 'Amp. VP':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'elaboracao_pa_data_inicial',
					'elaboracao_pa_data_final',
					'adjudicacao_num_pa',
					'workflow_data_inicial',
					'workflow_data_final',
					'disponivel_sap'
				];
			break;	
			case 'Renov':	
			case 'PCAE':
			case 'Rampa':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'elaboracao_pa_data_inicial',
					'elaboracao_pa_data_final',
					'adjudicacao_num_pa',
					'workflow_data_inicial',
					'workflow_data_final',
					'numero_pedido',
					'criacao_de_pedido_data_inicial',
					'criacao_de_pedido_data_final',					
					'disponivel_sap'
				];
			break;	
			case 'RFI':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'dados_bravo_sav',
					'dados_bravo_rqf'
				];
				$('.rodadas input, .rodadas select').attr('required', true).attr('placeholder', 'campo obrigatório *').addClass('obrigatorio').on('blur change', function() {
					if($(this).is('.obrigatorio') && $(this).val()){
						$(this).removeClass('obrigatorio').attr('placeholder', '');
					} else if(!$(this).val()){
						$(this).addClass('obrigatorio').attr('placeholder', 'campo obrigatório *');
					}
				});
			break;		
			case 'Rep. Global':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'elaboracao_pa_data_inicial',
					'elaboracao_pa_data_final',
					'adjudicacao_num_pa',		
					'workflow_data_inicial',
					'workflow_data_final',
					'numero_pedido',								
					'dados_bravo_sav',
					'dados_bravo_rqf',
					'criacao_de_pedido_data_inicial',
					'criacao_de_pedido_data_final',					
					'disponivel_sap'					
				];
			break;	
			case 'Comp. Direta':
				filtered_fields = [
					'elaboracao_documento_data_inicial',
					'elaboracao_documento_data_final',
					'criacao_de_pedido_data_inicial',
					'criacao_de_pedido_data_final',					
					'disponivel_sap'					
				];
			break;																																			
		}

		// Define required pros campos da regra excluindo os default

		$.each( fields, function( key, value ) {
			if(jQuery.inArray(value, default_fields) === -1){
				$('[name="'+value+'"]').attr('required', false).attr('placeholder', '').removeClass('obrigatorio');
			}
		}),
		$.each( filtered_fields, function( key, value ) {
			$('[name="'+value+'"]').attr('required', true).attr('placeholder', 'campo obrigatório *').addClass('obrigatorio').on('blur change', function() {
				if($(this).is('.obrigatorio') && $(this).val()){
					$(this).removeClass('obrigatorio').attr('placeholder', '');
				} else if(!$(this).val()){
					$(this).addClass('obrigatorio').attr('placeholder', 'campo obrigatório *');
				}
			});	
		});				
	});	

	// $( ".rodadas .columns" ).children().each(function() {
	// 	if($(this).index() == 0){
	// 	  $(this).find('.rodadas-footer').closest('li').find('[name="rodada-tipo[]"]').val('Rodada Comercial');
	// 	}
	// });   

	// - Controle de forms. 

	// var filled = 0,
	// 	checkes = 0,
	// 	result = 0,
	// 	fieldsAfterChecks = 0,
	// 	rfields = 0,
	// 	cfields = 0,
	// 	columnFields = 0,
	// 	fields = [],
	// 	curr_val;

	// $( "#processo-form" ).each(function() {		
	// 	// - Desabilitando todos os campos, exceto o primeiro.
	// 	if(!$(this).is('.-edit')){
	// 		$(this).children('div.fieldset:not(:first-of-type)').each(function() {
	// 			$(this).addClass('disabled');
	// 		});	
	// 		// - Pegando campos e a quantidade.
	// 		$(this).children('div.fieldset').find('input,select').each(function() {
	// 			// - Filtra por tipo.
	// 			if($(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden'){
	// 				// - Filtra por classes.e names.
	// 				if(!$(this).is('.moeda') && !$(this).is('.money') && $(this).attr('name') != 'data-final-rodada[]' && $(this).attr('name') != 'data-inicial-rodada[]'){
	// 					// - Elimina readonlies.
	// 					if(!$(this).attr('readonly')){
	// 						fields.push($(this).attr('name'));
	// 						// - Trabalhando eventos.
	// 						$(this).on('focus', function() {
	// 							// - Pega valor atual, antes de inserir.
	// 							curr_val = $(this).val();
	// 						}).on('blur', function() {
	// 							if($(this).val()){
	// 								// - Habilita campo posterior e soma campos filled.
	// 								$(this).closest('div.fieldset').nextAll('div.fieldset:first').addClass('enabled');

	// 								if(curr_val == '' && $(this).val() != curr_val){
	// 									filled +=1;
	// 								}	

	// 								// -
	// 								console.log(filled + '/' + fields.length);	
	// 								$(this).closest('div.fieldset').nextAll('div.fieldset:first').addClass('enabled');
	// 							} else {
	// 								// - Subtrai e limpa campos vazios.
	// 								if($(this).val() != curr_val && $(this).is('select') || $(this).val() == '' && $(this).val() != curr_val && !$(this).is('select')){
	// 									filled -=1;
	// 									// -
	// 									console.log(filled + '/' + fields.length);	
	// 									$(this).closest('div.fieldset').nextAll('div.fieldset:first, div.fieldset').removeClass('enabled'); 
	// 								}
	// 								$(this).closest('div.fieldset').nextAll('div.fieldset').find('input,select').each(function() {
	// 									if(jQuery.inArray($(this).attr('name'), fields) !== -1){
	// 										curr_val = $(this).val();
	// 					                    $(this).val('');

	// 										if(curr_val != $(this).val()){
	// 											filled -=1;
	// 											// -
	// 											console.log(filled + '/' + fields.length);	
	// 										} 
	// 									} else if($(this).is(':checked')){
	// 										// - Desmarca campos clicados se marcados, quando for limpar todos.
	// 				                        $(this).trigger('click');
	// 				                    }
	// 								});									
	// 							}
	// 							// - Pega porcentagem.
	// 							result = Math.ceil( 100 - ((fields.length - filled)/fields.length) * 100 );
	// 							console.log(result);
	// 							// - Mostrar %
	// 						    if($('.progressBar').length){
	// 						      var el = $('.progressBar');
	// 						      el.find('.progressStatus > span').html(Math.ceil(parseInt(result)));
	// 						      el.find('.progressLoader > span').css('width', Math.ceil(parseInt(result))+'%');
	// 						      el.find('.progressStatus').css('left', result+'%');
	// 						      $('[name="status"]').val(Math.ceil(parseInt(result)));
	// 						    } 							
	// 						});						
	// 					}
	// 				}
	// 			}
	// 			// - Campos de SOCIEDADE.
	// 			if($(this).attr('type') == 'checkbox') {
	// 				$(this).on('change', function() {
	// 					if($(this).is(':checked')){
	// 						checkes +=1;
	// 						if(checkes >= 1){
	// 							$(this).closest('div.fieldset').nextAll('div.fieldset:first').addClass('enabled');
	// 						}
	// 					} else {
	// 						if(checkes >= 1){
	// 							checkes -=1;
	// 							if(checkes == 0){
	// 								$(this).closest('div.fieldset').nextAll('div.fieldset').find('input:not([readonly="readonly"]),select').each(function() {
	// 									if($(this).val()){
	// 										fieldsAfterChecks +=1;
	// 									}
	// 								});
	// 								$(this).closest('div.fieldset').nextAll('div.fieldset').removeClass('enabled').find('input:not([readonly="readonly"]),select').val('');
	// 								if(fieldsAfterChecks > 0){
	// 									filled -=fieldsAfterChecks;
	// 								}
	// 								fieldsAfterChecks = 0;
	// 							}						
	// 						}
	// 					}
	// 					console.log(checkes);	
	// 				});
	// 			}
	// 			// - Rodadas
	// 			if($(this).attr('name') == 'data-final-rodada[]' || $(this).attr('name') == 'data-inicial-rodada[]'){
	// 				$(this).each(function() {
	// 					$(this).on('focus', function() {
	// 						curr_val = $(this).val();
	// 					}).on('change', function() {
	// 						var val = $(this).val();
	// 						if(val){
	// 							$(this).attr('value', val);
	// 						} else {
	// 							$(this).attr('value', '');
	// 						}
	// 					}).on('blur', function() {
	// 						if(curr_val == '' && $(this).val() != curr_val){
	// 							rfields +=1;
	// 						} else if($(this).val() == '' && $(this).val() != curr_val){
	// 							if(rfields >= 1){
	// 								rfields -=1;
	// 							}
	// 						}
	// 						console.log(rfields + ' ' + $(this).closest('ul').find('input:not([readonly="readonly"])').length);
	// 						if(rfields == $(this).closest('ul').find('input:not([readonly="readonly"])').length){
	// 							$(this).closest('div.fieldset').nextAll('div.fieldset:first').addClass('enabled');
	// 						} else {
	// 							var nfields = [];
								
	// 							$(this).closest('div.fieldset').nextAll('div.fieldset').find('input,select').each(function() {
	// 								if($(this).val() && (!$(this).is('.money') && !$(this).is('.moeda') && !$(this).attr('readonly["readonly"]') && !$(this).closest('ul').is('.columns') && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden')){
	// 									if(jQuery.inArray($(this).attr('name'), nfields) === -1){
	// 										nfields.push($(this).attr('name'));
	// 									}
	// 								}				
	// 							});	 		
								
	// 							console.log(nfields);

	// 							filled -=nfields.length;

	// 							setTimeout(function(){
	// 								nfields = [];
	// 							}, 600);									

	// 							// =

	// 							$(this).closest('div.fieldset').nextAll('div.fieldset:first, div.fieldset').removeClass('enabled').find('input, select').val(''); 
	// 						}
	// 					});					
	// 				});
	// 			}
	// 		});	
	// 		// - Drafts
	// 	    $( document ).idleTimer( {
	// 	        timeout:900000, 
	// 	        idle:true
	// 	    }).on( "idle.idleTimer", function(event, elem, obj){
	// 	        // function you want to fire when the user goes idle
	// 			console.log('Idle');

	// 	        var nfields = [];

	// 			$("#processo-form").children('div.fieldset').find('input,select').each(function() {
	// 				if($(this).val() && (!$(this).is('.money') && !$(this).is('.moeda') && !$(this).attr('readonly["readonly"]') && !$(this).closest('ul').is('.columns') && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden')){
	// 					if(jQuery.inArray($(this).attr('name'), nfields) === -1){
	// 						nfields.push($(this).attr('name'));
	// 					}
	// 				}				
	// 			});	 

	// 			console.log(nfields.length);

	// 			if(nfields.length >= 2){
	// 				var dataparam = $("#processo-form").serialize();

	// 		        $.ajax({
	// 		            type: 'POST',
	// 		            async: true,
	// 		            url: window.location.origin + '/functions/draft.php',
	// 		            data: dataparam,
	// 		            datatype: 'json',
	// 		            cache: true,
	// 		            global: false,
	// 		            beforeSend: function() { 
	// 		                $("#loader").css('display', 'flex');
	// 		            },
	// 		            success: function(data) {
	// 		                if(data){
	// 		                	// 
	// 		                	var json = jQuery.parseJSON(data);
	// 		                	window.location = window.location.origin + '/processo/' + json.uid + '/' + json.id + '?draft=true';
	// 		                }
	// 		            },
	// 		            complete: function() { 
	// 		                $("#loader").css('display', 'none');
	// 		            }
	// 		        }); 			
	// 			}
	// 	    }).on( "active.idleTimer", function(event, elem, obj, triggerevent){
	// 	        // function you want to fire when the user becomes active again
	// 	    	console.log('Active');
	// 	    	nfields = [];
	// 	    });									
	// 	} else {
	// 		var nfields = [], 
	// 			status = $('[name="status"]').val();

	// 		function progressBar(status){
	// 		    if($('.progressBar').length && status){
	// 		      var el = $('.progressBar');
	// 		      el.find('.progressStatus > span').html(status);
	// 		      el.find('.progressLoader > span').css('width', status+'%');
	// 		      el.find('.progressStatus').css('left', status+'%');
	// 		    } 					
	// 		}	

	// 		progressBar($('[name="status"]').val());				

	// 		$(this).children('div.fieldset').find('input,select').each(function() {
	// 			// - Filtra por tipo.
	// 			if($(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden'){
	// 				// - Filtra por classes.e names.
	// 				if(!$(this).is('.moeda') && !$(this).is('.money') && $(this).attr('name') != 'data-final-rodada[]' && $(this).attr('name') != 'data-inicial-rodada[]'){
	// 					// - Elimina readonlies.
	// 					if(!$(this).attr('readonly')){
	// 						fields.push($(this).attr('name'));
	// 					}
	// 				}
	// 			}
	// 			if(jQuery.inArray($(this).attr('name'), fields) !== -1){
	// 				if($(this).val()){
	// 					filled +=1;
	// 					nfields.push($(this).attr('name'));
	// 				}
	// 			}				
	// 		});		

	// 		$(this).find(".forms-footer .btn").click(function( event ) {
	// 		    // event.preventDefault();

	// 			$(this).closest('form').children('div.fieldset').find('input,select').each(function() {
	// 				if($(this).val() && (!$(this).is('.money') && !$(this).is('.moeda') && !$(this).attr('readonly["readonly"]') && !$(this).closest('ul').is('.columns') && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden')){
	// 					if(jQuery.inArray($(this).attr('name'), nfields) === -1){
	// 						nfields.push($(this).attr('name'));
	// 					}
	// 				} else if(!$(this).val() && (!$(this).is('.money') && !$(this).is('.moeda') && !$(this).attr('readonly["readonly"]') && !$(this).closest('ul').is('.columns') && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'hidden')){
	// 					if(jQuery.inArray($(this).attr('name'), nfields) !== -1){
	// 					    var index = nfields.indexOf($(this).attr('name'));
						 
	// 					    if (index > -1) {
	// 					       nfields.splice(index, 1);
	// 					    }							
	// 					}
	// 				}				
	// 			});	 

	// 			result = Math.ceil( 100 - ((fields.length - nfields.length)/fields.length) * 100 );

	// 			$('[name="status"]').val(result);

	// 			progressBar($('[name="status"]').val());
	// 		});
	// 	}

	// });	

	

	// $( '.remove-rodada' ).on('click', function(event) {
	//     event.preventDefault();

	//     if($(this).closest('li').index() > 0 && $(this).closest('li').is(':last-of-type')){
	// 	    // $(this).closest('li').remove();
	// 	  //   if($(this).closest('li').attr('data-origin')){
	// 	  //     // var vars = window.location.pathname,
	// 	  //     //     id = vars.split('/')[3],
	// 	  //     //     uid = vars.split('/')[2],
	// 	  //     //     position = parseInt($('.rodadas .columns').children().last().index());

	// 	  //     //   $.ajax({
	// 	  //     //       type: 'POST',
	// 	  //     //       async: true,
	// 	  //     //       url: window.location.origin + '/functions/delete.php',
	// 	  //     //       data: {
	// 	  //     //         'table' : 'rodadas',
	// 	  //     //         'pid' : id,
	// 	  //     //         'position' : position,
	// 	  //     //       },
	// 	  //     //       datatype: 'json',
	// 	  //     //       cache: true,
	// 	  //     //       global: false,
	// 	  //     //       beforeSend: function() { 
	// 	  //     //           $("#loader").css('display', 'flex'),
	// 	  //     //           $(this).closest('li').remove();
	// 	  //     //       },
	// 	  //     //       success: function(data) {
	// 	  //     //           console.log(data);
	// 	  //     //       },
	// 	  //     //       complete: function() { 
	// 	  //     //           $("#loader").css('display', 'none');
	// 	  //     //       }
	// 	  //     //   });  
	// 	  //   } else {
	// 		 // //    if(!$('#processo-form').is('.-edit')){

	// 			// // 	var counter = 0;

	// 			// // 	$(this).closest('li').find('input:not([readonly="readonly"])').each(function() {
	// 			// // 		if($(this).val()){
	// 			// // 			counter +=1;
	// 			// // 		}
	// 			// // 	});    		

	// 			// // 	rfields -=counter;

	// 			// // 	setTimeout(function(){
	// 			// // 		counter = 0;
	// 			// // 	}, 600);
	// 			// // }

	// 		 //    if($('.rodadas .columns').children().length % 2 === 0){
	// 		 //      nivel_rodada -=1;
	// 		 //    }
	// 	  //   }	  
	//     }
	// 	alert();
	// });
});
  