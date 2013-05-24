<?php
	/**
	 * @version 0.13 3/3/2011 
	 * @package geral
	 * @author Mario Akita
	 * @desc pagina que concentra todas as funcoes de busca do sgd
	 */
include_once('includeAll.php');
$res = array();
$data = array();
//print_r($_SERVER['REQUEST_URI']);
//conexao no BD
$bd = new BD($conf["DBLogin"], $conf["DBPassword"], $conf["DBhost"], $conf["DBTable"]);

if (isset($_GET['tipoBusca'])){//tipo de busca
	//busca de campos
	if(isset($_GET['docs']) && $_GET['tipoBusca'] == 'campoSearch'){
		print '<table width="100%">';
		if (!isset($_GET['mini']) || $_GET['mini'] != 'true') print '<tr><td width="50%">';
		$camposPartes = '';
		$_GET['docs'] = rtrim($_GET['docs'], ',');
		$tipos = explode(',', $_GET['docs']);
		if (count($tipos) == 0 || (count($tipos) == 1 && $tipos[0] == '')) {
			print ('<b>Pelo menos um tipo de documento deve ser escolhido.</b>');
			
		} elseif (count($tipos) == 1) {
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $tipos[0];
			$doc->loadTipoData();
			$campos = explode(',',$doc->dadosTipo['campos']);
			if (!isset($_GET['mini']) || $_GET['mini'] != 'true') print('<table width="100%">');
			print ('<input type="hidden" id="tipos" value="'.$_GET['docs'].'" />
			<tr class="c" width="40%"><td class="c" width="25%">N&deg; CPO:</td><td class="c" width="60%"><input id="numCPO" type="text" size="5" maxlength="5" /></td></tr>
			<tr class="c"><td class="c" width="25%">N&deg; do documento: </td><td class="c"><input type="test" id="numDoc" size="10" maxlength="10" /><br /></td></tr>
			<tr class="c"><td class="c" width="25%">Criado em/entre: </td><td class="c"><input id="dataCriacao1" type="text" size="15" maxlength="10" /> e <input id="dataCriacao2" type="text" size="15" maxlength="10" /</td></tr>
			<tr class="c"><td class="c" width="25%">Despachado em/entre: </td><td class="c"><input id="dataDespacho1" type="text" size="15" maxlength="10" /> e <input id="dataDespacho2" type="text" size="15" maxlength="10" /></td></tr>
			<tr class="c"><td class="c" width="25%">Despachado para: </td><td class="c"><input id="unDespacho" type="text" size="40" maxlength="250" /></td></tr>
			<tr class="c"><td class="c" width="25%">Recebido em/entre: </td><td class="c"><input id="dataReceb1" type="text" size="15" maxlength="10" /> e <input id="dataReceb2" type="text" size="15" maxlength="10" /></td></tr>
			<tr class="c"><td class="c" width="25%">Recebido de: </td><td class="c"><input id="unReceb" type="text" size="40" maxlength="250" /></td></tr>
			<tr class="c"><td class="c" width="25%">Conte&uacute;do do despacho: </td><td class="c"><input id="contDesp" type="text" size="40" /></td></tr>
			<tr class="c"><td class="c" width="25%">Conte&uacute;do de <b>qualquer</b> campo: </td><td class="c"><input type="text" id="contGen" size="40" /></td></tr>');
			// verifica se o usuario tem permissao para realizar busca no arquivo
			if (checkPermission(68)) {
				print('<tr class="c"><td class="c" width="25%">Arquivado?: </td><td class="c"><input type="radio" id="buscaArquivo" name="buscaArquivo" value="1" /> Sim <input type="radio" id="buscaArquivo" name="buscaArquivo" value="0" /> N&atilde;o <input type="radio" id="buscaArquivo" name="buscaArquivo" value="-1" checked="checked" /> Ambos </td></tr>');
			}
			if (isset($_GET['anex']) && $_GET['anex'] == 'true') {
				print('<tr class="c" style="Display:none"><td class="c" width="25%">A&ccedil;&atilde;o de anexar:</td><td class="c"><input type="radio" id="actionAnex" name="actionAnex" value="1"  checked="checked"/></td></tr>');
			}
			if (!isset($_GET['mini']) || $_GET['mini'] != 'true') print('</table></td><td width="50%"><table width="100%">');
			foreach ($campos as $c) {
				
				$campoHtml = montaCampo($c,'bus');
				 
				$camposPartes .= $campoHtml['nome'] . ',';
				
				//if (stripos($campoHtml['nome'],"docsDesp") !== false || stripos($campoHtml['nome'],"docResp") !== false)
					 //print ('<tr class="c" style="Display:none"><td class="c">'.$campoHtml['label'].':</td><td class="c">'.$campoHtml['cod'].'</td></tr>');//
				/*else {
					print ('<tr class="c"><td class="c">'.$campoHtml['label'].':</td><td class="c">'.$campoHtml['cod'].'</td></tr>');//
				}*/
				//print ('<tr class="c"><td class="c">'.$campoHtml['label'].':</td><td class="c">'.$campoHtml['cod'].'</td></tr>');//
				if ($campoHtml['verAcao'] < 0 || ($campoHtml['verAcao'] > 0 && !checkPermission($campoHtml['verAcao'])))
					continue;
				elseif (stripos($campoHtml['nome'],"docResp") === false)
					print ('<tr class="c"><td class="c" width="25%">'.$campoHtml['label'].':</td><td class="c">'.$campoHtml['cod'].'</td></tr>');
			}
			print('<input type="hidden" id="camposNomes" value="'.rtrim($camposPartes,",").'" />');
			if (!isset($_GET['mini']) || $_GET['mini'] != 'true') print('</table></td></tr>');
			print ('<tr><td colspan="2"><center><input type="submit" id="btnBuscar" value="Buscar" class="campoDoc" /></center></td></tr></table>');
		} else {
			$width = 'width="25%"';
			if (!isset($_GET['mini']) || $_GET['mini'] != 'true') {
				$width = "";
				print('<table width="50%">');
			}
			print('
			<input type="hidden" id="tipos" value="'.$_GET['docs'].'" />
			<input type="hidden" id="camposNomes" value="" />
			<tr class="c"><td class="c" '.$width.'>N&deg; CPO: </td><td class="c"><input id="numCPO" type="text" size="5" maxlength="5" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>N&deg; do documento: </td><td class="c"><input type="test" id="numDoc" size="10" maxlength="10" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Criado em/entre: </td><td class="c"><input id="dataCriacao1" type="text" size="15" maxlength="10" /> e <input id="dataCriacao2" type="text" size="15" maxlength="10" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Despachado em: </td><td class="c"><input id="dataDespacho1" type="text" size="15" maxlength="10" /> e <input id="dataDespacho2" type="text" size="15" maxlength="10" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Despachado para: </td><td class="c"><input id="unDespacho" type="text" size="40" maxlength="250" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Recebido em/entre: </td><td class="c"><input id="dataReceb1" type="text" size="15" maxlength="10" /> e <input id="dataReceb2" type="text" size="15" maxlength="10" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Recebido de: </td><td class="c"><input id="unReceb" type="text" size="40" maxlength="250" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Conte&uacute;do do despacho: </td><td class="c"><input id="contDesp" type="text" size="40" /><br /></td></tr>
			<tr class="c"><td class="c" '.$width.'>Conte&uacute;do de qualquer campo: </td><td class="c"><input type="text" id="contGen" size="40" /></td></tr>');
			// verifica se o usuario tem permissao para realizar busca no arquivo
			if (checkPermission(68)) {
				print('<tr class="c"><td class="c" '.$width.'>Arquivado?: </td><td class="c"><input type="radio" id="buscaArquivo" name="buscaArquivo" value="1" /> Sim <input type="radio" id="buscaArquivo" name="buscaArquivo" value="0" /> N&atilde;o <input type="radio" id="buscaArquivo" name="buscaArquivo" value="-1" checked="checked" /> Ambos </td></tr>');
			}
			if (isset($_GET['anex']) && $_GET['anex'] == 'true') {
				print('<tr class="c" style="Display:none"><td class="c" '.$width.'>A&ccedil;&atilde;o de anexar:</td><td class="c"><input type="radio" id="actionAnex" name="actionAnex" value="1"  checked="checked"/></td></tr>');
			}
			print('<tr><td colspan="2"><center><input type="submit" id="btnBuscar" value="Buscar" class="campoDoc" /></center></td></tr></table>');
			if (!isset($_GET['mini']) || $_GET['mini'] != 'true') print('</table>');
		}
		exit();
		
	} elseif($_GET['tipoBusca'] == "busca") {
		$valoresBusca = null;
		$camposBuscaDesp = null;
		$tiposDoc = null;
		
		//tratamento de acentos, etc
		foreach ($_GET as $i => $g) {
			$_GET[$i] = SGEncode(urldecode($g),ENT_QUOTES, null, false);
		}
		
		//quais tipos de documento procurar?
		foreach (explode(',',$_GET['tipoDoc']) as $tipo) {
			$tp = getDocTipo($tipo);
			if(count($tp)){
				$tiposDoc[] = array('id' => $tp[0]['id'], 'nomeAbrv' => $tipo, 'tab' => $tp[0]['tabBD']);
			}
		}
		
		//dados de campos especificos
		$campos = explode("|", $_GET['valoresBusca']);
		//campos
		foreach ($campos as $c) {
			if($c != '') {
				$dados = explode("=", $c);
				$valoresBusca[$dados[0]] = $dados[1];
			}  
		}
		
		//montar consulta despacho
		$resDesp = array();
		foreach (array('dataDespacho', 'unDespacho', 'dataReceb', 'unReceb', 'contDesp') as $idx) {
			if(isset($_GET[$idx]) && $_GET[$idx]){
				if($idx == 'dataReceb1' || $idx == 'dataReceb2' ) {
					$resDesp[$idx] = montaData($_GET['dataReceb1'], $_GET['dataReceb2']);
				} elseif($idx == 'dataDespacho1' || $idx == 'dataDespacho2') {
					$resDesp[$idx] = montaData($_GET['dataDespacho1'], $_GET['dataDespacho2']);
				} else {
					$resDesp[$idx] = $_GET[$idx];
				}
			}
		}
		
		if (!isset($_GET['dataCriacao1'])) {
			$_GET['dataCriacao1'] = null;
		}
		if (!isset($_GET['dataCriacao2'])) {
			$_GET['dataCriacao2'] = null;
		}
		
		
		//montar consulta doc
		$arquivado = "";
		if (isset($_GET['arquivado'])) $arquivado = $_GET['arquivado'];
		if ((isset($_GET['inicioRes'])) && (isset($_GET['numResult']))) { // verifica se as variaveis de paginacao estao setadas
			$res = searchDoc($_GET['numCPO'],$_GET['numDoc'],montaData($_GET['dataCriacao1'], $_GET['dataCriacao2']), $tiposDoc, $valoresBusca, $resDesp, $_GET['contGen'], $arquivado, $_GET['anex'], $_GET['inicioRes'], $_GET['numResult']);
		}		
		else {
			$res = searchDoc($_GET['numCPO'],$_GET['numDoc'],montaData($_GET['dataCriacao1'], $_GET['dataCriacao2']), $tiposDoc, $valoresBusca, $resDesp, $_GET['contGen'], $arquivado, $_GET['anex']);
		}
		
		//$condensedSearch = true;
		
	//BUSCA POR CAMPO EXATO
	}elseif($_GET['tipoBusca'] == "cadSearch"){//seleciona o tipo de busca
		//clausula especial para cadastro de documentos com REP
		if(isset($_GET['tabela']) && $_GET['tabela'] == 'doc_rep') {
			$val = explode('|',urldecode($_GET['valores']));
			foreach ($val as $v) {
				$v = explode('=', $v);
				if(isset($v[0]) && isset($v[1]) && $v[1] && $v[0])
					$valores[$v[0]] = $v[1];
			}
			
			$res = $bd->query("SELECT id FROM doc WHERE id = ".$valores['numero_rep']." AND labelID = 10");
			if(count($res)) {
				$contr = new Contrato($res[0]['id']);
				$contr->loadCampos();
				
				$obras = $contr->getObras();
				$obrasNome = '';
				
				foreach ($obras as $o) { //var_dump($o);
					$obrasNome .= $o['nome'].', ';
				}
					$obrasNome = rtrim($obrasNome,", ");
				
				$empresa = new Empresa($bd);
				$empresa->load($contr->campos['empresaID']);
				
				$processo = new Documento($contr->campos['numProcContr']);
				$processo->loadDados();
				
				$ret = array('obras' => $obrasNome ,'empresa' => $empresa->get('nome'), 'processo' => $processo->numeroComp);
			} else {
				$ret = array('obras' => 0 ,'empresa' => 0, 'processo' => 0);
			}
			print json_encode(array($ret));
			exit();
		}
		
		if (isset($_GET['campos']) && isset($_GET['tabela']) && isset($_GET['labelID'])) {
			$tab = $_GET['tabela'];
			$labelID = $_GET['labelID'];
			$campos = explode(",", $_GET['campos']);
			$val = explode('|',urldecode($_GET['valores']));
			
			foreach ($val as $v) {
				$v = explode('=', $v);
				if(isset($v[0]) && isset($v[1]) && $v[1] && $v[0])
					$valores[$v[0]] = $v[1];
			}
			
			//monta busca com os campos preenchidos
			$query = '';
			foreach ($campos as $c) {
				if (isset($valores[$c]) != false) {
					$tipoCampo = $bd->query("SELECT tipo,attr,extra FROM label_campo WHERE nome = '$c'");
					if($tipoCampo[0]['tipo'] == 'composto') {
						$partes = explode("+",$tipoCampo[0]['attr']);
						$query .= ' AND ( '.$c." LIKE '";
						foreach ($partes as $p) {
							if(isset($valores[$p]) != null) {
								$query .= SGEncode($valores[$p],ENT_QUOTES, null, false);
							} else {
								$query .= str_replace('"','',$p);
							}
						}
						$query .= "') ";						
					} elseif($tipoCampo[0]['extra'] == 'parte') {
						continue;							
					} else {
						$query .= " AND tab.".$c." = '".SGEncode($valores[$c],ENT_QUOTES, null, false)."'";
					}
				} else {
					showError(9);
				}
			}
			//efetua a busca retornando os IDs das matches
			
			$q = "
			SELECT doc.id FROM doc AS doc
			INNER JOIN ".$tab." AS tab ON doc.tipoID = tab.id
			WHERE doc.labelID = ".$labelID.$query;
			$res = $bd->query($q);
			
			//print($q.'<br>');
			/*print_r($bd->query($q)); print("<BR>");
			/*$q = "
			SELECT doc.id FROM doc AS doc
			INNER JOIN doc_gen AS tab ON doc.tipoID = tab.id
			Where doc.labelID = 7 AND tab.tipoDoc = 'CARTA' AND tab.numero_dgen = '1233' AND tab.anoE = '2011' AND tab.unOrg = '22.07.02.00.00.00 - MANUTENCAO (MANUT)'";
			$res = $bd->query($q);
			
			/*$res = $bd->query("
			SELECT doc.id FROM sg.doc AS doc
			RIGHT JOIN ".$tab." AS tab ON doc.tipoID = tab.id
			WHERE doc.labelID = ".$labelID.$query);//*/
			
			//print_r($q);
			//print_r($res);exit();
		}
	}elseif($_GET['tipoBusca'] == "buscaSearch"){
		if(isset($_GET['tipoDoc'])) {
			$tipoDoc = $_GET['tipoDoc'];// print_r($_GET);exit();
			if($tipoDoc == "_outro_") {//seleciona em qual tabela(s) procurar
				$sql = "SELECT id FROM doc as d WHERE d.id > 0";
			} else {
				$doc = new Documento('0');
				$doc->dadosTipo['nomeAbrv'] = $tipoDoc; //print_r($doc);
				$doc->loadTipoData();  
				$tab = $doc->dadosTipo['tabBD'];
				//print($tab);exit();
				
				//$tab = $bd->query("SELECT id,tabBD FROM label_doc WHERE nomeAbrv='".$tipoDoc."'");
				$sql = "SELECT d.id FROM doc as d
				LEFT JOIN ".$tab." AS t ON d.tipoID = t.id
				WHERE d.labelID = ".$doc->dadosTipo['id'];
			}
			
			foreach ($_GET as $cNome => $cValor) {
				if($cNome == "tipoDoc" || $cNome == 'tipoBusca')
					continue;
				
				elseif($cNome == 'dataCr'){//algoritmo deve identif intervalo de data ou data especifica
					if($cValor != '') {
						$dataCr = montaData($_GET['dataCr']);
						$sql .= ' AND d.data > '.$dataCr[0].' AND d.data < '.$dataCr[1];
					}
				} elseif ($cNome == "numCPO"){//campo numero CPO
					if($cValor != '') {
						$sql .= ' AND d.id='.$cValor;
					}
				} elseif ($cNome == 'desp'){//restringe a busca para os ids que tem o despacho igual ao da busca
					if($cValor != '') {	
						$resDesp = $bd->query("SELECT docID as id FROM data_historico WHERE acao LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%' GROUP BY docID");
						if (count($resDesp)) {
							$sql .= ' AND (';
							$firstID =  true;
							foreach ($resDesp as $r) {
								if(!$firstID)
									$sql .= " OR";
								$sql .= " d.id=".$r['id'];
								$firstID = false;
							}//end foreach
							$sql .= ')';
						}
					}
				} else {
					$tipoCampo = $bd->query("SELECT tipo,attr,extra FROM label_campo WHERE nome = '$cNome'");
					if($cValor != '' || $tipoCampo[0]['tipo'] == 'composto'){
						if($tipoCampo[0]['tipo'] == 'userID' && strpos($tipoCampo[0]['tipo'] == 'userID', "select") === false){
							$userID = $bd->query("SELECT id FROM usuarios WHERE nome LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%'");
							if (count($userID)){
								$sql .= ' AND (';
								foreach ($userID as $id) {
									$sql .= "$cNome = ".SGEncode($id['id'], ENT_QUOTES, null, false)." OR ";
								}
								$sql = rtrim($sql," OR ");
								$sql .= ")";
							} 
							
						} elseif($tipoCampo[0]['tipo'] == 'select' && $cValor == "nenhum") {
							$sql .= ' AND t.'.$cNome." LIKE '%'";
						} elseif($tipoCampo[0]['tipo'] == 'composto') {
							$partes = explode("+",$tipoCampo[0]['attr']);
							$sql .= ' AND ( '.$cNome." LIKE '";
							foreach ($partes as $p) {
								if(isset($_GET[$p]) != null){
									$sql .= "%".SGEncode($_GET[$p], ENT_QUOTES, null, false)."%";
								}else{
									$sql .= SGEncode(str_replace('"','',$p), ENT_QUOTES, null, false);
								}
							}
							$sql .= "') ";
						} elseif($tipoCampo[0]['tipo'] == 'userID'){
							if($cValor){
								$res = $bd->query("SELECT id FROM usuarios WHERE nome LIKE '%{".SGEncode($cValor, ENT_QUOTES, null, false)."}%' OR sobrenome LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%' OR username LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%'");
								//var_dump("SELECT id FROM usuarios WHERE nome LIKE '%{".SGEncode($cValor, ENT_QUOTES, null, false)."}%' OR sobrenome LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%' OR username LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%'");
								foreach ($res as $user) {
									$sql .= ' AND t.'.$cNome." = ".$user['id'];
								}
								
								
							}
						} elseif($tipoCampo[0]['extra'] == 'parte') {
							continue;							
						} else {
							$sql .= ' AND t.'.$cNome." LIKE '%".SGEncode($cValor, ENT_QUOTES, null, false)."%'";
						}
					}
				}
			}
			$sql .= ' LIMIT 100';
			
			//realiza a query
			//print($sql);exit();
			$res = $bd->query($sql);/*$file = fopen("pessoas.txr", "w");
							fwrite($file, $restr);fclose($file);*/
			
		}//end isset campos
	
	} elseif ($_GET['tipoBusca'] == 'numCPO' && isset($_GET['docID'])) {
		$res[0]['id'] = $_GET['docID'];
	
	} elseif($_GET['tipoBusca'] == 'repBusca') {
		if(!isset($_POST['numero']) || !$_POST['numero'] || !isset($_POST['unOrg']) || !$_POST['unOrg'] || !isset($_POST['tipo']) || !$_POST['tipo'] || !isset($_POST['ano']) && !$_POST['ano'])
		{
			print(json_encode(array(array('duplicata' => false, 'error' =>true))));
			exit();
		}
		
		$sql = "SELECT id FROM doc_gen WHERE tipoDocGen = '".SGEncode($_POST['tipo'])."' AND numero_dgen = '{$_POST['numero']}' AND anoE = '{$_POST['ano']}' AND unOrg = '".SGEncode($_POST['unOrg'])."'";
		$dupl = $bd->query($sql);
		//var_dump($sql);
		$duplicata = false;
		if(count($dupl))
			$duplicata = true;
		
		print(json_encode(array(array('duplicata' => $duplicata))));
		exit();
	}//end if tipoBusca
}else{//end isset tipoBusca
	exit();	
}

// carrega todos os tipos de doc do bd e deixa em memória
// a tabela é pequena, então guardar esta tabela em memória é vantajoso
$docTypes = getAllDocTypes();
$tipoDoc = array();
foreach($docTypes as $dt) {
	// cria array que representará a tabela
	$tipoDoc[$dt['id']] = $dt;
}

//conversao para JSON
foreach ($res as $r) {
	//leitura dos dados do documento
	/*$arqTemp = fopen("busca.txt", "a");
	fwrite($arqTemp, $r['id'] . "\n");
	fclose($arqTemp);*/
	$doc = new Documento($r['id']);
	$doc->loadDados();
	$doc->dadosTipo = $tipoDoc[$doc->labelID];
	$doc->loadCampos();
	//$doc->loadCampos($bd);
	
	//inicio da montagem do array de saida
	//copia dos campos do documento
	$d = $doc->campos; 
	
	if (isset($r['total'])) $d['total'] = $r['total'];
	
	$d['tipo'] = $doc->dadosTipo;
	
	// clausula especial para contrato
	// se selecionou apenas 1 tipo e o tipo do doc atual é contrato (ou seja, selecionou apenas tipo contrato)
	if (isset($tiposDoc) && count($tiposDoc) == 1 && $d['tipo']['nomeAbrv'] == 'contr') {
		$d['numeroCompl'] = $doc->numeroComp;
		
		// seleciona todas as obras associadas a este contrato
		$sql = "SELECT o.id, o.nome FROM obra_obra AS o INNER JOIN obra_doc AS c ON o.id = c.obraID WHERE c.docID = ".$r['id'];
		$obras = $bd->query($sql);
		
		// inicializa variaveis
		$d['data_contrato'] = array();
		$d['data_contrato']['obras'] = array();
		$d['data_contrato']['proc'] = array();
		$d['data_contrato']['empresa'] = 'Desconhecida';
		
		// se encontrou alguma obra
		if (count($obras) > 0) {
			$d['data_contrato']['obras'] = $obras;
		}
		
		// verificação de segurança: todo contrato deve estar associado a um processo
		if ($doc->docPaiID != 0) {
			// carrega processo pai
			$procPai = new Documento($doc->docPaiID);
			// seta tipo processo
			$procPai->dadosTipo = $tipoDoc[1];
			$procPai->loadDados();
			$procPai->loadCampos();
			
			// seta dados do processo pai
			$d['data_contrato']['proc']['id'] = $doc->docPaiID;
			$d['data_contrato']['proc']['numeroCompl'] = $procPai->numeroComp;
			$d['data_contrato']['proc']['tipoProc'] = $procPai->campos['tipoProc'];
		}
		
		// carrega dados da empresa
		if (isset($doc->campos['empresaID']) && $doc->campos['empresaID'] != 0) {
			$sql = "SELECT nome FROM empresa WHERE id = ".$doc->campos['empresaID'];
			$empresa = $bd->query($sql);
			
			// se achou alguma empresa, seta o nome
			if (count($empresa) > 0) {
				$d['data_contrato']['empresa'] = $empresa[0]['nome'];
			}
		}
	}

	$sigilo = true;
	if (verificaSigilo($doc) && !checkPermission(67)) $sigilo = false;
	
	if (isset($doc->arquivado)) {
		$d['arquivado'] = $doc->arquivado;
	}
	else {
		$d['arquivado'] = 0;
	}
	
	if ($sigilo) {
		$d['emitente'] = $doc->emitente;
		if ($doc->dadosTipo['nomeAbrv'] == "pr")
			$d['emitente'] = $doc->campos['unOrgInt'];
		elseif ($doc->dadosTipo['nomeAbrv'] == "sap")
			$d['emitente'] = $doc->campos['unOrgIntSAP'];
	}
	else $d['emitente'] = "";
	
	//adiciona o nome do documento
	$d['nome'] = $doc->dadosTipo['nome'].' '.$doc->numeroComp;
	
	// adiciona tipo do processo (se o doc for processo)
	if ($doc->dadosTipo['nomeAbrv'] == 'pr') {
		$d['tipoProc'] = $doc->campos['tipoProc'];
		$d['guardachuva'] = $doc->campos['guardachuva'];
	}
	if ($doc->dadosTipo['nomeAbrv'] == 'sap') {
		$d['guardachuva'] = "";
	}
	
	//ignora o ID (autoincrement na tabela de tipo) para ID (tabela doc) da CPO
	$d['id'] = $doc->id;
	
	//adiciona o ID do documento pai e flag de anexado
	$d['anexavel'] = $doc->dadosTipo['docAnexo'];
	$d['anexado'] = $doc->anexado;
	$d['docPaiID'] = $doc->docPaiID;
	
	//carregamento dos dados dos documentos anexos
	$d['docs'] = $doc->getDocAnexoDet();
	if(!isset($d['docs']) || !($sigilo))
		$d['docs'] = array();
	
	unset($d['documento']);
	
	//carregamento dos dados dos arquivos anexos
	$d['arqs'] = $doc->anexo;
	if ($d['arqs'] == null || !($sigilo))
		$d['arqs'] = array();
	
	//carregamento dos dados da obra associada
	//TODO carregamento de obras
	$d['obra'] = array(array("id" => "", "nome" => ""));
	
	//carregamento dos dados do historico do documento
	$d['hist'] = $doc->getHist();
	
	//carega o dono atual do documento
	$d['ownerID'] = $doc->owner;
	
	$d['ownerName'] = getDocOwner($doc, $bd);
	
	//carrega se o documento eh despachavel (ownerID != usuario atual)
	if ($d['ownerID'] == $_SESSION['id'] || $d['ownerID'] == 0)
		$d['despachavel'] = 1;
	else
		$d['despachavel'] = 0;
	
	//se nao houver empresa, coloca array vazio
	if(!isset($d['empresa']))
		$d['empresa'] = array();

	//se hao houver assunto, coloca hifen
	if(!isset($d['assunto']) || !($sigilo))
		$d['assunto'] = '-';
		
	$obras = $doc->getObras(true);
	if (count($obras) > 0) {
		$sqlObra = "SELECT e.id, e.nome FROM obra_empreendimento AS e INNER JOIN obra_obra AS o ON e.id = o.empreendID WHERE ";
		foreach ($obras as $o) {
			$sqlObra .= "o.id = ".$o['id']." OR ";
		}
		$sqlObra = rtrim($sqlObra, "OR ");
		$empreend = $bd->query($sqlObra);
	}
	else {
		$empreend = $doc->getEmpreend();
	}
 
	$empreendList = array();
	if ($empreend != null) {
		foreach ($empreend as $e) {
			$lista = array(0 => $e['id'], 1 => $e['nome']);
			$empreendList[] = $lista;
		}
	}
	$d['empreendList'] = $empreendList;
		
	$d['solicitante'] = "";
	if ($doc->solicitante != "0") {
		$d['solicitante'] = $doc->solicitante;
		$solic = getUserFromUsername($doc->solicitante);
		$solic = $solic[0];
		$d['solicID'] = $solic['id'];
		$d['solicNome'] = $solic['nomeCompl'];
		$d['solicArea'] = $solic['area'];
	}
	
	if (isset($d['dataAssinatura'])) {
		if ($d['dataAssinatura'] != 0) {
			$d['dataAssinatura'] = date("d/m/Y", $d['dataAssinatura']);
		}
		else {
			$d['dataAssinatura'] = "---";
		}
	}
	if (isset($d['prazoContr'])) {
		if ($d['prazoContr'] != 0) {
			$d['prazoContr'] = date("d/m/Y", $d['prazoContr']);
		}
		else {
			$d['prazoContr'] = "---";
		}
	}
	if (isset($d['vigenciaContr'])) {
		if ($d['vigenciaContr'] != 0) {
			$d['vigenciaContr'] = date("d/m/Y", $d['vigenciaContr']);
		}
		else {
			$d['vigenciaContr'] = "---";
		}
	}
	if (isset($d['inicioProjObra'])) {
		if ($d['inicioProjObra'] != 0) {
			$d['inicioProjObra'] = date("d/m/Y", $d['inicioProjObra']);
		}
		else {
			$d['inicioProjObra'] = "---";
		}
	}
	if (isset($d['prazoProjObra'])) {
		if ($d['prazoProjObra'] != 0) {
			$d['prazoProjObra'] = date("d/m/Y", $d['prazoProjObra']);
		}
		else {
			$d['prazoProjObra'] = "---";
		}
	}
	if (isset($d['dataTermino'])) {
		if ($d['dataTermino'] != 0) {
			$d['dataTermino'] = date("d/m/Y", $d['dataTermino']);
		}
		else {
			$d['dataTermino'] = "---";
		}
	}
	
	// verificacao de codificacao utf8
	$d = verificaCodificacao($d);
	
	$data[] = $d;
}
//fwrite($arqTeste, microtime(true));
print json_encode($data);
$bd->disconnect();
//fclose($arqTeste);
function montaData($data1, $data2){
	if(!$data1 && !$data2)
		return array(null,null);
	$datas = array(explode('/',$data1),explode('/',$data2));
	
	if(count($datas) == 2 && count($datas[0]) == 3 && count($datas[1]) == 3){//intervalo
		return array(mktime(0,0,1,$datas[0][1],$datas[0][0],$datas[0][2]),mktime(23,59,59,$datas[1][1],$datas[1][0],$datas[1][2]));
	} elseif(count($datas) == 2 && count($datas[0]) == 3 && count($datas[1]) < 3) {//apenas 1 data
		return array(mktime(0,0,1,$datas[0][1],$datas[0][0],$datas[0][2]),mktime(23,59,59,$datas[0][1],$datas[0][0],$datas[0][2]));
	} else {
		return array(null,null);
	}
}

/*function verificaCodificacao($vetor) {
	if (is_string($vetor) == true) {
		$vetor = mb_check_encoding($vetor, 'UTF-8') ? $vetor : utf8_encode($vetor);
		return $vetor;
	}
	if (is_array($vetor) == false) {
		return $vetor;
	}
	foreach($vetor as $campo => $valor) {
		$vetor[$campo] = verificaCodificacao($valor);
	}
	return $vetor;
}*/

?>