
function getUrlParameter(name) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
// function percent(){
//     // Get the div you want to look in.
//     var div = document.getElementById("processo-form");
//     // Get all the input fields inside your div
//     var inputs = div.querySelectorAll('#processo-form > .fieldset > span > input, #sociedades li > div > input, .rodadas .columns > li > .fieldset input');

//     // Get the number of the found inputs.
//     var totalInputs = inputs.length;

//     // Loop through them and check which of them has a value.
//     var inputsWithValue = 0;

//     for(var i=0; i<totalInputs; i++){
//         if(inputs[i].value!==''){
//           inputsWithValue +=1;   
//         }
//     }

//     var filledPercentage = ((inputsWithValue/totalInputs)*100);   
//     $('[name="status"]').val(filledPercentage.toString().split(".")[0]);      

//     if($('.progressBar').length){
//       var el = $('.progressBar');
//       el.find('.progressStatus > span').html(filledPercentage.toString().split(".")[0]);
//       el.find('.progressLoader > span').css('width', filledPercentage.toString().split(".")[0] + '%');
//       el.find('.progressStatus').css('left', (filledPercentage.toString().split(".")[0] - 2) + '%');
//     }  
// }
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

var nivel_rodada = parseInt($('.rodadas .columns').children().last().find('.nivel_rodada').val());

