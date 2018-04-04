/**
 * Created by fenix on 26/06/2017.
 */
$(function () {
  $('#btnLogin').on('click',function(){
      $.ajax({
          url:document.location.protocol+'//'+document.location.host  + "/usuario/login",
          type:"POST",
          data:{
              email: $("#email").val(),
              password: $("#password").val()
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      }).done(function(response){
            if(response.code == 200){
                //Aqui se inicia sesion con el cliente
                window.location=document.location.protocol+'//'+document.location.host+"/";
            }else{
                    if(response.code == 403) {
                        //parte de los clientes redirigimos al index (primero generando la cookie)
                        swal("Error",response.msg,'warning');
                        window.location = document.location.protocol + '//' + document.location.host  + "/area";
                    }else {
                        swal("Error",response.msg,response.detail);
                    }
            }
      }).fail(function(){
          swal("Error","No pudimos conectarnos al servidor","error");
      });
  });
    /*$(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            console.log('enter capturado');
        }
    });*/
});