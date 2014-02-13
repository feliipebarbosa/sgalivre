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
    var day = document.getElementById("day").value;
    var hour_start_morning = document.getElementById("hour_start_morning").value;
    var hour_end_morning = document.getElementById("hour_end_morning").value;
    var hour_start_afternoon = document.getElementById("hour_start_afternoon").value;
    var hour_end_afternoon = document.getElementById("hour_end_afternoon").value;


    if (hour_end_morning <= hour_start_morning){
        window.showErrorDialog("O horário de início não pode ser maior ou igual ao final.", "ERRO");
        return;
    }
    else if (hour_end_afternoon <= hour_start_afternoon){
        window.showErrorDialog("O horário de início não pode ser maior ou igual ao final.", "ERRO");
        return;
    }
    else if(hour_start_afternoon <= hour_start_morning){
        window.showErrorDialog("O horário de início não pode ser maior ou igual ao final.", "ERRO");
        return;
    }
    else if(hour_start_afternoon <= hour_end_morning){
        window.showErrorDialog("O horário de início não pode ser maior ou igual ao final.", "ERRO");
        return;
    }


    Ajax.simpleLoad(AGEN_PATH + "acoes/criar_agenda.php", "conteudo_servicos", "POST", Ajax.encodeFormAsPost(form), false);


}