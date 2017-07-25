/**
 * Created by fenix on 01/04/2017.
 */
$(function () {
    $('#btnNew').on('click', function () {
        $('#titulo-modal').text("Nuevo Movimiento");
        $("#modalMovement").modal("show");
    });
    $('#btnMovement').on('click', function () {
        $('#movementForm').submit();
    });
    $('#btnDetail').on('click', function () {
        $('#detailForm').submit();
    });
    //*
    var table = $('#movementTable').DataTable({
        stateSave: true,
        'ajax':document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public"  +'/area/resource/movimientos',
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
        'columns': [
            {
                "className": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            {data: 'id'},
            {data: 'nombre'},
            {data: 'folio_factura'},
            {data: function (row) {
                     return '$'+ row['total'];
                   }
            },
            {data: 'fecha'},
            {data: 'observaciones'},
            {
                data: function (row) {
                    var str="Movimiento Cancelado";
                    if(row.status == 1) {
                        str = "<div align='center'>";
                        str += "<div class='col-md-6'> ";
                        str += "<button class='btn btn-success' id='btnNewDetail' onclick='showDetailForm("+row['id']+")'><i class='fa fa-plus'></i></button></div>";
                        str += "<div class='col-md-6'> <button id='btnEliminar' class='btn btn-danger block' onclick='cancelMovement(" + row['id'] + ")'><i class='fa fa-minus-circle'></i></button> </div>";
                        str += "</div>";
                    }
                    return str;
                }
            }
        ],
        'language': {
            url: 'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });
    //agregamos el listener para abrir y cerrar opciones
    $('#movementTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            subtable(row);
            tr.addClass('shown');
        }
    });
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        number: "Por favor, escribe un número válido.",
        digits: "Por favor, escribe sólo dígitos.",
        maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres.")
    });

    $('#movementForm').validate({
        rules: {
            proveedor_id: {
                required: true
            },
            tipo: {
                required: true
            },
            observaciones: {
                required: true
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
            newMovement();
            return false;
        }

    });

    $('#detailForm').validate({
        rules: {
            producto_id: {
                required: true,
                number:true
            },
            precio: {
                required: true,
                number: true
            },
            cantidad: {
                required: true,
                min: 1
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
            newDetail($("#movement_id").val());
            return false;
        }

    });

    $("#estado").on('change', function () {
        $('#municipio option').remove();
        if ($(this).val() != "") {
            $.ajax({
                url: 'api/municipios/' + $(this).val(),
                type: 'get'
            }).done(function (json) {
                if (json.code === 200) {
                    $('<option></option>', {text: "Seleccione un municipio"}).attr('value', "").appendTo('#municipio');
                    $.each(json.msg, function (i, row) {
                        $('<option></option>', {text: row.nombre}).attr('value', row.id).appendTo('#municipio');
                    });
                } else {
                    $('<option></option>', {text: "No se obtuvieron resultados"}).attr('value', "").appendTo('#municipio');
                }
            });
        }
    });

    $('#producto_id').select2();
    $('#proveedor_id').select2();
});

//
function newMovement(){
    var data = new FormData(document.getElementById("movementForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public"  +"/area/resource/movimientos",
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
            $('#modalMovement').modal("hide");
            $('#movementTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function cancelMovement(id) {
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
        ruta =document.location.protocol+'//'+document.location.host  +'/area/resource/movimientos/'+id;
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
//
function reset() {
    document.getElementById("movementForm").reset();
    $("#movementForm").validate().resetForm();
}
//
function resetD() {
    document.getElementById("detailForm").reset();
    $("#detailForm").validate().resetForm();
}
//
function subtable(row) {
    $.ajax({
        url: document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public"  +"/area/resource/movimientos/" + row.data()['id'] + '/detalle',
        type: 'get'
    }).done(function (json) {
        console.log(json.code);
        if (json.code === 200) {
            var detalle = '<table id="detailTable" class="dataTables_wrapper form-inline dt-bootstrap table-responsive table-striped col-md-10 col-lg-offset-1" >' +
                "   <thead>" +
                "       <th> Renglon" +
                "       </th>" +
                "       <th> Fecha" +
                "       </th>" +
                "       <th> Producto" +
                "       </th>" +
                "       <th> Cantidad" +
                "       </th>" +
                "       <th> Importe" +
                "       </th>" +
                "       <th> Total" +
                "       </th>" +
                "       <th> Acciones" +
                "       </th>"
            "   </thead>" +
            "   <tbody>";
            $.each(json.msg, function (i, row) {
                detalle += '<tr>';
                detalle += '<th>' + (i + 1) + '</th>';
                detalle += '<th>' + row.fecha + '</th>';
                detalle += '<th>' + row.nombre+ '</th>';
                detalle += '<th>' + row.cantidad + '</th>';
                detalle += '<th> $' + row.importe + '</th>';
                detalle += '<th> $' + row.total + '</th>';
                if(row.status == 1) {
                    detalle += "<th> <button id='btnEliminar' class='btn btn-danger block' onclick='removeDetail(" + row.id + ")'><i class='fa fa-trash-o'></i></button>";
                }else{
                    detalle += "<th><p class='alert'>Movimiento cancelado </p>"
                }
                detalle += '</tr>';
            });
            detalle += '</tbody>' +
                '</table>';
        } else {
            var detalle = "<table id='detailTable' class='dataTables_wrapper form-inline dt-bootstrap table-responsive col-md-12' >" +
                "   <thead>" +
                "       <th> Renglon" +
                "       </th>" +
                "       <th> Fecha" +
                "       </th>" +
                "       <th> Producto" +
                "       </th>" +
                "       <th> Cantidad" +
                "       </th>" +
                "       <th> Importe" +
                "       </th>" +
                "       <th> Total" +
                "       </th>" +
                "       <th> Acciones" +
                "       </th>" +
                "   </thead>" +
                "   <tbody>" +
                "   <td valign='top' colspan='9' class='ataTables_empty'>Ningún dato disponible en esta tabla</td>" +
                "   </tbody>" +
                "  </table>";
        }
        row.child(detalle).show();
    }).fail(function () {

          var detalle = "<table id='detailTable' class=' dt-bootstrap table-responsive col-md-12' >" +
            "   <thead>" +
            "       <th> Renglon" +
            "       </th>" +
            "       <th> Fecha" +
            "       </th>" +
            "       <th> Producto" +
            "       </th>" +
            "       <th> Cantidad" +
            "       </th>" +
            "       <th> Importe" +
            "       </th>" +
            "       <th> Total" +
            "       </th>" +
            "       <th> Acciones" +
            "       </th>" +
            "   </thead>" +
            "   <tbody>" +
            "   <td valign='top'  class='ataTables_empty'>Ningún dato disponible en esta tabla</td>" +
            "   </tbody>" +
            "  </table>";
        row.child(detalle).show();
    });
}
//
function showDetailForm(id){
    $("#movement_id").val(id);
    $('#titulo-modalD').text("Nuevo Detalle");
    $("#modalDetail").modal("show");
}
//
function newDetail(id){
    var data = new FormData(document.getElementById("detailForm"));
    $.ajax({
        url: document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public"  +"/area/resource/movimientos/"+id+"/create",
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
            $('#modalDetail').modal("hide");
            $('#movementTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function removeDetail(id){
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
        ruta =document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public"  +'/area/resource/movimientos/detail/'+id;
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