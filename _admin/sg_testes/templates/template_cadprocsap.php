<script type="text/javascript" src="scripts/commom.js?r={$randNum}"></script>
<script type="text/javascript" src="scripts/cad_proc_sap.js?r={$randNum}"></script>

<div class="hid">
<br />
{$campos_busca}
<form accept-charset="{$charset}" id="cadForm" action="sgd.php?acao=salvar&novaJanela={$nova_janela}" method="post" enctype="multipart/form-data">
<table width="100%">
<tr><td>{$emitente}</td></tr>
<tr><td>{$campos}</td></tr>
<tr><td>
	<b>Anexar Arquivo:</b><br />
	{$anexarArq}
</td></tr>

<tr><td colspan="2"><b>Hist&oacute;rico:</b></td></tr>
<tr><td colspan="2">{$historico}</td></tr>
<tr><td colspan="2"><b>Recebimento:</b></td></tr>
<tr><td colspan="2">{$recebimento}</td></tr>
<tr><td colspan="2"><b>Instruir:</b></td></tr>
<tr><td colspan="2">{$despacho}</td></tr>
<tr><td colspan="2" align="center"><input type="submit" id="submitCad" value="Enviar"></td></tr>
</table>
</form>
</div>