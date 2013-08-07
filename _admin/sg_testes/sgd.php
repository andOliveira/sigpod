<?php
	/**
	 * @version 1.0 20/4/2011 
	 * @package geral
	 * @author Mario Akita
	 * @desc pagina que lida com os modulos de gerenciamento de documentos 
	 */
	include_once('includeAll.php');
	include_once('sgd_modules.php');
	
	//verifica se o usuario esta logado
	checkLogin(6);
	
	//cria uma nova pagina HTML
	$html = new html($conf);
	//seta o texto de cabecalho da pagina
	$html->header = "Ger&ecirc;ncia de Documentos";
	//gera o codigo de tela para esta pagina
	$html->campos['codPag'] = showCodTela();
	//completa o nome de usuario
	$html->user = $_SESSION['nomeCompl'];
	//inicia conexao com o banco de dados
	$bd = new BD($conf["DBLogin"], $conf["DBPassword"], $conf["DBhost"], $conf["DBTable"]);
	if(isset($_REQUEST['acao']))
		$_GET['acao']=$_REQUEST['acao'];
	if (isset($_GET['acao'])) {
/*VD*/	if( $_GET['acao'] == "ver" || $_GET['acao'] == "desp" || $_GET['acao'] == "anexArq" || $_GET['acao'] == "entrada" || $_GET['acao'] == "anexDoc" || $_GET['acao'] == 'edit' || $_GET['acao'] == 'saveAnex' || $_GET['acao'] == 'atribEmpreend' || $_GET['acao'] == 'atribObra' || $_GET['acao'] == 'atribEmpreendAjax' ||$_GET['acao'] == 'atribObraAjax' || $_GET['acao'] == 'arquivar' || $_GET['acao'] == 'arqAjax' || $_GET['acao'] == 'solicDesarqAjax') {
		//rotina para  ver documento
			if(isset($_GET['docID'])){
				//se o ID do documento estiver especificado, cria variavel e carrega os dados do doc
				$doc = new Documento($_GET['docID']);
				$doc->loadCampos();
			}else{
				//senao, mostra erro: vc quer ver um documento sem numero
				showError(7);
			}
			//verifica permissão
			//print_r($_SESSION['perm']); print("<BR>".$doc->dadosTipo['verAcaoID']); exit();
			if(! checkPermission($doc->dadosTipo['verAcaoID'])){
				showError(12); 
			}
			
			//rotina para completar o template, caminho do arquivo e titulo 
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->head .= '<script type="text/javascript" src="CKEditor/ckeditor.js?r={$randNum}"></script>
							<script type="text/javascript" src="scripts/sgd_mini.js?r={$randNum}"></script>
							<script type="text/javascript" src="scripts/busca_doc2.js?r={$randNum}"></script>
							<!--<script type="text/javascript" src="scripts/jquery.autocomplete.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />-->
			';
			//completa o espaco de menu com as acoes possiveis para o documento
			$html->menu = showAcoes($doc);
			//area 1 - contem os detalhes do documento
			
			//area 2 - detalhes do emissor
			$html->content[2] = '';
			$html->content[3] = '';
			$html->content[4] = '';
			$html->content[5] = '';

			//area 3 - historico do documento
			//$html->content[3] = showHist($doc);
			//area 4 - area para despachar
			//$html->content[4] = showDesp('f',getDeptos(),$doc);
			//area 5 - area para anexar arquivo
			//$html->content[5] = showAnexar('f',$doc);
			//dependendo da acao, gera JS para esconder/mostrar as areas pertinentes
			
			if ($_GET['acao'] == "ver") {
				$html->head .= '
								<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
								<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
				';
				
				//loga a acao do usuario no BD para administracao
				doLog($_SESSION['username'], "viu detalhes do documento ".$doc->id." (".$doc->dadosTipo['nome']." ".$doc->numeroComp.")", $bd);
				
				if ($doc->dadosTipo['nomeAbrv'] == 'contr') {
					$tipo = $doc->dadosTipo;
					$doc = new Contrato($doc->id);
					$doc->dadosTipo = $tipo;
					$doc->loadDados();
					$doc->loadCampos();
					$html->content[1] = $doc->showResumo();
					$html->content[3] = $doc->showEmpresaResumo();
				}
				else {
					/**
					 * Solicitacao 002
					 * inclusão do desanexar.js
					 */
					$html->head.='<script type="text/javascript" src="scripts/desanexar.js"></script>';
					$html->content[1] = showDetalhes($doc);
					$html->content[3] = showRespostas($doc, $bd);
				}
				
				//mostra detalhes do doc/emissor e historico. esconde anexar arquivo e despacho
				$html->content[2] = showEmissor($doc);
				//$html->content[4] = showHist($doc);
				$html->content[4] = $doc->showHist();
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c5").hide();});</script>';
				
			} elseif ($_GET['acao'] == "desp") {
				//esconde os detalhes/historico e anexar arquivo. mostra despachar
				$html->head .= '<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script><link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />';
				$html->content[1] = showDesp('f',getDeptos(),$doc);
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
			} elseif ($_GET['acao'] == "anexArq") {
				if(isset($_GET['feedback'])) {
					//faz upload de arquivos, salva no documento e loga no historico
					$html->content[1] = "<b>Arquivos</b><br />";			
					$relArq = $doc->doUploadFiles();
					$html->content[1] .= montaRelArq($relArq);
					$doc->salvaAnexos();
					$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
					
				} else {
					//esconde os detalhes/historico e despachar. mostra anexar arquivo
					$html->content[1] = showAnexar('f',$doc);
					$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
				}
			} elseif ($_GET['acao'] == "entrada") {
				$html->head = '<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
						<script type="text/javascript" src="scripts/sgd_mini.js?r={$randNum}"></script>
						<script type="text/javascript" src="scripts/busca_doc2.js?r={$randNum}"></script>
						<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />';
				
				$html->content[1] = showEntradaForm(getDeptos(),$doc);
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
			} elseif ($_GET['acao'] == 'anexDoc') {
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c3").hide();$("#c4").hide();$("#c5").hide();';
				//if (isset($_GET['proc']) && $_GET['proc'] == true) {
				if ($doc->dadosTipo['nomeAbrv'] != "resp" && $doc->dadosTipo['nomeAbrv'] != "rr") {
					$html->menu .= '$("#c1").hide(); $("input:radio[name=tipo]").filter("[id=addOutr]").attr("checked", true);';
					
					// rotina para desativar anexacao a documentos que não podem ter anexos a este doc
					$sqlAnex = "SELECT d.nomeAbrv FROM label_doc_anexo AS a INNER JOIN label_doc AS d ON a.tipoAnexoID = d.id WHERE tipoDocID = " .$doc->dadosTipo['id']. " AND aceitaAnexo = 0";
					$resAnex = $bd->query($sqlAnex);
					foreach ($resAnex as $a) {
						$html->menu .= '$("input:checkbox[name=tipoDoc]").filter("[id='.$a['nomeAbrv'].']").attr("disabled", true);';
					}
					$html->menu .= '$("input:checkbox[name=tipoDoc]").filter("[id=resp]").attr("disabled", true);';

					$html->menu .= 'carregaCampos();';
				}
				else
					$html->menu .= '$("#c2").hide();';
				$html->menu .= '});</script>';
				$html->content[1] = addAnexarDoc($doc);
				$html->content[2] = showBuscaForm('anexDoc');
			} elseif ($_GET['acao'] == 'atribEmpreend') {
				if(! checkPermission(57)) {
					showError(12);
				}	
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
				$html->content[1] = showAtribuirAEmpreend($_GET['docID']);
			
			} elseif ($_GET['acao'] == 'atribObra') {
				if(! checkPermission(5)) {
					showError(12);
				}	
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
				$html->content[1] = showAtribuirAObra($_GET['docID']);
			
			} elseif ($_GET['acao'] == 'atribEmpreendAjax') {
				if(!isset($_GET['empreendID'])) {
					print json_encode(array(array('success' => false))); exit(); 
				}
					print json_encode(atribEmpreend($_GET['docID'], $_GET['empreendID'], $bd, $_GET['desfazer']));
					exit(); 
				
			} elseif ($_GET['acao'] == 'atribObraAjax') {
				if(!isset($_GET['obraID'])) {
					print json_encode(array(array('success' => false))); exit(); 
				}
				if (isset($_GET['desfazer'])) {
					print json_encode(atribObra($_GET['docID'], $_GET['obraID'], $bd, $_GET['desfazer']));
					exit(); 
				}
				else {
					print json_encode(atribObra($_GET['docID'], $_GET['obraID'], $bd));
					exit();
				}
				
			} elseif($_GET['acao'] == 'saveAnex') {
				if(!isset($_GET['paiID']) || !isset($_GET['filhoID'])) {
					print json_encode(array(array('success' => 'false'))); exit(); 
				}
				print json_encode(anexarDoc($_GET['filhoID'],$_GET['paiID']));
				exit();
				
			} elseif ($_GET['acao'] == 'edit') {
				if(!isset($_GET['docID']) || !isset($_GET['campo']) || !isset($_POST['newVal'])) {					
					print json_encode(array(array('success' => 'false'))); exit(); 
				}
				$res = editDoc($_GET['docID'], $_GET['campo'],SGEncode(urldecode($_POST['newVal']),ENT_QUOTES, null, false));
				print json_encode($res);
				exit();
			}
			elseif ($_GET['acao'] == 'arquivar') {
				if(!isset($_GET['docID'])) {
					$html->content[1] = '&Eacute; necess&aacute;rio especificar um documento para realizar esta a&ccedil;&aatilde;o.';
				}
				elseif($doc->anexado) {
					 $html->content[1] .= 'Este documento est&aacute; anexado e n&atilde;o pode ser arquivado.';
				}
				else {
					$despachado = 0;
					
					if ($doc->arquivado == 0) $arqLabel = "arquivado";
					else { 
						$arqLabel = "desarquivado";
						if ($doc->solicDesarquivamento != "0") {
							$despachado = getUserFromUsername($doc->solicDesarquivamento);
							$despachado = $despachado[0];
						}
					}
					
					if (doArquiva($doc)) {
						// atualiza menu
						$html->menu = showAcoes($doc);
						// imprime mensagem
						$html->content[1] = 'Documento ('.$doc->id.') <b>'.$doc->dadosTipo['nome'].' '.$doc->numeroComp.'</b>:  '.$arqLabel.' com sucesso!';
						if ($despachado != 0) $html->content[1] .= '<br />Este documento havia sido solicitado por '.$despachado['nomeCompl'].'. Por favor, leve este documento para esta pessoa. O sistema j&aacute; realizou o despacho.';
					}
					else $html->content[1] = 'Falha ao desarquivar o documento. Por favor, tente novamente.';
					
					
				}
				$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
			}
			elseif ($_GET['acao'] == 'arqAjax') {
				if(!isset($_GET['docID'])) {
					print json_encode(array(array('success' => false))); exit(); 
				}
				if (doArquiva($doc)) {
					print json_encode(array(array('success' => true))); exit();
					exit();
				}
				else {
					print json_encode(array(array('success' => false)));
					exit();
				}
			}
			elseif ($_GET['acao'] == 'solicDesarqAjax') {
				if(!isset($_GET['docID'])) {
					print json_encode(array(array('success' => false, 'feedback' => utf8_encode('Erro de docID')))); exit(); 
				}
				
				print json_encode(showSolicDesarq($doc, true));
				exit();
			}

			
/*CD*/	}elseif ($_GET['acao'] == "cad") {
			//rotina para cadastrar documento
			//caso nao seja especificado tipo de documento a ser cadastrado, mostra erro
			if(!(isset($_GET['tipoDoc']))) showError(7);
			//cria novo doc para ler acaoID
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
			$doc->loadTipoData();
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['cadAcaoID'])) {
				showError(12);
			}			
			//rotina para definir template, caminho, titulo, nome de usuario.
			$html->setTemplate($conf['template']);
			$html->path = showNavBar(array(array("url" => "","name" => "Cadastrar Documento")));
			$html->title .= "SGD : Cadastrar Documento";
			//menu principal
			$html->menu = showMenu($conf['template_menu'],$_SESSION["perm"],2310,$bd);
			//gera formulario para cadastro de documento
			$novaJanela = 0;
			if (isset($_GET['novaJanela']))
				$novaJanela = 1;
			
			$restaurar = false;
			if (isset($_GET['restaurar']))
				$restaurar = true;
				
			$html->content[1] = showForm("cad",$_GET['tipoDoc'],$novaJanela,$bd, null, $restaurar);
			$html->head .= '<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />';

		} elseif($_GET['acao'] == "cad_mini") {
			//rotina para cadastrar documento em janela pequena
			//caso nao seja especificado tipo de documento a ser cadastrado, mostra erro
			if(!(isset($_GET['tipoDoc']))) showError(7);
			//cria novo doc para ler acaoID
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
			$doc->loadTipoData();
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['cadAcaoID'])) {
				showError(12);
			}			
			//rotina para definir template, caminho, titulo, nome de usuario.
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Cadastrar Documento")));
			$html->title .= "SGD : Cadastrar Documento";
			$html->content[1] = '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();$(".boxLeft").css("width","0");$(".boxRight").css("width","100%");});</script>';
			//gera formulario para cadastro de documento
			$novaJanela = 0;
			if (isset($_GET['novaJanela']))
				$novaJanela = 1;
			
			$restaurar = false;
			if (isset($_GET['restaurar']))
				$restaurar = true;
				
			$html->content[1] .= showForm("cad",$_GET['tipoDoc'], $novaJanela,$bd, null, $restaurar);
			
