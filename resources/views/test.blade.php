<script src="{{asset('/js/prototype.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    var Comet = Class.create();

    Comet.prototype = {

        timestamp: 0,
        url: document.location.protocol+'//'+document.location.host+"/HerramientasTepic/public/js/backend.php",
        noerror: true,

        initialize: function() { },

        connect: function()
        {
            var dato2 = "sin valor";
            this.ajax = new Ajax.Request(this.url, {
                method: 'get',
                parameters: { 'timestamp' : this.timestamp,'dato2' : dato2 },
                onSuccess: function(transport) {
                    // manejar la respuesta del servidor
                    var response = transport.responseText.evalJSON();
                    this.comet.timestamp = response['timestamp'];
                    this.comet.handleResponse(response);
                    this.comet.noerror = true;
                },
                onComplete: function(transport) {
                    // enviar una nueva solicitud de ajax cuando finalice esta solicitud
                    if (!this.comet.noerror)
                    // si se produce un problema de conexión , intente volver a conectar cada 5 segundos
                        setTimeout(function(){ comet.connect() }, 5000);
                    else
                        this.comet.connect();
                    this.comet.noerror = false;
                }
            });
            this.ajax.comet = this;
        },

        disconnect: function()
        {
        },

        handleResponse: function(response)//recibimos la respuesta en tiempo real
        {
            $('content').innerHTML += '<div>' + response['msg'] +" 2o valor "+response['dato2']+'</div>';
        },

        doRequest: function(request)//enviar el nuevo mensaje
        {
            var dato2 = document.getElementById('texto2').value;//segundo parametro

            new Ajax.Request(this.url, {
                method: 'get',
                parameters: { 'msg' : request ,'dato2' : dato2 }
            });
        }

    }
    var comet = new Comet();
    comet.connect();//instancia un objeto
</script>