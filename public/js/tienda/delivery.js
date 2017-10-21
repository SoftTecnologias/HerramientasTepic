/**
 * Created by cared on 08/09/2017.
 */
$(function(){
    $("input[name=delivery]").on("change",function(){
        if ( $("input[name=delivery]:checked").val() > 2 ){ // directo al summary
            $("#btnContinuar").text("Continuar con la captura de direcciones");
        }else{ //Sigue con la dirección
            $("#btnContinuar").text("Continuar con el resumen de pago");
        }
    });
});

