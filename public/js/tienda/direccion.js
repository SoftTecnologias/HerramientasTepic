$(document).ready(function(e) {
    $("#estado").on('change', function () {
        var estadoseleccionado = $("#estado option:selected").val();
        if (estadoseleccionado != '00') {
            $.ajax({
                url: document.location.protocol + '//' + document.location.host  + '/getMunicipios/' + estadoseleccionado,
                type: 'GET'
            }).done(function (response) {
                if (response.code == 200) {
                    $('#municipio option').remove();
                    $('<option>', {
                        text: 'Seleccione un Municipio'
                    }).attr('value', "00").appendTo('#municipio');
                    $.each($.parseJSON(response.msg), function (i, row) {
                        $('<option>', {text: row.nombre}).attr('value', row.id_municipio).appendTo('#municipio');
                    });
                }
            }).fail(function () {

            });
        }
    });
    $("#municipio").on('change', function () {
        var localidadseleccionada = $("#municipio option:selected").val();
        if (localidadseleccionada != '00') {
            $.ajax({
                url: document.location.protocol + '//' + document.location.host  + '/getLocalidades/' + localidadseleccionada,
                type: 'GET'
            }).done(function (response) {
                if (response.code == 200) {
                    $('#localidad option').remove();
                    $('<option>', {
                        text: 'Seleccione una Localidad'
                    }).attr('value', "00").appendTo('#localidad');
                    $.each($.parseJSON(response.msg), function (i, row) {
                        $('<option>', {text: row.nombre}).attr('value', row.id_localidad).appendTo('#localidad');
                    });
                }
            }).fail(function () {

            });
        }
    });
    $("#guardarDir").on('click',function () {
       if(validar() == false){return;}
        var data = {
            estado:$("#estado").val(),
            municipio:$("#municipio").val(),
            localidad:$("#localidad").val(),
            calle1:$("#calle1").val(),
            calle2:$("#calle2").val(),
            calle3:$("#calle3").val(),
            num:$("#numext").val(),
            cp:$("#cp").val(),
            ref:$("#ref").val(),
            colonia: $("#neighborhood")
        };
        $.ajax({
            url:document.location.protocol+'//'+document.location.host  +'/user/direccion',
            type:"POST",
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(json){
            if(json.code == 200) {
                swal("Realizado", json.msg, json.detail).then(function () {
                    location.reload();
                });
            }else{

                swal("Error",json.msg,json.detail).then(function () {
                    location.reload();
                });
            }
        }).fail(function(){
            swal("Error","Tuvimos un problema de conexion","error");
        });
    });
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            console.log(curInputs[i].validity);
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});

// Listrap v1.0, by Gustavo Gondim (http://github.com/ggondim)
// Licenced under MIT License
// For updates, improvements and issues, see https://github.com/inosoftbr/listrap

jQuery.fn.extend({
    listrap: function () {
        var listrap = this;
        listrap.getSelection = function () {
            var selection = new Array();
            listrap.children("li.active").each(function (ix, el) {
                selection.push($(el)[0]);
            });
            return selection;
        }
        var toggle = "li .listrap-toggle ";
        var selectionChanged = function() {
            $(this).parent().parent().toggleClass("active");
            listrap.trigger("selection-changed", [listrap.getSelection()]);
        }
        $(listrap).find(toggle + "img").on("click", selectionChanged);
        $(listrap).find(toggle + "span").on("click", selectionChanged);
        return listrap;
    }
});
$(document).ready(function () {
    $(".listrap").listrap().on("selection-changed", function (event, selection) {
        console.log(selection);
    });

});
function validar() {
    if($("#estado").val() == 00 || $("#municipio").val() == 00 || $("#localidad").val() == 00 ||
        $("#calle1").val() == '' || $("#calle2").val() == '' || $("#calle3").val() == '' ||
        $("#numext").val() == '' ||$("#cp").val() == '' || $("#neighborhood").val() == ''){
        swal('error','Rellene todos los campos','');
        return false;
    }
}
