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

try {

    $current_step = Session::getInstance()->get('SGA_INSTALL_STEP');
    if ($current_step->get_numero() == 3) {
        if (empty($_POST['db_host'])) {
            throw new Exception('O endereço do Banco de Dados deve ser informado.');
        }
        if (empty($_POST['db_port'])) {
            throw new Exception('A porta do Banco de Dados deve ser informado.');
        }
        if (empty($_POST['db_name'])) {
            throw new Exception('O nome do Banco de Dados deve ser informado.');
        }
        if (empty($_POST['db_user'])) {
            throw new Exception('O usuário do Banco de Dados deve ser informado.');
        }
        if (empty($_POST['db_pass'])) {
            throw new Exception('A senha do Banco de Dados deve ser informado.');
        }

        $db['db_type'] = 'pgsql';
        $db['db_host'] = $_POST['db_host'];
        $db['db_port'] = $_POST['db_port'];
        $db['db_name'] = $_POST['db_name'];
        $db['db_user'] = $_POST['db_user'];
        $db['db_pass'] = $_POST['db_pass'];

        Session::getInstance()->set('DATABASE', $db);

        // Abrir conexão
        $dbh = new PDO($db['db_type'].':host='.$db['db_host'].';port='.$db['db_port'].';dbname=postgres', $db['db_user'], $db['db_pass']);
        
        // Fechar conexão
        $dbh = null;
    
    
        $current_step->set_next_enabled(true);
        echo 'true';
    }
    else {
        Template::display_error('O teste do banco não deveria ter sido chamado nesta etapa da instalação');
    }
}
catch (PDOException $e) {
    Template::display_error('<pre>'.$e->getMessage().'</pre>');
}
catch (Exception $e) {
    Template::display_error($e->getMessage());
}
?>