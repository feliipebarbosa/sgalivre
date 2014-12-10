var AGEN_PATH = "?redir=modules/sga/agenda/";
var Agenda = function() {

    var self = this;
    var date;

    this.update = function() {


        var ajaxList = new AjaxList();
        ajaxList.add(ajax);

        ajaxList.loadURLs();
    }

    this.refresh = function() {
        self.update();
        setInterval(self.update, 3000);
    }
}

Agenda.criar_agen = function(){

    var sucesso = function() {
        window.showInfoDialog('Agenda criada com sucesso.');
    }

    var form = document.getElementById('frm_criar_agenda');
    var p = new Object();
    var itens = "";

    var listaMarcados = document.getElementsByTagName("INPUT");  
    for (i = 0; i < listaMarcados.length; i++) {  
        var item = listaMarcados[i];  
        if (item.type == "checkbox" && item.checked) {  
            itens += item.id;  
            itens +=", ";
        }  
    }

    var dias_semana = itens.substr(0,itens.length -2);

    var dias =  dias_semana.split(",");

    for(i = 0; i < dias.length; i++){

        p['dias_semana'] = dias[i];

        var dados = p['dias_semana'].split('_');
                
        if (dados[3] == ''){
            Ajax.simpleLoad(AGEN_PATH + "acoes/criar_agenda.php", "", "POST", Ajax.encodePostParameters(p), true);
        } 

    }

    var d = new Object();
    var itensDesmarcado = "";
    var listaDesmarcados = document.getElementsByTagName("INPUT");  
    for (i = 0; i < listaDesmarcados.length; i++) {  
        var itemDesmarcado = listaDesmarcados[i];  
        if (itemDesmarcado.type == "checkbox" && !itemDesmarcado.checked) {
            var dias_desmarcados = itemDesmarcado.id.split('_');

            if (dias_desmarcados[3] == "checked='checked'"){
                itensDesmarcado += itemDesmarcado.id;   
                itensDesmarcado +=", ";
            }
        }  
    }  

    var dias_semana_desmarcados = itensDesmarcado.substr(0,itensDesmarcado.length -2);
    var dias_desmarcados =  dias_semana_desmarcados.split(",");

    for(i = 0; i < dias_desmarcados.length; i++){
        d['dias_desmarcados'] = dias_desmarcados[i];
        alert(d['dias_desmarcados']);
        Ajax.simpleLoad(AGEN_PATH + "acoes/desmarcar_agenda.php", "", "POST", Ajax.encodePostParameters(d), true);        
    }  
    

}