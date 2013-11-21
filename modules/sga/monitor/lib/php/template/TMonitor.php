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
 * Classe TMonitor
 *
 * responsavel pela estrutura HTML do Monitor
 *
 */
class TMonitor extends Template {

	/**
	 * Método para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/monitor.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/monitor.js"></script>' . "\n";
		$misc .= '<script type="text/javascript">
			                var monitor = new Monitor();
			                SGA.addOnLoadListener(monitor.refresh);
		          </script>';
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela do módulo monitor
	 * @param $monitor
	 */
	public static function display_monitor($monitor) {
		TMonitor::display_monitor_topo();
		TMonitor::display_monitor_conteudo($monitor);
		TMonitor::display_monitor_baixo($monitor);
	}

	/**
	 * Constrói o topo da tela do módulo monitor
	 */
	public static function display_monitor_topo() {
		?>
		<div id="monitor">
			<div id="template_topo">
				<?php
					$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
					Template::display_topo_padrao($modulo);
				?>
			</div>
			<div id="monitor_topo">
				<div id="topo_left">Previdencia Social</div>
				<div id="topo_right"></div>
				<div id="topo_center"><h1>Monitor</h1></div>
			</div>
		<?php
	}

	/**
	 * Constrói conteudo do módulo monitor
	 * @param $monitor
	 */
	public static function display_monitor_conteudo($monitor) {
		?>
			<div id="monitor_conteudo">
				<div id="monitor_ultima_senha">
					<p id="ultima_senha">&Uacute;ltimo: <span id="ult_senha"></span></p>
					<p id="fila">Total da Fila: <span id="total_senhas">0</span></p>
				</div>
				<div id="fila_servicos">
				</div>
			</div>
		<?php
	}
	
	/**
	 * Mostra filas de clientes referentes a cada serviço
	 * @param $monitor
	 * @param $pagina
	 */
	public static function display_monitor_filas($monitor, $pagina) {
        $primeiro = true;
        foreach ($monitor->get_servicos_at($pagina,$pagina+4) as $servico) {
	        $s = '';
	        if ($primeiro) {
		        $s = ' primeira';
		        $primeiro = false;
	        }
	        $fila = DB::getInstance()->get_fila(array($servico->get_id()), SGA::get_current_user()->get_unidade()->get_id());
	        $total = $fila->size();  
	        
	        echo '<div class="monitor_fila_servico'.$s.'">' . "\n";
	        echo "	<h3>{$servico->get_sigla()} - {$servico->get_nome()} <span title='Total'>$total</span></h3>\n";
	        echo "	<ul>\n";
	        foreach ($fila->get_atendimentos() as $atendimento) {
	            $cliente = $atendimento->get_cliente();
	            $dt_cheg = $atendimento->get_dt_cheg();
		        $pri = ($cliente->get_senha()->is_prioridade())?'prioridade':'';
		        $link = "javascript:Monitor.transfereSenha({$atendimento->get_id()}, '{$cliente->get_senha()->get_numero()}', {$servico->get_id()}, {$cliente->get_senha()->get_prioridade()->get_id()})";
		        echo '		<li>
		                        <a href="'.$link.'" class="'.$pri.'" title="'.$atendimento->get_dt_cheg().($cliente->get_nome() != null ? ' - '.$cliente->get_nome() : '').'">'.$cliente->get_senha()->get_full_numero().'</a>
		                    </li>' . "\n";
	        }
	        echo "	</ul>\n";
	        echo "</div>\n";
	    }
	}

