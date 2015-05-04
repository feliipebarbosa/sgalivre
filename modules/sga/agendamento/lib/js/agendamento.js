var AGENDAM_PATH = "?redir=modules/sga/agendamento/";

var Agendamento = function() {

    var self = this;

    this.update = function() {
    }

    this.refresh = function() {
        self.update();
    }

}

Agendamento.buscarAgenda = function(dia){
	var dia = dia.toString();
	alert(dia);

	var p = new Object();
	p['dia'] = dia;

	window.createLoading("conteudo_resultado_unidade");
	Ajax.simpleLoad(AGENDAM_PATH + "buscar_agenda.php", "carrega_agenda", "POST", Ajax.encodePostParameters(p), true);

}