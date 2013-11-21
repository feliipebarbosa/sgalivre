<?php

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

/**
 * Classe TRelatorios
 *
 * responsavel pela estrutura HTML do modulo Relatórios
 *
 */
class TRelatorios extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/relatorios.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/relatorios.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			                var rels = new Relatorios();
			                SGA.addOnLoadListener(rels.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}
	
	/**
	 * Constrói a tela do módulo relatórios
	 *
	 */
	public static function display_relatorios() {
		$topo = array('TRelatorios', 'display_rel_topo');
		$menu = array('TRelatorios', 'display_rel_menu');
		$conteudo = array('TRelatorios', 'display_rel_content');
		Template::display_template_padrao($topo, $menu, $conteudo);
	}
	
	/**
	 * Constrói o topo da tela de relatórios
	 */
	public static function display_rel_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}
	
	/**
	 * Constrói menus da tela de relatórios
	 *  
	 */
	public static function display_rel_menu() {
		$usuario = SGA::get_current_user();
		Template::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}
	
	public static function display_rel_content() {
//		Template::display_page_title('Gerar Relatório');
//		TRelatorios::display_rel_select();
		//echo '<a target="blank" href="?mod=sga.relatorios&rel=1">Estatistica por Serviços Mestres</a>';
	}
	
	/**
	 * Conteudo da tela de relatórios
	 */
	public static function display_relatorio_conteudo(){
		Template::display_page_title('Gerar Relatório');
		TRelatorios::display_rel_select();
	}

    /**
	 * Conteudo da tela de relatórios
	 */
	public static function display_estatisticas_conteudo(){
		Template::display_page_title('Gerar Estatísticas');
		TRelatorios::display_est_select();
	}

        /**
	 * Conteudo da tela de gráficos
	 */
	public static function display_graficos_conteudo(){
		Template::display_page_title('Gerar Gráficos');
		TRelatorios::display_gfc_select();
	}

    /**
	 *
	 */
	public static function display_gfc_select() {
		?>
            <div class="menu_relatorio">
                <form id="frm_rel" method="post" action="">
                    <div>
                    	<input id="idGrupo" name="idGrupo" type="hidden" value=""/>
                        <span><?php echo TRelatorios::display_select_graficos();?></span>
                        <span>Início:</span>
                        <span><?php echo Template::display_date_field('dt_min', date("d/m/Y", time() - 24*60*60), '');?></span>
                        <span>Fim:</span>
                        <span><?php echo Template::display_date_field('dt_max', date("d/m/Y"), '');?></span>
                        <div class="controle_grupos"><?php TRelatorios::display_controle_grupos();?></div>
                    </div>
                    <div>
                        <span>Formato:</span>
                        <span><?php echo TRelatorios::display_select_formato();?></span>
                        <span>Exibir:</span>
                        <span><?php echo TRelatorios::display_select_exibicao();?></span>
                        <span><?php echo Template::display_action_button('Gerar', 'images/relatorio.png', 'Relatorios.gerarGrafico();', 'button', 'btn_gerar','','',true);?></span>
                    </div>
                    <div>
                        <input id="check_gfc_unidades" name="check_gfc_unidades" type="checkbox" checked="true" value="1"/><label for="check_gfc_unidades" >Resultados p/ unidade</label>
                        <input id="check_gfc_agregado" name="check_gfc_agregado" type="checkbox" checked="true" value="1"/><label for="check_gfc_agregado" >Resultado Agregado</label>
                    </div>
                </form>
            </div>
		<?php
	}

    	/**
	 *
	 */
	public static function display_select_graficos() {
		$graficos = array (1 => "Ranking das Unidades",
							2 => "Atendimentos Efetuados",
                            3 => "Variação do Tempos Médios");

		echo Template::display_jump_menu($graficos, 'id_rel', '', '-- Selecione --', 'Relatorios.onSelecionaRelatorio();');
	}

	/**
	 * 
	 */
	public static function display_est_select() {
		?>
            <div class="menu_relatorio">
                <form id="frm_rel" method="post" action="">
                    <div>
                    	<input id="idGrupo" name="idGrupo" type="hidden" value=""/>
                        <span><?php echo TRelatorios::display_select_estatisticas();?></span>
                        <span>Início:</span>
                        <span><?php echo Template::display_date_field('dt_min', date("d/m/Y", time() - 24*60*60), '');?></span>
                        <span>Fim:</span>
                        <span><?php echo Template::display_date_field('dt_max', date("d/m/Y"), '');?></span>
                        <div class="controle_grupos"><?php TRelatorios::display_controle_grupos();?></div>
                    </div>
                    <div>
                        <span>Formato:</span>
                        <span><?php echo TRelatorios::display_select_formato();?></span>
                        <span>Exibir:</span>
                        <span><?php echo TRelatorios::display_select_exibicao();?></span>
                        <span><?php echo Template::display_action_button('Gerar', 'images/relatorio.png', 'Relatorios.gerarEstatistica();', 'button', 'btn_gerar','','',true);?></span>
                    </div>
                    <div>
                        <input id="check_est_unidades" name="check_est_unidades" type="checkbox" checked="true" value="1"/><label for="check_est_unidades">Resultados p/ unidade</label>
                        <input id="check_est_agregado" name="check_est_agregado" type="checkbox" checked="true" value="1"/><label for="check_est_agregado">Resultado Agregado</label>
                    </div>
                </form>
            </div>
		<?php
	}
	
	/**
	 * 
	 */
	public static function display_select_estatisticas() {
		$estatisticas = array (1 => "Por Macrosserviços",
							2 => "Por Serviços Codificados",
                            3 => "Por Atendentes",
                            4 => "Tempos Médios");
                        
		echo Template::display_jump_menu($estatisticas, 'id_rel', '', '-- Selecione --', 'Relatorios.onSelecionaRelatorio();');
	}
	
	public static function display_rel_select() {
		?>
            <div class="menu_relatorio">
                <form id="frm_rel" method="post" action="">
                    <div>
                    	<input id="idGrupo" name="idGrupo" type="hidden" value=""/>
                        <span><?php echo TRelatorios::display_select_relatorios();?></span>
                        <span>Início:</span>
                        <span><?php echo Template::display_date_field('dt_min', date("d/m/Y", time() - 24*60*60), '');?></span>
                        <span>Fim:</span>
                        <span><?php echo Template::display_date_field('dt_max', date("d/m/Y"), '');?></span>
                        <div class="controle_grupos"><?php TRelatorios::display_controle_grupos();?></div>
                    </div>
                    <div>
                        <span>Formato:</span>
                        <span><?php echo TRelatorios::display_select_formato();?></span>
                        <span>Exibir:</span>
                        <span><?php echo TRelatorios::display_select_exibicao();?></span>
                        <span><?php echo Template::display_action_button('Gerar', 'images/relatorio.png', 'Relatorios.gerarRelatorio();', 'button', 'btn_gerar','','',true);?></span>
                    </div>
                    <div>
                        <input id="check_rel_unidades" name="check_rel_unidades" type="checkbox" checked="true" value="1"><label for="check_rel_unidades">Resultados p/ unidade</label>
                        <input id="check_rel_agregado" name="check_rel_agregado" type="checkbox" checked="true" value="1"><label for="check_rel_agregado">Resultado Agregado</label>
                    </div>
                </form>
            </div>
		<?php
	}
	
	public static function display_select_relatorios() {
		$relatorios = array (1 => "Serviços Disponíveis - Global",
							2 => "Serviços Disponíveis - Unidade",
							3 => "Atendimentos Concluídos",
							4 => "Atendimentos em todos os status",
                            5 => "Senhas por Status");
							
		echo Template::display_jump_menu($relatorios, 'id_rel', '', '-- Selecione --', 'Relatorios.onSelecionaRelatorio();');
	}
	
	/**
	 * 
	 */
	public static function display_select_formato() {
		$formatos = array ('html' => "HTML",
							'pdf' => "PDF");
							
		echo Template::display_jump_menu($formatos, 'formato', 'html', null, 'Relatorios.onSelecionaFormato();');
	}
	
	/**
	 *
	 */
	public static function display_select_exibicao() {
		$relatorios = array (0 => "Página atual",
							1 => "Nova página");
							
		echo Template::display_jump_menu($relatorios, 'id_exibicao', 1, null);
	}

    /**
	 * Conteudo da tela de configuacao dos grupos
	 */
	public function display_controle_grupos() {
		$admin = SGA::get_current_user();
        $temp = DB::getInstance()->get_arvore_grupos();
		$raiz = DB::getInstance()->get_arvore_grupos();
        $tmp_grupos = array();
        TRelatorios::get_arvore_grupos_array($raiz, &$tmp_grupos);
		
        // Obtem as lotacoes do admin nas quais ele pode editar usuarios
        $lotacoes = DB::getInstance()->get_lotacoes_editaveis($admin->get_id(), Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_id(), $raiz->get_id(), true);
        
        // se o usuario não possui a permissao na raiz
        // (caso existir deverá ser o unico grupo retornado devido ao filtro de redundancia)
        // caso ele possuir a arvore inteira de grupos é exbida, não sendo necessário filtrar grupos
		if (!$lotacoes[0]->get_grupo()->is_raiz()) {
			// Monta uma arvore parcial
			// filtrando grupos não permitidos
	        $filhos = array();
	        $grupos = array();
	        $raiz->clear_filhos();
	        foreach ($lotacoes as $lotacao) {
	        	//$raiz->add_filho($lotacao->get_grupo());
	            $grupo = $lotacao->get_grupo();
	            $filhos = DB::getInstance()->get_sub_grupos($grupo->get_id());
	            if (sizeOf($filhos) > 0){
	                foreach ($filhos as $f){
						$grupo->add_filho($f);	
	        		}
	            }
	            $raiz->add_filho($grupo);
	        }
	    }
              
		?>
        <div title="Lista de grupos." class="lista">
            <ul id="lista_grupos"  class="arvore_grupos">
                <?php
                    TRelatorios::display_item_arvore_grupo($raiz);
                   //parent::display_jump_menu($grupos, "groups_list", "", null, "Configuracao.onSelecionaGrupo();", 15);
                ?>
            </ul>
		</div>
		<?php
	}
	
	
 	public static function get_arvore_grupos_array(Grupo $grupo, $array) {
        $array[$grupo->get_id()] = $grupo;
        foreach ($grupo->get_filhos() as $g) {
            TRelatorios::get_arvore_grupos_array($g, &$array);
        }
    }

    
	/**
	 * Constrói árvore dos grupos
     * @param $grupo
	 */
    public static function display_item_arvore_grupo(Grupo $grupo) {
        $possui_filhos = sizeof($grupo->get_filhos()) > 0;
        ?>
            <li id="li_grupo_<?php echo $grupo->get_id();?>">
                <div id="span_grupo_<?php echo $grupo->get_id();?>" class="<?php echo ($possui_filhos ? "item_grupo_pai" : "item_grupo_filho");?>"><a href="javascript:Relatorios.selectGrupo(<?php echo $grupo->get_id();?>);"><?php echo $grupo->get_nome();?></a></div>
                <?php
                    if ($possui_filhos) {
                        ?>
                        <ul>
                            <?php
                                foreach ($grupo->get_filhos() as $filho) {
                                    TRelatorios::display_item_arvore_grupo($filho);
                                }
                            ?>
                        </ul>
                        <?php
                    }
                ?>
            </li>
        <?php
    }
    
}

?>
