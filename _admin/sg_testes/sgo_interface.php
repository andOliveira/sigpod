<?php

include_once 'conf.inc.php';

/**
 * cria um iframe para a visualizacao do mapa
 */
function showHomeObrasGmaps() {
    return '
	<a href="sgo.php?acao=cadastrar">Nova obra sem local definido</a><br />
	<iframe src="sgo_map.php?mode=cad" style="min-height: 500px; width: 100%; height: 75%; padding: 0; margin: 0; border: 0;"></iframe>';
}

// cria html do teste formulário
function showTestePaginaHTML() {
    global $conf;

    return '
        <span class="header">Relatório</span>
        <br/>
        <br/>
        <table style="width: 100%;">
         <tr class="c">
            <td class="cc" style="width:150px;">Número do Processo</td>        
            <td class="cc">Nome Empreendimento</td>         
            <td class="cc">Assunto</td>         
         </tr>
            {Campos_tabela}
        </table> 
        <br/>
';
}

/**
 * cria um iframe para a visualizacao do mapa de BUSCA
 */
function showBuscaObrasGmaps() {
    global $conf;
    return '
	<table id="busca" style="width: 100%;">
		<tr>
			<td style="max-width: 50%;">
				<span class="header">Filtrar por:</span>
				<form accept-charset="' . $conf['charset'] . '" id="buscaObraForm">
					<table style="width: 100%">
						<tr class="c"><td class="c" colspan="2">
							<b>Campus:</b> (clique sobre o nome para mudar o mapa)<br />
							<table style="width: 100%">
								<tr>
									<td colspan="2" style="width: 25%; text-align: center;">
										Campinas<br />
									</td>
									<td colspan="2" style="width: 25%; text-align: center;">
										Paul&iacute;nia<br />
									</td>
									<td colspan="2" style="width: 25%; text-align: center;">
										Limeira<br />
									</td>
									<td colspan="2" style="width: 25%; text-align: center;">
										Piracicaba<br />
									</td>
								</tr>
								<tr>
									<td style="width: 10%; text-align: right;">
										<input type="checkbox" class="campus" id="campus_unicamp" name="campus_unicamp" value="unicamp" />
									</td>
									<td style="width: 15%; text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'unicamp\')">Unicamp</a><br />
									</td>
									<td style="width: 10%; text-align: right;">
										<input type="checkbox" class="campus" id="campus_cpqba" name="campus_cpqba" value="cpqba" />
									</td>
									<td style="width: 15%; text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'cpqba\')">CPQBA</a>
									</td>
									<td style="width: 10%; text-align: right;">
										<input type="checkbox" class="campus" id="campus_lim1" name="campus_lim1" value="lim1" />
									</td>
									<td style="width: 15%; text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'lim1\')">Campus 1</a><br />
									</td>
									<td style="width: 10%; text-align: right;">
										<input type="checkbox" class="campus" id="campus_fop" name="campus_fop" value="fop" />
									</td>
									<td style="width: 15%; text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'fop\')">FOP</a><br />
									</td>
								</tr>
								<tr>
									<td style="text-align: right;">
										<input type="checkbox" class="campus" id="campus_cotuca" name="campus_cotuca" value="cotuca" />
									</td>
									<td style="text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'cotuca\')">Cotuca</a><br />
									</td>
									<td style="text-align: right;"></td>
									<td style="text-align: left;"></td>
									<td style="text-align: right;">
										<input type="checkbox" class="campus" id="campus_fca" name="campus_fca" value="fca" />
									</td>
									<td style="text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'fca\')">FCA</a><br />
									</td>
									<td style="text-align: right;">
										<input type="checkbox" class="campus" id="campus_pircentro" name="campus_pircentro" value="pircentro" />
									</td>
									<td style="text-align: left;">
										<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'gmapsRes\').contentWindow.focusCampus(\'pircentro\')">Centro</a><br />
									</td>
								</tr>
							</table>
						</td></tr>
						<tr class="c"><td class="c" colspan="2">
							<b>Nome do Empreendimento ou Obra: </b>
							<input type="text" name="nome" id="nome" size="50" maxlength="200" autocomplete="off" />
						</td></tr>
						<tr class="c"><td class="c" colspan="2">
							<b>Unidade/&Oacute;rg&atilde;o solicitante: </b>
							<input type="text" name="unOrg" id="unOrg" size="60" maxlength="200" />
						</td></tr>
						<tr class="c"><td class="c"  style="width:50%">
							<b>Caracter&iacute;stica: </b><br />
							{$caract_checkbox}
						</td>
						<td class="c" style="width:50%">
							<b>Tipo: </b><br />
							{$tipo_checkbox}
						</td>
						</tr>
						<tr class="c"><td class="c" style="width:50%">
							<b>&Aacute;rea: </b> <br />
							<input type="checkbox" class="area" name="area1" id="area1" value="1" /> At&eacute; {$a1} m<sup>2</sup><br />
							<input type="checkbox" class="area" name="area2" id="area2" value="2" /> De {$a2} a {$a3} m<sup>2</sup><br />
							<input type="checkbox" class="area" name="area3" id="area3" value="3" /> Acima de {$a4} m<sup>2</sup><br />
							<input type="checkbox" class="area" name="area0" id="area0" value="0" /> N&atilde;o informado.
							<input type="hidden" id="a1" value="{$a1}" />
							<input type="hidden" id="a2" value="{$a2}" />
							<input type="hidden" id="a3" value="{$a3}" />
							<input type="hidden" id="a4" value="{$a4}" />
						</td>
						<td class="c" style="width:50%">
							<b>Pavimentos: </b><br />
							<input type="checkbox" class="pav" name="pav0"  id="pav0" value="1" /> T&eacute;rreo<br />
							<input type="checkbox" class="pav" name="pav1"  id="pav1" value="2" /> 1 ou 2 pavimentos<br />
							<input type="checkbox" class="pav" name="pav2"  id="pav2" value="3" /> Acima de 2 pavimentos<br />
							<input type="checkbox" class="pav" name="pavNF" id="pavNF" value="0" /> N&atilde;o informado.
						</td></tr>
						<tr class="c"><td class="c">
							<b>Elevador: </b>
							<input type="checkbox" class="elev" name="elevador1" id="elevador1" value="1" /> Com elevador
						</td><td class="c">
							<input type="checkbox" class="elev" name="elevador0" id="elevador0" value="0" /> Sem elevador
						</td></tr>
						<tr class="c"><td class="c"  style="width:50%">
							<b>Recursos: </b><br />
							<input type="checkbox" class="rec" name="rec1" id="rec1" value="1" /> Custo de at&eacute; R$ {$r1}<br />
							<input type="checkbox" class="rec" name="rec2" id="rec2" value="2" /> Custo entre R$ {$r2} e R$ {$r3}<br />
							<input type="checkbox" class="rec" name="rec3" id="rec3" value="3" /> Custo maior que R$ {$r4}<br />
							<input type="checkbox" class="rec" name="rec0" id="rec0" value="0" /> Sem custo definido<br />
						</td><td class="c"  style="width:50%">
							<input type="checkbox" name="todosRec" class="todos_rec" id="todosRec" value="1" /> Com todos os recursos garantidos<br />
							<input type="checkbox" name="pendRec" class="todos_rec" id="pendRec" value="0" /> Com recursos pendentes
							<input type="hidden" id="r1" value="{$r1}" />
							<input type="hidden" id="r2" value="{$r2}" />
							<input type="hidden" id="r3" value="{$r3}" />
							<input type="hidden" id="r4" value="{$r4}" />
						</td></tr>
						<tr class="c">
						<td class="c" style="text-align: center;" colspan="2">
							<input type="submit" value="Filtrar" />
						</td></tr>
					</table>
				</form>
			</td>
			<td style="width: 50%; min-width:550px;">
				<table style="width: 100%"><tr><td>Mostrar: <a href="javascript:void(0)" id="show_map" style="text-decoration: underline;" onclick="showMap()">mapa</a> <a href="javascript:void(0)" id="show_list" onclick="showList()">lista</a></td><td align="right" id="numRes"></td></tr></table>
				<div style="position: relative; max-height: 610px;">
					<iframe id="gmapsRes" src="sgo_map.php?mode=bus" scrolling="no" style="height: 95%; width: 100%; min-height: 500px; padding: 0; margin: 0; border: 0;  overflow-y: hidden; position: absolute;"></iframe>
					<div id="listaRes" style="display:none; height:610px; width: 100%; overflow-y: auto; position: absolute; max-height: 550px">
					</div>
					<div id="divLoading" style="display: none; height: 610px; width: 700px; overflow-y: auto; z-index: 10000; position: absolute; opacity: 0.8;">
						<center><img src="img/carregando.gif"></center>
					</div>
				</div>
			</td>
		</tr>
	</table>';
}

