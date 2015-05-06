var AGENDAM_PATH = "?redir=modules/sga/agendamento/";

var Agendamento = function() {

    var self = this;

    this.update = function() {
    }

    this.refresh = function() {
        self.update();
    }

}

Agendamento.atualizaTela = function(p) {

	Ajax.simpleLoad(AGENDAM_PATH + 'buscar_agenda.php', 'carrega_agenda', "POST", Ajax.encodePostParameters(p), true);
}

Agendamento.buscarAgenda = function(dia){

	var p = new Object();
	p['dia'] = dia;

	Ajax.simpleLoad(AGENDAM_PATH + "buscar_agenda.php", "carrega_agenda", "POST", Ajax.encodePostParameters(p), true);

}

Agendamento.criarAgendamento = function() {    
  
    var p = new Object();
    var form = document.frm_criar_agendamento;
	var Radio=null;

	Radio = form.agendamento;
	for(var i=0;i<Radio.length;i++){
		if(Radio[i].checked) {
			p['id'] = Radio[i].id;
		}
	}

	p['dia'] = document.getElementById('dia').value;

	Ajax.simpleLoad(AGENDAM_PATH + "acoes/criar_agendamento.php", '', "POST", Ajax
		.encodePostParameters(p), false, Agendamento.atualizaTela(p)); 
    
}