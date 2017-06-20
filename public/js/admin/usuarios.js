/**
 * Created by fenix on 21/03/2017.
 */
$(function(){
    $('#btnNew').on('click',function(){

        $("#password").rules("remove");
        $( "#cpassword" ).rules("remove");
        $("#npassword").rules("remove");

        $('#password').rules("add",{
            required:true,
            minlength:4,
            messages:{
                required:"La contraseña es necesaria",
                minlength:"La contraseña no debe contener menos de 4 caracteres"
            }
        });
        $('#cpassword').rules("add",{
            required:true,
            minlength:4,
            equalTo:"#password",
            messages:{
                required:"La contraseña es necesaria",
                minlength:"La contraseña no debe contener menos de 4 caracteres",
                equalTo:"Las contraseñas no coinciden"
            }
        });

        $('#titulo-modal').text("Nuevo Usuario");
        $('#npass').addClass("hidden");
        $('#modalUser').modal("show");
    });
    $('#btnUser').on('click',function () {
        $("#userForm").submit();
    });
    $('#userTable').DataTable({
        'ajax':{
            url:'api/usuarios',
            dataSrc:function(json){
                return json;
            }
        },
        'columns':[
            {
              data:function(row) {
                  var str = "";
                  str = "<div align='center col-md-3'>";
                  str += "<img class=\"img-responsive \" src='../images/usuarios/" + row['img'] + "' alt='" + row['id'] + "'>";
                  str += "</div>";
                  return str;
              }
            },{
               data:'nombre'
            },{
               data:'ape_pat'
            },{
               data:'ape_mat'
            },{
               data:'email'
            },{
               data:function(row){
                   if(row['rol']==1){
                       return "Administrador";
                   }else{
                       return "Usuario Estandar";
                   }
               }
            },{
               data:function (row) {
                   str = "<div align='center'>";
                   str +=   "<button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showUser(" + row['id'] + ");'><i class='glyphicon glyphicon-edit'></i></button> ";
                   str +=   "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteUser(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button> ";
                   str +="</div>";
                   return str;
               }
            }
        ],
        'language':{
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });
    /*Validacion de los archivos*/
    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    /*Validacion del lado del cliente*/
    $('#userForm').validate({
        rules:{
            'nombre'    : {
                required:true,
                minlength:3,
                maxlength:100
            },
            'ape_pat'   : {
                required:true,
                minlength:3,
                maxlength:100
            },
            'ape_mat'   : {
                minlength:3,
                maxlength:100,
                required: false
            },
            'email'     : {
                required:true,

                email:true
            },
            'username'  : {
                required:true,
                minlength:4,
                maxlength:32
            },
            'img'       : {
                extension: "png|jpg|gif",
                filesize: 1048576
            }
        },
        messages:{
            'nombre'    : {
                required: "Este campo es requerido",
                minlength: "Debe insertar al menos 3 caracteres",
                maxlength: "El nombre no puede superar los 100 caracteres"
            },
            'ape_pat'   : {
                required: "Este campo es requerido",
                minlength: "Debe insertar al menos 3 caracteres",
                maxlength: "El nombre no puede superar los 100 caracteres"
            },
            'ape_mat'   : {
                minlength: "Debe insertar al menos 3 caracteres",
                maxlength: "El nombre no puede superar los 100 caracteres"
            },
            'email'     : {
                required:"Este campo es requerido",
                remote:"",
                email:"El campo no cubre las caracteristicas de un email"
            },
            'username'  : {
                required: "Este campo es requerido",
                remote:""
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
            userAction();
            return false;
        }
    });
});

function userAction(){
    if($("#id").val()==""){
        newUser();
    }else{
        updateUser($("#id").val());
    }
}
function newUser(){
 data = new FormData(document.getElementById("userForm"));
 $.ajax({
     url:"api/usuarios",
     type:"POST",
     data: data,
     contentType:false,
     processData: false
 }).done(function(json){
     if(json.code == 200) {
         swal("Realizado", json.msg, json.detail);
         $('#modalUser').modal("hide");
         $('#userTable').dataTable().api().ajax.reload();
         reset();
     }else{
         swal("Error",json.msg,json.detail);
     }
 }).fail(function(){
     swal("Error","Tuvimos un problema de conexion","error");
 });
}
function updateUser(id){
    $("#id").val(id);
    datos = new FormData(document.getElementById("userForm"));
    $.ajax({
        url:"api/usuarios/"+id,
        type:"post",
        data: datos,
        contentType:false,
        processData: false
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalUser').modal("hide");
            $('#userTable').dataTable().api().ajax.reload();
            reset();
        }else{
            console.log(json);
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
function deleteUser(id){
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
        ruta ='api/usuarios/'+id;
        $.ajax({
            url:ruta,
            type:'delete'
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#userTable').dataTable().api().ajax.reload();
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showUser(id){

    $("#password").rules("remove","required");
    $( "#npassword" ).rules("remove","required");
    $( "#cpassword" ).rules("remove","required");

    $('#password').rules("add",{
        required:false,
        minlength:4,
        messages:{
            minlength:"La contraseña no debe contener menos de 4 caracteres"
        }
    });
    $('#npassword').rules("add",{
        required:"#input:filled",
        minlength:4,
        messages:{
            required:"Debe definir una contraseña nueva",
            minlength:"La contraseña no debe contener menos de 4 caracteres"
        }
    });
    $('#cpassword').rules("add",{
        required:"#npassword:filled",
        minlength:4,
        equalTo:"#npassword",
        messages:{
            minlength:"La contraseña no debe contener menos de 4 caracteres",
            equalTo:"Las contraseñas no coinciden",
            required:"Debe confirmar la contraseña"
        }
    });

    $.ajax({
        url: "api/usuarios/"+id,
        type: 'GET'
    }).done(function(json){
        if(json.code==200){
            reset();
            $("#id").val(json.msg.id);
            $("#nombre").val(json.msg.nombre);
            $("#ape_pat").val(json.msg.ape_pat);
            $("#ape_mat").val(json.msg.ape_mat);
            $("#email").val(json.msg.email);
            $("#username").val(json.msg.username);
            $("#rol").val(json.msg.rol);
            $('#npass').removeClass("hidden");
            $("#titulo-modal").text("Editar Usuario");
            $("#modalUser").modal("show");
        }
    }).fail(function(){
        swal("Error","No pudimos recuperar los datos","warning");
    });

}
function reset(){
    document.getElementById("userForm").reset();
    $("#id").val("");
    $("#password").rules("remove");
    $( "#npassword" ).rules("remove");
    $( "#cpassword" ).rules("remove");
    $("#userForm").validate().resetForm();

}