/**
 * Gera o feedback do cadastro de obras
 * @param array $fb
 */
function verObraFeedback($fb, $msg = null) {
    global $conf;
    //caso o feedback seja positivo, gera mensagem pertinente
    $data = '';
    if ($fb['success']) {
        $text = "A&ccedil;&atilde;o efetuada com sucesso!";
        if ($msg == 'cad') {
            $text .= '<br />
			C&oacute;digo da Obra: {$cod_obra}<br />
			Nome da Obra: {$nome_obra}</a><br />
			Unidade/&Oacute;rg&atilde;o: {$unOrg_obra}<br />
			';
            $data = '';
        } elseif ($msg == 'cadEmpr') {
            $text .= '<br />Empreendimento <b>{$empreend_nome}</b> cadastrado com sucesso!<br />';
            $text .= 'Clique <a onclick="window.open(\'sgo.php?acao=verEmpreend&amp;empreendID={$empreend_id}\',\'obra\',\'width=\'+screen.width*0.95+\',height=\'+screen.height*0.9+\',scrollbars=yes,resizable=yes\').focus()">aqui</a> visualizar o empreendimento.';
            $text .= '<script type="text/javascript">$(document).ready(function() { $("#feedback").removeAttr("title"); });</script>';
            $data = '';
        } elseif ($msg == 'slvEmpr') {
            $text .= '<br /><br />Empreendimento <b>{$empreend_nome}</b> editado com sucesso!';
            $data = '';
        } elseif (isset($msg)) {
            $text .= $msg;
            $data = '';
        }
    } else {
        //caso tenha ocorrido um erro, mostra a mensagem de erro ocorrida
        $text = "Ocorreu um erro ao efetuar essa a&ccedil;&atilde;o.<br /> Descri&ccedil;&atilde;o:
		<b>{$fb['errorFeedback']}</b>
		<br />
		<span style=\"text-align:center\"><a href=\"javascript:history.go(-1);\">Voltar</a></span>";
        $data = '';
    }

    $html = '<div id="feedback" title="Alerta">' . $text . '</div>' . $data;

    return $html;
}

function showEmpreendResumoTemplate() {
    return array('template' => '
	<table style="border-width: 0; width: 100%;">
		<tr id="empreendResumo">
			<td width="60%">
				<table style="border-width: 0;" width="100%">
					<tr colspan="4">
						<td class="c" colspan="4"><!-- filler --></td>
					</tr>
					<tr colspan="4">
						<td class="c" colspan="2"><b>Nome do Empreendimento:</b></td>
						<td class="c" colspan="2">{$nome_empreend}</td>
					</tr>
					<tr colspan="4">
						<td class="c" colspan="2"><b>Solicitante:</b></td>
						<td class="c" colspan="2">{$solicitante_nome_empreend}</td>
					</tr>
					<tr colspan="4">
						<td class="c" colspan="2"><b>Unidade/&Oacute;rg&atilde;o:</b></td>
						<td class="c" colspan="2">{$unorg_empreend}</td>
					</tr>
					<tr colspan="4">
						<td class="c"><b>Ramal:</b></td>
						<td class="c">{$solicitante_ramal_empreend}</td>
						<td class="c"><b>Email:</b></td>
						<td class="c">{$solicitante_email_empreend}</td>
					</tr>
					<tr colspan="4">
						<td class="c" colspan="2"><b>Estado do Empreendimento:</b></td>
						<td class="c" colspan="2">{$empreend_estado}</td>
					</tr>
					{$tr_editEmpreendLink}
				</table>
			</td>
			<td>
				<table style="border-width: 0;" width="100%">
					<tr>
						<td class="c" colspan="2"><!-- filler2 --></td>
					</tr>
					<tr>
						<td class="c"><b>Respons&aacute;vel:</b></td>
						<td class="c">{$responsavel}</td>
					</tr>
					<tr>
						<td class="c" colspan="2">{$equipe}</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><center><a onclick="toggleResumo()">[ocultar/mostrar detalhes do Empreendimento]</a></center></td>
		</tr>
		<tr>
			<td class="c" colspan="2">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Resumo</a></li>
					<!--<li><a href="sgo.php?acao=verPlan&empreendID={$empreendID}{$procIT}">Planejamento</a></li>-->
					<li><a href="#tabs-2">Planejamento</a></li>
					{$tabs_obras}
				</ul>
				<div id="tabs-1">
					<b>Informa&ccedil;&otilde;es sobre as obras:</b>
					{$info_obras}
				</div>
				<div id="tabs-2" style="overflow: auto;">
					{$tab_plan}
				</div>
			</div>
			</td>
		</tr>
	</table>
	<style type="text/css">
		.ui-tabs-nav { margin: 1px; }
	</style>
	',
        'tr_editEmpreendLink' => '
	<tr colspan="4">
		<td class="c" colspan="4"><a href="sgo.php?acao=editEmpreend&empreendID={$empreendID}">[Editar Empreendimento]</a></td>
	</tr>'
    );
}

function obraActionMenu() {
    return array('estrutura' => '
	<div class="boxLeftMenu">	
		{$acoes}
	</div>',
        'acoes' => array(9 => '<a id="editObraDetLink" class="menuHeader" href="sgo.php?acao=edit&obraID={$obraID}&amp;empreendID={$empreendID}" >
							Editar detalhes da Obra
							</a>',
            'voltar' => '<a id="verObraLink" class="menuHeader" href="sgo.php?acao=ver&obraID={$obraID}" >
							Ver dados da Obra
							</a>',
            'salvar' => ''
        )
    );
}

