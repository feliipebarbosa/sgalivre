var AGENDA_PATH = "?redir=modules/sga/agenda/";
var Agenda = function() {

    var self = this;
    var date;

    this.update = function() {

        var ajax = new Ajax(AGENDA_PATH + "atend_fila.php",new Target("fila"));

        var ajaxList = new AjaxList();
        ajaxList.add(ajax);

        ajaxList.loadURLs();
    }

    this.refresh = function() {
        self.update();
        setInterval(self.update, 3000);
    }
}