/**
 * Created by fenix on 20/03/2017.
 */
$(function(){
    $('#btnNew').on('click', function () {
        $('#titulo-modal').text("Nuevo Producto");
        reset();
        $("#modalProduct").modal("show");
    });
    $('#btnAceptar').on('click',function(){
        $('#productForm').submit();
    });
    /* Dependencia de los combos*/
    $('#categoryid').change(function(){
        var categoria = $(this).val();
        if(categoria != '00'){
            $.ajax({
                url:document.location.protocol+'//'+document.location.host+'/api/getsubcategoria/'+categoria,
                type:'GET'
            }).done(function(response){
                if(response.code == 200){
                    $('#subcategoryid option').remove();
                    $('<option>',{
                        text:'Seleccione una subcategoria'
                    }).attr('value',"00").appendTo('#subcategoryid');
                    $.each($.parseJSON(response.msg),function(i, row){
                        $('<option>',{text:row.name}).attr('value',row.subcategoryid).appendTo('#subcategoryid');
                    });
                }
            }).fail(function(){

            });
        }
    });
    //fin de dependencia
    //*
    var table= $('#tblProducts').DataTable({
        'scrollX':true,
        'scrollY':'600px',
        "processing": true,
        "serverSide": true,
        "ajax": document.location.protocol+'//'+document.location.host + '/HerramientasTepic/public' +'/area/resource/productos',
        'createdRow':function(row,data,index){
            if(data.stock <= data.reorderpoint ){
              $('td', row).addClass("danger");
            }else {
                if (parseInt(data.stock) > parseInt(data.reorderpoint)) {
                    $('td', row).addClass("success");
                }
            }
        },
        columns: [
            {data: function (row) {
                str = "";
                str = "<div align='center' >";
                str += " <img class=\"imagen\" src='../img/productos/" + row['photo'] + "' alt='" + row['id'] + "' style='height: 50px; width: 150px;'>";
                str += "</div>";
                return str;
            }},
            {data: 'code'},
            {data: 'name'},
            {data: 'marca'},
            {data: 'categoria'},
            {data: 'subcategoria'},
            {data: function (row) {
                str = "";
                srt = "<div align='left'>";
                str += "<p class='price'>$" + formato(row['price1']) + row['currency'] + "</p> </div>";
                return str;
            }},
            {data: function (row) {
                str = "";
                srt = "<div align='left'>";
                str += "<p class='price'>$" + formato(row['price2']) + row['currency'] + "</p> </div>";
                return str;
            }},
            {data: function (row) {
            console.log(row);
                str = "<div align='center'>";
                str +=" <button id='btnEditar' class='btn btn-primary btn-xs col-md-6' onclick='showProduct("
                     + row['id'] + ","
                     + row['categoryid'] + ","
                     + "\""+encodeURI(row['code']) + "\","
                     + "\""+row['currency'] + "\","
                     +"\""+encodeURI(row['longdescription']) + "\","
                     + row['brandid']+", \""
                     +encodeURI(row['name'])+"\", \""
                     +row['photo'] +"\", \""
                     +row['photo2'] +"\", \""
                     +row['photo3'] +"\","
                     +row['price1'] +","
                     +row['price2'] +","
                     +row['price3'] +","
                     +row['price4'] +","
                     +row['price5'] +","
                     +row['reorderpoint'] +",\""
                     +escape(row['shortdescription']) +"\","
                     +row['stock'] +","
                     +row['subcategoryid']+")'><i class='glyphicon glyphicon-edit'></i></button>";
                str += "<button id='btnEliminar' class='btn btn-danger btn-xs col-md-6' onclick='deleteProduct(" + row['id'] + ")'><i class='fa fa-trash-o'></i></button>";
                str += "</div>";
                return str;
            }}
        ],
        'language': {
            url:'https://cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json',
            sLoadingRecords : '<span style="width:100%;"><img src="http://www.snacklocal.com/images/ajaxload.gif"></span>'
        }
    });
    // Apply the search
    /*Validacion de los archivos*/
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        number: "Por favor, escribe un número válido.",
        digits: "Por favor, escribe sólo dígitos.",
        maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
    });
    $.validator.addMethod('filesize', function (value, element, param) {
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param);
    }, $.validator.format("El archivo debe ser menor a 1MB"));
    $('#productForm').validate({
        rules: {
            code: {
                required: true,
                minlength: 2,
                maxlength: 20
            },
            name: {
                required: true
            },
            currency: {
                required: true,
                maxlength: 3
            },
            shortdescription: {
                required: true,
                maxlength: 50
            },
            brandid: {
                required: true
            },
            categoryid: {
                required: true
            },
            subcategoryid: {
                required: true
            },
            price1: {
                required: true,
                number: true
            },
            'img[]': {
                extension: "png|jpg|gif",
                filesize: 1048576
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
            productAction();
            return false;
        }

    });

    /*files de las imagenes*/
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
    $("#img2").fileinput({
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
    $("#img3").fileinput({
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
    $("#imgu2").on("change",function(){
        if($(this).val()!==""){
            $('#im2').attr("src",$(this).val());
            $('#im2').removeClass("hidden");
        }else{
            $('#im2').addClass("hidden");
        }
    });
    $("#imgu3").on("change",function(){
        if($(this).val()!==""){
            $('#im3').attr("src",$(this).val());
            $('#im3').removeClass("hidden");
        }else{
            $('#im3').addClass("hidden");
        }
    });
});
//
function productAction(){
    if($("#productid").val()==""){
        newProduct();
    }else{
        updateProduct($("#productid").val());
    }
}
//
function newProduct(){
    var data = new FormData(document.getElementById("productForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host + '/HerramientasTepic/public' +"/area/resource/productos",
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
            $('#modalProduct').modal("hide");
            $('#tblProducts').dataTable().api().ajax.reload(null,false);
            reset();
        }else{
            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function updateProduct(id){
    $("#productid").val(id);
    var datos = new FormData(document.getElementById("productForm"));
    $.ajax({
        url:document.location.protocol+'//'+document.location.host + '/HerramientasTepic/public' +"/area/resource/productos/"+id,
        type:"POST",
        data: datos,
        contentType:false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(json){
        if(json.code == 200) {
            swal("Realizado", json.msg, json.detail);
            $('#modalProduct').modal("hide");
            $('#tblProducts').dataTable().api().ajax.reload(null, false);
            reset();
        }else{

            swal("Error",json.msg,json.detail);
        }
    }).fail(function(){
        swal("Error","Tuvimos un problema de conexion","error");
    });
}
//
function deleteProduct(id){
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
        ruta =document.location.protocol+'//'+document.location.host + '/HerramientasTepic/public' +'/area/resource/productos/'+id;
        $.ajax({
            url:ruta,
            type:'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code==200) {
                swal("Realizado", json.msg, json.detail);
                $('#tblProducts').dataTable().api().ajax.reload(null,false);
            }else{
                swal("Error", json.msg, json.detail);
            }
        }).fail(function(response){
            swal("Error", "tuvimos un problema", "warning");
        });
    });
}
//
function showProduct(productid, categoryid, code, currency, longdescription, brandid, name, photo,
                     photo2, photo3, price1, price2, price3, price4, price5, reorderpoint,
                     shortdescription, stock, subcategoryid){
    $('#titulo-modal').text("Editar Producto");
    $('#productid').val(productid);
    $('#code').val(decodeURI(code));
    $('#name').val(decodeURI(name));
    $('#currency').val(currency);
    $('#shortdescription').val(decodeURI(shortdescription));
    $('#longdescription').val(decodeURI(longdescription));
    $('#brandid').val(brandid);
    $('#categoryid').val(categoryid);
    $.ajax({
        url: document.location.protocol+'//'+document.location.host+'/api/getsubcategoria/'+categoryid,
        type: 'GET'
    }).done(function (json) {
        if (json.code === 200) {
            $("#subcategoryid option").remove();
            $('<option>', {
                text: 'Seleccione una subcategoria'
            }).attr('value', 0).appendTo("#subcategoryid");
            $.each($.parseJSON(json.msg), function (i, row) {
                $('<option>', {text: row.name}).attr('value', row.id).appendTo('#subcategoryid');
            });
            $('#subcategoryid').val(subcategoryid);
        }
    });
    $('#price1').val(price1);
    $('#price2').val(price2);
    $('#price3').val(price3);
    $('#price4').val(price4);
    $('#price5').val(price5);
    $('#reorderpoint').val(reorderpoint);
    $("#im1").attr("src","../img/productos/"+photo);
    $("#im2").attr("src","../img/productos/"+photo2);
    $("#im3").attr("src","../img/productos/"+photo3);
    $('#im1').removeClass("hidden");
    $('#im2').removeClass("hidden");
    $('#im3').removeClass("hidden");
    $('#modalProduct').modal("show");

}
//
function reset(){
    document.getElementById("productForm").reset();
    $("#productForm").validate().resetForm();
    $("#minimo").val(1);
    $("#maximo").val(2);
    $('#im1').addClass("hidden");
    $('#im1').attr("src","");
    $('#im2').addClass("hidden");
    $('#im2').attr("src","");
    $('#im3').addClass("hidden");
    $('#im3').attr("src","");
}
//
function formato(numero) {
    var num, datos;
    num = numero + "";
    if (num % 1 === 0) {
        return parseInt(num) + '.00';
    } else {
        datos = num.split(".");
        if (datos[1].lenght < 2)
            return num + '0';
        else
            return num;
    }
}