function empreendActionMenu() {
    return array('estrutura' => '
	<div class="boxLeftMenu">	
		{$acoes}
	</div>',
        'acoes' => array(8 => '<a id="editObraDetLink" class="menuHeader" href="sgo.php?acao=newObra&amp;empreendID={$empreendID}" >
							Adicionar obra
							</a>',
            7 => '<a id="editObraDetLink" class="menuHeader" href="sgo.php?acao=editEmpreend&amp;empreendID={$empreendID}" >
							Editar Empreendimento
							</a>',
            'voltar' => '<a id="verObraLink" class="menuHeader" href="sgo.php?acao=verEmpreend&empreendID={$empreendID}" >
							Voltar para detalhes do Empreendimento
							</a>')
    );
}

function showEmpreendTopMenuTemplate() {
    return '
	<table style="border-width: 0;" width="100%">
		<tr>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verEmpreend" class="menu_link" id="resumo_link">Resumo</a></td>
			
                        <td style="text-align: center;" class="topMenu">
                            <a href="sgo.php?empreendID={$empreendID}&acao=verDocsPend" class="menu_link" id="docs_link">
                                Documentos Associados
                            </a>
                        </td>
                        
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verFinancas" class="menu_link" id="recursos_link">Finan&ccedil;as</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verLivroObra" class="menu_link" id="livro_link">Livro de Obra</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verQuestionamentos" class="menu_link" id="questoes_link">Questionamentos</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verContratos" class="menu_link" id="contratos_link">Contratos</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verMedicoes" class="menu_link" id="medicoes_link">Medi&ccedil;&otilde;es</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verMensagens" class="menu_link" id="mensagens_link">Mensagens</a></td>
			<td style="text-align: center;" class="topMenu"><a href="sgo.php?empreendID={$empreendID}&acao=verHistorico" class="menu_link" id="historico_link">Hist&oacute;rico</a></td>
		</tr>
	</table>';
}

function showEmpreendObrasTemplate() {
    return array('template' => '
		<table style="border-width: 0;" width="100%">
		<tr>
			<td class="c" colspan="9"><!-- filler --></td>
		</tr>
		<tr>
			<td class="c" style="text-align: center; font-weight: bold;">Nome da Obra</td>
			<td class="c" style="text-align: center; font-weight: bold;">Respons&aacute;vel</td>
			<td class="c" style="text-align: center; font-weight: bold;">Etapa</td>
			<td class="c" style="text-align: center; font-weight: bold;">Fase</td>
			<td class="c" style="text-align: center; font-weight: bold;">Estado</td>
			<td class="c" style="text-align: center; font-weight: bold;">Observa&ccedil;&otilde;es</td>
		</tr>
		{$obra_tr}
	</table>
	', 'obra_tr' => '
	<tr class="c">
		<td class="c" style="text-align: center;"><a id="linkObraTab_{$obra_id}" onclick="javascript:$(\'#tabs\').tabs(\'select\', {$obra_index});">{$obra_nome}</a></td>
		<td class="c" style="text-align: center;">{$obra_resp}</td>
		<td class="c" style="text-align: center;">{$obra_etapa}</td>
		<td class="c" style="text-align: center;">{$obra_fase}</td>
		<td class="c" style="text-align: center;">{$obra_estado}</td>
		<td class="c" style="text-align: center;">{$obra_obs}</td>
	</tr>
	', 'obra_noTR' => '
	<tr class="c">
		<td class="c" colspan="8" style="text-align: center;"><b>Este empreendimento ainda n&atilde;o possui obras.</b></td>
	</tr>
	');
}

function showObraTopMenu($mini = 0) {
    if ($mini) {
        return '
		<table style="border-width: 0;" width="100%">
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="2">Resumo</a></td></tr>
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="3">Detalhes</a></td></tr>
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="4">Etapas</a></td></tr>
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="5">Finan&ccedil;as</a></td></tr>
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="6">Hist&oacute;rico</a></td></tr>
		</table>';
    }

    return '
	<table style="border-width: 0;" width="100%">
		<tr>
			<td width="20%" style="text-align: center;" class="topMenu"><a href="javascript:void(0)" class="menu_link" id="resumo_link">Resumo</a></td>
			<td width="20%" style="text-align: center;" class="topMenu"><a href="javascript:void(0)" class="menu_link" id="detalhes_link">Detalhes</a></td>
			<td width="20%" style="text-align: center;" class="topMenu"><a href="javascript:void(0)" class="menu_link" id="etapas_link">Etapas</a></td>
			<td width="20%" style="text-align: center;" class="topMenu"><a href="javascript:void(0)" class="menu_link" id="recursos_link">Finan&ccedil;as</a></td>
			<td width="20%" style="text-align: center;" class="topMenu"><a href="javascript:void(0)" class="menu_link" id="historico_link">Hist&oacute;rico</a></td>
		</tr>
	</table>';
}

function showObraResumoTemplate() {
    return array('template' => '
	<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome}</p>
	<table style="border-width: 0;" width="100%">
		<tr>
			<td style="text-align: center; min-width:270px; width:25%;" rowspan="4" class="c">{$img}</td>
			<td style="width: 15%;" class="c"><b>Unidade: </b></td>
			<td style="width: 60%;" class="c">{$unOrg}</td>
		</tr>
		<tr>
			<td style="width: 15%;" class="c"><b>Descri&ccedil;&atilde;o: </b></td>
			<td style="width: 60%;" class="c">{$descricao}</td>
		</tr>
		<tr>
			<td style="width: 15%;" class="c"><b>&Aacute;rea: </b></td>
			<td style="width: 60%;" class="c">{$area}</td>
		</tr>
		<tr>
			<td colspan="2" style="width: 25%; text-align: left;" class="c">{$editar_link}</td>
		</tr>
	</table>

	<p style="text-align: center; font-size: 12pt; font-weight: bold;">Planejamento</p>	
	
	<table style="border-width: 0;" width="100%">
		<tr>
			<td class="c" colspan="3"><!-- filler --></td>
		</tr>
		<tr>
			<td class="c" style="text-align: center; font-weight: bold;">Etapa</td>
			<td class="c" style="text-align: center; font-weight: bold;">Processo</td>
			<td class="c" style="text-align: center; font-weight: bold;">Estado</td>
		</tr>
		{$etapa_tr}
	</table>
	
	<p style="text-align: center; font-size: 12pt; font-weight: bold;">Finan&ccedil;as</p>
	
	<table style="width:100%">
		<tr>
			<td class="c" colspan="2"></td>
		</tr>
		<tr>
			<td class="c"><b>Montante total reservado:</b></td>
			<td class="c"  style="color: #00CC00 "><b>R$ {$total_c}</b></td>
		</tr>
		<!--<tr>
			{$origens_td}
		</tr>-->
		<tr>
			<td class="c"><b>Montante total desembolsado:</b></td>
			<td class="c" style="color: red; "><b>R$ {$total_d}</b></td>
		</tr>
		<tr>
			<td class="c"><b>Balan&ccedil;o:</b></td>
			<td class="c" style="color:{$cor_total}"><b>R$ {$total_geral}</b></td>
		</tr>
		
	</table>
	',
        'editar_link' => '<a class="mini_link" id="7" href="javascript:void(0)">[Editar Obra]</a>',
        'img' => '<img src="img/obras/{$obraCod}/{$imgNome}" alt="{$obraNome}" style="margin: 5px; width: 250px">',
        'etapa_tr' => '
	<tr class="c">
		<td class="c" style="text-align: center;">{$etapa_nome}</td>
		<td class="c" style="text-align: center;">{$etapa_proc}</td>
		<td class="c" style="text-align: center;">{$etapa_estado}</td>
	</tr>');
}

function showObraDetalhesTemplate() {
    return array('template' => '
	<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome}</p>
	<table style="border-width: 0;" width="100%">
	<tr><td class="c" colspan="2"></td></tr>
	<tr class="c"><td class="c"><b>C&oacute;d. Obra:</b></td><td class="c">{$cod}</td></tr>
	<tr class="c"><td class="c"><b>Unidade/&Oacute;rg&atilde;o:</b></td><td class="c">{$unOrg}</td></tr>
	<tr class="c"><td class="c"><b>Caracter&iacute;stica:</b></td><td class="c">{$caract}</td></tr>
	<tr class="c"><td class="c"><b>Tipo:</b></td><td class="c">{$tipo}</td></tr>
	<tr class="c"><td class="c"><b>Local:</b></td><td class="c">{$local}</td></tr>
	<tr class="c"><td class="c"><b>&Aacute;rea:</b></td><td class="c">{$area}</td></tr>
	<tr class="c"><td class="c"><b>Respons&aacute;vel</b></td><td class="c">{$responsavel_nome}</td></tr>
	<!-- <tr class="c"><td class="c"><b>Respons&aacute;vel pela Obra:</b></td><td class="c">{$responsavelObra_nome}</td></tr> -->
	<tr class="c"><td class="c"><b>Estado Atual:</b></td><td class="c"></td></tr>
	<tr class="c"><td class="c"><b>Alterações em materiais com amianto:</b></td><td class="c">{$amianto}</td></tr>
	<tr class="c"><td class="c"><b>Ocupa&ccedil;&atilde;o:</b></td><td class="c">{$ocupacao}</td></tr>
	<tr class="c"><td class="c"><b>Res&iacute;duos:</b></td><td class="c">{$residuos}</td></tr>
	<tr class="c"><td class="c"><b>N&deg; de pavimentos:</b></td><td class="c">{$pavimentos}</td></tr>
	<tr class="c"><td class="c"><b>Elevador:</b></td><td class="c">{$elevador}</td></tr>
	
	<!--<tr class="c"><td class="c"><b></b></td><td class="c"></td>-->
	</table>');
}

function showEmpreendEditFormTemplate() {
    global $conf;
    return array('template' => '
	<form accept-charset="' . $conf['charset'] . '" action="sgo.php?acao=salvarEmpreend&amp;empreendID={$empreendID}" method="post" enctype="multipart/form-data">
	<table style="border-width: 0;" width="100%">
	<tr><td class="c" colspan="2"></td></tr>
	<tr class="c"><td class="c"><b>Nome do Empreendimento:</b></td><td class="c" width="70%">{$nome}</td></tr>
	<tr class="c"><td class="c"><b>Descri&ccedil;&atilde;o:</b></td><td class="c">{$descricao}</td></tr>
	<tr class="c"><td class="c"><b>Local previsto:</b></td><td class="c">{$local}</td></tr>
	<tr class="c"><td class="c"><b>Justificativa:</b></td><td class="c">{$justif}</td></tr>
	<tr class="c"><td class="c"><b>Respons&aacute;vel:</b></td><td class="c">{$responsavel}</td></tr>
	<tr class="c"><td class="c" colspan="2" style="text-align: center"><b>Solicitante</b></td></tr>
	<tr class="c"><td class="c"><b>Unidade/&Oacute;rg&atilde;o:</b></td><td class="c">{$unorg}</td></tr>
	<tr class="c"><td class="c"><b>Nome:</b></td><td class="c">{$nome_solic}</td></tr>
	<tr class="c"><td class="c"><b>Departamento:</b></td><td class="c">{$depto_solic}</td></tr>
	<tr class="c"><td class="c"><b>E-mail:</b></td><td class="c">{$email_solic}</td></tr>
	<tr class="c"><td class="c"><b>Ramal:</b></td><td class="c">{$ramal_solic}</td></tr>
	<tr class="c"><td class="c" colspan="2" style="text-align: center"><input type="submit" value="Enviar" /></td></tr>
	</table>
	</form>
	');
}

function showEquipeFormTemplate() {
    global $conf;
    return array('template' => '
	<span class="header">Editar Equipe</span>
	<script type="text/javascript" src="scripts/sgo_equipe.js?r={$randNum}"></script>
	<form accept-charset="' . $conf['charset'] . '" id="editEquipe" method="post" enctype="multipart/form-data">
	<table style="border-width: 0;" width="100%">
	<tr><td class="c" colspan="2"></td></tr>
	<tr class="c">
		<td class="c" width="50%"><center>
				Membros desta equipe:<br />
				<select name="equipe" id="equipe" multiple="multiple" style="width: 250px; height: 300px;">{$equipe}</select><br /><br />
				<input type="button" id="remover" value="Remover membros &gt;&gt;" /><br />
			</center>
		</td>
		<td class="c" width="50%">
			<center>
				Membros fora desta equipe:<br />
				<select name="usuarios" id="usuarios" multiple="multiple" style="width: 250px; height: 300px;">{$usuarios}</select><br /><br />
				<input type="button" id="adicionar" value="&lt;&lt; Adicionar membros" /><br />
			</center>
		</td>
	</tr>
	<tr class="c"><td class="c" colspan="2" style="text-align: center"><input type="submit" value="Salvar altera&ccedil;&otilde;es" /></td></tr>
	</table>
	</form>
	
	<br/><br /><center><b>Dica:</b> Para selecionar m&uacute;ltiplos usu&aacute;rios, segure a tecla CTRL e clique nos usu&aacute;rios desejados.</center>
	<div id="equipeAlert"></div>
	');
}

function showObraEditFormTemplate() {
    global $conf;
    return array('template' => '
	<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome}</p>
	<form accept-charset="' . $conf['charset'] . '" action="sgo.php?acao=salvar&obraID={$obraID}&empreendID={$empreendID}" method="post" enctype="multipart/form-data">
	<table style="border-width: 0;" width="100%">
	<tr><td class="c" colspan="2"></td></tr>
	<tr class="c"><td class="c"><b>Novo Nome:</b></td><td class="c">{$novo_nome}</td></tr>
	<tr class="c"><td class="c"><b>C&oacute;d. Obra:</b></td><td class="c">{$cod}{$cod_hidden_input}</td></tr>
	<tr class="c"><td class="c"><b>Caracter&iacute;stica:</b></td><td class="c">{$caract}</td></tr>
	<tr class="c"><td class="c"><b>Tipo:</b></td><td class="c">{$tipo}</td></tr>
	<tr class="c">
		<td class="c"><b>Local:</b></td>
		<td class="c">
			{$local}
		</td>
	</tr>
	<tr class="c"><td class="c"><b>Descri&ccedil;&atilde;o:</b></td><td class="c">{$descr}</td></tr>
	<tr class="c"><td class="c"><b>&Aacute;rea:</b></td><td class="c">{$area}</td></tr>
	<tr class="c"><td class="c"><b>Respons&aacute;vel:</b></td><td class="c">{$responsavelProj_nome}</td></tr>
	<!--<tr class="c"><td class="c"><b>Respons&aacute;vel pela Obra:</b></td><td class="c">{$responsavelObra_nome}</td></tr> 
	<tr class="c"><td class="c"><b>Estado Atual:</b></td><td class="c">{$estado}</td></tr>-->
	<tr class="c"><td class="c"><b>Alterações em materiais com amianto:</b></td><td class="c">{$amianto}</td></tr>
	<tr class="c"><td class="c"><b>Ocupa&ccedil;&atilde;o:</b></td><td class="c">{$ocupacao}</td></tr>
	<tr class="c"><td class="c"><b>Res&iacute;duos:</b></td><td class="c">{$residuos}</td></tr>
	<tr class="c"><td class="c"><b>N&deg; de pavimentos:</b></td><td class="c">{$pavimentos}</td></tr>
	<tr class="c"><td class="c"><b>Elevador:</b></td><td class="c">{$elevador}</td></tr>
	<tr class="c"><td class="c"><b>Observa&ccedil;&otilde;es:</b></td><td class="c">{$observacoes}</td></tr>
	
	<tr class="c"><td class="c"><b>Aparecer no Site P&uacute;blico:</b></td><td class="c">{$visivel}</td></tr>
	
	<tr class="c">
		<td class="c"><b>Imagem de exibição:</b></td>
		<td class="c"><input type="radio" id="img_selMt" name="img_sel" value="mantain" checked="checked" /> Manter imagem atual <br />
					  <input type="radio" id="img_selUp" name="img_sel" value="upload" /> Tranferir imagem do computador: {$img}<br />
					  <input type="radio" id="img_selSl" name="img_sel" value="select" disabled="disabled" /> Selecionar foto da galeria (NF)<br />
					  <input type="radio" id="img_selRm" name="img_sel" value="remove" /> Remover imagem de exibi&ccedil;&atilde;o</td></tr>
	<tr class="c"><td class="c" colspan="2" style="text-align:center"><input type="submit" value="Enviar" /></td></tr>
	</form>
	<!--<tr class="c"><td class="c"><b></b></td><td class="c"></td></tr>-->
	</table>');
}

function showRecursosTemplate() {
    return array('template' => '
	<br /><span class="header">Finan&ccedil;as:</span>
	<script type="text/javascript" src="scripts/sgo_recursos.js?r={$randNum}"></script>
	<script type="text/javascript" src="scripts/jquery.ui.datepicker-pt-BR.js"></script>
	<table style="border-width: 0;" width="100%" id="recTable">
	<tr><td class="c" colspan="5"></td></tr>
	<tr>
		<td class="c" width="10%"><b>Montante</b></td>
		<td class="c" width="12%"><b>Origem</b></td>
		<td class="c" width="7%"><b>Prazo</b></td>
		<td class="c" width="45%"><b>Justificativa</b></td>
		<td class="c" width="25%"><b>Modificado por:</b></td>
	</tr>
	{$recurso_tr}
	</table>
	
	<table style="border-width: 0;" width="100%" id="newRecTable">
	<tr>
		<td class="c" width="10%"></td>
		<td class="c" width="12%"></td>
		<td class="c" width="7%"></td>
		<td class="c" width="45%"></td>
		<td class="c" width="25%"></td>
	</tr>
	{$novoRec_tr}
	</table>
	
	{$novoRecLink}
	',
        'novoRec_link' => '<span style="display: block; text-align: right">
						<a href="javascript:void(0)" onclick="javascript:novoRecurso()">Adicionar novo recurso</a>
						</span>',
        'recurso_tr' => '<tr class="c" id="recurso_tr_{$rec_id}">
						<td class="c">R$ <span id="montante_{$rec_id}">{$rec_montante}</span></td>
						<td class="c"><span id="origem_{$rec_id}">{$rec_origem}</span></td>
						<td class="c"><span id="prazo_{$rec_id}">{$rec_prazo}</span></td>
						<td class="c"><span id="justif_{$rec_id}">{$rec_justif}</span></td>
						<td class="c"><span id="responsavel_{$rec_id}">{$rec_mod_user}</span> em <span id="dataModif_{$rec_id}">{$rec_mod_data}</span> {$editar_link}</td>
					</tr>',
        'editar_link' => '<a id="editar_{$rec_id}" href="javascript:void(0)" onclick="rec_edit({$rec_id})">[editar]</a>',
        'semRec_tr' => '<tr id="noRecRow"><td class="c" colspan="5" style="text-align:center"><b>Nenhum recurso encontrado.</b></td></tr>',
        'novoRec_tr' => '<tr id="novoRecRow" style="display:none" class="c">
						<td class="c"><input type="text" id="novoRec_montante" size="10" /></td>
						<td class="c"><input type="text" id="novoRec_origem" size="20" /></td>
						<td class="c"><input type="text" class="datepicker" id="novoRec_prazo"  size="10" /></td>
						<td class="c"><textarea id="novoRec_justif" cols="75" rows="5"></textarea></td>
						<td class="c"><a href="javascript:void(0)" onclick="salvaRec({$empreend_id},0)">[Salvar]</a></td>
					</tr>');
}

function showObraEtapasTemplate() {
    return array(
        'template' => '	<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome}</p>
						<table style="border-width: 0;" width="100%" id="etapasTable">
						<tr class="c"><td class="c"></td><td class="c"></td><td class="c"></td></tr>
						<tr class="c"><td class="c"><b>Etapa</b></td><td class="c"><b>Processo</b></td><td class="c"><b>Detalhes</b></td></tr>
						{$etapa_tr}
						</table>',
        'etapa_tr' => '<tr class="c"><td class="c">{$etapa_nome}</td><td class="c">{$etapa_proc}</td><td class="c"><a href="javascript:void(0)" onclick="showEtapaDet({$etapaID})">Ver detalhes</a></td></tr>',
        'semEtapa_tr' => '<tr id="noEtapaRow"><td colspan="3" style="text-align:center"><b>Nenhuma etapa encontrada.</b></td></tr>',
        'addEtapaLink' => '<span id="addRecRow" style="text-align:right; display: block; width: 100%"><a href="javascript:addEtapa(true)" class="addEtapaLink">Adicionar Etapa</a></span>',
        'etapa_det_tr' => '<tr class="c" id="det{$etapaID}" style="display:none;"><td class="c" colspan="3">{$etapa_det}</td></tr>',
        'addEtapaTable' => '<table width="100%" id="addEtapaTable" style="display:none; margin: 5px 15px 5px 5px; border: 1px black solid;">
						<tr>
						<td class="c" colspan="2"><span class="header">Cadastro de Etapa</span></td>
						</tr>
						<tr class="c">
						<td class="c" width="35%"><b>Tipo de Etapa: </b></td>
						<td class="c" width="65%">{$tipoEtapa}*</td>
						</tr>
						<tr class="c">
						<td class="c"><b>Respons&aacute;vel: </b></td>
						<td class="c">{$responsavel}*</td>
						</tr>
						<tr class="c">
						<td class="c"><b>Processo: </b></td>
						<td class="c">
							<div id="procEtapaNomes">Nenhum Selecionado</div>
							<input type="hidden" name="procEtapa" id="procEtapa" />
							<a href="javascript:void(0);" id="addProcesso" onclick="escolherDoc(\'procEtapa\')"> Adicionar Processo </a>*
						</td>
						</tr>
						<tr class="c">
						<td><b></b></td>
						<td><input type="button" value="Adicionar" onclick="salvaEtapa(0)" id="salvaEtapa" /></td>
						</tr>
						</table>');
}

/**
 * @deprecated
 */
function showHistoricoTemplate() {
    return array('template' => '
		<table style="border-width: 0;" width="100%" id="recTable">
		<tr>
                    <td class="c" colspan="4">
                    </td>
                </tr>
		<tr class="c">
                    <td class="c" width="20%" style="text-align: center;">
                        <b>Data</b>
                    </td>
                    <td class="c" width="15%" style="text-align: center;">
                        <b>Usu&aacute;rio</b>
                    </td>
                    <td class="c" width="75%" style="text-align: center;">
                        <b>A&ccedil;&atilde;o</b>
                    </td>
                </tr>
		{$tr_entradas}
		</table>',
        'entrada_tr' => '<tr class="c">
                            <td class="c" style="text-align: center;">
                                {$entr_data}
                            </td>
                            <td class="c" style="text-align: center;">
                                {$entr_user}
                            </td>
                            <td class="c">
                                {$entr_texto}
                            </td>
                         </tr>',
        'semEntr_tr' => '<tr id="noRecRow"><td colspan="3" style="text-align:center"><b>Nenhuma entrada encontrada.</b></td></tr>');
}

function showDocumentosTemplate() {
    return array('template' => '
		<script type="text/javascript" src="scripts/jquery.tablesorter.min.js"></script>
		<br /><span class="header">Documentos associados:</span>
		<br /><center>Filtrar por: {$filtros}</center><br /><br />
		<table style="border-width: 0;" width="100%" id="docTable">
		<thead>
			<tr class="c"><th class="c" width="5%" style="text-align: center;"><b>N&deg; Doc.</b></th><th class="c" width="25%" style="text-align: center;"><b>Tipo/N&uacutemero</b></th><th class="c" width="25%" style="text-align: center;"><b>Emitente</b></th><th class="c" width="35%" style="text-align: center;"><b>Assunto</b></th><td class="c" width="10%" style="text-align: center;"></td></tr>
		</thead>
		{$docs_tr}
		</table>
		{$tabelas_obras}
		<script type="text/javascript">
                    $(document).ready(function() {
                        $("#docTable").tablesorter();
				
			$("#docTable").bind("sortEnd", function() {
                            $("#docTable").find("tr").each(function() {
                                if ($(this).hasClass("child")) return true; // equivale a "continue"
						
				var id = $(this).attr("id");
				if (id != undefined && id != "") 
				$(".docfilho"+id).insertAfter($(this));
                            });
			});
				
			$(".docsObras").tablesorter();
				
			$(".docsObras").bind("sortEnd", function() {
                            $(this).find("tr").each(function() {
                            	if ($(this).hasClass("child")) return true; // equivale a "continue"
						
				var id = $(this).attr("id");
				if (id != undefined && id != "") 
				$(".docfilho"+id).insertAfter($(this));
                            });
			});
                    });
		</script>
		',
        'doc_tr' => '<tr class="c" id="{$doc_id}"><td class="c" style="text-align: center;">{$doc_id}</td><td class="c" style="text-align: center;">{$doc_link}{$doc_num}</a></td><td class="c" style="text-align: center;">{$doc_emitente}</td><td class="c" style="text-align: center;">{$doc_assunto}</td><td class="c" style="text-align: center;">{$link_mostra_filho}</td></tr>',
        'docfilho_tr' => '<tr class="c child docfilho{$docpai_id} hide" style="display:none"><td class="c" style="text-align: center;">{$doc_id}</td><td class="c" style="text-align: center;">{$doc_link}{$doc_num}</a></td><td class="c" style="text-align: center;">{$doc_emitente}</td><td class="c" colspan="2" style="text-align: center;">{$doc_assunto}</td></tr>',
        'semDoc_tr' => '<tr id="noRecRow"><td colspan="4" style="text-align:center"><b>Nenhum documento associado.</b></td></tr>',
        'link_mostra_filho' => '<a id="mostra_filho{$doc_id}" href="javascript:mostraFilhos({$doc_id}, \'show\')">Mostrar Filhos</a>',
        'tabela_obras' => '
		<br /><br />
		<span style="width: 100%; text-align: center; font-weight: bold; display: block;">{$obra_nome}</span>
		<table style="border-width: 0;" width="100%" class="docsObras">
			<thead>
				<tr class="c"><th class="c" width="5%" style="text-align: center;"><b>N&deg; Doc.</b></th><th class="c" width="25%" style="text-align: center;"><b>Tipo/N&uacutemero</b></th><th class="c" width="25%" style="text-align: center;"><b>Emitente</b></th><th class="c" width="35%" style="text-align: center;"><b>Assunto</b></th><td class="c" width="10%" style="text-align: center;"></td></tr>
			</thead>
			{$docs_tr_obra}
		</table>'
    );
}

function getDocLink($id, $name = null) {
    global $conf;

    if ($name != null) {
        $name = 'name="' . $name . '"';
    }

    $html = '<a ' . $name . ' href="javascript:void(0)" onclick="window.open({$pag}, {$det}, {$param}).focus()">';
    $link = "'sgd.php?acao=ver&amp;docID=" . $id . "'";
    $detalhe = "'detalhe" . $id . "'";
    $opcoes = "'width='+screen.width*newWinWidth+', height='+screen.height*newWinHeight+', scrollbars=yes, resizable=yes'";
    return str_replace(array('{$pag}', '{$det}', '{$param}'), array($link, $detalhe, $opcoes), $html);
}

function showEmpreendSugerirTemplate() {
    return array('tr' => '<tr class="c"><td class="c"><b>{$empreend_nome}</b> ({$empreend_unOrg_sigla})</td><td class="c" style="min-width:200px; text-align: right; vertical-align:middle;"><a id="link_{$empreendID}" href="javascript:void(0)" onclick="atribEmpreend({$empreendID},\'{$empreend_nome}\',false)">Atribuir a este empreendimento</a></td></tr>');
}

/**
 * gera o formulario para o cadastro de obras colocando ou nao as coordenadas lidas
 * @param array $pos
 */
function showEmpreendCadForm($pos) {
    global $conf;
    $html = '
            <h3>
                Cadastro de Empreendimento
            </h3>
            <form accept-charset="' . $conf['charset'] . '" action="sgo.php?acao=salvarNova" method="post" id="cadNovaObra">
                <table style="border: 0; width:100%" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                           <td class="c" colspan="3">
                           </td>
                        </tr>
                        <tr class="c" id="passo1">
                            <td class="c">
                                <b>1.</b>
                            </td>
                            <td class="c">
                                <b>Of&iacute;cio de Requisi&ccedil;&atilde;o:</b>
                            </td>
                            <td class="c">
                                <div id="caixaAviso" style="border: 1px red solid; text-align: center; color: red; font-weight:bold; background-color: yellow; padding: 5px; display: none;">
                                </div>
				<input type="hidden" name="ofir" id="ofir" value="{$ofirID}"/>
				<div id="ofirNomes">
                                    {$ofirNome}
                                </div>
				<br />
				<div id="ofirLink">
                                    <a href="javascript:void(0);" onclick="newDocInNewWindow(\'ofe\',\'ofir\',\'cad\');">Cadastrar Of&iacute;cio de Requisi&ccedil;&atilde;o</a> ou <a href="javascript:void(0);" onclick="escolherDoc(\'ofir\');">Usar documento j&aacute; cadastrado</a>
				</div>
                            </td>
			</tr>
			<tr class="c" id="passo2">
                            <td class="c">
                                <b>2.</b>
                            </td>
                            <td class="c">
                                <b>Identifica&ccedil;&atilde;o do solicitante:</b>
                            </td>
			    <td class="c">
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tbody>
                                        <tr class="c">
                                            <td class="c" width="40%">
                                                <b>Unidade/&Oacute;rg&atilde;o:</b>
                                                <br />
                                                (a Unidade deve estar no formato da tabela de c&oacute;digos da Unicamp)
                                            </td>
                                            <td class="c">
                                                <input type="text" class="cadObra" id="unOrgInput" name="unOrgInput" size="85" maxlength="250" value="{$unOrgSolic}" style="{$estilo}"><input type="hidden" class="obrigatorio cadObra" name="solicUnOrg" id="unOrgSolic" value="{$unOrgSolic}" style="{$estilo}" />*
                                            </td>
					</tr>
					<tr class="c">
                                            <td class="c" width="40%">
                                                <b>
                                                  Nome:
                                                </b>
                                            </td>
                                            <td class="c">
                                                <input type="text" class="cadObra" name="solicNome" id="solicNome" size="85" maxlength="250" value="{$nomeSolic}"  style="{$estilo}" />
                                            </td>
					</tr>
					<tr class="c">
                                            <td class="c">
                                               <b>Departamento:</b>
                                            </td>
                                            <td class="c">
                                               <input type="text" class="cadObra" name="solicDepto" id="solicDepto" size="85" maxlength="50" value="{$deptoSolic}"  style="{$estilo}" />
                                            </td>
					</tr>
					<tr class="c">
                                            <td class="c">
                                               <b>E-mail:</b>
                                            </td>
                                            <td class="c">
                                               <input type="text" class="cadObra" name="solicEmail" id="solicEmail" size="85" maxlength="100" value="{$emailSolic}"  style="{$estilo}" />
                                            </td>
					</tr>	
					<tr class="c">
                                            <td class="c">
                                                <b>Ramal:</b>
                                            </td>
                                            <td class="c">
                                                <input type="text" class="cadObra int" name="solicRamal" id="solicRamal" size="5" maxlength="10" value="{$ramalSolic}"  style="{$estilo}" />
                                            </td>
					</tr>
                                    </tbody>
				</table>
                            </td>
			</tr>
			<tr class="c" id="passo3">
                            <td class="c">
                                <b>3.</b>
                            </td>
                            <td class="c">
                                <b>Informa&ccedil;&otilde;es da Obra:</b>
                            </td>
                            <td class="c">
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tbody>
                                        <tr class="c">
                                            <td class="c" width= "40%">
                                                <b>Nome do Empreendimento</b>
                                            </td>
                                            <td class="c">
                                            	<input type="text" class="obrigatorio" name="nome" id="nome" size="85" maxlength="250" autocomplete="off"/>*
                                                <div id="sugestoesObra" style="padding: 3px; margin: 2px; border: 1px #BE1010 solid; display: none;">
                                                </div>
                                            </td>
					</tr>
					<tr class="c">
                                            <td class="c" width= "40%">
                                                <b>Breve Descri&ccedil;&atilde;o</b>
                                            </td>
                                            <td class="c">
                                                {$textarea_descr}
                                            </td>
					</tr>							
					<tr class="c">
                                            <td class="c">
                                                <b>Local previsto:</b>
                                            </td>
                                            <td class="c">
                                                {$input_local}
                                            </td>
					</tr>
					<tr class="c">
                                            <td class="c">
                                                <b>Justificativa da necessidade:</b>
                                            </td>
                                            <td class="c">
                                                {$textarea_justif}
                                            </td>
					</tr>
                                        <tr class="c">
                                            <td class="c">
                                                <b>Respons&aacute;vel:</b>
                                            </td>
                                            <td class="c">
                                                {$responsavel}
                                            </td>
					</tr>
					<!-- <tr class="c">
						<td class="c">
                                                    <b>Tipo de Obra</b>
                                                </td>
						<td class="c">
                                                    {$select_tipo} *
						</td>
                                            </tr>
                                            <tr class="c">
						<td class="c">
                                                    <b>Caracter&iacute;stica de Obra</b>
                                                </td>
						<td class="c">
                                                    {$select_caract} *
						</td>
                                            </tr>
                                            <tr class="c">
						<td class="c">
                                                    <b>Breve descri&ccedil;&atilde;o da obra</b>
                                                </td>
						<td class="c">
                                                    <textarea id="descricao" name="descricao" rows="3" cols="20" style="width: 85%">
                                                    </textarea>
						</td>
                                            </tr>
                                            <tr class="c">
                                            	<td class="c">
                                                    <b>&Aacute;rea aproximada da interven&ccedil;&atilde;o</b>
                                                </td>
						<td class="c">
                                                    <input class="float" type="text" id="dimensao" name="dimensao" id="dimensao" size="10" maxlength="10" />
                                                    <select id="dimensaoUn" name="dimensaoUn" style="text-align: right; background-color: #DDFFDD">
                                                        <option value=""> -- Selecione -- </option>
							<option value="m">m</option>
							<option value="m2" selected="selected">m<sup>2</sup></option>
							<option value="m3">m<sup>3</sup></option>
							<option value="kVA">kVA</option>
                                                    </select>
						</td>
								</tr>
								<tr class="c">
									<td class="c"><b>Haver&aacute; altrera&ccedil;&atilde;o em elementos que contenham amianto?</b>(ex: divis&oacute;rias, telhas)</td>
									<td class="c"><input type="radio" id="amianto" name="amianto" value="1" />Sim | <input type="radio" name="amianto" value="0" />N&atilde;o </td>
								</tr>
								<tr class="c">
									<td class="c"><b>Qual ser&aacute; a ocupa&ccedil;&atilde;o e uso do local?</b></td>
									<td class="c"><input type="text" id="ocupacao" name="ocupacao" size="50" maxlength="200" /></td>
								</tr>
								<tr class="c">
									<td class="c"><b>Quais res&iacute;duos ser&atilde;o gerados ap&oacute;s a ocupa&ccedil;&atilde;o do local?</b></td>
									<td class="c"><input type="text" id="residuos" name="residuos" size="50" maxlength="200" /></td>
								</tr>
								<tr class="c">
									<td class="c"><b>Quantidade de pavimentos:</b></td>
									<td class="c"><input class="int" type="text" id="pavimentos" name="pavimentos" size="2" maxlength="2" /></td>
								</tr>
								<tr class="c">
									<td class="c"><b>A obra ter&aacute; elevador?</b></td>
									<td class="c"><input type="radio" name="elevador" value="1" />Sim | <input type="radio" name="elevador" value="0" />N&atilde;o </td>
								</tr> -->
								<!--tr class="c">
									<td class="c" colspan="2"><span style="font-size:12pt; font-weight:bold;">Recursos Financeiros</span></td>
								</tr>
								<tr class="c">
									<td class="c"><b>H&aacute; recursos garantidos?</b></td>
									<td class="c"><input type="radio" id="recursos1" name="recursos" value="1" />Sim | <input type="radio" id="recursos0" name="recursos" value="0" checked="checked" />N&atilde;o </td>
								</tr>
								<tr class="c">
									<td class="c"><b>Montante de recursos garantidos:</b></td>
									<td class="c">R$ <input class="float" type="text" id="montanteRec" name="montanteRec" size="10" maxlength="10" disabled="disabled" /> </td>
								</tr>
								<tr class="c">
									<td class="c"><b>Origem dos recursos:</b></td>
									<td class="c"><input type="text" name="origemRec" id="origemRec" size="30" maxlength="200" disabled="disabled" /> </td>
								</tr>
								<tr class="c">
									<td class="c"><b>Prazo de Conv&ecirc;nios</b></td>
									<td class="c"><input type="text" name="prazoRec" id="prazoRec" size="10" maxlength="10" disabled="disabled" /> (dd/mm/aaaa)</td>
								</tr-->
							</tbody>
						</table>
					</td>
				</tr>
				<!--<tr class="c" id="passo4">
					<td class="c"><b>4.</b></td>
					<td class="c"><b>Localiza&ccedil;&atilde;o da Obra:</b></td>
					<td class="c">
						{$local}
						<!--Lat: <input type="text" id="latObra" name="latObra" size="7" maxlength="18" value="' . $pos['lat'] . '" />&deg; Long: <input type="text" id="lngObra" name="lngObra" size="7" maxlength="18" value="' . $pos['lng'] . '" />&deg;
						<br /><span style="font-size: 9pt;">(use valores negativos para coordenadas Sul/Oeste e <b>ponto</b> como separador decimal)</span></td>--
				</tr>-->
				<tr class="c" id="passo5">
					<td class="c"><b>5.</b></td>
					<td class="c"><b>Solic. Abertura de Processo:</b></td>
					<td class="c">
						<input type="checkbox" id="abrirSAP" name="abrirSAP" value="1" /> Gerar nova Solicita&ccedil;&atilde;o de Abertura de Processo de Planejamento.
					</td>
				</tr>
				<tr>
					<td align="center" colspan="3"><input type="submit" value="Enviar" /></td>
					<td></td>
				</tr>
				<tr>
					<td><b></b></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		</form>';
    return $html;
}

/**
 * Mostra tela do livro de obra para empreendimentos
 * @return $html
 */
function showLivroDeObraTemplate() {
    $html = '<br /><span class="header">Livro de Obra:</span>';

    // TODO: fazer esta tela :P

    return $html;
}

/**
 * Mostra tela de questionamentos para empreendimentos
 * @return $html
 */
function showQuestionamentosTemplate() {
    $html = '<br /><span class="header">Questionamentos:</span>';

    // TODO: fazer esta tela :P

    return $html;
}

/**
 * Mostra tela de contratos para empreendimentos
 * @return $html
 */
function showContratosTemplate() {
    $html = '
	<br />
	<span class="header">Contratos:</span>
	<br />
	{$link_novo_contrato}
	<div id="novoContrato" style="display: none;">{$novo_contrato}</div>
	<br />
	<br />
	<br />
	<table width="100%">
		<tr>
			<th>Nº CPO</th>
			<th>N&uacute;mero Contrato</th>
			<th>Obras</th>
			<th>Tipo Processo</th>
			<th>Processo</th>
			<th>Empresa</th>
			<th>Data Vig&ecirc;ncia</th>
			<th>Data Conclus&atilde;o</th>
		</tr>
		{$tabela_contratos}
	</table>
	';

    return $html;
}

/**
 * Mostra tela de medições para empreendimentos
 * @return $html
 */
function showMedicoesTemplate() {
    $html = '<br /><span class="header">Medi&ccedil;&otilde;es:</span>';

    // TODO: fazer esta tela :P

    return $html;
}

/**
 * Mostra tela de mensagens para empreendimentos
 * @return $html
 */
function showMensagensTemplate() {
    global $conf;
    return array(
        'template' =>
        '<br /><span class="header">Mensagens:</span><br />
	<script type="text/javascript" src="scripts/sgo_msg.js?r={$randNum}"></script>
			  <script type="text/javascript" src="scripts/jquery-ui-1.8.18.custom.min.js?r={$randNum}"></script>
			  <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
	
		{$novaMsg_link}
		
	<div id="mensagens" width="100%">
	{$msg_rows}
	</div>
	<div id="newMsg">
	<form accept-charset="' . $conf['charset'] . '" name="novaMsgForm" id="novaMsgForm" action="sgo.php?acao=novaMsg&amp;empreendID={$empreendID}" method="post" enctype="multipart/form-data">
	<!--input type="hidden" name="empreendID" value="{$empreendID}"-->
	<table><tr><td><b>De</b>:</td><td id="remetente">{$remetente}</td></tr>
	<tr><td><b>Assunto*</b>:</td><td>{$assunto}</td></tr>
	<tr><td><b>Conteudo</b>:</td><td>{$conteudo}</td></tr>
	<tr style="display: none;"><td colspan="2"><input type="hidden" id="replyTo" name="replyTo" value="0"></td></tr>
	<tr><td><b>Anexar Arquivo</b>:</td><td><div id="fileUpCell"><div id="arqs"></div><input type="file" name="arq1" id="arq1" onclick="showInputFile(2)"></div></td></tr>
	</table></form></div>',
        'novaMsg_link' =>
        '<center><a onclick="novaMsg()">Nova Mensagem</a></center><br />'
    );
}

function showEtapaTemplate() {
    $html = array(
        'menu' => '
		<table style="border-width: 0;" width="100%">
			<tbody>
				{$atribuirResponsaveis}
				{$menu_itens}
				<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><br /><br /><br /><br /> </td></tr>
				{$addObra_link}
				<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" onclick="showItSuplementar({$empreendID})">Informa&ccedil;&otilde;es T&eacute;cnicas Suplementares</a></td></tr>
			</tbody>
		</table>',
        'addObra_link' => '<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:addObra({$empreendID}, {$menuObra_id})" class="mini_link" id="{$menuObra_id}">Adicionar Obra</a></td></tr>',
        //'menu_item' => '
        //		<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" id="{$item_id}">{$item_label}</a></td></tr>',

        'menu_item' => '
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" onclick="carregaFase({$empreendID}, {$obraID}, {$etapaTipoID}, {$faseTipoID})">{$item_label}</a></td></tr>',
        'atribuirResponsaveis' => '
			<tr class="c"><td width="20%" style="text-align: center;" class="topMenu c"><a href="javascript:void(0)" class="mini_link" onclick="showResponsaveisEtapa({$empreendID}, {$obraID}, {$etapaTipoID})">Atribuir Respons&aacute;veis</a></td></tr>',
        'divContent' => '
				<div class="boxCont" style="display: none;" id="{$content_id}">
       				{$content}
      			</div>',
        'template' => '
		<div class="container">
			<div class="boxLeftEmpr">
				<div class="boxCont" id="r1">
       				{$menu}
      			</div>
      		</div>
 		   	<div class="boxRightEmpr">
 		   		{$conteudo}
 		   		
 		   		<div class="boxCont" style="display: none;" id="{$obraCont_id}">
       				Tela de Adicionar Obra
      			</div>
    		</div>
		</div>'
    );

    return $html;
}

function showFaseTemplate() {
    global $conf;
    $html = array(
        'template' => '
			<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome_fase}</p>
			<br />
			<form accept-charset="' . $conf['charset'] . '" id="formFase" action="sgo.php?acao=salvaFase&empreendID={$empreendID}&obraID={$obraID}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="etapaID" id="etapaID" value="{$etapa_id}" />
			<input type="hidden" name="etapaTipoID" id="etapaTipoID" value="{$etapa_tipoID}" />
			<input type="hidden" name="faseTipoID" id="faseTipoID" value="{$fase_tipo_id}" />
			{$extra_hidden} 
				<table style="width: 100%;">
					{$campos}
					<tr class="c">
						<td class="c" colspan="4"><center><input type="submit" id="submitFase{$tipoFaseID}" {$estilo} /></center></td>
					</tr>
				</table> 
			</form>
		',
        'campo' => '
			<tr class="c">
				<td class="c" style="width: 20%; vertical-align:middle;"><b>{$campo_nome}</b>: </td>
				<td class="c" style="width: 40%; vertical-align:middle;">{$campo_html}</td>
				<td class="c" style="width: 10%; vertical-align:middle;"><b>Observa&ccedil;&otilde;es</b>: </td>
				<td class="c" style="width: 30%; vertical-align:middle;">{$observacoes}</td>
			</tr>
		',
        'linha_inteira' => '<tr><td colspan="4">{$campo_html}</td></tr>'
    );

    return $html;
}

function showRespEtapaTemplate() {
    global $conf;
    $html = array(
        'template' => '
			<p style="text-align: center; font-size: 14pt; color:#BE1010;">{$nome_etapa}</p>
			<br />
			<form accept-charset="' . $conf['charset'] . '" action="sgo.php?acao=salvaResponsavel&empreendID={$empreendID}&obraID={$obraID}&tipoEtapa={$tipoID}" method="post">
				<table style="width: 100%;">
					{$campos}
					<tr class="c" id="todosSelectTr">
						<td class="c" style="width: 15%;"><b>Atribuir todas as responsabilidades a</b>: </td>
						<td class="c" style="width: 35%;">{$todos_select}</td>
						<td class="c" style="width: 50;" colspan="2"><!-- filler --></td>
					</tr>	
					<tr class="c"><td class="c" colspan="4"><center><input type="submit" id="submitResp" /></center></td></tr>
				</table> 
			</form>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#todosSelect").change(function() {
						var id = $("#todosSelect option:selected").val();
						
						$("select").each(function() {
							$("option[value="+id+"]", this).attr("selected", "selected");
						});
					});
					
					$("select:not(#todosSelect)").change(function() {
						$("option[value=0]", $("#todosSelect")).attr("selected", "selected");
					});
				});
			</script>
		',
        'campo' => '
			<tr class="c">
				<td class="c" style="width: 15%;"><b>{$campo_nome}</b>: </td>
				<td class="c" style="width: 35%;">{$campo_html}</td>
				<td class="c" style="width: 15%;"><b>Estado</b>: </td>
				<td class="c" style="width: 35%;">{$estado}</td>
			</tr>
		'
    );

    return $html;
}

//<tr class="c"><td class="c" colspan="4"><!-- filler --></td></tr>

function showCadContrTemplate() {
    $html = array(
        'template' => '
		<center><h3>Novo Contrato</h3></center>
		<table width="100%">
			<tr class="c">
				<td class="c"><b>Selecione o Processo de Contrata&ccedil;&atilde;o</td>
			</tr>
			{$tabela_processos}
		</table>
		{$div_obras}
		{$div_recursos}
	'
    );

    return $html;
}

function showCampoTabela($id) {
    return
            array('template' => '
				<table style="border: 0; width: 100%">
				<tr class="c">
					<td colspan="4" class="c">
						<b>Locais:</b><br />
				</tr>
				<tr>
					<td colspan="4" class="c">
						<table id="table_locais" style="border: 0; width: 100%">
						<tr class="c">
							<td class="c"><b>Local/Ambiente</b></td>
							<td class="c"><b>Caracter&iacute;stica</b></td>
							<td class="c"><b>Climatiza&ccedil;&atilde;o</b></td>
							<td class="c"><b>Dados/Telefonia</b></td>
							<td class="c"><b>Rede Estabilizada</b></td>
							<td class="c"><b>Rede de Gases</b></td>
							<td class="c"><b>&Aacute;rea (m<sup>2</sup>)</b></td>
							<td class="c"><b>Observa&ccedil;&otilde;es Gerais</b></td>
							<td class="c"><b>Caracter&iacute;sticas Espec&iacute;ficas</b></td>
							<td class="c"></td>
						</tr>
						{$local_tr}
						</table>
					</td>
				</tr>
				{campos}
				<tr class="addLocal" style="display:none;">
					<td colspan="4" class="c">
						<b>Caracter&iacute;sticas Espec&iacute;ficas:</b>
					</td>
				</tr>
				<tr class="addLocal" style="display:none;">
					<td colspan="4" class="c">
						<table style="width:100%" id="caract_especif_table">
						<tr class="header">
							<td class="c" colspan="3"></td>
						</tr>
						<tr class="header">
							<td class="c"><b>Caracter&iacute;stica</b></td>
							<td class="c"><b>Valor</b></td>
							<td class="c"><b>Observa&ccedil;&otilde;es</b></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr class="addLocal" style="display:none;">
					<td colspan="4" class="c">
						<a href="javascript: void(0)" onclick="showAddCaractEspec()">[Editar Caracter&iacute;sticas Espec&iacute;ficas]</a>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="c">
						<a id="addLocalLink" class="addLocalLink" href="javascript:void(0)" onclick="showAddLocal()">[Adicionar Local]</a>
						<span class="cancelarAddLocalLink" style="display:none"> | </span>
						<a id="cancelarAddLocalLink" class="cancelarAddLocalLink" href="javascript:void(0)" onclick="cancelAddLocal()" style="display:none">[Cancelar]</a>
					</td>
				</tr>
				</table>
					
				<div id="div_alerta" style="display:none">
					Selecione os tipos de Caracter&iacute;sticas:
					<table id="caract_epec_cad_table" style="width: 100%">
						<tr>
							<td class="c" colspan="4"></td>
						</tr>
						<tr>
							<td class="c"></td>
							<td class="c"><b>Nome da Caracter&iacute;stica</b></td>
							<td class="c"><b>Dados Adicionais</b></td>
							<td class="c"><b>Observa&ccedil;&otilde;es</b></td>
						</tr>
						{$caract_especif_tr}
				</table>
				</div>
				<input type="hidden" name="local_id" id="local_id" value="" />
				<input type="hidden" name="' . $id . '" id="' . $id . '" value="{$local_json}" />
				', 'campos_tr' => '
				<tr class="addLocal" style="display:none;">
					<td class="c">{$nomeCampo}</td>
					<td class="c">{$htmlCampo}</td>
				</tr>
				', 'caract_especif_option' => '
				<option class="caract_espec_option" value="{$opt_val}">{$opt_label}</option>
				', 'local_tr' => '
				<tr class="c" id="local_{$id_local}">
					<td class="c"><span id="local_{$id_local}_nome">{$local_nome}</span></td>
					<td class="c"><span id="local_{$id_local}_caract">{$local_caract}</span></td>
					<td class="c"><span id="local_{$id_local}_climatiz">{$local_climatiz}</span></td>
					<td class="c"><span id="local_{$id_local}_dados">{$local_dados}</span></td>
					<td class="c"><span id="local_{$id_local}_estab">{$local_estab}</span></td>
					<td class="c"><span id="local_{$id_local}_gases">{$local_gases}</span></td>
					<td class="c"><span id="local_{$id_local}_area">{$local_area}</span></td>
					<td class="c"><span id="local_{$id_local}_obsGerais">{$local_obsGerais}</span></td>
					<td class="c"><span id="local_{$id_local}_caractEspec">{$local_caractEspec}</span></td>
					<td class="c"><a href="javascript:void(0)" class="editLocalLink" onclick="javascript:editarLocal({$id_local})">[Editar]</a></td>
				</tr>
				', 'caract_especif_tr' => '
				<tr>
					<td class="c" style="width:30px;"><input type="checkbox" class="caract_espec" id="caract_espec_{$caract_espec_nome}" value="{$caract_espec_nome}" title="{$caract_espec_label}" /></td>
					<td class="c">{$caract_espec_label}</td>
					<td class="c">{$caract_espec_input}</td>
					<td class="c"><input type="text" class="caract_espec_obs" name="caract_espec_obs_{$caract_espec_nome}" id="caract_espec_obs_{$caract_espec_nome}" /></td>
				</tr>
				'
    );
}

?>