function addMultipleInput(e){
    var el = $(e).closest('.rodadas').find('.columns').children();

    console.log(nivel_rodada);

    if($('.rodadas .columns').children().length % 2 === 0){
      nivel_rodada +=1;
    }

    if($('.rodadas .columns').children().last().index() % 2 === 0){
      $val = 'Rodada Técnica';
    } else {
      $val = 'Rodada Comercial';
    }

    el.eq(0).clone(true).appendTo($(e).closest('.rodadas').find('.columns')).closest('li').find('.nivel_rodada').val(nivel_rodada).attr('value',nivel_rodada).closest('li').attr('data-origin', '').find('input[type="date"]').val('').closest('li').find('.rodadas-footer').closest('li').find('[name="rodada-tipo[]"]').val($val).attr('value',$val);
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
$(document).ready(function () {
    $('#senha').on('keypress', function() {
      $(this).attr('type', 'text');
    }).on('keyup blur', function() {
      setTimeout(function(){ 
        $(this).attr('type', 'password');
      }, 300);
    });

    $( "#subfamilia" ).find('select').on( "change", function() {
        if(!$(this).closest('.fieldset').find($('.subfamilia_result')).length){
            $('<div class="subfamilia_result"/>').appendTo($(this).closest('.fieldset'));
        }
        $('.subfamilia_result').html($(this).val());
    });

    // if(!$('#processo-form.-edit').length){
    //   if($('#processo-form').length){
    //     $('#processo-form').children('div.fieldset').addClass('disabled'),
    //     $('#processo-form').children('div.fieldset').first().removeClass('disabled');     
    //   }
    // }

    // if($('.register.processo').length){
    //   $( ".register.processo input" ).each(function() {
    //       $(this).on('blur', function (e) {
    //           if($(this).val()){
    //               // percent();
    //           }
    //       });
    //   });            
    // }

    // var fields = [],
    //     fields__notIn = ['[class*="nome_processo"],[class*="numero_processo"],[class*="tipo_processo"]'],
    //     inputsWithValue = 0,
    //     chosen_byType = null;

    // $('[name="tipo_processo"]').on('change', function() {
    //   // $(this).closest('div.fieldset:visible').nextAll('div.fieldset:visible').fadeOut();
    //   chosen_byType = true;
    //   fields = [];
      
    //   switch($(this).val()) {
    //     case 'ADJUDICAÇÃO DIRETA':
    //       fields.push('[class*="requisicao"]','[class*="responsavel"]','[class*="id_comprador"]','[class*="id_rodadas"]','[class*="id_negociacao"]','[class*="id_elaboracao_pa"]','[class*="id_workflow"]','[class*="id_disponivel_sap"]');
    //       // $(this).closest('form').children('div.fieldset:not()').fadeOut();
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     case 'LICITAÇÃO':
    //     case 'LEILÃO':
    //       fields.push('[class*="requisicao"]','[class*="responsavel"]','[class*="id_comprador"]','[class*="id_estrategia"]','[class*="id_rodadas"]','[class*="id_negociacao"]','[class*="id_elaboracao_pa"]','[class*="id_leilao"]','[class*="id_workflow"]','[class*="id_criacao_de_pedido"]','[class*="id_tramite_assinatura"]','[class*="id_disponivel_sap"]');
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     case 'RENOVAÇÃO':
    //     case 'PCAE':
    //     case 'RAMPA DE SAÍDA':
    //     case 'RÉPLICA GLOBAL':
    //       fields.push('[class*="requisicao"]','[class*="responsavel"]','[class*="id_comprador"]','[class*="id_rodadas"]','[class*="id_elaboracao_pa"]','[class*="id_workflow"]','[class*="id_criacao_de_pedido"]','[class*="id_tramite_assinatura"]','[class*="id_disponivel_sap"]');
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     default:
    //       // code block
    //   }
    //   // $(this).closest('form').children(fields.toString()).on('change', function() {
    //   //   for(var i=0; i<inputs_length; i++){
    //   //       if($(fields.toString().split(',')[i]).value!==''){
    //   //         inputsWithValue +=1;   
    //   //       } else {
    //   //         inputsWithValue -=1; 
    //   //       }
    //   //   }   
    //   // });

    //   // console.log(inputsWithValue);
    //   if(window.location.pathname.split('/')[2] && window.location.pathname.split('/')[3]){
    //     $(this).closest('form').children('.fieldset:not('+ fields__notIn.toString() +')').removeClass('chosen_byType').addClass('disabled').fadeOut(),
    //     $(this).closest('form').children(fields.toString()).addClass('chosen_byType').fadeIn().removeClass('disabled');
    //   } else {
    //     $(this).closest('form').children('.fieldset:not('+ fields__notIn.toString() +')').removeClass('chosen_byType').addClass('disabled').fadeOut(),
    //     $(this).closest('form').children(fields.toString()).addClass('chosen_byType').fadeIn().first().removeClass('disabled');  
    //   }
    // });

    // if(window.location.pathname.split('/')[2] && window.location.pathname.split('/')[3]){
    //   var tipo = $('[name=tipo_processo]').val();
    //   switch(tipo) {
    //     case 'ADJUDICAÇÃO DIRETA':
    //       fields.push('[class*="responsavel"]','[class*="requisicao"]','[class*="id_comprador"]','[class*="id_rodadas"]','[class*="id_negociacao"]','[class*="id_elaboracao_pa"]','[class*="id_workflow"]','[class*="id_disponivel_sap"]');
    //       // $(this).closest('form').children('div.fieldset:not()').fadeOut();
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     case 'LICITAÇÃO':
    //     case 'LEILÃO':
    //       fields.push('[class*="responsavel"]','[class*="requisicao"]','[class*="id_comprador"]','[class*="id_estrategia"]','[class*="id_rodadas"]','[class*="id_negociacao"]','[class*="id_elaboracao_pa"]','[class*="id_leilao"]','[class*="id_workflow"]','[class*="id_criacao_de_pedido"]','[class*="id_tramite_assinatura"]','[class*="id_disponivel_sap"]');
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     case 'RENOVAÇÃO':
    //     case 'PCAE':
    //     case 'RAMPA DE SAÍDA':
    //     case 'RÉPLICA GLOBAL':
    //       fields.push('[class*="responsavel"]','[class*="requisicao"]','[class*="id_comprador"]','[class*="id_rodadas"]','[class*="id_elaboracao_pa"]','[class*="id_workflow"]','[class*="id_criacao_de_pedido"]','[class*="id_tramite_assinatura"]','[class*="id_disponivel_sap"]');
    //       var inputs_length = $(this).closest('div.fieldset').nextAll('div.fieldset[style=""]').length;
    //       break;
    //     default:
    //       // code block
    //   }
    //   $('form').children('.fieldset:not('+ fields__notIn.toString() +')').removeClass('chosen_byType').addClass('disabled').fadeOut(),
    //   $('form').children(fields.toString()).addClass('chosen_byType').fadeIn().removeClass('disabled');
    //   if($('.progressBar').length){
    //     var el = $('.progressBar');
    //     el.find('.progressStatus > span').html($('[name="status"]').val());
    //     el.find('.progressLoader > span').css('width', $('[name="status"]').val()+'%');
    //     el.find('.progressStatus').css('left', $('[name="status"]').val()+'%');
    //   } 
    // }

    // var inputs = 0,
    //     chosen_inputs = 0;

    // $( ".register.processo div.fieldset input, .register.processo div.fieldset select" ).each(function() {
    //   $(this).on('change', function (e) {
    //     var next_step = $(this).closest('div.fieldset').next('div.fieldset');
    //     var all_next_steps = $(this).closest('div.fieldset').nextAll('div.fieldset');
        
    //     var chosen_inputs_length = $(this).closest('form').find('div.fieldset.chosen_byType input, div.fieldset.chosen_byType select').length;
    //     var inputs_length = $(".register.processo div.fieldset:not(.chosen_byType) input, .register.processo div.fieldset:not(.chosen_byType) select").length;
        
    //     if ($(this).val()) {
    //         // Should also perform validation here
    //         if($(this).closest('div.fieldset').is('.chosen_byType')){
    //           var all_next_steps = $(this).closest('div.fieldset').nextAll('div.fieldset.chosen_byType');
    //           var next_step = $(this).closest('div.fieldset').nextAll('div.fieldset.chosen_byType').first();
    //           chosen_inputs +=1;
    //         }
    //         inputs +=1;
    //         next_step.removeClass('disabled');
    //     }
    //     // // If the element doesn't have a value
    //     else {
    //         // Clear the value of all next steps and disable
    //         if($(this).closest('div.fieldset').is('.chosen_byType')){
    //           chosen_inputs -=1;
    //         }
    //         inputs -=1;
    //         all_next_steps.find('input, select').val('').addClass('disabled');
    //     }

    //     // var inputs : submited inputs
    //     // var chosen_inputs + 3 (Obrigatorios) : contagem com chosen inputs

    //     console.log(
    //       chosen_inputs + '/' + 
    //       chosen_inputs_length + ' => ' + 
    //       inputs + '/' + inputs_length + ' | ' + 
    //       chosen_inputs + '/' + inputs + ' # ' +
    //       ((chosen_inputs / chosen_inputs_length) * 100) + '% / ' +
    //       ((chosen_inputs / inputs) * 100) + '% / ' +
    //       ((inputs / inputs_length) * 100) + '%'
    //     );

    //     if($(this).closest('div.fieldset').is('.chosen_byType')){
    //       // console.log('Percent: ' + ((chosen_inputs / chosen_inputs_length) * 100) + '%');
    //       var percent = ((chosen_inputs / chosen_inputs_length) * 100).toString();
    //     } else {
    //       // console.log('Percent: ' + ((inputs / inputs_length) * 100) + '%');
    //       var percent = ((inputs / inputs_length) * 100).toString();
    //     }

    //     if($('.progressBar').length){
    //       var el = $('.progressBar');
    //       el.find('.progressStatus > span').html(Math.ceil(parseInt(percent)));
    //       el.find('.progressLoader > span').css('width', Math.ceil(parseInt(percent))+'%');
    //       el.find('.progressStatus').css('left', percent+'%');
    //       $('[name="status"]').val(Math.ceil(parseInt(percent)));
    //     }  

    //   });
    // });

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

    $( '.remove-rodada' ).click(function( event ) {
        event.preventDefault();

        

        if($(this).closest('li').attr('data-origin')){
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
                    $('.rodadas .columns').children().eq(position).fadeOut();
                },
                success: function(data) {
                    console.log(data);
                },
                complete: function() { 
                    $("#loader").css('display', 'none');
                }
            });  
        }

        $(this).closest('li').remove();

        if($('.rodadas .columns').children().length % 2 === 0){
          nivel_rodada -=1;
        }   
    });   
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

  $( ".rodadas .columns" ).children().each(function() {
    if($(this).index() == 0){
      $(this).find('.rodadas-footer').closest('li').find('[name="rodada-tipo[]"]').val('Rodada Comercial');
    }
  });    
});
      
      