/*NV*/	}elseif ($_GET['acao'] == "novo") {
			//rotina para cadastrar documento
			//caso nao seja especificado tipo de documento a ser cadastrado, mostra erro
			if(!(isset($_GET['tipoDoc']))) showError(7);
			//cria novo doc para ler acaoID
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
			$doc->loadTipoData($bd);
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['novoAcaoID'])){
				showError(12);
			} 
			//inclui no cabecalho os scripts para usar o CKEdit
			$html->head .= '<script type="text/javascript" src="ckeditor/ckeditor.js?r={$randNum}"></script>
						<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
						<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />';
			//rotina para novo documento
			//rotina para definir template, caminho, titulo, nome de usuario.
			$html->setTemplate($conf["template"]);
			$html->path = showNavBar(array(array("url" => "","name" => "Novo Documento")));
			$html->title .= "SGD : Novo Documento";
			//menu principal
			$html->menu = showMenu($conf['template_menu'],$_SESSION["perm"],2310,$bd);
			//gera formulario de novo documento
			$novaJanela = 0;
			if (isset($_GET['novaJanela']))
				$novaJanela = 1;
				
			$restaurar = false;
			if (isset($_GET['restaurar']))
				$restaurar = true;
				
			$html->content[1] = showForm("novo",$_GET['tipoDoc'], $novaJanela,$bd, null, $restaurar);
			
		}elseif ($_GET['acao'] == "novo_mini"){
			//rotina para cadastrar documento
			//caso nao seja especificado tipo de documento a ser cadastrado, mostra erro
			if(!(isset($_GET['tipoDoc']))) showError(7);
			//cria novo doc para ler acaoID
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
			$doc->loadTipoData($bd);
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['novoAcaoID'])){
				showError(12);
			} 
			//inclui no cabecalho os scripts para usar o CKEdit
			$html->head .= '<script type="text/javascript" src="ckeditor/ckeditor.js?r={$randNum}"></script>
						<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
						<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />';
			//rotina para novo documento
			//rotina para definir template, caminho, titulo, nome de usuario.
			$html->setTemplate($conf["template_mini"]);
			$html->path = showNavBar(array(array("url" => "","name" => "Novo Documento")));
			$html->title .= "SGD : Novo Documento";
			//gera formulario de novo documento
			$novaJanela = 0;
			if (isset($_GET['novaJanela']))
				$novaJanela = 1;
			
			$restaurar = false;
			if (isset($_GET['restaurar']))
				$restaurar = true;
				
			$html->content[1] = showForm("novo",$_GET['tipoDoc'], $novaJanela,$bd, null, $restaurar);
			$html->content[1] .= '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();$(".boxLeft").css("width","0");$(".boxRight").css("width","100%");});</script>';
			
