/**
 * Created by fenix on 19/03/2017.
 */
$(function(){
    $('#btnNew').on('click', function () {
        reset();
        $('#titulo-modal').text("Nueva Subcategoria");
        $("#modalSubcategory").modal("show");
    });

    $('#subcategoryTable').DataTable({
        "processing": true,
        "serverSide": true,
        'ajax': document.location.protocol+'//'+document.location.host+'/area/resource/subcategorias',
        'columns':[
            {
                data:'name'
            },{
                data:'categoria'
            },{
                data:function (row) {
                    str = "<div align='center'>";
                    str += " <button id='btnEditar' class='btn btn-primary block col-md-3' onclick='showSubcategory(" + row['id'] +", \""+row['name']+"\","+row['categoryid']+")'><i class='glyphicon glyphicon-edit'></i></button>";
                    str += "<button id='btnEliminar' class='btn btn-danger block col-md-3' onclick='deleteSubcategory(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
                    str += "</div>";
                    return str;
                }
            }
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json'
        }
    });

    $('#subcategoryForm').validate({
        rules:{
            name:{
                required:true,
                minlength:3
            }
        },
        messages:{
            name:{
                required:"El nombre de la subcategoria es obligatoria",
                minlength:"La subcategoria debe tener mas de tres caracteres"
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
            subcategoryAction();
            return false;
        }
    });

    $('#btnSubcategory').on('click',function () {
       $("#subcategoryForm").submit();
    });
});
function subcategoryAction(){
    if($('#subcategoryid').val()==""){
        newSubcategory();
    }else{
        updateSubcategory($('#subcategoryid').val());
    }
}
function newSubcategory(){
 var nombre = $('#name').val();
 var catid = $('#categoryid').val()
    $.ajax({
     url:document.location.protocol+'//'+document.location.host+'/area/resource/subcategorias',
     type:'POST',
     data:{name:nombre,categoryid:catid},
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
     }
 }).done(function(json){
    if(json.code == 200) {
        swal("Realizado", json.msg, json.detail);
        $('#modalSubcategory').modal("hide");
        $('#subcategoryTable').dataTable().api().ajax.reload(null,false);
        reset();
    }else{
        swal("Error",json.msg,json.detail);
    }

 }).fail(function(response){
    console.log(response);
 });
}
function updateSubcategory(id){
    $('#subcategoryid').val(id)
    $.ajax({
        url:document.location.protocol+'//'+document.location.host+"/area/resource/subcategorias/"+id,
        type:"PUT",
        data:{
            name: $("#name").val(),
            categoryid: $('#categoryid').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalSubcategory').modal("hide");
            $('#subcategoryTable').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }

    }).fail(function(){
        swal("Error", "No pudimos conectarnos","warning")
    });
}
function deleteSubcategory(id){

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
        ruta =document.location.protocol+'//'+document.location.host+'/area/resource/subcategorias/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#subcategoryTable').dataTable().api().ajax.reload(null, false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
function showSubcategory(id, name, categoryid){
    $("#categoryid").val(categoryid);
    $('#subcategoryid').val(id);
    $("#name").val (name);
    $("#titulo-modal").text("Editar Subcategoria");
    $("#modalSubcategory").modal("show");
}
function reset(){
    document.getElementById("subcategoryForm").reset();
    $("#subcategoryForm").validate().resetForm();
    $('#titulo-modal').text("");
    $("#subcategoryid").val('');
    $('#name').val("");
    $('#categoryid').val("");
}
