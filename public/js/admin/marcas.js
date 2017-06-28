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
                   str  = "<div align='center'>";
                   str +=   "<button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showBrand(" + row['id'] +", \""+row['name']+"\", \""+row['logo']+"\")'><i class='glyphicon glyphicon-edit'></i></button>";
                   str +=   "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteBrand(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
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