/*SV*/	}elseif ($_GET['acao'] == "salvar"){
			//rotina para salvar os dados na criacao/cadastro de documento em nova janela
			//rotina para definir template, caminho, titulo, nome de usuario
			$html->head .= '<script type="text/javascript" src="ckeditor/ckeditor.js?r={$randNum}"></script>
							<script type="text/javascript" src="scripts/sgd_mini.js?r={$randNum}"></script>
							<!--<script type="text/javascript" src="scripts/jquery.autocomplete.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />-->';
			if (isset($_GET['novaJanela']) && $_GET['novaJanela'] == 1) {
				$html->setTemplate($conf["template_mini"]);
				$html->path = showNavBar(array(array("url" => "","name" => "Salvar documento")), "mini");
				$html->title .= "SGD : Salvar Documento";
				$html->menu = '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();$(".boxLeft").css("width","0");$(".boxRight").css("width","100%");});</script>';
			}
			else {
				$html->setTemplate($conf["template"]);
				$html->path = showNavBar(array(array("url" => "","name" => "Salvar documento")));
				$html->title .= "SGD : Salvar Documento";
				//menu principal
				$html->menu = showMenu($conf['template_menu'],$_SESSION["perm"],2310,$bd);
			}
			
			//funcao que salva os dados e gera visualizacao dos resultados
			foreach($_POST as $k=>$v){
				$_POST[$k] = SGEncode($v, ENT_QUOTES, null, false);
			}
			//var_dump($_POST);exit();
			
			//cadastrar doc gen atraves de rep
			if($_POST['tipoDocCad'] == 'rep') {
				$tiposDoc = array(
					'1' => array('nome' => 'ART'),
					'2' => array('nome' => 'As built'),
					'3' => array('nome' => 'Ata de reuni&atilde;o'),
					'4' => array('nome' => 'CD'),
					'5' => array('nome' => 'Certid&atilde;o CREA'),
					'6' => array('nome' => 'Composi&ccedil;&atilde;o de Pre&ccedil;os Unit&aacute;rios (CPU)'),
					'7' => array('nome' => 'Convoca&ccedil;&atilde;o de empresa / unidade'),
					'8' => array('nome' => 'Credenciamento de Colabor(es)'),
					'9' => array('nome' => 'Cronograma f&iacute;sico / f&iacute;sico-financeiro'),
					'10' => array('nome' => 'Croqui'),
					'11' => array('nome' => 'Curva ABC'),
					'12' => array('nome' => 'Di&aacute;rio de obra'),
					'13' => array('nome' => 'Email'),
					'14' => array('nome' => 'Garantia'),
					'15' => array('nome' => 'Manual'),
					'16' => array('nome' => 'Medi&ccedil;&atilde;o'),
					'17' => array('nome' => 'Mem&oacute;ria de c&aacute;lculo'),
					'18' => array('nome' => 'Memorial descritivo'),
					'19' => array('nome' => 'Nota fiscal'),
					'20' => array('nome' => 'Notifica&ccedil;&atilde;o'),
					'21' => array('nome' => 'Parecer t&eacute;cnico'),
					'22' => array('nome' => 'Projeto'),
					'23' => array('nome' => 'Proposta / Planilha / Or&ccedil;amento'),
					'24' => array('nome' => 'Protocolo PPCI'),
					'25' => array('nome' => 'Relat&oacute;rio de acompanhamento'),
					'26' => array('nome' => 'Relat&oacute;rio de atividades'),
					'27' => array('nome' => 'Relat&oacute;rio de intercorr&ecirc;ncia'),
					'28' => array('nome' => 'Relat&oacute;rio de pend&ecirc;ncias'),
					'29' => array('nome' => 'Relat&oacute;rio fotogr&aacute;fico'),
					'30' => array('nome' => 'Solicita&ccedil;&atilde;o de aditivo/supress&atilde;o de <br>prazo, valor ou reequil&iacute;brio'),
					'31' => array('nome' => 'Solicita&ccedil;&atilde;o de Atestado de Capacidade T&eacute;cnica'),
					'32' => array('nome' => 'Solicita&ccedil;&atilde;o de retirada de material'),
					'33' => array('nome' => 'Solicita&ccedil;&atilde;o de trabalho'),
					'34' => array('nome' => 'Subcontrato'),
					'35' => array('nome' => 'Termo de recebimento Provis&oacute;rio ou Definitivo'),
					'36' => array('nome' => 'Outros')
				);
				$id_docGen = 0;
				$dgen = getDocTipo('dgen');
				$campos = explode(',', $dgen[0]['campos']);
				$camposBusca = explode(',', $dgen[0]['campoBusca']);
				$camposGerais = '';
				
				foreach ($campos as $c) {
					$busca = false;
					
					foreach ($camposBusca as $cb) {
						if($c == $cb)
							$busca = true;
					}
					if (!$busca)
						$camposGerais .= $c.',';
					
				}
				$camposGerais = rtrim($camposGerais,',');

				$contrato = new Contrato($_POST['_numero_rep']);
				$contrato->loadCampos();
				$empreend = $contrato->getEmpreend();
				
				$empresa = new Empresa($bd);
				$empresa->load($contrato->campos['empresaID']);
				
				$html->content[1] = '';
				
				for ($i = 1; $i <= 36; $i++){
					if(isset($_POST['doc'.$i])){
						if($_POST['doc'.$i.'_numero'] == '')
							$numero = 'SIGPOD';
						else
							$numero = $_POST['doc'.$i.'_numero'];
							
						$html->content[1] .= salvaDados(
							array(
								'tipoDocCad' => 'dgen',
								'camposBusca' => $dgen[0]['campoBusca'],
								'id' => '0',
								'action' => 'cad',
								'camposGerais' => $camposGerais,
								'_tipoDocGen' => $tiposDoc[$i]['nome'],
								'_unOrg' => $empresa->get('nome'),
								'_numero_dgen' => $numero,
								'_anoE' => $_POST['doc'.$i.'_ano'],
								'assunto' => $_POST['doc'.$i.'_assunto'],
								'solicNome' => $empresa->get('nome'),
								'solicEmail' => $empresa->get('email'),
								'solicDepto' => '',
								'solicRamal' => $empresa->get('telefone'),
								'anexos' => $_POST['doc'.$i.'_obs'],
								'unOrgReceb' => '',
								'para' => '',
								'outro' => '',
								'despExt' => '',
								'despacho' => ''
							)
							, $bd, $id_docGen
						);

						if($empreend[0]['id'] > 0)
							atribEmpreend($id_docGen, $empreend[0]['id'], $bd);
					}
				}		
				
			} else {
				//print 'tqet';
				$html->content[1] = salvaDados($_POST,$bd);
							
			}
			
			
			
/*BM*/	}elseif ($_GET['acao'] == "busca_mini") {
			//verifica permissao para realizar acao de buscar
			if(! checkPermission(1)){
				showError(12);
			}
			//rotina para gerar tela de busca de documentos (nova janela)
			//define a acao a ser tomada quando o usuario clica no link, se nao houver nenhuma explicita, considera que eh para mostrar detalhes do documento
			if(!isset($_GET['onclick'])) $_GET['onclick'] = 'ver';
			//rotina para definir template, caminho, titulo, nome de usuario.
			$html->setTemplate($conf["template_mini"]);
			$html->path = showNavBar(array(array("url" => "","name" => "Adicionar Documento")));
			$html->head .= '<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
							<script type="text/javascript" src="scripts/busca_doc2.js?r={$randNum}"></script>
							<!--<script type="text/javascript" src="scripts/jquery.autocomplete.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />-->
							<script type="text/javascript" src="scripts/jquery.tools.min.js?r={$randNum}"></script>';
			$html->title .= "SGD : Adicionar Documento";
			//gera javascript para ocultar os divs nao utilizados
			$html->content[1] = '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();$(".boxLeft").css("width","0");$(".boxRight").css("width","100%");';
			if (isset($_GET['onclick']) && $_GET['onclick'] == 'referenciar') { 
				$html->content[1] .= '$("input:checkbox[name=tipoDoc]").filter("[id=pr]").attr("checked", true);';
				$html->content[1] .= '$("input:checkbox[name=tipoDoc]").filter("[id!=pr]").attr("disabled", true);';
				$html->content[1] .= 'carregaCampos();';
			}
			elseif (isset($_GET['target']) && $_GET['target'] == 'ofir') {
				$html->content[1] .= '$("input:checkbox[name=tipoDoc]").filter("[id=ofe]").attr("checked", true);';
				$html->content[1] .= '$("input:checkbox[name=tipoDoc]").filter("[id!=ofe]").attr("disabled", true);';
				$html->content[1] .= 'carregaCampos();';
			}
			$html->content[1] .= '});</script>';
			//gera formulario de busca de documentos na area 1
			$html->content[1] .= showBuscaForm($_GET['onclick']);
			//gera botao de buscar novamente na area 2
			//$html->content[2] = '<center><input type="button" onclick="novaBusca()" value="Buscar novamente" /></center>';
			//gera div de resposta na area 3
			//$html->content[3] = '<div id="resBusca" width="100%"></div>';
			
/*BU*/	}elseif ($_GET['acao'] == "buscar") {
			//verifica permissao para realizar acao de buscar
			if(! checkPermission(1)){
				showError(12);
			}
			//rotina para gerar tela de busca de documentos
			//define a acao a ser tomada quando o usuario clica no link, se nao houver nenhuma explicita, considera que eh para mostrar detalhes do documento
			if(!isset($_GET['onclick'])) $_GET['onclick'] = 'ver';
			//rotina para definir template, caminho, titulo, nome de usuario
			$html->setTemplate($conf["template"]);
			$html->path = showNavBar(array(array("url" => "","name" => "Buscar Documento")));
			$html->title .= "SGD : Buscar Documento";
			$html->head .= '<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
							<script type="text/javascript" src="scripts/busca_doc2.js?r={$randNum}"></script>
							<!--<script type="text/javascript" src="scripts/jquery.autocomplete.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />-->
							<script type="text/javascript" src="scripts/jquery.tools.min.js?r={$randNum}"></script>
							';
			$html->menu = showMenu($conf['template_menu'],$_SESSION["perm"],2310,$bd);
			//gera formulario de busca na area 1
			$html->content[1] = showBuscaForm($_GET['onclick']);
			//gera novas areas no layour principal
			//$contVisible = array(true,false,false);
			//$html->content[1] .= addContentBox(2,$contVisible);
			//gera botao de buscar novamente na area 2
			//$html->content[2] = '<center><input type="button" onclick="novaBusca()" value="Buscar novamente" /></center>';
			//gera div de resposta na area 3
			//$html->content[3] = '<div id="resBusca" width="100%"></div>';
		
/*DP*/	}elseif ($_GET['acao'] == 'despachar'){
			//cria novo documento com o ID especificado
			$doc = new Documento($_POST['id']);
			$doc->loadTipoData($bd);
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['despAcaoID'])){
				showError(12);
			}
			//rotina para efetuar despacho de um arquivo
			if(!isset($_POST['funcID'])) $_POST['funcID'] = false;
			//rotina para definir template, caminho, titulo, nome de usuario	
			$html->title .= "SGD : Despachar Documento";
			$html->setTemplate($conf["template_mini"]);
			$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c4").hide();$("#c2").hide();$("#c3").hide();$("#c5").hide();});</script>';
			$html->path = showNavBar(array(array("url" => "","name" => "Despachar Documento")),'mini');
			//retira acentos do conteudo do despacho
			//$_POST['despacho'] = SGEncode($_POST['despacho'], ENT_QUOTES);
			//efetua o despacho do documento
			$entrada = false;
			if(!isset($_POST['unOrgReceb'])) $_POST['unOrgReceb'] = '';
            if(!isset($_POST['rrNumReceb'])) $_POST['rrNumReceb'] = '';
            if(!isset($_POST['rrAnoReceb'])) $_POST['rrAnoReceb'] = '';
			if(isset($_GET['entrada']) && $_GET['entrada'] == '1') { $entrada = 1; }
			
			/*montaRelArq($doc->doUploadFiles(),$bd);
			$anexoSalvo = $doc->salvaAnexos();
			if ($anexoSalvo === true) {
				$html->content[1] .= "<br />Arquivos anexados com sucesso.<br />";
			}elseif ($anexoSalvo === false){
				$html->content[1] .= "<br /><b>Erro ao anexar arquivos.</B><br />";
			}*/
			
			$html->content[1] = showDespStatus($doc, array('para' => SGEncode($_POST['para'],ENT_QUOTES, null, false), "outro" => SGEncode($_POST['outro'],ENT_QUOTES, null, false), 'funcID' => SGEncode($_POST['funcID'],ENT_QUOTES, null, false), 'despExt' => SGEncode($_POST['despExt'],ENT_QUOTES, null, false),"despacho" => SGEncode($_POST['despacho'],ENT_QUOTES, null, false),"unOrgReceb" => SGEncode($_POST['unOrgReceb'],ENT_QUOTES, null, false), "rrNumReceb" => SGEncode($_POST['rrNumReceb'],ENT_QUOTES, null, false), "rrAnoReceb" => SGEncode($_POST['rrAnoReceb'],ENT_QUOTES, null, false)),'showFB',$entrada);
			$html->content[1] .= '<br /><a href="sgd.php?acao=ver&docID='.$_POST['id'].'">Voltar para os detalhes do documento.</a>';
			
