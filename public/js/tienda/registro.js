/**
 * Created by fenix on 18/07/2017.
 */
$(function(){
    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    /*Validacion del lado del cliente*/
    $('#clientForm').validate({
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
        showUpload:false,
        browseClass: "btn btn-success",
        browseLabel: "Elegir Foto",
        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "Eliminar",
        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
        maxFileSize: 1024,
    });

    $('#registro').on('click',function () {
        console.log("nuevo usuario");
        data = new FormData(document.getElementById("clientForm"));
        $.ajax({
            url: document.location.protocol + '//' + document.location.host   + "/HerramientasTepic/public" +"/usuario/registro",
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
                swal({
                    title: 'Se ha realizado el registro',
                    text: "para finalizar sigue el link que te mandamos a tu correo!",
                    type: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Entendido!'
                }).then(function () {
                    location.href =document.location.protocol + '//' + document.location.host   + "/HerramientasTepic/public/";
                })
            } else {
                swal("Error", json.msg, json.detail);
            }
        }).fail(function () {
            swal("Error", "Tuvimos un problema de conexion", "error");
        });
    });


});

