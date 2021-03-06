$(document).ready(function() {
	$("input[name='multiCheck[]'].solic").each(function() {
		$(this).attr("disabled", "disabled");
		$(this).attr("title", "Este documento foi Solicitado.");
	});
	
	$("input[name='multiCheck[]'].solicDes").each(function() {
		$(this).attr("disabled", "disabled");
		$(this).attr("title", "O Desarquivamento desde documento foi solicitado.");
	});
	
	$("input[name='multiCheck[]']").change(function() {
		$(this).parents('tr').toggleClass('selected');
	});
	
	$("#topSelectAction, #bottomSelectAction").each(function() {
		$(this).bind('change', function() {
			var option = $(this).children("option").filter(":selected").val();
			
			var docs = selectDocs();
			if (docs.length > 0) {
				if (option == "despAll") {
					showDespDialog(docs);
				}
				else if (option == "solicArqAll") {
					showSolicArqDialog(docs);
				}
				else if (option == "arqAll") {
					showArqDialog(docs);
				}
			}
			else {
				alert("Por favor, selecione pelo menos um documento da sua lista de documentos pendentes antes de prosseguir.");
			}
			
			$(this).children("option:selected").removeAttr("selected");
			$(this).children("option[value='']").attr("selected", "selected");
		});
	});
});


function selectDocs() {
	var checkBoxes = $("input[name='multiCheck[]']:checked");

	var ret = '';
	
	$.each(checkBoxes, function() {
		// verifica se a linha está visível. caso negativo, não adiciona doc a lista de despachos
		if ($(this).parents('tr').css('display').indexOf('none') != -1) {
			return true; // continue
		}
		
		ret += $(this).val();
		ret += ',';
	});
	
	// remove ultima virgula
	ret = ret.slice(0, -1);
	
	return ret;
}

function showSolicArqDialog(docs) {
	var lista = docs.split(",");
	
	var text = "Voc&ecirc; est&aacute; tentando solicitar o arquivamento para os documentos:<br /><br />";
	
	for (var i = 0; i < lista.length; i++) {
		text += "" + lista[i] + " - <b>" + $("td[name='"+lista[i]+"']").children("a").html() + "</b><br />";
	}
	text +=  "<br />Clique em <b>Continuar</b> para prosseguir ou em <b>Cancelar</b> para rever a lista de documentos.";
	
	$("#multiSolArqInterface").html(text);
		
	$("#multiSolArqInterface").dialog({ 
		resizable: true,
		height: 300,
		width: 400,
		modal: true,
		buttons: {
			"Continuar": function() {
				$.post('sgd.php?acao=solArqAll', {
					docs: docs
				}, function(d) {
					try {
						d = eval(d);
					} catch(e) {
						if (e instanceof SyntaxError) {
							alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message + " Retorno: " + d);
						}
					}
					
					if (d[0].success == true) {
						alert('Arquivamento dos documentos solicitado com sucesso! Leve os documentos ao Protocolo.');
						window.location.reload();
					}
					else {
						alert("Falha ao tentar solicitar arquivamento. Por favor, tente novamente.");
					}
				});
				$(this).dialog('close'); 
			},
			"Cancelar": function() { $(this).dialog('close'); }
		}
	});
	
}

function showArqDialog(docs) {
	var lista = docs.split(",");
	
	var text = "Voc&ecirc; est&aacute; tentando arquivar os documentos:<br /><br />";
	
	for (var i = 0; i < lista.length; i++) {
		text += "" + lista[i] + " - <b>" + $("td[name='"+lista[i]+"']").children("a").html() + "</b><br />";
	}
	text +=  "<br />Clique em <b>Continuar</b> para prosseguir ou em <b>Cancelar</b> para rever a lista de documentos.";
	
	$("#multiArqInterface").html(text);
	
	$("#multiArqInterface").dialog({ 
		resizable: false,
		height: 300,
		width: 400,
		modal: true,
		buttons: {
			"Continuar": function() {
				$.post('sgd.php?acao=arqAll', {
					docs: docs
				}, function(d) {
					try {
						d = eval(d);
					} catch(e) {
						if (e instanceof SyntaxError) {
							alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message + " Retorno: " + d);
						}
					}
					
					if (d[0].success == true) {
						alert('Arquivamento dos documentos realizado com sucesso! Guarde os documentos no Arquivo.');
						window.location.reload();
					}
					else {
						alert("Falha ao tentar realizar arquivamento. Por favor, tente novamente.");
					}
				});
				$(this).dialog('close'); 
			},
			"Cancelar": function() { $(this).dialog('close'); }
		}
	});
	
}

function showDespDialog(docs) {
	$("#multiDespInterface").dialog({ 
		resizable: true,
		height: 300,
		width: 400,
		modal: true,
		buttons: {
			"OK": function () {
				var funcID = $("#subp option:selected").val();
				var para = $("#para option:selected").val();
				var despExt = $("#despExt").val();
				var outro = $("#outro").val();
				var despacho = $("#despacho").val();
				var rr = false;
				
				if ($("#rr").length > 0 && $("#rr").attr("checked") == "checked")
					rr = true;
				
				if (funcID == undefined)
					funcID = '';
				if (para == undefined)
					para = '';
				if (despExt == undefined)
					despExt = '';
				if (outro == undefined)
					outro = '';
				if (despacho == undefined)
					despacho = '';
				
				if (funcID == '' && (para == '' || para.indexOf('Selecione') != -1) && despExt == '' && outro == '' && despacho == '') {
					$(this).dialog('close');
					return;
				}
				
				$.post('sgd.php?acao=despAll', {
					docs: docs,
					funcID: funcID,
					para: htmlentities(para),
					despExt: despExt,
					outro: outro,
					despacho: despacho,
					rr: rr
				}, function(d) {
					try {
						d = eval(d);
					} catch(e) {
						if (e instanceof SyntaxError) {
							alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message + " Retorno: " + d);
						}
					}
					
					if (d[0].success == true) {
						if ($("#RRDialog").length <= 0) {
							$("body").append('<div id="RRDialog"></div>');
						}
						var link = '';
						
						var text = 'Despacho realizado com sucesso!<br />';
						if (d[0].rrID != undefined && d[0].rrID != null) {
							link += "window.open('sgd.php?acao=ver&docID="+d[0].rrID;
							link += "','doc','width='+screen.width*0.95+',height='+screen.height*0.9+',scrollbars=yes,resizable=yes')";
							link += ".focus()";
							
							text += 'Rela&ccedil;&atilde;o de Remessa gerada com sucesso! Clique <a onclick="'+link+'">aqui</a> ';
							text += 'para visualizar e imprimir a Rela&ccedil;&atilde;o de Remessa.';
							
							$(this).dialog('close');
							
							$("#RRDialog").html(text);
							$("#RRDialog").dialog({ 
								resizable: true,
								height: 300,
								width: 400,
								modal: true,
								buttons: {
									"OK": function() {
										window.location.reload();
										$(this).dialog('close');
									}
								}
							});
							
						}
						//window.location.reload();
						
					}
					else {
						if (d[0].errorFeedback != undefined)
							alert(html_entity_decode(d[0].errorFeedback));
						else
							alert('Erro. Tente novamente.');
					}
				});
				
				$(this).dialog("close");
			},
			"Cancelar": function () { $(this).dialog("close"); }
		}
	});
}