/*AA*/	}elseif ($_GET['acao'] == 'anexar'){
			//verifica permissão
			if(! checkPermission(13)){
				showError(12);
			}
			//rotina para efetuar despacho de um arquivo
			$doc = new Documento($_POST['id']);
			//inicializacao de variaveis
			$doc->bd = $bd;
			$doc->loadDados();
			//rotina para definir template, caminho, titulo, nome de usuario	
			$html->title .= "SGD : Anexar Arquivo ao Documento";
			$html->setTemplate($conf["template_mini"]);
			$html->menu .= '<script type="text/javascript">$(document).ready(function(){$("#c4").hide();$("#c2").hide();$("#c3").hide();$("#c5").hide();});</script>';
			$html->path = showNavBar(array(array("url" => "","name" => "Anexar Arquivo ao Documento")),'mini');
			//rotina para anexar um arquivo
			//upload do arquivo
			$html->content[1] = montaRelArq($doc->doUploadFiles(),$bd);
			//salva dados no BD
			$anexoSalvo = $doc->salvaAnexos();
			if ($anexoSalvo === true) {
				$html->content[1] .= "<br />Arquivos anexados com sucesso.<br />";
			}elseif ($anexoSalvo === false){
				$html->content[1] .= "<br /><b>Erro ao anexar arquivos.</B><br />";
			}elseif ($anexoSalvo === 0){
				$html->content[1] .= "<br />N&atilde;o h&aacute; arquivo anexado.<br />";
			}
			$html->content[1] .= '<br /><a href="sgd.php?acao=ver&docID='.$_POST['id'].'">Voltar para os detalhes do documento.</a>';
			
/*ND*/	}elseif ($_GET['acao'] == 'novoDocVar') {
			//tipo de documento e action devem estar especificados explicitamente
			if(!isset($_GET['tipoDoc']) || !isset($_GET['action']))
				showError(11);
			//cria novo doc para ler acaoID
			$doc = new Documento(0);
			$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
			$doc->loadTipoData($bd);
			//verifica permissão
			if(! checkPermission($doc->dadosTipo['novoAcaoID'])){
				showError(12);
			}
			//realiza o tratamento das variaveis
			$dados = trataGetVars($_GET,$bd);
			//define template, barra de navegacao, titulo e menu
			$html->setTemplate($conf["template"]);
			$html->path = showNavBar(array(array("url" => "","name" => "Salvar documento")));
			$html->title .= "SGD : Salvar Documento";
			$html->menu = '<script type="text/javascript">$(document).ready(function(){$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();$(".boxLeft").css("width","0");$(".boxRight").css("width","100%");});</script>';
			//salva o documento no BD
			foreach ($dados as $k => $v) {
				$dados[$k] = SGDecode($v);
			}
			$html->content[1] = salvaDados($dados,$bd);
			
		
		} elseif ($_GET['acao'] == 'remontarDoc') {
			if(!isset($_GET['docID'])) {
				$ret = array(array('success' => false, 'errorFeedback' => 'Documento nao especificado'));
			} else {
				$filename = geraPDF($_GET['docID'], time());
				$ret = array(array('success' => true, 'filename' => $filename));
			}
			print json_encode($ret);
			exit();
			
		//} elseif ($_GET['acao'] == 'geraCI' && isset($_GET['id'])) {
			//
		////	geraCI($_GET['id']);
			
		}
		elseif ($_GET['acao'] == 'cadProcSap') { 
			// rotina para cadastrar processo associado a um sap
			// caso nao seja especificado um id de um processo
			if (!isset($_GET['docID'])) {
				$ret = array(array('success' => false, 'errorFeedback' => 'Documento nao especificado'));
			}
			else {
				// verifica permissao
				if (!checkPermission(58)) {
					showError(12);
				}
				$sap = new Documento($_GET['docID']);
				$sap->loadCampos();
				$sap->loadDados();
				// rotina para definir template, caminho, titulo, nome de usuario.
				$html->setTemplate($conf['template_mini']);
				$html->path = showNavBar(array(array("url" => "","name" => "Cadastrar Documento")), "mini");
				$html->title .= "SGD : Cadastrar Documento";
				//menu principal
				$html->menu = showAcoes($sap);

				// verifica se a SAP j� possui processo
				if ($sap->docPaiID == 0) {
					// gera formulario para cadastro de documento (no caso, processo)
					$novaJanela = 0;
					if (isset($_GET['novaJanela']))
						$novaJanela = 1;
					
					$html->head .= '
								<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
								<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
					';
						
					$restaurar = false;
					if (isset($_GET['restaurar']))
						$restaurar = true;
					
					$html->content[1] = showForm($_GET['acao'],"pr", $novaJanela,$bd,$_GET['docID'],$restaurar);

					// preenche campos a partir do sap
					$html->content[1] .= '<script type="text/javascript">
						$(document).ready(function(){
							$(".hid").slideDown("");
							$("#c2").hide();
							$("#c3").hide();
							$("#c4").hide();
							$("#c5").hide();';
					if (isset($sap->campos['assunto'])) $html->content[1] .= '$("#assunto").val("'.str_replace("\n","",strtoupper(SGDecode($sap->campos['assunto']))).'");';
					$html->content[1] .= '$("#unOrgProc").val("01.07.63.00.00.00 - COORDENADORIA DE PROJETOS E OBRAS (CPO)");';
					if (isset($sap->campos['unOrgIntSAP'])) $html->content[1] .= '$("#unOrgInt").val(convertFromHTML("' .$sap->campos['unOrgIntSAP']. '"));';
					if (isset($sap->campos['tipoProc'])) $html->content[1] .= '$("#tipoProc").find("option[value=\'' .$sap->campos['tipoProc']. '\']").attr("selected", true);';
					if (isset($sap->campos['guardachuva'])) $html->content[1] .= '$("input:radio[name=guardachuva]").filter(\'[value='.$sap->campos['guardachuva'].']\').attr("checked", true);';
					if (isset($sap->campos['referProc']) && $sap->campos['referProc'] != '') $html->content[1] .= 'referDoc(\'Processo '.$sap->campos['referProc'].'\', \'referProc\');';
					$html->content[1] .= '});</script>';
				}
				else {
					// sap já possui processo
					$html->content[1] .= "<b>Erro</b>: Esta Solicita&ccedil;&atilde;o de Abertura de Processo j&aacute; possui processo aberto.<br />";
					$html->content[1] .= '<script type="text/javascript">$(document).ready(function(){$(".hid").slideDown("");$("#c2").hide();$("#c3").hide();$("#c4").hide();$("#c5").hide();});</script>';
				}
				
			}
			
		} elseif ($_GET['acao'] == "resp") {
			// rotina para criacao de nova resposta
			// caso nao seja especificado um id de um doc, retorna erro
			if (!isset($_GET['docID'])) {
				//$ret = array(array('success' => false, 'errorFeedback' => 'Documento nao especificado'));
				showError(11);
			}
			else {
				// verifica permissao
				if (!checkPermission(60)) {
					showError(12);
				}				
				// inclui no cabecalho os scripts para usar o CKEdit
				$html->head .= '<script type="text/javascript" src="ckeditor/ckeditor.js?r={$randNum}"></script>';
				// rotina para novo documento
				// rotina para definir template, caminho, titulo, nome de usuario.
				$html->setTemplate($conf["template_mini"]);
				$html->path = showNavBar(array(array("url" => "","name" => "Novo Documento")),"mini");
				$html->title .= "SGD : Novo Documento";
				// carrega os dados do documento ao qual se vai responder
				$doc = new Documento($_GET['docID']);
				$doc->loadDados();
				$doc->loadCampos();
				
				// menu principal
				$html->menu = showAcoes($doc);
				$html->content[1] = '<script type="text/javascript">$(document).ready(function() {
						$("#c2").hide();
						$("#c3").hide();
						$("#c4").hide();
						$("#c5").hide();
			    	});</script>';
				
				// verifica se este doc pode receber resposta
				$tipoDoc = getDocTipo($doc->dadosTipo['nomeAbrv']);
				if (count($tipoDoc) < 0) {
					showError(5);
				}
				if ($tipoDoc[0]['docResp'] == 0) {
					$html->content[1] .= 'ERRO: Este documento n&atilde;o pode ter uma resposta / informa&ccedil;&atilde;o.';
					$html->showPage();
					return;
				}
				
				if ($doc->anexado == 1) {
					$html->content[1] .= 'Este documento n&atilde;o pode ter resposta pois ele &eacute; anexo de outro documento.';
					$html->showPage();
					return;
				}
				
				// pega a resposta ativa atual, se existir
				$respAtiva = $doc->getRespAtiva();
				if ($respAtiva['podeCriarResp'] == false && ($doc->owner == 0)) { // documento nao pode ter resposta...
					$html->content[1] .= "Este documento n&atilde;o pode ter resposta pois ele est&aacute; fora da CPO.";
					$html->showPage();
					return;
				}
				elseif ($respAtiva['idRespostaAtiva'] > 0) { // documento ja possui resposta ativa
					$html->content[1] .= 'Este documento j&aacute; possu&iacute; uma resposta ativa. Clique <a href="sgd.php?acao=ver&docID='.$respAtiva['idRespostaAtiva'].'&novaJanela=1">aqui</a> para edit&aacute;-la.';
		 			$html->showPage();
					return;
				}
				
				// documento nao possui resposta ativa, entao mostra formulario para criacao de nova resposta.
				//gera formulario de novo documento
				$novaJanela = 0;
				if (isset($_GET['novaJanela']))
					$novaJanela = 1;
				
				$html->head .= '
								<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
								<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
				';
				
				$restaurar = false;
				if (isset($_GET['restaurar']))
					$restaurar = true;
				
				$html->content[1] .= showForm("novo","resp", $novaJanela,$bd, null, $restaurar);
				$link = "<a onclick=\"window.open('sgd.php?acao=ver&docID=".$doc->id."','doc','width='+screen.width*".$conf["newWindowWidth"]."+',height='+screen.height*".$conf["newWindowHeight"]."+',scrollbars=yes,resizable=yes').focus()\">".$doc->dadosTipo['nome']." ".$doc->numeroComp.'</a>';
				$html->content[1] = str_replace('{$docResposta}', $link, $html->content[1]);
				$html->content[1] .= '<script type="text/javascript">$(document).ready(function() {
					$("#docResp").val("' .$_GET['docID']. '");
					$("#assunto").val("' .str_replace("\n","",str_ireplace(array('"',"'"), array('\"','\\\''), mb_strtoupper(SGDecode($doc->campos['assunto'])))). '");
			    });</script>';
				//$("#label_despacho").html("<b>Instruir:</b>");
			}
		} elseif ($_GET['acao'] == "visualizar") {
			// Rotina para gerar visualizacao de um doc antes de salvar o documento no bd (tanto para cad/novo como para edicao)
			if (!isset($_GET['tipoDoc']) && !isset($_GET['docID'])) {
				// info insuficiente para esta acao
				showError(11);
			}
			
			// verifica se tipoDoc esta setado: no caso, a visualizacao eh de um documento que nao esta
			// cadastrado no sistema ainda
			if (isset($_GET['tipoDoc'])) {
				$doc = new Documento(0);
				$doc->dadosTipo['nomeAbrv'] = $_GET['tipoDoc'];
				$doc->loadTipoData($bd);
			}
			else { // caso tipoDoc nao esteja setado, a visualizacao eh de edicao
				$doc = new Documento($_GET['docID']);
				$doc->loadCampos();
			}
			
			// carrega os campos do doc a ser visualizado
			foreach ($_POST as $campo => $val) {
				if ($campo == 'acao' || $campo == 'tipoDoc' || $campo == 'docID') continue;
				
				$doc->campos[$campo] = ($val);
			}
			$doc->data = time();
			// chama geraPDF em modo de visualizacao (3o parametro)
			//var_dump($_POST);
			print(geraPDF(0, false, true, $doc));
			return;
		} elseif ($_GET['acao'] == 'solDoc') {
			// rotina para solicitar um documento
			if (!isset($_GET['docID'])) {
				showError(11);
			}
			$doc = new Documento($_GET['docID']);
			$doc->loadDados();
			$html->menu = showAcoes($doc);
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes"), array("url" => "","name" => "Solicitar Documento")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->content[1] = showSolicDocForm($doc, $_SESSION['area']);
			$html->content[1] .= '<script type="text/javascript">$(document).ready(function() { $("#c2").hide(); $("#c3").hide(); $("#c4").hide(); $("#c5").hide();	});</script>';
		
		} elseif ($_GET['acao'] == 'solDocConf'){
			if (!isset($_GET['docID']) || !isset($_POST['motivo_req'])) {
				showError(11);
			}
			$doc = new Documento($_GET['docID']);
			$doc->loadDados();
			$html->menu = showAcoes($doc);
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes"), array("url" => "","name" => "Solicitar Documento")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->content[1] = showSolicDoc($doc, $_SESSION['area'], $_POST['motivo_req']);
			$html->content[1] .= '<script type="text/javascript">$(document).ready(function() { $("#c2").hide(); $("#c3").hide(); $("#c4").hide(); $("#c5").hide();	});</script>';
		
		}
		elseif ($_GET['acao'] == 'solArq') {
			// rotina para solicitar arquivamento de um documento
			if (!isset($_GET['docID'])) {
				showError(11);
			}
			$doc = new Documento($_GET['docID']);
			$doc->loadDados();
			$html->menu = showAcoes($doc);
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->content[1] = showSolicArq($doc);
			$html->content[1] .= '<script type="text/javascript">$(document).ready(function() { $("#c2").hide(); $("#c3").hide(); $("#c4").hide(); $("#c5").hide();	});</script>';
		
		}  elseif ($_GET['acao'] == 'solDesarq') {
			// rotina para solicitar desarquivamento de um documento
			if (!isset($_GET['docID'])) {
				showError(11);
			}
			$doc = new Documento($_GET['docID']);
			$doc->loadDados();
			$html->menu = showAcoes($doc);
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->content[1] = showSolicDesarq($doc);
			$html->content[1] .= '<script type="text/javascript">$(document).ready(function() { $("#c2").hide(); $("#c3").hide(); $("#c4").hide(); $("#c5").hide();	});</script>';
		}
		
		elseif ($_GET['acao'] == 'parecido') {
			if(!isset($_GET['digito']) || !isset($_GET['tipo']) || !isset($_GET['central']) || !isset($_GET['ano']) || !isset($_GET['unOrgProc'])) {
				print json_encode(array()); exit(); 
			}
			$res = getParecidos(array('digito' => $_GET['digito'], 'tipo' => $_GET['tipo'], 'central' => $_GET['central'], 'ano' => $_GET['ano']), $_GET['unOrgProc'], '1', $bd);
			print json_encode($res);
			exit();
		}
		elseif ($_GET['acao'] == 'getArquivarAcao') {
			// rotina especial para ajax. retorna qual a acao de arquivar
			if (checkPermission(80)) {
				// tem permissao para solicitar desarquivamento, então a acao sera de solicitar desarq.
				print json_encode(array(array('acao' => 'solicArq')));
				exit();
			}
			if (checkPermission(70)) {
				// nao tem permissao para solicitar, só para desarquivar, então a acao sera de desarquivar
				print json_encode(array(array('acao' => "desarquivar")));
				exit();
			}
			print json_encode(array());
			exit();
			
		} elseif($_GET['acao'] == 'preview') {
			$html = '<html>
			<head>
				<title>SiGPOD :: Documentos :: Visualizar Documento</title>
				<script type="text/javascript" src="scripts/pdfobject.js"></script>
				<script type="text/javascript">
				window.onload = function (){
					var myPDF = new PDFObject({ url: "files/temp_pdf/user'.$_SESSION['id'].'_tempfile.pdf" }).embed();
				};
				</script>
			</head>
			 
			<body>
				<p>N&atilde;o h&aacute; plugin para PDF disponivel no seu computador. <a href="files/temp_pdf/user'.$_SESSION['id'].'_tempfile.pdf">Baixe aqui o PDF para abr&iacute;-lo em seu computador.</a></p>
			</body>
			</html>';
			print $html;
			exit();
		}
		elseif ($_GET['acao'] == 'novoITAjax') {
			print showForm("novo","it", 1,$bd);
		}
		elseif ($_GET['acao'] == 'editContrFunc') {
			if (!isset($_POST['docID']) || $_POST['docID'] == '') {
				//$ret = array("success" => false, "errorNo" => 1, "errorFeedback" => "Dados Insuficientes"));
				print("Dados insuficientes.");
				exit();
			}
			else {
				$doc = new Contrato($_POST['docID']);
				$doc->loadCampos();
				
				$ret = $doc->salvaEditFunc($_POST);
			}
			
			$html->setTemplate($conf['template_mini']);
			$html->path = showNavBar(array(array("url" => "","name" => "Detalhes")),'mini');
			$html->title .= "SGD > Detalhes > ".$doc->dadosTipo['nome']." ".$doc->numeroComp;
			$html->head .= '<script type="text/javascript" src="CKEditor/ckeditor.js?r={$randNum}"></script>
							<script type="text/javascript" src="scripts/sgd_mini.js?r={$randNum}"></script>
							<script type="text/javascript" src="scripts/busca_doc2.js?r={$randNum}"></script>
							<!--<script type="text/javascript" src="scripts/jquery.autocomplete.js?r={$randNum}"></script>
							<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />-->
			';
			//completa o espaco de menu com as acoes possiveis para o documento
			$html->menu = showAcoes($doc);
			
			$html->head .= '
				<script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
				<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
			';
			
			//loga a acao do usuario no BD para administracao
			//doLog($_SESSION['username'], "viu detalhes do documento ".$doc->id." (".$doc->dadosTipo['nome']." ".$doc->numeroComp.")", $bd);
				
			$html->content[1] = $doc->showResumo();
			$html->content[3] = $doc->showEmpresaResumo();
				
			//mostra detalhes do doc/emissor e historico. esconde anexar arquivo e despacho
			$html->content[2] = showEmissor($doc);
			//$html->content[4] = showHist($doc);
			$html->content[4] = $doc->showHist();
			$html->menu .= '
			<script type="text/javascript">
				$(document).ready(function() {
					$("#c5").hide();
					
					alert("Dados salvos com sucesso!");
				});
			</script>';
		}
		elseif ($_GET['acao'] == 'desativaFuncAjax') {
			if (!checkPermission(97)) {
				print json_encode(array("success" => false));
				exit();
			}
			if (!isset($_GET['docID']) || $_GET['docID'] == '') {
				print json_encode(array("success" => false));
				exit();
			}
			if (!isset($_GET['crea']) || $_GET['crea'] == '') {
				print json_encode(array("success" => false));
				exit();
			}
			
			$doc = new Contrato($_GET['docID']);
			$doc->loadCampos();
			
			print json_encode($doc->desativaFunc($_GET['crea']));
			exit();
			
		}
		elseif ($_GET['acao'] == 'geraBarcode') {
			if (isset($_GET['code'])) {
				print geraBarcode($_GET['code']);
			}
			exit();
		}
		elseif ($_GET['acao'] == 'geraREP') {
			if (!isset($_GET['docID'])) {
				
			}
			else {
				$doc = new Contrato($_GET['docID']);
				$doc->loadCampos();
				
				if ($doc->dadosTipo['nomeAbrv'] != "contr") {
					print 'Este documento n&atilde;o &eacute; um contrato.';
					exit();
				}
				
				geraREP($doc);
				
				$html = '<html>
				<head>
					<title>SiGPOD :: Documentos :: Visualizar Documento</title>
					<script type="text/javascript" src="scripts/pdfobject.js"></script>
					<script type="text/javascript">
					window.onload = function (){
						var myPDF = new PDFObject({ url: "files/['.$doc->id.']relacao_entrada_protocolo.pdf" }).embed();
					};
					</script>
				</head>
				 
				<body>
					<p>N&atilde;o h&aacute; plugin para PDF disponivel no seu computador. <a href="files/temp_pdf/user'.$_SESSION['id'].'_tempfile.pdf">Baixe aqui o PDF para abr&iacute;-lo em seu computador.</a></p>
				</body>
				</html>';
				print $html;
				exit();
			}
		}
		elseif ($_GET['acao'] == 'despAll') {
			if (!isset($_POST['docs'])) {
				print json_encode(array(array('success' => 'false')));
				exit();
			}
			else {
				if (!checkPermission(56)) {
					print json_encode(array(array('success' => 'false', 'errorFeedback' => 'Voc&ecirc; n&atilde;o possui permiss&atilde;o para despachar documentos.')));
					exit();
				}
				
				$arrayDocs = explode(',', $_POST['docs']);
				
				$erroFB = false;
				$erroDocs = array();
				
				foreach($arrayDocs as $docID) {
					$doc = new Documento($docID);
					$ret = $doc->doDespacha($_SESSION['id'], array('para' => $_POST['para'], "outro" => $_POST['outro'], 'funcID' => $_POST['funcID'], 'despExt' => $_POST['despExt'], "despacho" => $_POST['despacho']));
					
					if (!$ret) {
						$erroFB = true;
						$erroDocs[] = $docID;
					}
				}
				
				if (!$erroFB) {
					if ($_POST['despExt'] != null && $_POST['rr'] == true) {
						// gerar rr
						$dados = 
							array(
								'tipoDocCad' => 'rr',
								'camposBusca' => 'numeroRR,anoE',
								'id' => '0',
								'action' => 'novo',
								'camposGerais' => 'emitenteRR,unOrgDest,docsDesp',
								'emitenteRR' => $_SESSION['id'],
								'unOrgDest' => $_POST['despExt'],
								'docsDesp' => $_POST['docs'],
								'para' => $_POST['para'],
								'outro' => $_POST['outro'],
								'despExt' => $_POST['despExt'],
								'despacho' => $_POST['despacho']
							);
						
						$docRR = 0;
						salvaDados($dados, $bd, $docRR);
						print json_encode(array(array('success' => true, 'rrID' => $docRR)));
						exit();
					}
					
					print json_encode(array(array('success' => true)));
				}
				else {
					print json_encode(array(array('success' => false, 'erroDocs' => $erroDocs)));
				}
				exit();
			}
		}
		elseif ($_GET['acao'] == 'solArqAll') {
			if (!isset($_POST['docs'])) {
				print json_encode(array(array('success' => 'false')));
				exit();
			}
			else {
				if (!checkPermission(79)) {
					print json_encode(array(array('success' => 'false', 'errorFeedback' => 'Voc&ecirc; n&atilde;o possui permiss&atilde;o para solicitar arquivamento de documentos.')));
					exit();
				}
				
				$arrayDocs = explode(',', $_POST['docs']);
				
				foreach($arrayDocs as $docID) {
					$doc = new Documento($docID);				
					
					showSolicArq($doc);
				}
				
				print json_encode(array(array('success' => true)));
				exit();
			}
		}
		elseif ($_GET['acao'] == 'arqAll') {
			if (!isset($_POST['docs'])) {
				print json_encode(array(array('success' => 'false')));
				exit();
			}
			else {
				if (!checkPermission(69)) {
					print json_encode(array(array('success' => 'false', 'errorFeedback' => 'Voc&ecirc; n&atilde;o possui permiss&atilde;o para solicitar arquivamento de documentos.')));
					exit();
				}
				
				$arrayDocs = explode(',', $_POST['docs']);
				
				foreach($arrayDocs as $docID) {
					$doc = new Documento($docID);
					$doc->loadCampos();			
					
					doArquiva($doc);
				}
				
				print json_encode(array(array('success' => true)));
				exit();
			}
		}
		elseif ($_GET['acao'] == 'getProxDiaUtil') {
			if (!isset($_GET['data'])) {
				print json_encode(array(array('success' => false)));
				exit();
			}
			
			$data = Contrato::getProxDiaUtil($_GET['data']);
			if ($data != false) 
				print json_encode(array(array('success' => true, 'data' => $data)));
			else
				print json_encode(array(array('success' => false)));
				
			exit();
			
		}
		elseif($_REQUEST['acao']=='unappendDoc'){
			/**
			 * Solicitacao 002
			 * Requisicao para remover anexo a partir do docFilho!
			 * Como a remoção é a partir do doc filho, entao buscamos pelos atributos do pai 
			 * no banco de dados( para saber qual tabela(s) temos associada(s) )
			 * fazendo ligacao do .js com .php
			 */
			//verifica permissão
// 			if(! checkPermission(13)){
// 				showError(12);
// 			}
			$doc = new Documento($_REQUEST['id']);
			$doc->bd = $bd;
			$doc->loadDados();
			$doc->loadCampos();
			if($doc->getDocPai()->removeDocAnexo($_REQUEST['id'],false)){
				$doc->doLogHist($_SESSION['id'], '', '', '', 'desanexE', '', '', $doc->getDocPai()->id);
				$doc->getDocPai()->doLogHist($_SESSION['id'], '',	'', '', 'desanexO', '', '', $_REQUEST['id']);
				echo json_encode(array("success"=>true,"historico"=>$doc->getDocPai()->showHist()));//sucesso
			}
			else
				echo json_encode(array("success"=>false,"error"=>"Erro. Este usuário não tem privilégios suficentes para realizar esta operação."));//algum erro
			$bd->disconnect();
			exit(0);//saida ok
		}
		else {
			//se acao eh invalida, volta para o inicio
			header("Location: index.php");
		}
	} else {
		//se nao ha acao especificado, volta para o inicio
		header("Location: index.php");
	}
	$html->showPage();
	$bd->disconnect();
?>