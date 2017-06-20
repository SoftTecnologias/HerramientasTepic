/**
 * Created by fenix on 19/03/2017.
 */
$(function(){
    $('#btnNew').on('click', function () {
        reset();
        $('#titulo-modal').text("Nueva Categoria");
        $("#modalCategory").modal("show");
    });

    $('#categoryTable').DataTable({
        "processing": true,
        "serverSide": true,
        'ajax': document.location.protocol+'//'+document.location.host+'/HerramientasTepic/public'+'/area/resource/categorias',
        'columns':[
            {
                data:'name'
            },{
                data:function (row) {
                    str = "<div align='center'>";
                    str += " <button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showCategory(" + row['id'] +", \""+row['name']+"\")'><i class='glyphicon glyphicon-edit'></i></button>";
                    str += "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteCategory(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
                    str += "</div>";
                    return str;
                }
            }
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });

    $('#categoryForm').validate({
        rules:{
            name:{
                required:true,
                minlength:3
            }
        },
        messages:{
            name:{
                required:"El nombre de la categoria es obligatoria",
                minlength:"La categoria debe tener mas de tres caracteres"
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
        submitHandler: function (form) {
            categoryAction();
            return false;
        }
    });

    $('#btnCategory').on('click',function () {
       $("#categoryForm").submit();
    });
});
function categoryAction(){
    if($('#categoryid').val()==""){
        newCategory();
    }else{
        updateCategory($('#categoryid').val());
    }
}
function newCategory(){
 var nombre = $('#name').val();
    $.ajax({
     url:document.location.protocol+'//'+document.location.host+'/HerramientasTepic/public'+'/area/resource/categorias',
     type:'POST',
     data:{name:nombre},
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 }).done(function(json){
    if(json.code == 200) {
        swal("Realizado", json.msg, json.detail);
        $('#modalCategory').modal("hide");
        $('#categoryTable').dataTable().api().ajax.reload(null,false);
        reset();
    }else{
        swal("Error",json.msg,json.detail);
    }

 }).fail(function(response){
    console.log(response);
 });
}
function updateCategory(id){
    $('#categoryid').val(id)
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+'/HerramientasTepic/public'+"/area/resource/categorias/"+id,
        type:"PUT",
        data:{
            name: $("#name").val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalCategory').modal("hide");
            $('#categoryTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }

    }).fail(function(){
        swal("Error", "No pudimos conectarnos","warning")
    });
}
function deleteCategory(id){

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
        ruta =document.location.protocol+'//'+document.location.host+'/HerramientasTepic/public'+'/area/resource/categorias/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#categoryTable').dataTable().api().ajax.reload(null, false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showCategory(id, name){
    reset();
    $("#categoryid").val(id);
    $("#name").val (name);
    $("#titulo-modal").text("Editar Categoria");
    $("#modalCategory").modal("show");
}
function reset(){
    document.getElementById("categoryForm").reset();
    $("#categoryForm").validate().resetForm();
    $('#titulo-modal').text("");
    $("#categoryid").val("");
    $('#name').val("");
}
