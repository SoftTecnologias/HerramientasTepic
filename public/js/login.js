/**
 * Created by fenix on 26/06/2017.
 */
$(function () {
  $('#btnLogin').on('click',function(){
      $.ajax({
          url:document.location.protocol+'//'+document.location.host+ "/login",
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
                console.log(response.msg);
                if(response.msg['rol']!= 1 ){
                    //parte de los trabajadores
                   location.reload();
                }else{
                    //parte de los clientes redirigimos al index (primero generando la cookie)
                    window.href=document.location.protocol + '//' + document.location.host+ "/";
                }
            }else{
                console.log('No se hizo');
                swal("Error",response.msg,response.detail);
            }
      }).fail(function(){
          swal("Error","No pudimos conectarnos al servidor","Error");
            console.log('ni conecto');
      });
  });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            console.log('enter capturado');
        }
    });
});