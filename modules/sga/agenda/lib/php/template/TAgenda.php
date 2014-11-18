<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TAgenda
 *
 * @author Felipe
 */
class TAgenda extends Template{
    
	/**
	 * MÃ©todo para importar arquivos de css e js
	 * @param $title
	 */
	public static function display_header($title='') {
		SGA::_include("modules/sga/agenda/content_loader.php");
		$tema = parent::get_tema();
		$mod_dir = Session::getInstance()->get(SGA::K_CURRENT_MODULE)->get_dir();
		$misc = '<link rel="stylesheet" href="themes/'. $tema->get_dir() .'/css/agenda.css" type="text/css" />' . "\n";
		$misc .= '<script type="text/javascript" src="modules/'.$mod_dir.'/lib/js/agenda.js"></script>' . "\n";
		parent::display_header($title, $misc);
	}

	/**
	 * Constrói tela da agenda
	 * @param $user
	 */
	public static function display_agenda(Usuario $user) {
		$topo = array('TAgenda', 'display_agenda_topo');
		$menu = array('TAgenda', 'display_agenda_menu');
		$conteudo = array('TAgenda', 'display_agenda_content');
		Template::display_template_padrao($topo, $menu, $conteudo);

	}

	/**
	 * Constrói o topo da tela da agenda
	 */
	public static function display_agenda_topo() {
		$modulo = Session::getInstance()->get(SGA::K_CURRENT_MODULE);
		Template::display_topo_padrao($modulo);
	}

	/**
	 * Constrói menus da tela da agenda
	 */
	public static function display_agenda_menu() {
		$usuario = SGA::get_current_user();
		TAgenda::display_user_info($usuario);
		Template::display_menu_padrao($modulo, $usuario);
	}

	/**
	 * Mostra conteúdo do método agenda
	 */
	public static function display_agenda_content() {
		TAgenda::display_conteudo();
		SGA::_include("modules/sga/agenda/content_loader.php");
		       
	}

