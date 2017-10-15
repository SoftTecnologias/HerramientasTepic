/**
 * Created by cared on 01/09/2017.
 */


$(function(){
    $("#updateCart").on('click',function(){
        filas = $("#checkoutTable tbody").find("tr");
        datos ="[";
        a=0;
        $.each(filas,function(i,row){
            if($(row).data("qty")!= $(row).find("input").val()){
            //Hay cambio por tanto se actualiza
                if(a==0) {
                    datos += "{ \"id\":\"" + $(row).data("id") + "\", \"cantidad\":" + $(row).find("input").val() + " }";
                    a++;
                }else{
                 datos+=",{ \"id\":\""+$(row).data("id") + "\", \"cantidad\":"+ $(row).find("input").val()+" }";
                }
            }
        });
        datos+="]";

        $.ajax({
            type:"POST",
            url:document.location.protocol + '//' + document.location.host  + '/user/cart/updateCart',
            data:{ productos: datos},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response){
            if(response.code == 200){
                construct_symmary(response.msg);
            }else{
                swal("UPSS", response.msg, response.detail);
            }
        }).fail(function(){
            swal("UPSS", "No pudimos conectarnos al servidor", "error");
        });
    });
});


function construct_symmary(carrito){
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    console.log(carrito);
    $("#carrito span").text("$ "+carrito.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" ("+carrito.cantidadProductos+" articulos)");
    $("#btnCheckout").text("Finalizar pedido ("+"$ "+carrito.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+")");
    $('#body-cart tr').remove();
    $('#body-cart td').remove();
    $('#checkoutTable tbody tr').remove();
    $('#checkoutTable tbody td').remove();

    $.each(carrito.productos, function (i, row) {
        console.log(row);
        precio = parseFloat(row.item.precio);
        total = parseFloat(row.total);
        selector = "row"+Base64.decode(row.item.id);
        //carrito menu
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

        //principal
        $('<tr>',{
            id: selector,
            "data-qty": row.cantidad,
            "data-id" : row.item.id
        }).attr('role','row').appendTo('#checkoutTable tbody');
        $('<td>').append($('<a>',{
            href:"#",
        }).append($("<img>",{
            src:document.location.protocol + '//' + document.location.host + '/img/productos/'+row.item.photo,
            alt:row.item.codes
        }))).appendTo("#checkoutTable #"+selector);
        $('<td>', {text: row.item.name}).attr('style','text-align: center; font-size:.80em;').appendTo("#checkoutTable #"+selector);
        $('<td>').append($('<input>',{
            value:row.cantidad,
            type:"number"
        })).appendTo("#checkoutTable #"+selector);
        $('<td>', {text: " $ "+precio.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#checkoutTable #"+selector);
        $('<td>', {text: " $ "+ total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')}).appendTo("#checkoutTable #"+selector);
        $("<td>").append($("<a>",{
            id: 'btnEliminar',
            href:'#',
            'data-toggle':"modal",
            'data-target':"#eliminar-modal",
            onclick:'removeToCart("'+row.item.id+'")'
        }).append($("<i>",{
            class:"fa fa-trash-o"
        }))).appendTo("#checkoutTable #"+selector);
    });
    $("#checkoutTable #total").text(" $ "+ carrito.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" MXN" );
    $("#summary #subtotal").text(" $ "+ carrito.total.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" MXN");
    $("#summary #total").text(" $ "+ (carrito.total).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')+" MXN" );
    $("#cantidadProductos").text("Tienes "+carrito.cantidadProductos+" articulo(s) en tu carrito.")
}