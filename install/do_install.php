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

if (Config::SGA_INSTALLED) {
	Template::display_error('O SGA já está instalado.');
}
else {
	try {
        $db = Session::getInstance()->get('DATABASE');
		$db_type = $db['db_type'];

        //chmod('lib/php/core/Config.php', 0666);
		// verifica se será possível escrever a configuração no arquivo Config.php
		if (!is_writable('lib/php/core/Config.php')) {
			throw new Exception('Você não tem permissão de escrita no arquivo (lib/php/core/Config.php). Não é possível armazenar as configurações informadas na instalação.');
		}
		if (!is_readable('install/sql/'.$db_type.'_install.sql')) {
            throw new Exception('Arquivo SQL de instalação não encontrado. (install/sql/'.$db_type.'_install.sql)');
        }

		try {
			
			$sql = file_get_contents('install/sql/'.$db_type.'_install.sql');

            if ($db['db_type'] == 'pgsql') {
                $db_class = 'PgSQLDB';

                $dbcon = @pg_connect('host='.$db['db_host'].' port='.$db['db_port'].' dbname=postgres user='.$db['db_user'].' password='.$db['db_pass']);
                if ($dbcon === FALSE) {
                    throw new Exception(pg_last_error($dbcon));
                }

                $result = @pg_query($dbcon, 'DROP DATABASE IF EXISTS '.$db['db_name']);
                if (!$result) {
                    throw new Exception(pg_last_error($dbcon));
                }
                $result = @pg_query('CREATE DATABASE '.$db['db_name']." ENCODING 'UTF8'");
                if (!$result) {
                    throw new Exception(pg_last_error($dbcon));
                }
                pg_close($dbcon);
                
                //$conn_string = "host=sheep port=5432 dbname=test user=lamb password=bar";
                $dbcon = @pg_connect('host='.$db['db_host'].' port='.$db['db_port'].' dbname='.$db['db_name'].' user='.$db['db_user'].' password='.$db['db_pass']);
                if ($dbcon === FALSE) {
                    throw new Exception(pg_last_error($dbcon));
                }

                $adm = Session::getInstance()->get("INSTALL_ADMIN");
                $escaped = array();
                foreach ($adm as $key => $value) {
                    $escaped[$key] = pg_escape_string($dbcon, $value);
                }

                $sql = str_replace('%login_usu%', $escaped['login_usu'], $sql);
                $sql = str_replace('%nm_usu%', $escaped['nm_usu'], $sql);
                $sql = str_replace('%ult_nm_usu%', $escaped['ult_nm_usu'], $sql);
                $sql = str_replace('%senha_usu%', $escaped['senha_usu'], $sql);
                
                $result = @pg_query($dbcon, $sql);
                
                if (!$result) {
                    throw new Exception(pg_last_error($dbcon));
                }
            }

            $config_content = file_get_contents('lib/php/core/Config.php');
            
            if (strpos($config_content, 'const SGA_INSTALLED = false;') === FALSE) {
                throw new Exception('Erro ao escrever arquivo Config.php, possivelmente ele foi editado manualmente, restaure o arquivo original.');
            }

            $config_content = str_replace('const SGA_INSTALLED = false;', 'const SGA_INSTALLED = true;', $config_content);
            $config_content = str_replace('%db_class%', $db_class, $config_content);
            $config_content = str_replace('%db_user%', $db['db_user'], $config_content);
            $config_content = str_replace('%db_pass%', $db['db_pass'], $config_content);
            $config_content = str_replace('%db_host%', $db['db_host'], $config_content);
            $config_content = str_replace('%db_port%', $db['db_port'], $config_content);
            $config_content = str_replace('%db_name%', $db['db_name'], $config_content);
            file_put_contents('lib/php/core/Config.php', $config_content);

            Template::display_popup_header('Instalação');
            Template::display_confirm_dialog('O '.SGA::NAME.' foi instalado com sucesso!', 'INSTALAÇÃO','document.location.reload();');
		
		}
		catch (Exception $e) {
			Template::display_error($e->getMessage());
		}
        
        if ($db['db_type'] == 'pgsql') {
            pg_close($dbcon);
        }

		
	}
	catch (Exception $e) {
		Template::display_exception($e);
	}
}
?>