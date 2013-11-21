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

set_time_limit(0);

class Config {

	const SGA_INSTALLED = true;

    const DB_CLASS = 'PgSQLDB';
	const DB_HOST = '10.71.0.215';
    const DB_PORT = '5432';
	const DB_USER = 'postgres';
	const DB_PASS = 'suporte';
	const DB_NAME = 'sga_teste';

}

try {
		
	$host = "10.71.0.215";
	$usuario = "postgres";
	$senha = "suporte";
	$banco= "sga_teste";
	
	$pdo = new PDO('pgsql:host='.$host.';dbname='.$banco, $usuario, $senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
	$query = "
			INSERT INTO atendimentos
			(
				id_uni,
				id_serv, id_pri, id_stat,
				num_senha, nm_cli, num_guiche,
				dt_cheg, dt_cha, dt_ini, dt_fim, ident_cli, id_usu
			) VALUES ( 
				:id_uni, :id_serv, :id_pri, 
				:id_stat, :num_senha, :nm_cli, 
				:num_guiche, :dt_cheg, :dt_cha, 
				:dt_ini, :dt_fim, :ident_cli, :id_usu
			)
		";

	$seleciona_id = "SELECT s.id_serv FROM uni_serv s INNER JOIN unidades u ON u.id_uni = s.id_uni 
					 WHERE s.id_uni = :id_uni
					 AND u.stat_uni = 1
					 AND  s.stat_serv = 1";
	
	$insere_atend_codif = "INSERT INTO atend_codif
							VALUES(:id_atend, :id_serv, 1)";
		
	$ids_uni = "SELECT id_uni FROM unidades";
	
	$ids_usu = "SELECT id_usu FROM usuarios WHERE stat_usu =1";
	
//	seleciona todos os serviços
	$serv_glob = "SELECT id_serv, nm_serv, stat_serv
				  FROM servicos 
				 ";
	
//	seleciona todas as unidades sem serviços
	$uni_sem_serv = "SELECT id_uni FROM unidades
					 WHERE(id_uni not in (select id_uni from uni_serv))" ;

//	seleciona todas as unidades cem serviços
	$uni_com_serv = "SELECT u.id_uni FROM unidades u 
					 INNER JOIN uni_serv s 
					 ON u.id_uni = s.id_uni
	      			" ;

//  seleciona os serviços restantes de uma unidade com serviço  
	$serv_uni = "SELECT s.id_serv, s.nm_serv, s.stat_serv FROM servicos s, uni_serv u
				 WHERE s.id_serv NOT IN (SELECT DISTINCT u.id_serv FROM uni_serv u)
				 and u.id_uni = :id_uni
				 ";

//	Insere um serviço em uma unidade
	$insere_serv_uni = "
		INSERT INTO uni_serv
		( 
			id_uni, id_serv, id_loc, nm_serv,
			sigla_serv, stat_serv
		) VALUES (
			:id_uni, :id_serv, :id_loc, :nm_serv,
			:sigla_serv, :stat_serv
		)
	";
	
	$acumular_atend = "SELECT sp_acumular_atendimentos(now())";
	
	$statement = $pdo->prepare($serv_glob);
	$statement->execute();
	$ids_serv = $statement->fetchAll();
	
	$statement = $pdo->prepare($uni_sem_serv);
	$statement->execute();
	$ids_uni_serv = $statement->fetchAll();
	
	$statement = $pdo->prepare($uni_com_serv);
	$statement->execute();
	$ids_uni_cserv = $statement->fetchAll();

	//inserir serviços em unidades sem serviços
	for($j=0; $j < sizeof($ids_uni_serv); $j++){
		for($i=0; $i < sizeof($ids_serv); $i++){		
			$statement = $pdo->prepare($insere_serv_uni);
			$statement->bindValue(':id_uni', $ids_uni_serv[$j][0], PDO::PARAM_INT);
			$statement->bindValue(':id_serv', $ids_serv[$i][0], PDO::PARAM_INT);
			$statement->bindValue(':stat_serv', $ids_serv[$i][2], PDO::PARAM_INT);
			$statement->bindValue(':nm_serv', $ids_serv[$i][1], PDO::PARAM_STR);
			$statement->bindValue(':sigla_serv', chr(rand(65,90)), PDO::PARAM_STR);
			$statement->bindValue(':id_loc', 1, PDO::PARAM_INT);
			$statement->execute();
		
		}
	}
	
	//inserir serviços em unidades com serviços
	for($j=0; $j < sizeof($ids_uni_cserv); $j++){
		$statement = $pdo->prepare($serv_uni);
		$statement->bindValue(':id_uni', $ids_uni_cserv[$j][0], PDO::PARAM_INT);
		$statement->execute();
		$ids_serv_uni = $statement->fetchAll();	
		for($i=0; $i < sizeof($ids_serv_uni); $i++){		
			$statement = $pdo->prepare($insere_serv_uni);
			$statement->bindValue(':id_uni', $ids_uni_cserv[$j][0], PDO::PARAM_INT);
			$statement->bindValue(':id_serv', $ids_serv_uni[$i][0], PDO::PARAM_INT);
			$statement->bindValue(':stat_serv', $ids_serv_uni[$i][2], PDO::PARAM_INT);
			$statement->bindValue(':nm_serv', $ids_serv_uni[$i][1], PDO::PARAM_STR);
			$statement->bindValue(':sigla_serv', chr(rand(65,90)), PDO::PARAM_STR);
			$statement->bindValue(':id_loc', 1, PDO::PARAM_INT);
			$statement->execute();
		}
	}
	
	//quantidade de inserções
	$cont = 10;
	
	$todos_uni_serv = array();
	$unidades = array();
	
	$statement = $pdo->prepare($ids_uni);
	$statement->execute();
	$ids_uni = $statement->fetchAll();
	
	//montar array com as unidades e seus respectivos servicos
	for($i=0; $i < sizeof($ids_uni) ; $i++){
		$statement = $pdo->prepare($seleciona_id);
		$statement->bindValue(':id_uni', $ids_uni[$i][0], PDO::PARAM_INT);
		$statement->execute();
		$serv = $statement->fetchAll();
	
		if(sizeof($serv) != 0){
			
			//guarda só as unidades que tem servico
			$unidades[]=$ids_uni[$i][0];
			
			$servicos_uni = array();
			for($j=0; $j < sizeof($serv);$j++){
				$servicos_uni[$j] = $serv[$j][0];
			}
			//guarda os servicos referentes a cada unidade
			$todos_uni_serv[$ids_uni[$i][0]] = $servicos_uni;
		}
	}
	
	$ini =date('Y-m-d H:i:s:ms',time());
	//inserir atendimentos encerrados, com os demais dados aleatóriamente.
	$statement = $pdo->prepare($query);
	for($i=1; $i <= $cont; $i++){
		
		$id_uni = $unidades[rand(0,sizeof($unidades)-1)];
		
		// 1 -> 1.05
		// 10 -> 1.5
		$mul_uni = 1 + ($id_uni/20);
		
		//conta quantidade de servicos da unidade
		$c = count($todos_uni_serv[$id_uni]);
		$id_serv = $todos_uni_serv[$id_uni][rand(0,$c-1)];
		$id_pri = rand(1,7);
		$num_guiche = rand(1,10); 
		$id_stat = 8;
		$nm_cli = "";
		$senha = $i;
		
		$dt_cheg = rand(mktime(0,0,0,10,1,2008),mktime(23,59,59,4,1,2009)); 
		$dt_cha= $dt_cheg + rand(10*60, 120*60) * $mul_uni; 
		$dt_ini= $dt_cha + rand(5, 180) * $mul_uni; 
		$dt_fim= $dt_ini + rand(10*60, 90*60) * $mul_uni; 
		
		$dt_cheg = date('Y-m-d H:i:s',$dt_cheg);
		$dt_cha = date('Y-m-d H:i:s',$dt_cha);
		$dt_ini = date('Y-m-d H:i:s',$dt_ini);
		$dt_fim = date('Y-m-d H:i:s',$dt_fim);
		
		$ident_cli=""; 

		$afirmacao = $pdo->prepare($ids_usu);
		$afirmacao->execute();
		$afirmacao = $afirmacao->fetchAll();

		//armazena os ids de todos os usuarios ativos do sistema
		$ids_usuarios = array();
		
		//tratamento do result set da query $ids_usu
		for($k=0; $k< sizeof($afirmacao); $k++){
			$ids_usuarios[]= $afirmacao[$k][0];
		}
		//recebe um id de usuario randomicamente
		$id_usu = $ids_usuarios[rand(0,sizeof($ids_usuarios))];
		$statement->bindValue(':id_uni', $id_uni, PDO::PARAM_INT);
		$statement->bindValue(':id_serv', $id_serv, PDO::PARAM_INT);
		$statement->bindValue(':id_pri', $id_pri, PDO::PARAM_INT);
		$statement->bindValue(':id_stat', $id_stat, PDO::PARAM_INT);
		$statement->bindValue(':num_senha', $senha, PDO::PARAM_INT);
		$statement->bindValue(':nm_cli', $nm_cli, PDO::PARAM_STR);
		$statement->bindValue(':num_guiche', $num_guiche, PDO::PARAM_INT);
		$statement->bindValue(':dt_cheg', $dt_cheg, PDO::PARAM_STR);
		$statement->bindValue(':dt_cha', $dt_cha, PDO::PARAM_STR);
		$statement->bindValue(':dt_ini', $dt_ini, PDO::PARAM_STR);
		$statement->bindValue(':dt_fim', $dt_fim, PDO::PARAM_STR);
		$statement->bindValue(':ident_cli', $ident_cli, PDO::PARAM_STR);
		$statement->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
		$statement->execute();
		//numero randomico da quantidade de servicos
		$contador = rand(0,$c-1);
		$array = array();
		//codifica varios servicos para o mesmo atendimento
		for ($l=0; $l<count($contador) ;$l++){
			$id_ult_atend = $pdo->lastInsertId("atendimentos_id_atend_seq");
			$declaracao = $pdo->prepare($insere_atend_codif);
			$declaracao->bindValue(':id_atend',$id_ult_atend,PDO::PARAM_INT);
			$declaracao->bindValue(':id_serv',$id_serv,PDO::PARAM_INT);
			$declaracao->execute();
			
			//armazena os ids_servicos ja inseridos
			$array[$id_serv] = $id_serv;
			$aux = $id_serv;
			//nao permite que unidades com apenas um servico tenho o mesmo servico codificado novamente
			if($contador > 1){
				//verifica se o servico escolhido ja foi inserido anteriormente
				while(array_key_exists($id_serv,$array) == $aux){
					$id_serv = $todos_uni_serv[$id_uni][rand(0,$c-1)];
				}
			}
		}
	}

	$fim = date('Y-m-d H:i:s:ms',time());
	echo "Inser&ccedil;&otilde;es efetuadas com sucesso!<br>$ini<br>$fim";
	/*
	$ini = date('Y-m-d H:i:s:ms',time());
	
	echo "<br><br>Guardando no hist&oacute;rico e limpando atendimentos<br>Inicio: $ini";
	$statement = $pdo->prepare($acumular_atend);
	$statement->execute();
	$fim = date('Y-m-d H:i:s:ms',time());
	
	echo "<br>Fim:$fim";
	*/
}
catch (PDOException $e) {
	// O trace da exceção exibe a senha do banco(entre outros)
	// nao dar re-thrown!
	exit("<pre><h1>ERRO FATAL</h1>\n\n".$e->getMessage()."\n\n".$e->getTraceAsString().'</pre>');
}


?>