$(document).ready(function(e) {
    $("#estado").on('change', function () {
        var estadoseleccionado = $("#estado option:selected").val();
        if (estadoseleccionado != '00') {
            $.ajax({
                url: document.location.protocol + '//' + document.location.host + '/getMunicipios/' + estadoseleccionado,
                type: 'GET'
            }).done(function (response) {
                if (response.code == 200) {
                    $('#municipio option').remove();
                    $('<option>', {
                        text: 'Seleccione un Municipio'
                    }).attr('value', "00").appendTo('#municipio');
                    $.each($.parseJSON(response.msg), function (i, row) {
                        $('<option>', {text: row.nombre}).attr('value', row.id_municipio).appendTo('#municipio');
                    });
                }
            }).fail(function () {

            });
        }
    });
    $("#municipio").on('change', function () {
        var localidadseleccionada = $("#municipio option:selected").val();
        if (localidadseleccionada != '00') {
            $.ajax({
                url: document.location.protocol + '//' + document.location.host + '/getLocalidades/' + localidadseleccionada,
                type: 'GET'
            }).done(function (response) {
                if (response.code == 200) {
                    $('#localidad option').remove();
                    $('<option>', {
                        text: 'Seleccione una Localidad'
                    }).attr('value', "00").appendTo('#localidad');
                    $.each($.parseJSON(response.msg), function (i, row) {
                        $('<option>', {text: row.nombre}).attr('value', row.id_localidad).appendTo('#localidad');
                    });
                }
            }).fail(function () {

            });
        }
    });
    $("#guardarDir").on('click',function () {
       if(validar() == false){return;}
        var data = {
            estado:$("#estado").val(),
            municipio:$("#municipio").val(),
            localidad:$("#localidad").val(),
            calle1:$("#calle1").val(),
            calle2:$("#calle2").val(),
            calle3:$("#calle3").val(),
            num:$("#numext").val(),
            cp:$("#cp").val(),
            ref:$("#ref").val()
        };
        $.ajax({
            url:document.location.protocol+'//'+document.location.host  +'/user/direccion',
            type:"POST",
            data: data,
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
});
function validar() {
    if($("#estado").val() == 00 || $("#municipio").val() == 00 || $("#localidad").val() == 00 ||
        $("#calle1").val() == '' || $("#calle2").val() == '' || $("#calle3").val() == '' ||
        $("#numext").val() == '' ||$("#cp").val() == '' || $("#ref").val() == ''){
        swal('error','Rellene todos los campos','');
        return false;
    }
}