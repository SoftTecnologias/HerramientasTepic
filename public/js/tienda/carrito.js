/**
 * Created by cared on 09/08/2017.
 */
$(function () {
    $("#btnRemover").on("click",function(){
        var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
        $.ajax({
            url: document.location.protocol + '//' + document.location.host  + '/user/cart/removeToCart',
            type: 'delete',
            data: {
                id: $("#removeForm #id").val(),
                cantidad: $("#removeForm #qty").val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (json) {
            if (json.code == 200) {
                console.log(json);
                swal('Producto eliminado del carrito', "Se ha retirado el producto de tu carrito", 'success');
                //En el mensaje vendrá el carrito así que lo volveremos a agregar
                $("#eliminar-modal").modal("hide");
                $("#carrito-modal").modal("hide");
                $("#carrito span").text("$ "+json.msg['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" ("+json.msg['cantidadProductos']+" articulos)");
                $("#btnCheckout").text("Finalizar pedido ("+"$ "+json.msg['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+")");
                $('#body-cart tr').remove();
                $('#body-cart td').remove();
                $.each(json.msg['productos'], function (i, row) {
                    console.log(row);
                    precio = parseFloat(row.item.precio);
                    total = parseFloat(row.total);
                    selector = "row"+Base64.decode(row.item.id);
                    $('<tr>',{
                        id: selector
                    }).attr('role','row').appendTo('#body-cart');
                    $('<td>', {text: row.item.name}).attr('style','text-align: center; font-size:.80em;').appendTo("#"+selector);
                    $('<td>', {text: row.cantidad}).appendTo("#"+selector);
                    $('<td>', {text: " $ "+precio.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#"+selector);
                    $('<td>', {text: " $ "+ total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#"+selector);
                    $("<td>").append($("<a>",{
                        id: 'btnEliminar',
                        href:'#',
                        'data-toggle':"modal",
                        'data-target':"#eliminar-modal",
                        onclick:'removeToCart("'+row.item.id+'")'
                    }).append($("<i>",{
                        class:"fa fa-trash-o"
                    }))).appendTo("#"+selector);
                });
            }
        }).fail(function (response) {
            console.log(response);
            swal('Advertencia', 'No se pudo eliminar el producto del carrito', 'warning');
        });
    });
});

function agregarProducto(id) {
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    swal({
        title: '¿ Cuantos productos ?',
        input: 'number',
        showCancelButton: true,
        confirmButtonText: 'Agregar productos',
        showLoaderOnConfirm: true,
        preConfirm: function (cantidad) {
            if(cantidad==null || cantidad==0){
                swal('ups!! :(', "Es necesario seleccionar por lo menos un Articulo", 'warning');
                return;
            }

            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: document.location.protocol+'//'+document.location.host  + '/user/cart/add', /*quitar o agregar segun corresponda*/
                    type: 'POST',
                    data: {
                        id: id,
                        cantidad: cantidad
                    }, headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (response) {
                    //Se agrega el producto y se muestra la chingadera
                    if (response.code == 200) {
                        swal('Producto Agregado al Carrito', "Se a añadido exitosamente", 'success');
                        $("#carrito").find("span").text("$ "+response.msg['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" ("+response.msg['cantidadProductos']+" articulos)");
                        $("#body-cart").find("tr").remove();
                        $("#btnCheckout").text("Finalizar pedido ("+"$ "+response.msg['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+")");
                        $.each(response.msg['productos'], function (i, row) {
                            console.log(row);
                            precio = parseFloat(row.item.precio);
                            total = parseFloat(row.total);
                            selector = "row"+Base64.decode(row.item.id);
                            $('<tr>',{
                                id: selector
                            }).attr('role','row').appendTo('#body-cart');
                            $('<td>', {text: row.item.name}).attr('style','text-align: center;font-size:.80em;').appendTo("#"+selector);
                            $('<td>', {text: row.cantidad}).appendTo("#"+selector);
                            $('<td>', {text: " $ "+precio.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#"+selector);
                            $('<td>', {text: " $ "+ total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#"+selector);
                            $("<td>").append($("<a>",{
                                id: 'btnEliminar',
                                href:'#',
                                'data-toggle':"modal",
                                'data-target':"#eliminar-modal",
                                onclick:'removeToCart("'+row.item.id+'")'
                            }).append($("<i>",{
                                class:"fa fa-trash-o"
                            }))).appendTo("#"+selector);
                        });
                        //En el mensaje vendrá el carrito así que lo volveremos a agregar
                    } else { //Codigo diferente
                        swal('ups!! :(', response.msg, 'warning'); //Mensaje de error personalizado
                    }
                }).fail(function () {
                    swal('ups!! :(', 'Lamentablemente no tuvimos acceso al servidor intente mas tarde ', 'warning'); //Mensaje de error al fallar la conexion
                });
            })
        },
        allowOutsideClick: false
    })
}

function removeToCart(id) {
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    $('#removeForm #id').val(id);
    columna = $("#row"+Base64.decode(id)+" td");
    $('#removeForm #qty').attr("max",$(columna[1]).text());
    $('#removeForm #qty').val($(columna[1]).text());

}