	/**
	 * Constrói parte de baixo da tela do módulo monitor
	 * @param $monitor
	 */
	public static function display_monitor_baixo($monitor) {		
		?>
			<div id="monitor_baixo">
				<div id="monitor_control">
				    <form action="" method="post">
			            <ul>
				            <li><?php Template::display_action_button("Anterior", "images/back.png", "Monitor.changePage(-1);", "button", "btn_anterior", true,'Clique para mostrar a página anterior.'); ?></li>
				            <li>
				                <?php
				                    $total = sizeof($monitor->get_servicos()) / 4;
				                    $paginas = array();
				                    for ($i = 0; $i < $total; $i++) {
				                        $paginas[$i * 4] = $i + 1;
				                    }
						            echo parent::display_jump_menu($paginas,'goto_page', $_SESSION['MONITOR_PAGINA'], '', "Monitor.gotoPage(this)");
					            ?>
				            </li>
				            <li><?php Template::display_action_button("Pr&oacute;ximo", "images/forw.png", "Monitor.changePage(1);", "button", "btn_proximo", false,'Clique para mostrar a próxima página'); ?></li>
			            </ul>
			        </form>
				</div>
				<div id="monitor_menu">
					<ul>
						<?php
							foreach ($monitor->get_menu() as $menu) {
								echo '<li><a href="'.$menu->get_link().'">'.$menu->get_nome().'</a></li>';
							}
						?>
						<li><a href="?logout">Sair</a></li>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Exibe o popup para cancelar senhas
	 * @param $servicos array de serviços
	 */
	public static function exibir_cancelar_senha() {
		?>
			<div>				
				<label for="id_radio_servico">Por Serviços</label>
				<input type="radio" name="radio_cancelar_senha" id="id_radio_servico" value="servico" onclick = "Monitor.onRadioCancelar(this)" />
				<label for="id_radio_senha">Por Senha</label>
				<input type="radio" name="radio_cancelar_senha" id="id_radio_senha" value="senha" onclick = "Monitor.onRadioCancelar(this)" />
			</div>
			<div id="id_cancelar_senha">  </div>		
			<span>
				<input id="confirmar_cancelar_senha" title="Clique para cancelar a senha." type="button" onclick="Monitor.confirmaCancelarSenha(this);" value="Confirmar" />
		    	<input title="Clique para fechar a janela." type="button" onclick="window.closePopup(this);" value="Cancelar" />
		    	<div id="id_label_cancelar_senha"></div>
        	</span>
	    <?php
	}
	
	/**
	 * Tela (dentro do popup cancelar senha) para cancelar senha procurando-a pelo número
	 * @param $mostra_periodo
	 * @param $onclick
	 */
	public static function exibir_cancelar_senha_por_senha($mostra_periodo=false,$onclick="Monitor.procuraSenha()") {
		?>
		
			<div id="id_exibir_cancelar_senha_por_senha">
                            <form action="" onsubmit="<?php echo $onclick?>; return false;" >
					<label for="id_text_senha">Digite a senha:  </label> 
					<input title="Digite o termo de busca." style="width:105px" type = "text" maxlength="10" id="id_text_senha" onkeypress="return SGA.txtBoxSoNumeros(this,event);" onclick="Monitor.selecionaTextBox(this)" /> 
					<?php echo parent::display_label_advertencia('label_cancelar_senha','advertencia');?>
                                        <?php
                                        if($mostra_periodo){
						echo TMonitor::exibir_periodo();
					}
					?>
					<input title="Clique para procurar a senha." type="submit"  id="id_button_procurar" value="Procurar" />

					<span id="id_label_senha"></span>
					<?php if($mostra_periodo){?>
						<span><input title="Clique para fechar a janela." type="button" onclick="window.closePopup(this);" value="Sair" /></span>
					<?php } ?>
				</form>
			</div>
				<?php
	}

	/**
	 * Tela para procurar senha através de período passado
	 */
	public static function exibir_periodo() {
		$data_atual = date("d/m/Y");
		?>
			<div>
				<label>Período</label>
				<div title="Data final e inicial da pesquisa.">
					<?php Template::display_date_field('id_data_comeco', $data_atual, 'Monitor.selecionaTextBox(this);')?>
					&nbsp;à&nbsp;
					<?php Template::display_date_field('id_data_fim', $data_atual, 'Monitor.selecionaTextBox(this);')?>
				</div>
				<div>(ex.: dd/mm/aaaa)</div>
			</div>
		<?php
	}
	
	/**
	 * 
	 * @param $atendimento
	 */
	public static function exibir_atendimento($atendimento) {
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		$servico = DB::getInstance()->get_servico_unidade($atendimento->get_servico(),$id_uni);
		//->get_servico_current_uni($atendimento->get_servico());
		$id_prioridade = $atendimento->get_cliente()->get_senha()->get_prioridade()->get_id();
		$nm_prioridade = DB::getInstance()->get_nm_pri($id_prioridade);
		?>
			<input type = "hidden" id = "id_id_atendimento" value="<?php echo $atendimento->get_id();?>"/> 
			<div><label for="id_label_numero_senha">Senha:</label>
			<label id="id_label_numero_senha"><?php echo $atendimento->get_cliente()->get_senha()->get_numero();?></label></div>
			<div><label for="id_label_numero_senha">Serviço:</label>
			<label id="id_label_nome_serv"><?php echo $servico->get_sigla().'-'.$servico->get_nome();?></label></div>
			<div><label for="id_label_prioridade_senha">Prioridade:</label>
			<label id="id_label_prioridade_senha"><?php echo $nm_prioridade?></label></div>
		<?php
	}
	
	/**
	 * Tela (dentro do popup cancelar senha) para cancelar senha procurando-a pelo servico
	 * @param $servicos
	 */
	public static function exibir_cancelar_senha_por_servico($servicos) {
		?>
			<div>Servico:</div> 
			<div>
				<?php
					echo parent::display_jump_menu($servicos,'servico_cancela_senha', $servico, '','Monitor.onServicoSelecionado();',0,'',"'Selecione o serviço.'");
					parent::display_label_advertencia('id_label_cancelar_por_servico');
				?>
			</div>
			<div>Senha:</div> 
			<div>
				<label id= "senhas_servico">
				<?php
					echo parent::display_jump_menu(array(), "prioridade", '', "Senhas",'',0,'',"'Selecione a senha.'");
				?>	
				</label>
				<?php
					parent::display_label_advertencia('id_label_cancelar');
				?>
				<div id= "senhas_servico">
			</div>
		<?php
	}
	
	
	/**
	 * Lista das prioridades
	 * @param $prioridades
	 * @param $default
	 */
	public static function exibir_prioridade_senha($prioridades, $default = null){
		
		echo parent::display_jump_menu($prioridades,'list_prio',$default,'Prioridade','',0,'',"'Selecione o tipo prioridade.'");	
		
	}
	
	
	/**
	 * Exibe as senhas de um array de atendimentos
	 * @param $atendimentos array
	 */
	public static function exibir_senhas_servico($atendimentos , $onchange = ""){
		$tmp = array();
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		foreach ($atendimentos as $atendimento) {
			$cliente = $atendimento->get_cliente();
			$numero = $cliente->get_senha()->get_full_numero();
			$tmp[$atendimento->get_id()] = $numero;
			$tmp[$atendimento->get_id()] .= '-'.DB::getInstance()->get_servico_unidade($atendimento->get_servico(), $id_uni)->get_sigla();
		}
		echo Template::display_jump_menu($tmp, 'id_cancelar_senhas', '', "Senhas", $onchange,0,'',"'Selecione a senha.'");
	}
	
	/**
	 * Exibe o popup para reativar senhas
	 * @param $servicos array
	 */
	public static function exibir_reativar_senha($servicos) {
		if(empty($servicos)){
			?>
				<div>
					Não há senhas para reativar.
					<ul>
						<li><input type="button" id="confirmar_reativar_senha" onclick="window.closePopup(this);" value="OK" /></li>
		        	</ul>
	        	</div>
			<?php
		}else{
			$prioridade = DB::getInstance()->get_prioridades();
			$ids_serv = array();
			$jump_macro = array();
			$jump_macro[-1] = "Todos";
			foreach ($servicos as $s) {
				$jump_macro[$s->get_id()] = $s->get_sigla().'-'.$s->get_nome();
				$ids_serv[] =  $s->get_id();
			}
			
			?>
				<div>Servico:</div> 
				<div style="min-width: 200px;">
					<?php
						echo parent::display_jump_menu($jump_macro,'servico_reativa_senha', -1, null,'Monitor.onServicoSelecionadoReativar();',0,'',"'Selecione o serviço.'");
					?>
				</div>
			
				<div>Senha:</div> 
				<div>
					<label id= "senhas_servico">				
						<?php
							$id_uni = SGA::get_current_user()->get_unidade()->get_id();
							$fila = DB::getInstance()->get_fila($ids_serv, $id_uni,$ids_stat=array(Atendimento::SENHA_CANCELADA, Atendimento::NAO_COMPARECEU));
							TMonitor::exibir_senhas_servico($fila->get_atendimentos(),'Monitor.onPrioridadeSenha();');
						?>
					</label>
					<label id="id_label_senha_servico" class="advertencia"></label>
				</div>
				<div>Prioridade:</div>
				<div>
					<label id="senhas_prioridade">
						<?php
						TMonitor::exibir_prioridade_senha($prioridade);
						?>
					</label>
					<?php parent::display_label_advertencia('id_label_prioridade_servico');?>
				</div>
				
				<span>
					<input id="confirmar_reativar_senha" title="Clique para reativar a senha." type="button" onclick="Monitor.confirmaReativarSenha(this);" value="Confirmar" />
			    	<input title="Clique para fechar a janela." type="button" onclick="window.closePopup(this);" value="Cancelar" />
	        	</span>
		    <?php
		}
	}
	
	/**
	 * Tela para transferência da senha
	 * @param $senha
	 * @param $servico
	 * @param $prioridade
	 */
	public static function display_transfere_senha($id_atend, $senha, $servico, $prioridade) {
		$servicos = array();
		$id_uni = SGA::get_current_user()->get_unidade()->get_id();
		
		$aux = DB::getInstance()->get_servicos_unidade_transfere_senha(Servico::SERVICO_ATIVO, $id_uni , $servico);
		
		foreach ($aux as $s){
			$servicos[$s->get_id()] = $s->get_sigla() .' - '. $s->get_nome();
			
		}
		$aux = DB::getInstance()->get_prioridades();
	    
		foreach ($aux as $p)
			$prioridades[$p->get_id()] = $p->get_nome();
		?>
		<div id="window_popup_content">
			<form id="transfere_form" action="transfere.php" method="post" >
				<div><input type="hidden" id="id_atend" name="id_atend" value="<?php echo $id_atend;?>" /></div>
				<div><input type="hidden" id="senha" name="senha" value="<?php echo $senha;?>" /></div>
				<div title="Senha a ser tranferida." ><span>Senha:</span> <?php echo $senha;?></div>
				<div title="Selecione o serviço que receberá a senha."><span>Novo Servico:</span> 
					<?php
						echo parent::display_jump_menu($servicos,'novo_servico', $servico, '--- Servicos ---');
					?>
				</div>
				<div title="Selecione o tipo de prioridade."><span>Prioridade:</span> 
					<?php
						echo parent::display_jump_menu($prioridades, "prioridade", $prioridade, "");
					?>
				</div>
				<span>
					<input title="Clique para transferir." type="button" onclick="Monitor.transferir(this);" value="Transferir" />
				    <input title="Clique para fechar a janela." type="button" onclick="window.closePopup(this);" value="Cancelar" />
		        </span>
			</form>
		</div>
		<?php
	}
	
	/**
	 * Tela de consultar senha
	 */
	public static function exibir_consultar_senhas (){
			TMonitor::exibir_cancelar_senha_por_senha(true,"Monitor.procuraConsultarSenha()");?> 
		<?php
	}
	
	/**
	 * Tela de resultado quando uma senha é encontrada na busca por período
	 * @param $atendimentos
	 */
	public static function exibir_atendimento_periodo($atendimentos){
		?>
		<div id="periodo">
			<?php
			foreach($atendimentos as $atendimento){?>
				<div id="servico_geral">
					<?php
					$servicos =  $atendimento->get_servico();
					$servicos = explode(',',(substr($servicos,1,-1)));
					$dt_cheg = substr($atendimento->get_dt_cheg(),0,10);
					$dt_cheg_hr = substr($atendimento->get_dt_cheg(),10,10);
					?>
					
					<div id="cabecalho_servico">
						<div id="data_top">Data: <?php echo $dt_cheg;?></div>
						<div id="caption_serv"><h1>Serviço</h1></div>
						<?php if ($servicos[0]==""){?>
							<div id="list_serv">Não existem serviços atribuídos a este atendimento</div>
						<?php }else{
							foreach ($servicos as $serv){
								$servico = DB::getInstance()->get_servico($serv);
								?>
								<div id="list_serv"><?php echo $servico->get_nome()?></div>
						<?php } 
						} ?>
					</div>
				</div>
				<div id="periodo_geral">
                                    <table id="row_cabecalho" style="">
                                        <thead>
                                            <tr>
                                                <th>Prioridade</th>
                                                <th>Status</th>
                                                <th>Atendente</th>
                                                <th>Guiche</th>
                                                <th>Chegada</th>
                                                <th>Início</th>
                                                <th>Fim</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td><?php echo $atendimento->get_cliente()->get_senha()->get_prioridade()->get_nome();?></td>
                                                <td><?php echo DB::getInstance()->get_status($atendimento->get_status());?></td>
                                                <td><?php echo ($atendimento->get_usuario() == null ? "- - -" : $atendimento->get_usuario());?></td>
                                                <td><?php echo $atendimento->get_num_guiche();?></td>
                                                <td><?php echo $dt_cheg_hr;?></td>
                                                <td><?php echo $atendimento->get_dt_ini();?></td>
                                                <td><?php echo $atendimento->get_dt_fim();?></td>
                                            </tr>
                                        </tbody>
                                    </table>
				</div>
			<?php
			}
			?>
		</div>
		<?php
	}

}
?>