	public static function display_conteudo() {
        $id_uni = SGA::get_current_user()->get_unidade()->get_id();
        $id_usuario = SGA::get_current_user()->get_id();
		$serv = DB::getInstance()->get_servicos_unidade($id_uni, array(1));
		
		Template::display_page_title('Criar Agenda'); 
		$horas = array('07:00'=>'07:00','08:00'=>'08:00','09:00'=>'09:00','10:00'=>'10:00','11:00'=>'11:00','12:00'=>'12:00','13:00'=>'13:00','14:00'=>'14:00','15:00'=>'15:00','16:00'=>'16:00','17:00'=>'17:00','18:00'=>'18:00','19:00'=>'19:00');
		?>
		

		<div class="agendamento">
		<form id="frm_criar_agenda" method="post" action="" onsubmit="Agenda.criar_agen(); return false;">	
				
				<? $dia_inicio = '2014/11/03'; ?>
				<h3>De <? echo date('d/m/Y', strtotime($dia_inicio)) ?> à <? echo date('d/m/Y', strtotime("+5 days",strtotime($dia_inicio))); ?> &nbsp; <a> >> </a> </h3>
			

			<table class="agendamento">
				<thead>
					<tr>
						<td style="width:40px; text-align:center; font-weight:bold;	padding-button:30px;"> Hora</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Segunda</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Terça</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Quarta</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Quinta</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Sexta</td>
						<td style="width:40px; text-align:center; font-weight:bold; padding-button:30px;"> Sábado</td>
					</tr>		
				</thead>
				<tr style="border: solid #d1d1d1 1px;">
					<td style="width:40px; text-align:center;">08:00</td>

					<? $segunda_08_00 = DB::getInstance()->get_agenda($dia_inicio, '08:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_08_00 != null) {
							$check_segunda_08_00 = "checked='checked'";
						}else{
							$check_segunda_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_08:00_<? echo $dia_inicio ?>_<?echo $check_segunda_08_00?>" value="segunda_08:00" <?php echo $check_segunda_08_00;?> /></td>
					
					<? $terca_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_08_00 != null) {
							$check_terca_08_00 = "checked='checked'";
						}else{
							$check_terca_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_08:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_08:00" <?php echo $check_terca_08_00;?>/></td>
					
					<? $quarta_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_08_00 != null) {
							$check_quarta_08_00 = "checked='checked'";
						}else{
							$check_quarta_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_08:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_08:00" <?php echo $check_quarta_08_00;?>/></td>
					

					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_08:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_08:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_08:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_08:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_08:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_08:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">08:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_08:30_<? echo $dia_inicio ?>" value="segunda_08:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_08:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_08:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_08:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_08:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_08:30_<? echo date('Y/m/d', strtotime("+3days",strtotime($dia_inicio))); ?>" value="quinta_08:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_08:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_08:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_08:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_08:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">09:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_09:00_<? echo $dia_inicio ?>" value="segunda_09:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_09:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_09:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_09:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_09:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_09:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_09:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_09:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_09:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_09:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_09:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">09:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_09:30_<? echo $dia_inicio ?>" value="segunda_09:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_09:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_09:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_09:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_09:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_09:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_09:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_09:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_09:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_09:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_09:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">10:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_10:00_<? echo $dia_inicio ?>" value="segunda_10:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_10:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_10:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_10:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_10:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_10:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_10:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_10:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_10:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_10:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_10:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">10:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_10:30_<? echo $dia_inicio ?>" value="segunda_10:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_10:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_10:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_10:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_10:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_10:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_10:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_10:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_10:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_10:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_10:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">11:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_11:00_<? echo $dia_inicio ?>" value="segunda_11:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_11:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_11:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_11:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_11:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_11:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_11:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_11:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_11:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_11:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_11:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">11:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_11_30_<? echo $dia_inicio ?>" value="segunda_11:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_11_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_11:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_11_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_11:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_11_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_11:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_11_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_11:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_11_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_11:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">12:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_12_00_<? echo $dia_inicio ?>" value="segunda_12:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_12_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_12:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_12_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_12:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_12_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_12:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_12_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_12:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_12_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_12:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">12:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_12_30_<? echo $dia_inicio ?>" value="segunda_12:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_12_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_12:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_12_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_12:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_12_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_12:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_12_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_12:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_12_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_12:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">13:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_13_00_<? echo $dia_inicio ?>" value="segunda_13:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_13_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_13:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_13_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_13:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_13_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_13:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_13_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_13:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_13_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_13:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">13:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_13_30_<? echo $dia_inicio ?>" value="segunda_13:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_13_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_13:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_13_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_13:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_13_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_13:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_13_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_13:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_13_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_13:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">14:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_14_00_<? echo $dia_inicio ?>" value="segunda_14:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_14_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_14:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_14_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_14:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_14_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_14:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_14_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_14:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_14_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_14:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">14:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_14_30_<? echo $dia_inicio ?>" value="segunda_14:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_14_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_14:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_14_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_14:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_14_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_14:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_14_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_14:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_14_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_14:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">15:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_15_00_<? echo $dia_inicio ?>" value="segunda_15:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_15_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_15:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_15_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_15:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_15_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_15:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_15_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_15:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_15_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_15:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">15:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_15_30_<? echo $dia_inicio ?>" value="segunda_15:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_15_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_15:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_15_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_15:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_15_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_15:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_15_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_15:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_15_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_15:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">16:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_16_00_<? echo $dia_inicio ?>" value="segunda_16:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_16_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_16:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_16_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_16:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_16_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_16:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_16_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_16:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_16_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_16:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">16:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_16_30_<? echo $dia_inicio ?>" value="segunda_16:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_16_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_16:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_16_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_16:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_16_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_16:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_16_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_16:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_16_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_16:30" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">17:00</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_17_00_<? echo $dia_inicio ?>" value="segunda_17:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_17_00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_17:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_17_00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_17:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_17_00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_17:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_17_00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_17:00" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_17_00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_17:00" /></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">17:30</td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda_17_30_<? echo $dia_inicio ?>" value="segunda_17:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terca_17_30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>" value="terca_17:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta_17_30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>" value="quarta_17:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta_17_30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>" value="quinta_17:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta_17_30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>" value="sexta_17:30" /></td>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_17_30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>" value="sabado_17:30" /></td>
				</tr>
			</table>	
			<!--<span title="Selecione o horario início da manhã"><span>Hora Início Manhã:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_start_morning', $default, '');
				?>
			</span>

			<span title="Selecione o horario fim da manhã"><span>Hora Fim Manhã:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_end_morning', $default, '');
				?>
			</span>

			<span title="Selecione o horario início da tarde"><span>Hora Início Tarde:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_start_afternoon', $default, '');
				?>
			</span>

			<span title="Selecione o horario fim da tarde"><span>Hora Fim Tarde:</span>
				<?php
					echo parent::display_jump_menu($horas,'hour_end_afternoon', $default, '');
				?>
			</span>-->
			<br>
			<br>
			<div>
				<?php
					Template::display_action_button("Confirmar", "images/tick.png", "Agenda.criar_agen();",'button','',true,'Clique para confirmar a criação da agenda.');
            		Template::display_action_button("Voltar", "images/cross.png", "Agenda.cancelarErroTriagem()",'button','',true,'Clique para voltar.');
				?>
			</div>
		</form>	
		</div>
		<?php
	}

}

?>