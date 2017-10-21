/**
 * Created by fenix on 01/04/2017.
 */
$(function(){
    $('#btnNew').on('click', function () {
        $('#titulo-modal').text("Nuevo Proveedor");
        $("#modalProvider").modal("show");
    });
    $('#btnProvider').on('click',function(){
        $('#providerForm').submit();
    });
    //*
    $('#providerTable').DataTable({
        stateSave: true,
        'ajax':document.location.protocol+'//'+document.location.host  +'/area/resource/proveedores',
        'createdRow':function(row,data,index){
            console.log("tipo: ",data.tipo, "status: ", data.status);
            if(data.status==1){
                if(data.tipo == 1) {
                    $('td', row).addClass("info");
                }else {
                    $('td', row).addClass("warning");
                }
            }else{
                $('td',row).addClass("danger");
            }
        },
        'columns':[
            {data:'nombre'},
            {data:'telefono1'},
            {data:'telefono2'},
            {data:'email'},
            {data:'contacto'},
            {data:function (row) {
                str = "<div align='center'>";
                str = "<div class='col-md-6'> ";
                str += " <button id='btnEditar' class='btn btn-primary block' onclick='showProvider(" + row['id'] + ");'><i class='glyphicon glyphicon-edit'></i></button> </div>";
                str += "<div class='col-md-6'> <button id='btnEliminar' class='btn btn-danger block' onclick='deleteProvider(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button> </div>";
                str += "</div>";
                return str;
            }
            }
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });
    // Validaciones
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        number: "Por favor, escribe un número válido.",
        digits: "Por favor, escribe sólo dígitos.",
        maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres.")
    });

    $('#providerForm').validate({
        rules: {
            nombre: {
                required: true,
                minlength:3
            },
            calle: {
                required: true,
                minlength:3
            },
            colonia: {
                required: true,
                minlength:3
            },
            estados:{
                required: true
            },
            municipio:{
                required: true
            },
            numero_int: {
                required: '#numero_ext:blank'
            },
            numero_ext: {
                required: '#numero_int:blank'
            },
            cp: {
                required: true,
                number:true
            },

            telefono1: {
                required: true,
                minlength:7,
                maxlength:20
            },
            telefono2: {
                minlength:7,
                maxlength:20
            },
            email: {
                required: true,
                email:true
            },
            contacto: {
                required: true,
                minlength:3
            }
        }
        ,
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            providerAction();
            return false;
        }

    });


});
$("select#estados").on('change',function(){
  var value = $(this).val();
    console.log(value);
    $('#municipio option').remove();
    $.ajax({
        url: document.location.protocol+'//'+document.location.host  +'/area/resource/proveedores/municipios/' + $(this).val(),
        type: 'GET'
    }).done(function(json){
        if(json.code===200){
            $('<option></option>', {text: "Seleccione un municipio"}).attr('value', "").appendTo('#municipio');
            $.each(json.msg, function (i, row) {
                $('<option></option>', {text: row.nombre}).attr('value', row.id).appendTo('#municipio');
            });
        }else{
            $('<option></option>', {text: "No se obtuvieron resultados"}).attr('value', "").appendTo('#municipio');
        }
    });

});
//
function providerAction(){
    if($("#id").val()==""){
        newProvider();
    }else{
        updateProvider($("#id").val());
    }
}
//
function newProvider(){
    var data = new FormData(document.getElementById("providerForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +"/area/resource/proveedores",
        type:"POST",
        data: data,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalProvider').modal("hide");
            $('#providerTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function updateProvider(id){
    $("#id").val(id);
    var datos = new FormData(document.getElementById("providerForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +'/area/resource/proveedores/update/'+id,
        type:"POST",
        data: datos,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalServices').modal("hide");
            $('#providerTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function deleteProvider(id){
    swal({
        title: '¿Estás seguro?',
        text: "Esto no se puede revertir!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo eliminarlo!',
        cancelButtonText: "Lo pensaré"
    }).then(function () {
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/proveedores/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#providerTable').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
//
function showProvider(id){
    $.ajax({
        url: document.location.protocol+'//'+document.location.host  +'/area/resource/proveedores/'+id,
        type: 'GET'
    }).done(function(json){
        if(json.code==200){
            reset();
            $("#id").val(json.msg.id);
            $("#nombre").val(json.msg.nombre);
            $("#calle").val(json.msg.calle);
            $("#colonia").val(json.msg.colonia);
            $("#numero_int").val(json.msg.numero_int);
            $("#numero_ext").val(json.msg.numero_ext);
            $("#cp").val(json.msg.cp);
            $("#estado").val(json.msg.estado);
            valor = json.msg.municipio;
            $.ajax({
                url: 'api/municipios/' + json.msg.estado,
                type: 'get'
            }).done(function(json){
                if(json.code===200){
                    $('<option></option>', {text: "Seleccione un municipio"}).attr('value', "").appendTo('#municipio');
                    $.each(json.msg, function (i, row) {
                        $('<option></option>', {text: row.nombre}).attr('value', row.id).appendTo('#municipio');
                    });
                    console.log(valor);
                    $("#municipio").val(valor);
                }else{
                    $('<option></option>', {text: "No se obtuvieron resultados"}).attr('value', "").appendTo('#municipio');
                }

            });
            $("#telefono1").val(json.msg.telefono1);
            $("#telefono2").val(json.msg.telefono2);
            $("#email").val(json.msg.email);
            $("#contacto").val(json.msg.contacto);

            $("#titulo-modal").text("Editar Proveedor");
            $("#modalProvider").modal("show");
        }
    }).fail(function(){
        swal("Error","No pudimos recuperar los datos","warning");
    });
}
//
function reset() {
    document.getElementById("providerForm").reset();
    $("#providerForm").validate().resetForm();
}

