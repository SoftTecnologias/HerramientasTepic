$(function(){
    $('#btnNew').on('click', function () {
        $('#titulo-modal').text("Nueva entrada");
        $("#modalBlog").modal("show");
    });
    $('#btnBlog').on('click',function(){
        $('#blogForm').submit();
    });
    $('#blogTable').DataTable({
        'ajax':{
            url:'api/blogs',
            dataSrc : function(json){
                return json;
            }
        }
        ,
        'columns':[
            {
                data:function(row){
                    var str="";
                    str = "<div align='center col-md-4'>";
                    str += "<img class=\"img-responsive \" src='../images/blogs/" + row['img'] + "' alt='" + row['id'] + "'>";
                    str += "</div>";
                    return str;
                }
            },{
                data:'titulo'
            },{
                data:'descripcion'
            },{
                data:'fecha'
            },{
                data:function (row) {
                    str  = "<div align='center'>";
                    str +=   "<button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showBlog(" + row['id'] + ");'><i class='glyphicon glyphicon-edit'></i></button>";
                    str +=   "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteBlog(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
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
    $('#blogForm').validate({
        rules:{
            'title':{
                required:true
            },
            'description':{
                required:true
            },
            'img': {
                extension: "png|jpg|gif",
                filesize: 1048576
            }
        },
        messages:{
            'title':{
                required:"Ingrese el nombre de la marca"
            },
            'description':{
                required: 'Debe escribir el contenido de esta entrada'
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
            blogAction();
            return false;
        }
    });

    /*files de las imagenes*/
    $("#img").fileinput({
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
    $("#imgu").on("change",function(){
        if($(this).val()!==""){
            $('#im1').attr("src",$(this).val());
            $('#im1').removeClass("hidden");
        }else{
            $('#im1').addClass("hidden");
        }
    });
});

function blogAction() {
    if($('#id').val()=="")
        newBlog();
    else
        updateBlog($("#id").val());
}
function newBlog(){
    data = new FormData(document.getElementById("blogForm"));

    $.ajax({
        url:"api/blogs",
        type:'post',
        data: data,
        dataType:'json',
        contentType:false,
        processData: false
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalBlog').modal("hide");
            $('#blogTable').dataTable().api().ajax.reload();
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });

}
function updateBlog(id){
    $("#id").val(id);
    var data = new FormData(document.getElementById("blogForm"));
    $.ajax({
        url:"api/blogs/"+id,
        type:"post",
        data: data,
        contentType:false,
        processData: false
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalBlog').modal("hide");
            $('#blogTable').dataTable().api().ajax.reload( null, false );
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function deleteBlog(id){
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
        ruta ='api/blogs/'+id;
        $.ajax({
            url:ruta,
            type:'delete'
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#blogTable').dataTable().api().ajax.reload();
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showBlog(id){
    $.ajax({
        url: "api/blogs/"+id,
        type: 'GET'
    }).done(function(json){
        if(json.code==200){
            reset();
            $("#id").val(json.msg.id);
            $("#title").val(json.msg.titulo);
            $("#description").val(json.msg.descripcion);
            $("#im1").attr('src',"../images/blogs/"+json.msg.img);
            $('#im1').removeClass("hidden");
            $("#titulo-modal").text("Editar Marca");
            $("#modalBlog").modal("show");
        }
    }).fail(function(){
        swal("Error","No pudimos recuperar los datos","warning");
    });
}
function reset(){
    $('#titulo-modal').text("");
    $("#id").val("");
    $('#title').val("");
    $('#description').val("");
    $('#imgu').val("");
    $('#img').val("");
}

