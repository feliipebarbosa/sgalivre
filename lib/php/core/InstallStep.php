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

class InstallStep {
	private $numero;
	private $nome;
    
	private $has_prev;
	private $has_next;
	
	private $prev_enabled;
	private $next_enabled;

    private $js_on_prev;
    private $js_on_next;
    private $onload;
	
	public function __construct($numero, $nome, $has_prev, $has_next, $prev_enabled, $next_enabled, $js_on_prev = '', $js_on_next = '', $onload = '') {
		$this->numero = $numero;
        $this->nome = $nome;
		$this->set($has_prev, $has_next, $prev_enabled, $next_enabled, $js_on_prev, $js_on_next, $onload);
	}
	
	public function set($has_prev, $has_next, $prev_enabled, $next_enabled, $js_on_prev = '', $js_on_next = '', $onload = '') {
		 $this->has_prev = $has_prev;
		 $this->has_next = $has_next;
		 $this->prev_enabled = $prev_enabled;
		 $this->next_enabled = $next_enabled;
         $this->js_on_prev = $js_on_prev;
		 $this->js_on_next = $js_on_next;
         $this->onload = $onload;
	}
	
	public function set_numero($numero) {
		$this->numero = $numero;
	}
	
	public function get_numero() {
		return $this->numero;
	}

    public function get_nome() {
        return $this->nome;
    }

	public function has_previous_step() {
		return $this->has_prev;
	}
	
	public function has_next_step() {
		return $this->has_next;
	}
	
	public function get_previous_enabled() {
		return $this->prev_enabled;
	}
	
	public function set_next_enabled($val) {
		 $this->next_enabled = $val;
	}
	
	public function get_next_enabled() {
		return $this->next_enabled;
	}

    public function get_js_on_prev() {
        return $this->js_on_prev;
    }

    public function get_js_on_next() {
        return $this->js_on_next;
    }

    public function get_js_onload() {
        return $this->onload;
    }
}
?>