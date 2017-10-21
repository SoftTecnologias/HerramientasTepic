$(function(){


    $('#btnAceptar').on('click',function(){
        $('#bannerForm').submit();

    });
    /* Dependencia de los combos*/
    var tabla= $('#tblBanner').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host  +'/area/resource/banner',
        "columnDefs": [
            { "width": "35%", "targets": [0,2,3]}
        ],
        columns: [
            {    data: function (row) {
                str = "";
                str = "<div align='center' >";
                str += " <img class=\"imagen\" src='../img/banner/" + row['img'] + "' alt='" + row['id'] + "' style='height: 50px; width: 150px;'>";
                str += "</div>";
                return str;
            }},
            {data: 'titulo'},
            {data: 'contenido'},
            {data: function (row) {
                // console.log(row);
                str = "<div align='center'>";
                str +=" <button id='btnEditar' class='btn btn-primary btn-xs col-md-6' onclick='showBanner("
                    +"\""+row['id']+"\""+","
                    +"\""+row['titulo']+"\""+","
                    +"\""+row['contenido']+"\""+","
                    +"\""+row['img']+"\""+")'> <i class='glyphicon glyphicon-edit'></i></button>";


                str += "<button id='btnEliminar' class='btn btn-danger btn-xs col-md-6'" +
                    "onclick='deleteBanner(\""+row['id']+"\")'><i class='fa fa-trash-o'></i></button>";
                str += "</div>";
                return str;
            }}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
    // Apply the search
    /*Validacion de los archivos*/

    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        number: "Por favor, escribe un número válido.",
        digits: "Por favor, escribe sólo dígitos.",
        maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
    });
    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    $('#bannerForm').validate({
        rules: {
            title: {
                required: true,
                maxlength: 100
            },
            contenido: {
                required: true,
                maxlength:120
            },
            img1: {
                extension: "png|jpg|gif",
                filesize: 1048576
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
            bannerAction();
            return false;
        }

    });

    //Diseño del seleccionador de imagenes
    $("#img1").fileinput({
        allowedFileTypes: ["image"],
        showUpload:false,
        browseClass: "btn btn-success",
        browseLabel: "Elegir Foto",
        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "Eliminar",
        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
        maxFileSize: 1024,
    });

    //Abrir Modal
    $(function(){
        $('#btnNew').on('click',function(){
            $('#titulo-modal').text("Nuevo Banner");
            reset();
            $('#modalBanner').modal('show');
        });
    });
    /* Inputs con url*/
    $("#imgu").on("change",function(){
        if($(this).val()!==""){
            $('#im1').attr("src",$(this).val());
            $('#im1').removeClass("hidden");
        }else{
            $('#im1').addClass("hidden");
        }
    });

    function bannerAction(){
        if($("#bannerid").val()==""){
            newBanner();
        }else{
            updateBanner($("#bannerid").val());
        }
    }







});

function newBanner(){
    var data = new FormData(document.getElementById("bannerForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +"/area/resource/banner",
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
            $('#modalBanner').modal("hide");
            $('#tblBanner').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}

function reset(){
    document.getElementById("bannerForm").reset();
    $("#bannerForm").validate().resetForm();
    $('#im1').addClass("hidden");
    $('#im1').attr("src","");
}

function updateBanner(id){
    $("#bannerid").val(id);
    var datos = new FormData(document.getElementById("bannerForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +'/area/resource/banner/'+id,
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
            $('#modalbanner').modal("hide");
            $('#tblBanner').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}

function deleteBanner(id){
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
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/banner/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tblBanner').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}

function showBanner(id,title, longdescription, photo){
    $('#titulo-modal').text("Editar Banner");
    $('#bannerid').val(id);
    $('#title').val(title);
    $('#contenido').val(longdescription);
    $("#im1").attr("src","../img/banner/"+photo);
    $('#im1').removeClass("hidden");
    $('#modalBanner').modal("show");

}

