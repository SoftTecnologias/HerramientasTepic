
$(function(){
    $('#editTelefono').on('click',function(){
      document.getElementById("tel").readOnly = false;
        $('#tel').focus();
        $('#tel').select();
    });
});

$(function () {
    $('#editEmail').on('click',function () {
       document.getElementById("mail").readOnly = false;
       $('#mail').focus();
       $('#mail').select();
    });
});

$(function() {

    $.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value != $(param).val();
    }, "Please specify a different (non-default) value");

    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    $('#userForm').validate({
        rules: {
            'mail': {
                required: true,
                email: true
            },
            photo: {
                extension: "png|jpg|gif",
                filesize: 1048576
            },
            'tel':{
                required:true
            },
            nuevacon:{
                required:'#conactual:filled',
                minlength:4,
            notEqual:'#conactual'
            },
            confirmcon:{
                required:'#nuevacon:filled',
                equalTo:'#nuevacon'
            }

        },
        messages: {
            'mail': {
                required: "Este campo es requerido",
                remote: "",
                email: "El campo no cubre las caracteristicas de un email"
            },
            tel: "El campo no cubre las caracteristicas de un telefono",
            nuevacon:{
                required:"Se requiere contraseña",
                minlength:'debe de contener por lo menos 4 caracteres',
                notEqual:'No puede ser la misma contraseña'
            },
            confirmcon: {
                required:'El campo anterior es Requerido',
                equalTo:'las contraseñas no coinciden'
            },
            photo:{
                extension:'Formato no admitido'
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
            updateUser($("#userid").val());
            return false;
        }
    });

    $('#btnGuardar').on('click',function () {
        $('#userForm').submit();

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
        maxFileSize: 1024,
    });

});

function updateUser(id) {
    swal({
        title: '¿Estás seguro?',
        text: "Esto no se puede revertir!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo guardar los cambios',
        cancelButtonText: "Lo pensaré"
    }).then(function () {
        $("#userid").val(id);
        ruta =  document.location.protocol+'//'+document.location.host+ '/area/perfil';
        data = new FormData(document.getElementById("userForm"));
        $.ajax({
            url: ruta,
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
                window.setTimeout('location.reload()', 3);
                //$('#userTable').dataTable().api().ajax.reload();
            } else {
                swal("Error", json.msg, json.detail);
                //window.setTimeout('location.reload()', 0);
            }
        }).fail(function (response) {
            swal("Error", "tuvimos un problema", "warning");
            //window.setTimeout('location.reload()', 0);
        });
    });
}


function userAction() {
    if ($("#userid").val() == "") {
       window.alert("si");
    } else {
        window.alert("no");
    }
}

