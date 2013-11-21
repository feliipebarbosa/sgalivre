
/**
 * 
 * Copyright (C) 2009 DATAPREV - Empresa de Tecnologia e Informações da Previdência Social - Brasil
 *
 * Este arquivo é parte do programa SGA Livre - Sistema de Gerenciamento do Atendimento - Versão Livre
 *
 * O SGA é um software livre; você pode redistribuí­-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como 
 * publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer
 * MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a 
 * Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 *
**/

var REL_PATH = "?redir=modules/sga/relatorios/";
var id_grupo_selecionado = null;

var Relatorios = function() {

    var self = this;
    
    this.update = function() {
    }
    
    this.refresh = function() {
        self.update();
    }

}
/**
 * Determina se a selectionlist selExibicao ficara habilitada ou não
 */
Relatorios.onSelecionaFormato = function() {
	var formato = document.getElementById('formato').value;
	var selExibicao = document.getElementById('id_exibicao');
	
	// HTML
	if (formato == "html") {
		selExibicao.value = 0;
		selExibicao.disabled = false;
	}
	else {
		selExibicao.value = 1;
		selExibicao.disabled = true;
	}
}
/**
 * Habilita o botao "Gerar" quando todos os elementos da consulta são indicados 
 */
Relatorios.onSelecionaRelatorio = function() {
	var selRel = document.getElementById('id_rel');
	var bool = false;
	bool = (id_grupo_selecionado!=null)?true:false;
	if (selRel.selectedIndex > 0 && bool) {
		document.getElementById('btn_gerar').disabled = false;
	}
	else {
		document.getElementById('btn_gerar').disabled = true;
	}
}

/**
 * Chama arquivo php para a exibiçao das estatisticas selecionadas
 */

Relatorios.gerarEstatistica = function() {
	var form = document.getElementById('frm_rel');
	var idGrupo = document.getElementById('idGrupo');
	idGrupo.setAttribute("value",id_grupo_selecionado);
	var exibicao = document.getElementById('id_exibicao').value;
	
	if (exibicao == "0") {
		Ajax.simpleLoad(REL_PATH + 'estatisticas.php?'+Ajax.encodeFormAsPost(form), 'template_conteudo', "GET", '', true);
	}
	else {
		window.open(REL_PATH + 'estatisticas.php?'+Ajax.encodeFormAsPost(form));
	}
}

/**
 * Chama arquivo php para a exibiçao do relatorio selecionada
 */

Relatorios.gerarRelatorio = function() {
	var form = document.getElementById('frm_rel');
	var idGrupo = document.getElementById('idGrupo');
	idGrupo.setAttribute("value",id_grupo_selecionado);
	var exibicao = document.getElementById('id_exibicao').value;
	
	if (exibicao == "0") {
		Ajax.simpleLoad(REL_PATH + 'relatorios.php?'+Ajax.encodeFormAsPost(form), 'template_conteudo', "GET", '', true);
	}
	else {
		window.open(REL_PATH + 'relatorios.php?'+Ajax.encodeFormAsPost(form));
	}
}

/**
 * Chama arquivo php para a exibiçao de acordo com o gráfico selecionado
 */

Relatorios.gerarGrafico = function() {
	var form = document.getElementById('frm_rel');
	var idGrupo = document.getElementById('idGrupo');
	idGrupo.setAttribute("value",id_grupo_selecionado);
	var exibicao = document.getElementById('id_exibicao').value;

	if (exibicao == "0") {
		Ajax.simpleLoad(REL_PATH + 'graficos.php?'+Ajax.encodeFormAsPost(form), 'template_conteudo', "GET", '', true);
	}
	else {
		window.open(REL_PATH + 'graficos.php?'+Ajax.encodeFormAsPost(form));
	}
}

/**
 * carrega a arvore de grupos
 */

Relatorios.onLoadGrupos = function() {
    $("#lista_grupos").treeview({
		collapsed: true,
		persist: "cookie"
	});
    Relatorios.selectGrupo(1);
}

/**
 * configura modos de exibição diferentes para o item selecionado na arvore grupos
 * @param id_grupo, checkbox
 */

var grupoBgAnt;
Relatorios.selectGrupo = function(id_grupo, checkbox) {
//    var li  = document.getElementById("li_grupo_"+id_grupo);
//    Relatorios.selectGrupoInternoLi(li, checkbox.checked );
//    Relatorios.onSelecionaRelatorio();
//    id_grupo_selecionado = id_grupo;
    var elem = $('#span_grupo_'+id_grupo).get(0);
    var elemGrupoSelecionado = $('#span_grupo_'+id_grupo_selecionado).get(0);
    if (elemGrupoSelecionado != null) {
        with (elemGrupoSelecionado.style) {
            border = 'none';
            font = 'normal 11.2px serif';
            background = grupoBgAnt;
        }
    }
    with (elem.style) {
        border = 'thin solid red';
        font = 'bold 14.5px serif';
        grupoBgAnt = background;
        background = 'url(themes/sga.default/imgs/arrow_right.png) left center no-repeat';
    }
    //alert("IDGRUPO: "+id_grupo);
    id_grupo_selecionado = id_grupo;
    
    Relatorios.onSelecionaRelatorio();

}

/**
 * 
 * @param li
 * @param check
 */

Relatorios.selectGrupoInternoLi = function(li, check) {
    for (var i = 0; i < li.childNodes.length; i++) {
        var n = li.childNodes[i];
        var tagName = n.tagName
        if (tagName == null) {
            continue;
        }
        tagName = tagName.toLowerCase();
        
        //alert("#"+i+" -> "+n.tagName);

        if (tagName == "ul") {
            Relatorios.selectGrupoInternoUl(n, check);
        }
        if (tagName == "div") {
            Relatorios.selectGrupoInternoLi(n, check);
        }
        else if (tagName == "input" && n.type != null && n.type.toLowerCase() == "checkbox") {
            n.checked = check;
        }
    }
}
/**
 * 
 * @param ul
 * @param check
 */
Relatorios.selectGrupoInternoUl = function(ul, check) {
    for (var i = 0; i < ul.childNodes.length; i++) {
        var n = ul.childNodes[i];
        var tagName = n.tagName
        if (tagName == null) {
            continue;
        }
        tagName = tagName.toLowerCase();

        if (tagName == "li") {
            Relatorios.selectGrupoInternoLi(n, check);
        }
    }
}
/**
 * 
 * @param url
 * Aparam onLoad
 */
Relatorios.alterarConteudo = function(url, onLoad) {
    SGA.alterarConteudo(REL_PATH + url, onLoad);
    
}
