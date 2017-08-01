/**
 * Created by fenix on 21/03/2017.
 */
$(function () {
    $('#btnNew').on('click', function () {

        $("#password").rules("remove");
        $("#cpassword").rules("remove");
        $("#npassword").rules("remove");

        $('#password').rules("add", {
            required: true,
            minlength: 4,
            messages: {
                required: "La contraseña es necesaria",
                minlength: "La contraseña no debe contener menos de 4 caracteres"
            }
        });
        $('#cpassword').rules("add", {
            required: true,
            minlength: 4,
            equalTo: "#password",
            messages: {
                required: "La contraseña es necesaria",
                minlength: "La contraseña no debe contener menos de 4 caracteres",
                equalTo: "Las contraseñas no coinciden"
            }
        });

        $('#titulo-modal').text("Nuevo Usuario");
        $('#npass').addClass("hidden");
        $('#modalUser').modal("show");
    });
    $('#btnUser').on('click', function () {
        $("#userForm").submit();
    });
    $('#userTable').DataTable({
        "processing": true,
        "serverSide": true,
        'ajax': document.location.protocol + '//' + document.location.host  + '/area/resource/usuarios',
        'columns': [
            {
                data: function (row) {
                    var str = "";
                    str = "<div align='center col-md-3'>";
                    str += "<img class=\"img-responsive \" src='../img/usuarios/" + row['photo'] + "' alt='" + row['id'] + "' style='width: 200px;height: 150px;'>";
                    str += "</div>";
                    return str;
                }
            }, {
                data: 'name'
            }, {
                data: 'lastname'
            }, {
                data: 'phone'
            }, {
                data: 'email'
            }, {
                data: 'rol'
            }, {
                data: function (row) {
                    str = "<div align='center'>";
                    if(row['roleid'] != 1){
                    str +=" <button id='btnEditar' class='btn btn-primary btn-xs col-md-6' onclick='showUser("
                        +"\""+row['id']+"\", "
                        +"\""+row['name']+"\", "
                        +"\""+row['lastname']+"\","
                        +"\""+row['email'] +"\","
                        +"\""+row['phone']+"\","
                        +"\""+row['roleid']+"\", "
                        +"\""+row['status']+"\","
                        +"\""+row['username']+"\""+")'><i class='glyphicon glyphicon-edit'></i></button> ";
                    str += "<button id='btnEliminar' class='btn btn-danger btn-xs col-md-6' onclick='deleteUser(\""+row['id']+"\")'><i class='fa fa-trash-o'></i></button>";
                    }
                    else{
                        uspr = row['userprice'];
                        opc1 = '';  opc2 = '';
                        opc3 = '';  opc4 = '';
                        opc5 = '';
                        console.log(uspr);
                        switch (uspr){
                            case '1': opc1='selected';break;
                            case '2': opc2='selected';break;
                            case '3': opc3='selected';break;
                            case '4': opc4='selected';break;
                            case '5': opc5='selected';break;
                        }

                        str += "<select class='selectpicker' style='btn-primary' name='userprice' id='userprice"+row['id']+"' onchange='updateClientePrice("
                            +"\""+row['id']+"\")'>" +
                            "<option value='1' "+opc1+">Precio 1</option>" +
                            "<option value='2' "+opc2+">Precio 2</option>" +
                            "<option value='3' "+opc3+">Precio 3</option>" +
                            "<option value='4' "+opc4+">Precio 4</option>" +
                            "<option value='5' "+opc5+">Precio 5</option>" +
                            "</select>";
                        $('.selectpicker').selectpicker({
                            style: 'btn-primary',
                            color: 'black',
                            size: 4
                        });
                    }
                    str += "</div>";
                    return str;
                }
            }
        ],
        'language': {
            url: 'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
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
        rules: {
            'name': {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            'lastname': {
                required: true,
                minlength: 3,
                maxlength: 200
            },
            'email': {
                required: true,
                email: true
            },
            'username': {
                required: true,
                minlength: 4,
                maxlength: 32
            },
            'img': {
                extension: "png|jpg|gif",
                filesize: 1048576
            }
        },
        messages: {
            'name': {
                required: "Este campo es requerido",
                minlength: "Debe insertar al menos 3 caracteres",
                maxlength: "El nombre no puede superar los 100 caracteres"
            },
            'lastname': {
                required: "Este campo es requerido",
                minlength: "Debe insertar al menos 3 caracteres",
                maxlength: "El nombre no puede superar los 100 caracteres"
            },
            'email': {
                required: "Este campo es requerido",
                remote: "",
                email: "El campo no cubre las caracteristicas de un email"
            },
            'username': {
                required: "Este campo es requerido",
                remote: ""
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

    $("#photo").fileinput({
        allowedFileTypes: ["image"],
        showUpload: false,
        browseClass: "btn btn-success",
        browseLabel: "Elegir Foto",
        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "Eliminar",
        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
        maxFileSize: 1024
    });
});

function userAction() {
    if ($("#userid").val() == "") {
        newUser();
    } else {
        updateUser($("#userid").val());
    }
}
function newUser() {
    console.log("nuevo usuario");
    data = new FormData(document.getElementById("userForm"));
    $.ajax({
        url: document.location.protocol + '//' + document.location.host   + "/area/resource/usuarios",
        type: "POST",
        data: data,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (json) {
        if (json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalUser').modal("hide");
            $('#userTable').dataTable().api().ajax.reload();
            reset();
        } else {
            swal("error", json.msg, json.detail);
        }
    }).fail(function () {
        swal("error", "Tuvimos un problema de conexion", "error");
    });
}
function updateUser(id) {
    console.log("Actualizacion de usuario");
    $("#userid").val(id);
    datos = new FormData(document.getElementById("userForm"));
    $.ajax({
        url: document.location.protocol + '//' + document.location.host   +"/area/resource/usuarios/" + id,
        type: "post",
        data: datos,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        processData: false
    }).done(function (json) {
        if (json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalUser').modal("hide");
            $('#userTable').dataTable().api().ajax.reload();
            reset();
        } else {
            console.log(json);
            swal("Error", json.msg, json.detail);
        }
    }).fail(function () {
        swal("Error", "Tuvimos un problema de conexion", "error");
    });
}
function deleteUser(id) {
    window.alert("a");
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
        ruta =  document.location.protocol + '//' + document.location.host  + '/area/resource/usuarios/'+ id;
        $.ajax({
            url: ruta,
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (json) {
            if (json.code == 200) {
                swal("Realizado", json.msg, json.detail);
                $('#userTable').dataTable().api().ajax.reload();
            } else {
                swal("Error", json.msg, json.detail);
            }
        }).fail(function (response) {
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showUser(id, name, lastname, email, phone, roleid, status, username) {
    $("#password").rules("remove", "required");
    $("#npassword").rules("remove", "required");
    $("#cpassword").rules("remove", "required");

    $('#password').rules("add", {
        required: false,
        minlength: 4,
        messages: {
            minlength: "La contraseña no debe contener menos de 4 caracteres"
        }
    });
    $('#npassword').rules("add", {
        required: "#input:filled",
        minlength: 4,
        messages: {
            required: "Debe definir una contraseña nueva",
            minlength: "La contraseña no debe contener menos de 4 caracteres"
        }
    });
    $('#cpassword').rules("add", {
        required: "#npassword:filled",
        minlength: 4,
        equalTo: "#npassword",
        messages: {
            minlength: "La contraseña no debe contener menos de 4 caracteres",
            equalTo: "Las contraseñas no coinciden",
            required: "Debe confirmar la contraseña"
        }
    });
    reset();
    $("#userid").val(id);
    $("#name").val(name);
    $("#lastname").val(lastname);
    $("#email").val(email);
    $("#username").val(username);
    $("#roleid").val(roleid);
    if(status=='I'){
        $('#status-1').prop('checked', true);
    }else{
        $('#status-0').prop('checked', true);
    }
    $('#phone').val(phone);
    $('#npass').removeClass("hidden");
    $("#titulo-modal").text("Editar Usuario");
    $("#modalUser").modal("show");

}
function reset() {
    document.getElementById("userForm").reset();
    $("#userid").val("");
    $("#password").rules("remove");
    $("#npassword").rules("remove");
    $("#cpassword").rules("remove");
    $("#userForm").validate().resetForm();

}
function updateClientePrice(id) {
    console.log("Actualizacion de usuario");
    var datosprice = document.getElementById('userprice'+id).value;
    swal({
            title: "Desea Cambiar el Precio?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "green",
            cancelButtonColor:"red",
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(function()
        {
                $.ajax({
                    url: document.location.protocol + '//' + document.location.host   +"/area/resource/usuarios/userprice/" + id,
                    type: "post",
                    data: {userprice:datosprice},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function (json) {
                    if (json.code == 200) {
                        swal("Realizado", json.msg, json.detail).then(function () {
                            location.reload();
                        });

                    } else {
                        swal("Error", json.msg, json.detail).then(function(){location.reload(1);});
                    }
                }).fail(function () {
                    swal("Error", "Tuvimos un problema de conexion", "error");
                });

        },function (isConfirm) {
            if(isConfirm){
                location.reload();
            }
        }
        );


}