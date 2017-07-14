/**
 * Created by fenix on 10/07/2017.
 */

$(function(){
    var url = document.location.pathname;
    var uPath = url.split("/");
    var pfinal = uPath.length-1;
    //Desencriptamos la URL
    asignaFiltros(uPath[pfinal]);
    // Asignamos los eventos a los filtros
    //Filtro de Precios
    $('.fprice').on('click',function(){
        if( $(this).hasClass('active') )
            removeUrlParameters('precio',$(this).data('val'),uPath[pfinal]);
        else
            toUrlParameters('precio',$(this).data('val'));
    });
    //Filtro de Categorias
    $('.fcategory').on('click',function(){
        if($(this).hasClass('active'))
            removeUrlParameters('categoria',$(this).data('val'),uPath[pfinal]);
        else
            toUrlParameters('categoria',$(this).data('val'));
    });
    //Filtro de Subcategorias
    $('.fsubcategory').on('click',function(){
        if( $(this).hasClass('active') )
            removeUrlParameters('subcategoria',$(this).data('val'),uPath[pfinal]);
        else
            toUrlParameters('subcategoria',$(this).data('val'));
    });
});


//se creará un nuevo parametro y si ya está el parametró se actualizará
function toUrlParameters(name,value) {
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    var url = document.location.pathname; //obtenemos la url
    var uPath = url.split("/"); //separamos la url por /
    var pfinal = uPath.length-1; //ultima parte de la url
    var prueba = document.location + "";
    var searchurl = Base64.decode(uPath[pfinal]); //obtenemos la parte de la busqueda
    var parametros = (searchurl.indexOf('/') != -1 )? searchurl.split('/')[1].split('&') : []; //Revisamos si existe / si existe se separan por / si no se declara vacio
    var base =searchurl.split('/')[0];
    var index =  -1; //Este nos ayuda a conocer cuando existe o no el parametro
    for( i = 0 ; i < parametros.length; i++ ){ //Recorremos todos los parametros (si existen)
        if(parametros[i].split('=')[0] == name){ //Verificamos parametro a parametro
            index = i ; //si concuerda se guarda la posicion y se sale
            break;
        }
    }

    if (index != -1) { // si existe el parametro
        var cv = parametros[index].split('=');  //obtenemos la clave valor
        if (cv[1].indexOf(',') == -1) { //Existen mas de un valor para este parametro
            cv[1] += (Base64.decode(value) == cv[1]) ? '' : ',' + Base64.decode(value); //Si existe el valor del parametro entonces se ignora sino se agrega junto la coma
        }else{ // sino recorremos para ver que el nuevo valor exista
            valores = cv[1].split(',');
            nuevo = false;
            valores.forEach(function(row,i){
                if(row == Base64.decode(value)){
                    nuevo=true; // si existe
                    return;
                }
            });
            if(!nuevo) //Se asigna al parametro si no existe
                cv[1] += ',' + Base64.decode(value);
        }
        parametros[index] = cv[0] + '=' + cv[1]  //reasignamos el parametro al arreglo
    } else {
            parametros.push(name + '=' + Base64.decode(value)); //No existe y lo agregamos al arreglo
    }

    //Generamos la url de busqueda nueva
    searchurl = ""; //limpiamos la url
    parametros.forEach( function (row,i) { //recorremos los parametros
            if (row.split('=')[0] != 'page')
                searchurl += row + '&';
    });


   document.location = prueba.replace(uPath[pfinal],"").replace("#","")+encodeURI(Base64.encode(base+"/"+searchurl.substr(0,searchurl.length-1)));
    ;
}

function removeUrlParameters(name, value, urlBusqueda){
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    var busquedaDecodificada = Base64.decode(urlBusqueda);
    var base = busquedaDecodificada.split('/')[0];
    var parametros = (busquedaDecodificada.split('/')[1].split('&')); //obtenemos los filtros
    var finales=[];
    parametros.forEach(function(row, index){
        var parametro = row.split('=');
        if(parametro[0] == name){
           if(parametro[1].indexOf(",") != -1) {
               var indice = parametro[1].split(',').indexOf(Base64.decode(value));
               var valores = parametro[1].split(',');
               if(indice != -1){
                   var nuevo = "";

                   valores.splice(indice,1); //Aqui borro el indice y le cambio la clase a las cosas

                   valores.forEach(function (row, i) {
                       nuevo += row +",";
                   });
                   if(nuevo != "")
                       finales.push( parametro[0] + '='+nuevo.substr(0,nuevo.length-1) );
                   return ;
               }
           }
        }else {
            finales.push(row);
        }
    });
    //Generamos la nueva url de busqueda
    var nuevo ="";
    finales.forEach(function (row,index) {
        if(index == 0){
            nuevo = row;
        }else{
            nuevo+='&' + row;
        }
    });
    url = document.location+"";
    var busqueda= base;
    if(nuevo != ""){
        busqueda+=  "/" + nuevo;
    }

    document.location = url.replace(urlBusqueda,"").replace("#","")+encodeURI(Base64.encode(busqueda));


}

function asignaFiltros(consulta){
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
    var filtros = Base64.decode(consulta);
    if(filtros.indexOf('/') != -1 ) {
        filtros = filtros.split('/')[1].split('&');
        filtros.forEach(function (row, index) {
            var fv = row.split('=');
            switch (fv[0]) {
                case 'precio':
                    var precios = $('ul.price li');
                    for (var i = 0; i < precios.length; i++) {
                        indice = fv[1].split(',').indexOf(Base64.decode($(precios[i]).data('val')));
                        if (indice != -1) {
                            $(precios[i]).addClass('active');
                            $(precios[i]).children().addClass('active');
                        }
                    }
                    break;
                case 'categoria':
                    var categorias = $('ul.category li');
                    for (var i = 0; i < categorias.length; i++) {
                        indice = fv[1].split(',').indexOf(Base64.decode($(categorias[i]).data('val')));
                        if (indice != -1) {
                            $(categorias[i]).addClass('active');
                            $(categorias[i]).children().addClass('active');
                        }
                    }
                    break;
                case 'subcategoria':
                    var subcategorias = $('ul.subcategory li');
                    for (var i = 0; i < subcategorias.length; i++) {
                        indice = fv[1].split(',').indexOf(Base64.decode($(subcategorias[i]).data('val')));
                        if (indice != -1) {
                            $(subcategorias[i]).addClass('active');
                            $(subcategorias[i]).children().addClass('active');
                        }
                    }
                    break;
            }
        });
    }

}
