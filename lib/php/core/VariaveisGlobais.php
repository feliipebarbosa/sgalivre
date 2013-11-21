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

class VariaveisGlobais {
    // static
    private static $instance;

    // fields
    private $vars;

    /**
     * @return VariaveisGlobais Retorna o gerenciador de variaveis globais
     */
    public static function getInstance() {
        if (VariaveisGlobais::$instance == null) {
            VariaveisGlobais::$instance = new VariaveisGlobais();
        }
        return VariaveisGlobais::$instance;
    }

    private function __construct() {
        $this->vars = DB::getInstance()->get_variaveis_globais();
    }

    public function get($chave) {
        return $this->vars[$chave];
    }

    public function set($chave, $valor) {
        $this->vars[$chave] = $valor;
        DB::getInstance()->salvar_variavel_global($chave, $valor);
    }

    public function exists($chave) {
        return isset($this->var[$chave]);
    }
}
?>
