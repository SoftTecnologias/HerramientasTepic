$(function(){


    $('#btnAceptar').on('click',function(){
       $('#serviceForm').submit();

    });
    /* Dependencia de los combos*/
    var tabla= $('#tblServicios').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host  +'/area/resource/servicios',
        "columnDefs": [
            { "width": "24%", "targets": [0,4,2,3]}
        ],
        columns: [
            {    data: function (row) {
                str = "";
                str = "<div align='center' >";
                str += " <img class=\"imagen\" src='../img/servicios/" + row['img'] + "' alt='" + row['id'] + "' style='height: 50px; width: 150px;'>";
                str += "</div>";
                return str;
            }},
            {data: 'title'},
            {data: 'shortdescription'},
            {data: 'longdescription'},
            {data: function (row) {
                // console.log(row);
                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
                str = "<div align='center'>";
                str +=" <button id='btnEditar' class='btn btn-primary btn-xs col-md-6' onclick='showService("
                    +"\""+Base64.encode(row['id'])+"\""+","
                    +"\""+row['title']+"\""+","
                    +"\""+row['shortdescription']+"\""+","
                    +"\""+row['longdescription']+"\""+","
                    +"\""+row['img']+"\""+")'> <i class='glyphicon glyphicon-edit'></i></button>";


                str += "<button id='btnEliminar' class='btn btn-danger btn-xs col-md-6'" +
                    "onclick='deleteService(\""+Base64.encode(row['id'])+"\")'><i class='fa fa-trash-o'></i></button>";
                 str += (row['show'] ==1) ?  "<input type='checkbox' id='check' name='check' checked onchange='verMiniatura("+"\""+Base64.encode(row['id'])+"\""+")'>":
                       "<input type='checkbox' id='check' name='check' onchange='verMiniatura("+"\""+Base64.encode(row['id'])+"\""+")'>";
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
    $('#serviceForm').validate({
        rules: {
            title: {
                required: true,
                maxlength: 150
            },
            shortdesc: {
                required: true,
                maxlength:200
            },
            longdesc: {
                required: true,
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
           servicesAction();
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
            $('#titulo-modal').text("Nuevo Servicio");
            reset();
            $('#modalServices').modal('show');
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

    function servicesAction(){
        if($("#serviceid").val()==""){
            newService();
        }else{
            updateService($("#serviceid").val());
        }
    }







});

function newService(){
    var data = new FormData(document.getElementById("serviceForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +"/area/resource/servicios",
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
            $('#modalServices').modal("hide");
            $('#tblServicios').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}

function reset(){
    document.getElementById("serviceForm").reset();
    $("#serviceForm").validate().resetForm();
    $('#im1').addClass("hidden");
    $('#im1').attr("src","");
}

function updateService(id){
    $("#serviceid").val(id);
    var datos = new FormData(document.getElementById("serviceForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +'/area/resource/servicios/'+id,
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
            location.reload(3);
            //$('#tblservicios').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}

function deleteService(id){
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
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/servicios/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tblServicios').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}

function showService(id,title, shortdescription, longdescription, photo){
    $('#titulo-modal').text("Editar Servicio");
     $('#serviceid').val(id);
     $('#title').val(title);
     $('#shortdesc').val(shortdescription);
     $('#longdesc').val(longdescription);
     $("#im1").attr("src","../img/servicios/"+photo);
     $('#im1').removeClass("hidden");
    $('#modalServices').modal("show");

}

function verMiniatura(id) {
    var texto1 = "";
    if($('#check').prop('checked')){
        texto1 = 'El servicio se mostrara en la pagina Principal';
    }else{
        texto1 = 'El servicio se dejara de mostrar en la pagina principal';
    }
    swal({
        title: '¿Estás seguro?',
        text: texto1,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: "Cancelar"
    }).then(function () {
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/servicio/'+id;
        if($('#check').prop('checked')){
            $('#no').val(1);
        }else{
            $('#no').val(0);
        }
        var datos = new FormData(document.getElementById('divc'));
        $.ajax({
            url:ruta,
            type:"POST",
            data: datos,
            contentType:false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tblservicios').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
