//tab js//
$(document).ready(function(e) {

    $("#editper").on('click',function () {
       $("#nombre").prop('disabled',false);
        $("#nombre").focus();
       $("#apellido").prop('disabled',false);
       $("#acciones").prop('hidden',false);
    });
    $("#edpas").on('click',function () {
        $("#passactual").prop('disabled',false);
        $("#passactual").focus();
        $("#newpass").prop('disabled',false);
        $("#confirmpass").prop('disabled',false);
    });
    $("#editcontact").on('click',function () {
        $("#calle1").prop('disabled',false);
        $("#calle2").prop('disabled',false);
        $("#calle3").prop('disabled',false);
        $("#cp").prop('disabled',false);
        $("#ref").prop('disabled',false);
        $("#tel").prop('disabled',false);
        $("#mail").prop('disabled',false);
        $("#accionesc").prop('hidden',false);
        $("#selestado").prop('disabled',false);
        $("#selciudad").prop('disabled',false);
        $("#sellocalidad").prop('disabled',false);
        $("#numext").prop('disabled',false);
    });
    $("#selestado").on('change',function () {
        var estadoseleccionado = $("#selestado option:selected").val();
        $("#sellocalidad").empty().append("<option value='000'>Seleccione una Localidad</option>");
            if(estadoseleccionado != '00'){
                $.ajax({
                    url:document.location.protocol+'//'+document.location.host+'/getMunicipios/'+estadoseleccionado,
                    type:'GET'
                }).done(function(response){
                    if(response.code == 200){
                        $('#selciudad option').remove();
                        $('<option>',{
                            text:'Seleccione un Municipio'
                        }).attr('value',"000").appendTo('#selciudad');
                        $.each($.parseJSON(response.msg),function(i, row){
                            $('<option>',{text:row.nombre}).attr('value',row.id_municipio).appendTo('#selciudad');
                        });
                    }
                }).fail(function(){

                });
            }
    });
    $("#selciudad").on('change',function () {
        var localidadseleccionada = $("#selciudad option:selected").val();
        console.log(localidadseleccionada);
        if(localidadseleccionada != '000'){
            $.ajax({
                url:document.location.protocol+'//'+document.location.host+'/getLocalidades/'+localidadseleccionada,
                type:'GET'
            }).done(function(response){
                if(response.code == 200){
                    $('#sellocalidad option').remove();
                    $('<option>',{
                        text:'Seleccione una Localidad'
                    }).attr('value',"0000").appendTo('#sellocalidad');
                    $.each($.parseJSON(response.msg),function(i, row){
                        $('<option>',{text:row.nombre}).attr('value',row.id_localidad).appendTo('#sellocalidad');
                    });
                }
            }).fail(function(){

            });
        }
    });
    $("#guardarper").on('click',function () {
        var id = $("#userid").val();
        var datos = {'nombre':$('#nombre').val(),apellido:$('#apellido').val()};
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+'/user/profile/'+id,
            type:"POST",
            data: datos,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                swal("Realizado", json.msg, json.detail).then(function () {
                    location.reload();
                });
            }else{

                swal("Error",json.msg,json.detail).then(function () {
                    location.reload();
                });
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });
    $("#guardarcontact").on('click',function () {
        var id = $("#userid").val();
        var datos = {
                    estado:$('#selestado').val(),
                    municipio:$('#selciudad').val(),
                    localidad:$('#sellocalidad').val(),
                    calle1:$('#calle1').val(),
                    calle2:$('#calle2').val(),
                    calle3:$('#calle3').val(),
                    cp:$('#cp').val(),
                    tel:$('#tel').val(),
                    email:$('#mail').val(),
                    ref:$('#ref').val(),
                    numext:$('#numext').val()
        };
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+'/user/profile/contact/'+id,
            type:"POST",
            data: datos,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                swal("Realizado", json.msg, json.detail).then(function () {
                    location.reload();
                });
            }else{

                swal("Error",json.msg,json.detail).then(function () {
                    location.reload();
                });
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });
    $("#changepass").on('click',function () {
        if($('#newpass').val() != '' || $('#passactual').val() != ''){
            if($('#newpass').val() != $('#confirmpass').val()){
                swal("Error",'Las contraseñas no coinciden','');
            }else{
                cambiaPass();}
    }else{
            swal("Error",'Escriba una contraseña','');
        }
    });
    $("#cancelar").on('click',function () {
       location.reload();
    });
    $("#cancelarc").on('click',function () {
        location.reload();
    });
    $("#foto").on('change',function (event) {
        var id = $("#userid").val();
         data = new FormData(document.getElementById("passform"));
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+'/user/profile/imagen/'+id,
            type:"POST",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            processData: false
        }).done(function(json){
            if(json.code == 200) {
                swal("Realizado", json.msg, json.detail).then(function () {
                    location.reload();
                });
            }else{

                swal("Error",json.msg,json.detail).then(function () {
                    location.reload();
                });
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });
    $('.form').find('input, textarea').on('keyup blur focus', function (e) {

        var $this = $(this),
            label = $this.prev('label');

        if (e.type === 'keyup') {
            if ($this.val() === '') {
                label.removeClass('active highlight');
            } else {
                label.addClass('active highlight');
            }
        } else if (e.type === 'blur') {
            if( $this.val() === '' ) {
                label.removeClass('active highlight');
            } else {
                label.removeClass('highlight');
            }
        } else if (e.type === 'focus') {

            if( $this.val() === '' ) {
                label.removeClass('highlight');
            }
            else if( $this.val() !== '' ) {
                label.addClass('highlight');
            }
        }

    });


    $('.tab a').on('click', function (e) {

        e.preventDefault();

        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
        target = $(this).attr('href');

        $('.tab-content > div').not(target).hide();

        $(target).fadeIn(600);

    });
//canvas off js//
    $('#menu_icon').click(function(){
        if($("#content_details").hasClass('drop_menu'))
        {
            $("#content_details").addClass('drop_menu1').removeClass('drop_menu');
        }
        else{
            $("#content_details").addClass('drop_menu').removeClass('drop_menu1');
        }


    });

//search box js//


    $("#flip").click(function(){
        $("#panel").slideToggle("5000");
    });

// sticky js//

    $(window).scroll(function(){
        if ($(window).scrollTop() >= 500) {
            $('nav').addClass('stick');
        }
        else {
            $('nav').removeClass('stick');
        }
    });

});
function cambiaPass(){
    swal({
        title: '¿Estás seguro?',
        text: "Esto no se puede revertir!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo eliminarlo!',
        cancelButtonText: "Lo pensaré"
    }).then(function(){
        var id = $("#userid").val();
        var datos = {
            actual:$('#passactual').val(),
            nueva:$('#newpass').val(),
            confirm:$('#confirmpass').val()
        };
        $.ajax({
            url:document.location.protocol+'//'+document.location.host+'/user/profile/pass/'+id,
            type:"POST",
            data: datos,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                swal("Realizado", json.msg, json.detail).then(function () {
                    location.reload();
                });
            }else{

                swal("Error",json.msg,json.detail).then(function () {
                    location.reload();
                });
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });

}
function infocompra(id){
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+'/user/infocompra/'+id,
        type:'GET'
    }).done(function(response){
        if(response.code == 200){
            $('#info tr').remove();
            $.each($.parseJSON(response.msg),function(i, row){
                var nuevaFila="<tr>";
                nuevaFila+="<td>"+row['producto']+"</td>";
                nuevaFila+="<td>"+row['marca']+"</td>";
                nuevaFila+="<td>"+row['cantidad']+"</td>";
                nuevaFila+="<td>"+row['preciounitario']+"</td>";
                nuevaFila+="<td>"+(row['preciounitario']*row['cantidad'])+"</td>";
                nuevaFila+="<td>"+row['divisa']+"</td>";
                nuevaFila+="</tr>";
                $("#info").append(nuevaFila);
            });
        }
    }).fail(function(){

    });
 $('#modalinfo').modal('show');
}
