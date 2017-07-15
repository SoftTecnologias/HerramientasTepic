/**
 * Created by fenix on 19/03/2017.
 */
$(function(){
    $('#btnNew').on('click', function () {
        reset();
        $('#titulo-modal').text("Nueva marca");
        $("#modalBrand").modal("show");
    });
    $('#btnBrand').on('click',function(){
        $('#brandForm').submit();
    });
    var table = $('#brandTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host  +'/area/resource/marcas',
        'columns':[
            {
              data:function(row){
                  var str="";
                  str = "<div align='center col-md-4'>";
                  str += "<img class=\"img-responsive \" src='../img/marcas/" + row['logo'] + "' alt='" + row['id'] + "'>";
                  str += "</div>";
                  return str;
              }
            },{
               data:'name'
            },{
               data:function (row) {
                   var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
                   str  = "<div align='center'>";
                   str +=   "<button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showBrand(" + row['id'] +", \""+row['name']+"\", \""+row['logo']+"\")'><i class='glyphicon glyphicon-edit'></i></button>";
                   str +=   "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteBrand(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
            str += (row['authorized'] ==1) ?  "<input type='checkbox' id='check' name='check' checked onchange='verMiniatura("+"\""+Base64.encode(row['id'])+"\""+")'>Distribuidor Authorizado":
                       "<input type='checkbox' id='check' name='check' onchange='verMiniatura("+"\""+Base64.encode(row['id'])+"\""+")'>Distribuidor Authorizado";
                   str += "</div>";
                   return str;
               }
            }
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });

    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    $('#brandForm').validate({
        rules:{
            'nombre':{
                required:true
            },
            'img': {
                extension: "png|jpg|gif",
                filesize: 1048576
            }
        },
        messages:{
            'nombre':{
                required:"Ingrese el nombre de la marca"
            },
            'img':{
                extension:"Solo se permiten extensiones png, jpg y gif"
            }
        },
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
        submitHandler: function () {
            brandAction();
            return false;
        }
    });

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
    /* Inputs con url*/
    $("#imgu1").on("change",function(){
        if($(this).val()!==""){
            $('#im1').attr("src",$(this).val());
            $('#im1').removeClass("hidden");
        }else{
            $('#im1').addClass("hidden");
        }
    });
});

function brandAction() {
    if($('#brandid').val()=="")
        newBrand();
    else
        updateBrand($("#brandid").val());
}
function newBrand(){
    var data = new FormData(document.getElementById("brandForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +"/area/resource/marcas",
        type:'POST',
        data: data,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalBrand').modal("hide");
            $('#brandTable').dataTable().api().ajax.reload(null, false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function updateBrand(id){
    $("#brandid").val(id);
    var data = new FormData(document.getElementById("brandForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host  +"/area/resource/marcas/"+id,
        type:"post",
        data: data,
        contentType:false,
        processData: false
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalBrand').modal("hide");
            $('#brandTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function deleteBrand(id){
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
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/marcas/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#brandTable').dataTable().api().ajax.reload();
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showBrand(id, name, logo){
            $("#brandid").val(id);
            $("#name").val(name);
            $("#im1").attr("src","../img/marcas/"+logo);
            $('#im1').removeClass("hidden");
            $("#titulo-modal").text("Editar Marca");
            $("#modalBrand").modal("show");
}
function reset(){
    document.getElementById("brandForm").reset();
    $("#brandForm").validate().resetForm();
    $('#titulo-modal').text("");
    $("#brandid").val("");
    $('#name').val("");
    $('#img').val("");
}

function verMiniatura(id) {
    var texto1 = "";
    if($('#check').prop('checked')){
        texto1 = 'Las Marcas Authorizadas se mostraran en la pagina Principal';
    }else{
        texto1 = 'Esta marca se dejara de mostrar en la pagina principal';
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
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/marcaauthorizada/'+id;
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
                $('#brandTable').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
