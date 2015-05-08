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
				
				<? $dia_inicio = '2015/05/11'; ?>
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
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_08:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_08_00;?>" value="terca_08:00" <?php echo $check_terca_08_00;?>/></td>
					
					<? $quarta_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_08_00 != null) {
							$check_quarta_08_00 = "checked='checked'";
						}else{
							$check_quarta_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_08:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_08_00;?>" value="quarta_08:00" <?php echo $check_quarta_08_00;?>/></td>
					
					<? $quinta_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_08_00 != null) {
							$check_quinta_08_00 = "checked='checked'";
						}else{
							$check_quinta_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_08:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_08_00;?>" value="quinta_08:00" <?php echo $check_quinta_08_00;?>/></td>
					
					<? $sexta_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_08_00 != null) {
							$check_sexta_08_00 = "checked='checked'";
						}else{
							$check_sexta_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_08:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_08_00;?>" value="sexta_08:00" <?php echo $check_sexta_08_00;?>/></td>
					
					<? $sabado_08_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '08:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_08_00 != null) {
							$check_sabado_08_00 = "checked='checked'";
						}else{
							$check_sabado_08_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_08:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_08_00;?>" value="sabado_08:00" <?php echo $check_sabado_08_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">08:30</td>

					<? $segunda_08_30 = DB::getInstance()->get_agenda($dia_inicio, '08:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_08_30 != null) {
							$check_segunda_08_30 = "checked='checked'";
						}else{
							$check_segunda_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_08:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_08_30;?>" value="segunda_08:30" <?php echo $check_segunda_08_30;?>/></td>
					
					<? $terca_08_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '08:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_08_30 != null) {
							$check_terca_08_30 = "checked='checked'";
						}else{
							$check_terca_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_08:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_08_30;?>" value="terca_08:30" <?php echo $check_terca_08_30;?>/></td>
					
					<? $quarta_08_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '08:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_08_30 != null) {
							$check_quarta_08_30 = "checked='checked'";
						}else{
							$check_quarta_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_08:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_08_30;?>" value="quarta_08:30" <?php echo $check_quarta_08_30;?>/></td>
					
					<? $quinta_08_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '08:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_08_30 != null) {
							$check_quinta_08_30 = "checked='checked'";
						}else{
							$check_quinta_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_08:30_<? echo date('Y/m/d', strtotime("+3days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_08_30;?>" value="quinta_08:30" <?php echo $check_quinta_08_30;?>/></td>
					
					<? $sexta_08_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '08:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_08_30 != null) {
							$check_sexta_08_30 = "checked='checked'";
						}else{
							$check_sexta_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_08:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_08_30;?>" value="sexta_08:30" <?php echo $check_sexta_08_30;?>/></td>
					
					<? $sabado_08_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '08:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_08_30 != null) {
							$check_sabado_08_30 = "checked='checked'";
						}else{
							$check_sabado_08_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_08:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_08_30;?>" value="sabado_08:30" <?php echo $check_sabado_08_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">09:00</td>

					<? $segunda_09_00 = DB::getInstance()->get_agenda($dia_inicio, '09:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_09_00 != null) {
							$check_segunda_09_00 = "checked='checked'";
						}else{
							$check_segunda_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_09:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_09_00;?>" value="segunda_09:00" <?php echo $check_segunda_09_00;?>/></td>
					
					<? $terca_09_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '09:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_09_00 != null) {
							$check_terca_09_00 = "checked='checked'";
						}else{
							$check_terca_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_09:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_09_00;?>" value="terca_09:00" <?php echo $check_terca_09_00;?>/></td>
					
					<? $quarta_09_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '09:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_09_00 != null) {
							$check_quarta_09_00 = "checked='checked'";
						}else{
							$check_quarta_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_09:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_09_00;?>" value="quarta_09:00" <?php echo $check_quarta_09_00;?>/></td>
					
					<? $quinta_09_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '09:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_09_00 != null) {
							$check_quinta_09_00 = "checked='checked'";
						}else{
							$check_quinta_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_09:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_09_00;?>" value="quinta_09:00" <?php echo $check_quinta_09_00;?>/></td>
					
					<? $sexta_09_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '09:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_09_00 != null) {
							$check_sexta_09_00 = "checked='checked'";
						}else{
							$check_sexta_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_09:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_09_00;?>" value="sexta_09:00" <?php echo $check_sexta_09_00;?>/></td>
					
					<? $sabado_09_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '09:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_09_00 != null) {
							$check_sabado_09_00 = "checked='checked'";
						}else{
							$check_sabado_09_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_09:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_09_00;?>" value="sabado_09:00" <?php echo $check_sabado_09_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">09:30</td>

					<? $segunda_09_30 = DB::getInstance()->get_agenda($dia_inicio, '09:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_09_30 != null) {
							$check_segunda_09_30 = "checked='checked'";
						}else{
							$check_segunda_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_09:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_09_30;?>" value="segunda_09:30" <?php echo $check_segunda_09_30;?>/></td>
					
					<? $terca_09_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '09:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_09_30 != null) {
							$check_terca_09_30 = "checked='checked'";
						}else{
							$check_terca_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_09:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_09_30;?>" value="terca_09:30" <?php echo $check_terca_09_30;?>/></td>
					
					<? $quarta_09_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '09:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_09_30 != null) {
							$check_quarta_09_30 = "checked='checked'";
						}else{
							$check_quarta_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_09:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_09_30;?>" value="quarta_09:30" <?php echo $check_quarta_09_30;?>/></td>

					<? $quinta_09_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '09:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_09_30 != null) {
							$check_quinta_09_30 = "checked='checked'";
						}else{
							$check_quinta_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_09:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_09_30;?>" value="quinta_09:30" <?php echo $check_quinta_09_30;?>/></td>
					
					<? $sexta_09_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '09:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_09_30 != null) {
							$check_sexta_09_30 = "checked='checked'";
						}else{
							$check_sexta_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_09:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_09_30;?>" value="sexta_09:30" <?php echo $check_sexta_09_30;?>/></td>
					
					<? $sabado_09_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '09:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_09_30 != null) {
							$check_sabado_09_30 = "checked='checked'";
						}else{
							$check_sabado_09_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_09:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_09_30;?>" value="sabado_09:30" <?php echo $check_sabado_09_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">10:00</td>

					<? $segunda_10_00 = DB::getInstance()->get_agenda($dia_inicio, '10:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_10_00 != null) {
							$check_segunda_10_00 = "checked='checked'";
						}else{
							$check_segunda_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_10:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_10_00;?>" value="segunda_10:00" <?php echo $check_segunda_10_00;?>/></td>

					<? $terca_10_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '10:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_10_00 != null) {
							$check_terca_10_00 = "checked='checked'";
						}else{
							$check_terca_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_10:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_10_00;?>" value="terca_10:00" <?php echo $check_terca_10_00;?>/></td>

					<? $quarta_10_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '10:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_10_00 != null) {
							$check_quarta_10_00 = "checked='checked'";
						}else{
							$check_quarta_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_10:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_10_00;?>" value="quarta_10:00" <?php echo $check_quarta_10_00;?>/></td>
					
					<? $quinta_10_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '10:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_10_00 != null) {
							$check_quinta_10_00 = "checked='checked'";
						}else{
							$check_quinta_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_10:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_10_00;?>" value="quinta_10:00" <?php echo $check_quinta_10_00;?>/></td>
					
					<? $sexta_10_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '10:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_10_00 != null) {
							$check_sexta_10_00 = "checked='checked'";
						}else{
							$check_sexta_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_10:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_10_00;?>" value="sexta_10:00" <?php echo $check_sexta_10_00;?>/></td>
					
					<? $sabado_10_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '10:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_10_00 != null) {
							$check_sabado_10_00 = "checked='checked'";
						}else{
							$check_sabado_10_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_10:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_10_00;?>" value="sabado_10:00" <?php echo $check_sabado_10_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">10:30</td>

					<? $segunda_10_30 = DB::getInstance()->get_agenda($dia_inicio, '10:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_10_30 != null) {
							$check_segunda_10_30 = "checked='checked'";
						}else{
							$check_segunda_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_10:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_10_30;?>" value="segunda_10:30" <?php echo $check_segunda_10_30;?>/></td>
					
					<? $terca_10_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '10:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_10_30 != null) {
							$check_terca_10_30 = "checked='checked'";
						}else{
							$check_terca_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_10:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_10_30;?>" value="terca_10:30" <?php echo $check_terca_10_30;?>/></td>
					
					<? $quarta_10_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '10:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_10_30 != null) {
							$check_quarta_10_30 = "checked='checked'";
						}else{
							$check_quarta_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_10:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_10_30;?>" value="quarta_10:30" <?php echo $check_quarta_10_30;?>/></td>
					
					<? $quinta_10_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '10:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_10_30 != null) {
							$check_quinta_10_30 = "checked='checked'";
						}else{
							$check_quinta_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_10:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_10_30;?>" value="quinta_10:30" <?php echo $check_quinta_10_30;?>/></td>
					
					<? $sexta_10_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '10:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_10_30 != null) {
							$check_sexta_10_30 = "checked='checked'";
						}else{
							$check_sexta_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_10:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_10_30;?>" value="sexta_10:30" <?php echo $check_sexta_10_30;?>/></td>
					
					<? $sabado_10_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '10:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_10_30 != null) {
							$check_sabado_10_30 = "checked='checked'";
						}else{
							$check_sabado_10_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_10:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_10_30;?>" value="sabado_10:30" <?php echo $check_sabado_10_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">11:00</td>
					
					<? $segunda_11_00 = DB::getInstance()->get_agenda($dia_inicio, '11:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_11_00 != null) {
							$check_segunda_11_00 = "checked='checked'";
						}else{
							$check_segunda_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_11:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_11_00;?>" value="segunda_11:00" <?php echo $check_segunda_11_00;?>/></td>
					
					<? $terca_11_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '11:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_11_00 != null) {
							$check_terca_11_00 = "checked='checked'";
						}else{
							$check_terca_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_11:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_11_00;?>" value="terca_11:00" <?php echo $check_terca_11_00;?>/></td>
					
					<? $quarta_11_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '11:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_11_00 != null) {
							$check_quarta_11_00 = "checked='checked'";
						}else{
							$check_quarta_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_11:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_11_00;?>" value="quarta_11:00" <?php echo $check_quarta_11_00;?>/></td>
					
					<? $quinta_11_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '11:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_11_00 != null) {
							$check_quinta_11_00 = "checked='checked'";
						}else{
							$check_quinta_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_11:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_11_00;?>" value="quinta_11:00" <?php echo $check_quinta_11_00;?>/></td>
					
					<? $sexta_11_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '11:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_11_00 != null) {
							$check_sexta_11_00 = "checked='checked'";
						}else{
							$check_sexta_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_11:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_11_00;?>" value="sexta_11:00" <?php echo $check_sexta_11_00;?>/></td>
					
					<? $sabado_11_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '11:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_11_00 != null) {
							$check_sabado_11_00 = "checked='checked'";
						}else{
							$check_sabado_11_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_11:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_11_00;?>" value="sabado_11:00" <?php echo $check_sabado_11_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">11:30</td>
					
					<? $segunda_11_30 = DB::getInstance()->get_agenda($dia_inicio, '11:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_11_30 != null) {
							$check_segunda_11_30 = "checked='checked'";
						}else{
							$check_segunda_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_11:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_11_30;?>" value="segunda_11:30" <?php echo $check_segunda_11_30;?>/></td>
					
					<? $terca_11_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '11:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_11_30 != null) {
							$check_terca_11_30 = "checked='checked'";
						}else{
							$check_terca_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_11:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_11_30;?>" value="terca_11:30" <?php echo $check_terca_11_30;?>/></td>
					
					<? $quarta_11_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '11:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_11_30 != null) {
							$check_quarta_11_30 = "checked='checked'";
						}else{
							$check_quarta_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_11:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_11_30;?>" value="quarta_11:30" <?php echo $check_quarta_11_30;?>/></td>
					
					<? $quinta_11_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '11:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_11_30 != null) {
							$check_quinta_11_30 = "checked='checked'";
						}else{
							$check_quinta_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_11:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_11_30;?>" value="quinta_11:30" <?php echo $check_quinta_11_30;?>/></td>
					
					<? $sexta_11_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '11:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_11_30 != null) {
							$check_sexta_11_30 = "checked='checked'";
						}else{
							$check_sexta_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_11:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_11_30;?>" value="sexta_11:30" <?php echo $check_sexta_11_30;?>/></td>
					
					<? $sabado_11_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '11:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_11_30 != null) {
							$check_sabado_11_30 = "checked='checked'";
						}else{
							$check_sabado_11_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_11:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_11_30;?>" value="sabado_11:30" <?php echo $check_sabado_11_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">12:00</td>
					
					<? $segunda_12_00 = DB::getInstance()->get_agenda($dia_inicio, '12:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_12_00 != null) {
							$check_segunda_12_00 = "checked='checked'";
						}else{
							$check_segunda_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_12:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_12_00;?>" value="segunda_12:00" <?php echo $check_segunda_12_00;?>/></td>
					
					<? $terca_12_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '12:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_12_00 != null) {
							$check_terca_12_00 = "checked='checked'";
						}else{
							$check_terca_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_12:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_12_00;?>" value="terca_12:00" <?php echo $check_terca_12_00;?>/></td>
					
					<? $quarta_12_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '12:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_12_00 != null) {
							$check_quarta_12_00 = "checked='checked'";
						}else{
							$check_quarta_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_12:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_12_00;?>" value="quarta_12:00" <?php echo $check_quarta_12_00;?>/></td>
					
					<? $quinta_12_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '12:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_12_00 != null) {
							$check_quinta_12_00 = "checked='checked'";
						}else{
							$check_quinta_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_12:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_12_00;?>" value="quinta_12:00" <?php echo $check_quinta_12_00;?>/></td>
					
					<? $sexta_12_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '12:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_12_00 != null) {
							$check_sexta_12_00 = "checked='checked'";
						}else{
							$check_sexta_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_12:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_12_00;?>" value="sexta_12:00" <?php echo $check_sexta_12_00;?>/></td>
					
					<? $sabado_12_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '12:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_12_00 != null) {
							$check_sabado_12_00 = "checked='checked'";
						}else{
							$check_sabado_12_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_12:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_12_00;?>" value="sabado_12:00" <?php echo $check_sabado_12_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">12:30</td>
					
					<? $segunda_12_30 = DB::getInstance()->get_agenda($dia_inicio, '12:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_12_30 != null) {
							$check_segunda_12_30 = "checked='checked'";
						}else{
							$check_segunda_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_12:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_12_30;?>" value="segunda_12:30" <?php echo $check_segunda_12_30;?>/></td>
					
					<? $terca_12_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '12:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_12_30 != null) {
							$check_terca_12_30 = "checked='checked'";
						}else{
							$check_terca_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_12:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_12_30;?>" value="terca_12:30" <?php echo $check_terca_12_30;?>/></td>
					
					<? $quarta_12_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '12:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_12_30 != null) {
							$check_quarta_12_30 = "checked='checked'";
						}else{
							$check_quarta_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_12:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_12_30;?>" value="quarta_12:30" <?php echo $check_quarta_12_30;?>/></td>
					
					<? $quinta_12_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '12:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_12_30 != null) {
							$check_quinta_12_30 = "checked='checked'";
						}else{
							$check_quinta_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_12:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_12_30;?>" value="quinta_12:30" <?php echo $check_quinta_12_30;?>/></td>
					
					<? $sexta_12_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '12:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_12_30 != null) {
							$check_sexta_12_30 = "checked='checked'";
						}else{
							$check_sexta_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_12:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_12_30;?>" value="sexta_12:30" <?php echo $check_sexta_12_30;?>/></td>
					
					<? $sabado_12_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '12:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_12_30 != null) {
							$check_sabado_12_30 = "checked='checked'";
						}else{
							$check_sabado_12_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_12:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_12_30;?>" value="sabado_12:30" <?php echo $check_sabado_12_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">13:00</td>
					
					<? $segunda_13_00 = DB::getInstance()->get_agenda($dia_inicio, '13:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_13_00 != null) {
							$check_segunda_13_00 = "checked='checked'";
						}else{
							$check_segunda_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_13:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_13_00;?>" value="segunda_13:00" <?php echo $check_segunda_13_00;?>/></td>
					
					<? $terca_13_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '13:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_13_00 != null) {
							$check_terca_13_00 = "checked='checked'";
						}else{
							$check_terca_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_13:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_13_00;?>" value="terca_13:00" <?php echo $check_terca_13_00;?>/></td>
					
					<? $quarta_13_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '13:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_13_00 != null) {
							$check_quarta_13_00 = "checked='checked'";
						}else{
							$check_quarta_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_13:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_13_00;?>" value="quarta_13:00" <?php echo $check_quarta_13_00;?>/></td>
					
					<? $quinta_13_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '13:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_13_00 != null) {
							$check_quinta_13_00 = "checked='checked'";
						}else{
							$check_quinta_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_13:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_13_00;?>" value="quinta_13:00" <?php echo $check_quinta_13_00;?>/></td>
					
					<? $sexta_13_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '13:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_13_00 != null) {
							$check_sexta_13_00 = "checked='checked'";
						}else{
							$check_sexta_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_13:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_13_00;?>" value="sexta_13:00" <?php echo $check_sexta_13_00;?>/></td>
					
					<? $sabado_13_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '13:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_13_00 != null) {
							$check_sabado_13_00 = "checked='checked'";
						}else{
							$check_sabado_13_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_13:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_13_00;?>" value="sabado_13:00" <?php echo $check_sabado_13_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">13:30</td>
					
					<? $segunda_13_30 = DB::getInstance()->get_agenda($dia_inicio, '13:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_13_30 != null) {
							$check_segunda_13_30 = "checked='checked'";
						}else{
							$check_segunda_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_13:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_13_30;?>" value="segunda_13:30" <?php echo $check_segunda_13_30;?>/></td>
					
					<? $terca_13_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '13:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_13_30 != null) {
							$check_terca_13_30 = "checked='checked'";
						}else{
							$check_terca_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_13:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_13_30;?>" value="terca_13:30" <?php echo $check_terca_13_30;?>/></td>
					
					<? $quarta_13_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '13:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_13_30 != null) {
							$check_quarta_13_30 = "checked='checked'";
						}else{
							$check_quarta_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_13:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_13_30;?>" value="quarta_13:30" <?php echo $check_quarta_13_30;?>/></td>
					
					<? $quinta_13_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '13:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_13_30 != null) {
							$check_quinta_13_30 = "checked='checked'";
						}else{
							$check_quinta_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_13:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_13_30;?>" value="quinta_13:30" <?php echo $check_quinta_13_30;?>/></td>
					
					<? $sexta_13_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '13:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_13_30 != null) {
							$check_sexta_13_30 = "checked='checked'";
						}else{
							$check_sexta_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_13:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_13_30;?>" value="sexta_13:30" <?php echo $check_sexta_13_30;?>/></td>
					
					<? $sabado_13_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '13:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_13_30 != null) {
							$check_sabado_13_30 = "checked='checked'";
						}else{
							$check_sabado_13_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_13:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_13_30;?>" value="sabado_13:30" <?php echo $check_sabado_13_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">14:00</td>
					
					<? $segunda_14_00 = DB::getInstance()->get_agenda($dia_inicio, '14:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_14_00 != null) {
							$check_segunda_14_00 = "checked='checked'";
						}else{
							$check_segunda_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_14:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_14_00;?>" value="segunda_14:00" <?php echo $check_segunda_14_00;?>/></td>
					
					<? $terca_14_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '14:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_14_00 != null) {
							$check_terca_14_00 = "checked='checked'";
						}else{
							$check_terca_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_14:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_14_00;?>" value="terca_14:00" <?php echo $check_terca_14_00;?>/></td>
					
					<? $quarta_14_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '14:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_14_00 != null) {
							$check_quarta_14_00 = "checked='checked'";
						}else{
							$check_quarta_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_14:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_14_00;?>" value="quarta_14:00" <?php echo $check_quarta_14_00;?>/></td>
					
					<? $quinta_14_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '14:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_14_00 != null) {
							$check_quinta_14_00 = "checked='checked'";
						}else{
							$check_quinta_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_14:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_14_00;?>" value="quinta_14:00" <?php echo $check_quinta_14_00;?>/></td>
					
					<? $sexta_14_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '14:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_14_00 != null) {
							$check_sexta_14_00 = "checked='checked'";
						}else{
							$check_sexta_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_14:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_14_00;?>" value="sexta_14:00" <?php echo $check_sexta_14_00;?>/></td>
					
					<? $sabado_14_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '14:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_14_00 != null) {
							$check_sabado_14_00 = "checked='checked'";
						}else{
							$check_sabado_14_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_14:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_14_00;?>" value="sabado_14:00" <?php echo $check_sabado_14_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">14:30</td>
					
					<? $segunda_14_30 = DB::getInstance()->get_agenda($dia_inicio, '14:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_14_30 != null) {
							$check_segunda_14_30 = "checked='checked'";
						}else{
							$check_segunda_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_14:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_14_30;?>" value="segunda_14:30" <?php echo $check_segunda_14_30;?>/></td>
					
					<? $terca_14_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '14:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_14_30 != null) {
							$check_terca_14_30 = "checked='checked'";
						}else{
							$check_terca_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_14:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_14_30;?>" value="terca_14:30" <?php echo $check_terca_14_30;?>/></td>
					
					<? $quarta_14_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '14:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_14_30 != null) {
							$check_quarta_14_30 = "checked='checked'";
						}else{
							$check_quarta_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_14:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_14_30;?>" value="quarta_14:30" <?php echo $check_quarta_14_30;?>/></td>
					
					<? $quinta_14_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '14:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_14_30 != null) {
							$check_quinta_14_30 = "checked='checked'";
						}else{
							$check_quinta_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_14:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_14_30;?>" value="quinta_14:30" <?php echo $check_quinta_14_30;?>/></td>
					
					<? $sexta_14_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '14:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_14_30 != null) {
							$check_sexta_14_30 = "checked='checked'";
						}else{
							$check_sexta_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_14:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_14_30;?>" value="sexta_14:30" <?php echo $check_sexta_14_30;?>/></td>
					
					<? $sabado_14_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '14:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_14_30 != null) {
							$check_sabado_14_30 = "checked='checked'";
						}else{
							$check_sabado_14_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_14:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_14_30;?>" value="sabado_14:30" <?php echo $check_sabado_14_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">15:00</td>
					
					<? $segunda_15_00 = DB::getInstance()->get_agenda($dia_inicio, '15:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_15_00 != null) {
							$check_segunda_15_00 = "checked='checked'";
						}else{
							$check_segunda_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_15:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_15_00;?>" value="segunda_15:00" <?php echo $check_segunda_15_00;?>/></td>
					
					<? $terca_15_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '15:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_15_00 != null) {
							$check_terca_15_00 = "checked='checked'";
						}else{
							$check_terca_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_15:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_15_00;?>" value="terca_15:00" <?php echo $check_terca_15_00;?>/></td>
					
					<? $quarta_15_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '15:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_15_00 != null) {
							$check_quarta_15_00 = "checked='checked'";
						}else{
							$check_quarta_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_15:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_15_00;?>" value="quarta_15:00" <?php echo $check_quarta_15_00;?>/></td>
					
					<? $quinta_15_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '15:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_15_00 != null) {
							$check_quinta_15_00 = "checked='checked'";
						}else{
							$check_quinta_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_15:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_15_00;?>" value="quinta_15:00" <?php echo $check_quinta_15_00;?>/></td>
					
					<? $sexta_15_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '15:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_15_00 != null) {
							$check_sexta_15_00 = "checked='checked'";
						}else{
							$check_sexta_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_15:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_15_00;?>" value="sexta_15:00" <?php echo $check_sexta_15_00;?>/></td>
					
					<? $sabado_15_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '15:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_15_00 != null) {
							$check_sabado_15_00 = "checked='checked'";
						}else{
							$check_sabado_15_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_15:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_15_00;?>" value="sabado_15:00" <?php echo $check_sabado_15_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">15:30</td>
					
					<? $segunda_15_30 = DB::getInstance()->get_agenda($dia_inicio, '15:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_15_30 != null) {
							$check_segunda_15_30 = "checked='checked'";
						}else{
							$check_segunda_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_15:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_14_30;?>" value="segunda_15:30" <?php echo $check_segunda_15_30;?>/></td>
					
					<? $terca_15_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '15:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_15_30 != null) {
							$check_terca_15_30 = "checked='checked'";
						}else{
							$check_terca_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_15:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_15_30;?>" value="terca_15:30" <?php echo $check_terca_15_30;?>/></td>
					
					<? $quarta_15_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '15:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_15_30 != null) {
							$check_quarta_15_30 = "checked='checked'";
						}else{
							$check_quarta_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_15:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_15_30;?>" value="quarta_15:30" <?php echo $check_quarta_15_30;?>/></td>
					
					<? $quinta_15_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '15:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_15_30 != null) {
							$check_quinta_15_30 = "checked='checked'";
						}else{
							$check_quinta_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_15:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_15_30;?>" value="quinta_15:30" <?php echo $check_quinta_15_30;?>/></td>
					
					<? $sexta_15_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '15:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_15_30 != null) {
							$check_sexta_15_30 = "checked='checked'";
						}else{
							$check_sexta_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_15:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_15_30;?>" value="sexta_15:30" <?php echo $check_sexta_15_30;?>/></td>
					
					<? $sabado_15_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '15:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_15_30 != null) {
							$check_sabado_15_30 = "checked='checked'";
						}else{
							$check_sabado_15_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_15:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_15_30;?>" value="sabado_15:30" <?php echo $check_sabado_15_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">16:00</td>
					
					<? $segunda_16_00 = DB::getInstance()->get_agenda($dia_inicio, '16:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_16_00 != null) {
							$check_segunda_16_00 = "checked='checked'";
						}else{
							$check_segunda_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_16:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_16_00;?>" value="segunda_16:00" <?php echo $check_segunda_16_00;?>/></td>
					
					<? $terca_16_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '16:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_16_00 != null) {
							$check_terca_16_00 = "checked='checked'";
						}else{
							$check_terca_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_16:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_16_00;?>" value="terca_16:00" <?php echo $check_terca_16_00;?>/></td>
					
					<? $quarta_16_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '16:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_16_00 != null) {
							$check_quarta_16_00 = "checked='checked'";
						}else{
							$check_quarta_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_16:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_16_00;?>" value="quarta_16:00" <?php echo $check_quarta_16_00;?>/></td>
					
					<? $quinta_16_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '16:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_16_00 != null) {
							$check_quinta_16_00 = "checked='checked'";
						}else{
							$check_quinta_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_16:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_16_00;?>" value="quinta_16:00" <?php echo $check_quinta_16_00;?>/></td>
					
					<? $sexta_16_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '16:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_16_00 != null) {
							$check_sexta_16_00 = "checked='checked'";
						}else{
							$check_sexta_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_16:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_16_00;?>" value="sexta_16:00" <?php echo $check_sexta_16_00;?>/></td>
					
					<? $sabado_16_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '16:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_16_00 != null) {
							$check_sabado_16_00 = "checked='checked'";
						}else{
							$check_sabado_16_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_16:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_16_00;?>" value="sabado_16:00" <?php echo $check_sabado_16_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">16:30</td>
					
					<? $segunda_16_30 = DB::getInstance()->get_agenda($dia_inicio, '16:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_16_30 != null) {
							$check_segunda_16_30 = "checked='checked'";
						}else{
							$check_segunda_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_16:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_16_30;?>" value="segunda_16:30" <?php echo $check_segunda_16_00;?>/></td>
					
					<? $terca_16_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '16:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_16_30 != null) {
							$check_terca_16_30 = "checked='checked'";
						}else{
							$check_terca_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_16:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_16_30;?>" value="terca_16:30" <?php echo $check_terca_16_30;?>/></td>
					
					<? $quarta_16_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '16:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_16_30 != null) {
							$check_quarta_16_30 = "checked='checked'";
						}else{
							$check_quarta_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_16:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_16_30;?>" value="quarta_16:30" <?php echo $check_quarta_16_30;?>/></td>
					
					<? $quinta_16_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '16:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_16_30 != null) {
							$check_quinta_16_30 = "checked='checked'";
						}else{
							$check_quinta_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_16:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_16_30;?>" value="quinta_16:30" <?php echo $check_quinta_16_30;?>/></td>
					
					<? $sexta_16_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '16:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_16_30 != null) {
							$check_sexta_16_30 = "checked='checked'";
						}else{
							$check_sexta_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_16:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_16_30;?>" value="sexta_16:30" <?php echo $check_sexta_16_30;?>/></td>
					
					<? $sabado_16_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '16:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_16_30 != null) {
							$check_sabado_16_30 = "checked='checked'";
						}else{
							$check_sabado_16_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_16:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_16_30;?>" value="sabado_16:30" <?php echo $check_sabado_16_30;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">17:00</td>
					
					<? $segunda_17_00 = DB::getInstance()->get_agenda($dia_inicio, '17:00', $id_uni, $id_usuario); ?>
					<?	if ($segunda_17_00 != null) {
							$check_segunda_17_00 = "checked='checked'";
						}else{
							$check_segunda_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_17:00_<? echo $dia_inicio ?>_<?php echo $check_segunda_17_00;?>" value="segunda_17:00" <?php echo $check_segunda_17_00;?>/></td>
					
					<? $terca_17_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '17:00', $id_uni, $id_usuario); ?>
					<?	if ($terca_17_00 != null) {
							$check_terca_17_00 = "checked='checked'";
						}else{
							$check_terca_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_17:00_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_17_00;?>" value="terca_17:00" <?php echo $check_terca_17_00;?>/></td>
					
					<? $quarta_17_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '17:00', $id_uni, $id_usuario); ?>
					<?	if ($quarta_17_00 != null) {
							$check_quarta_17_00 = "checked='checked'";
						}else{
							$check_quarta_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_17:00_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_17_00;?>" value="quarta_17:00" <?php echo $check_quarta_17_00;?>/></td>
					
					<? $quinta_17_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '17:00', $id_uni, $id_usuario); ?>
					<?	if ($quinta_17_00 != null) {
							$check_quinta_17_00 = "checked='checked'";
						}else{
							$check_quinta_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_17:00_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_17_00;?>" value="quinta_17:00" <?php echo $check_quinta_17_00;?>/></td>
					
					<? $sexta_17_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '17:00', $id_uni, $id_usuario); ?>
					<?	if ($sexta_17_00 != null) {
							$check_sexta_17_00 = "checked='checked'";
						}else{
							$check_sexta_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_17:00_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_17_00;?>" value="sexta_17:00" <?php echo $check_sexta_17_00;?>/></td>
					
					<? $sabado_17_00 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '17:00', $id_uni, $id_usuario); ?>
					<?	if ($sabado_17_00 != null) {
							$check_sabado_17_00 = "checked='checked'";
						}else{
							$check_sabado_17_00 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_17:00_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_17_00;?>" value="sabado_17:00" <?php echo $check_sabado_17_00;?>/></td>
				</tr>
				<tr>
					<td style="width:40px; text-align:center;">17:30</td>
					
					<? $segunda_17_30 = DB::getInstance()->get_agenda($dia_inicio, '17:30', $id_uni, $id_usuario); ?>
					<?	if ($segunda_17_30 != null) {
							$check_segunda_17_30 = "checked='checked'";
						}else{
							$check_segunda_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="segunda-feira_17:30_<? echo $dia_inicio ?>_<?php echo $check_segunda_17_30;?>" value="segunda_17:30" <?php echo $check_segunda_17_30;?>/></td>
					
					<? $terca_17_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))), '17:30', $id_uni, $id_usuario); ?>
					<?	if ($terca_17_30 != null) {
							$check_terca_17_30 = "checked='checked'";
						}else{
							$check_terca_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="terça-feira_17:30_<? echo date('Y/m/d', strtotime("+1 days",strtotime($dia_inicio))); ?>_<?php echo $check_terca_17_30;?>" value="terca_17:30" <?php echo $check_terca_17_30;?>/></td>
					
					<? $quarta_17_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))), '17:30', $id_uni, $id_usuario); ?>
					<?	if ($quarta_17_30 != null) {
							$check_quarta_17_30 = "checked='checked'";
						}else{
							$check_quarta_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quarta-feira_17:30_<? echo date('Y/m/d', strtotime("+2 days",strtotime($dia_inicio))); ?>_<?php echo $check_quarta_17_30;?>" value="quarta_17:30" <?php echo $check_quarta_17_30;?>/></td>
					
					<? $quinta_17_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))), '17:30', $id_uni, $id_usuario); ?>
					<?	if ($quinta_17_30 != null) {
							$check_quinta_17_30 = "checked='checked'";
						}else{
							$check_quinta_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="quinta-feira_17:30_<? echo date('Y/m/d', strtotime("+3 days",strtotime($dia_inicio))); ?>_<?php echo $check_quinta_17_30;?>" value="quinta_17:30" <?php echo $check_quinta_17_30;?>/></td>
					
					<? $sexta_17_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))), '17:30', $id_uni, $id_usuario); ?>
					<?	if ($sexta_17_30 != null) {
							$check_sexta_17_30 = "checked='checked'";
						}else{
							$check_sexta_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sexta-feira_17:30_<? echo date('Y/m/d', strtotime("+4 days",strtotime($dia_inicio))); ?>_<?php echo $check_sexta_17_30;?>" value="sexta_17:30" <?php echo $check_sexta_17_30;?>/></td>
					
					<? $sabado_17_30 = DB::getInstance()->get_agenda(date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))), '17:30', $id_uni, $id_usuario); ?>
					<?	if ($sabado_17_30 != null) {
							$check_sabado_17_30 = "checked='checked'";
						}else{
							$check_sabado_17_30 = "";
						}	
					?>
					<td style="width:40px; text-align:center;"><input type="checkbox" id="sabado_17:30_<? echo date('Y/m/d', strtotime("+5 days",strtotime($dia_inicio))); ?>_<?php echo $check_sabado_17_30;?>" value="sabado_17:30" <?php echo $check_sabado_17_30;?>/></td>
				</tr>
			</table>	
			
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