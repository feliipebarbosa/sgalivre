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

Agenda.criar_agen = function(button){

    var form = document.getElementById('frm_criar_agenda');
    //var dia_semana = document.getElementById("segunda_08_00").checked;
    var itens = "";

    var listaMarcados = document.getElementsByTagName("INPUT");  
    for (i = 0; i < listaMarcados.length; i++) {  
        var item = listaMarcados[i];  
        if (item.type == "checkbox" && item.checked) {  
            itens += item.id;  
            itens +=", ";
        }  
    }
    dia_semana = itens.substr(0,itens.length -2);



    Ajax.simpleLoad(AGEN_PATH + "acoes/criar_agenda.php", "conteudo_servicos", "POST", Ajax.encodeFormAsPost(form), false);



}