/*=============================================
  			  VARIÃ�VEIS GLOBAIS
=============================================*/
var newWinHeight = 0.9; // em porcentagem da tela
var newWinWidth = 0.95;  // em porcentagem da tela


/*=============================================
  FUNCOES PARA GERENCIAMENTO DE FORMULARIOS
=============================================*/
function HTMLEncode(str){
	  var i = str.length,
	      aRet = [];

	  while (i--) {
	    var iC = str[i].charCodeAt();
	    if (iC < 65 || iC > 127 || (iC>90 && iC<97)) {
	      aRet[i] = '&#'+iC+';';
	    } else {
	      aRet[i] = str[i];
	    }
	   }
	  return aRet.join('');    
	}

function closeDialog(){//fecha todos os dialogos
	$(".dialog").hide();
}

function showDialog(id){//abre um dialogo
	$("#"+id).show();
}
	
function showInputFile(i){//mostra outro campo para anexo de arquivo
	$("#arq"+(i-1)).attr("onclick",";");
	$("#fileUpCell").append('<br /><input type="file" id="arq'+i+'" name="arq'+i+'" onclick="showInputFile('+(i+1)+')" />');
}


/*function html_entity_decode(str) {
	var ta=document.createElement("textarea");
	ta.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
	return ta.value;
}*/

/**
 * @param a
 * @returns string
 * 
 * @deprecated
 */
function convertFromHTML(a){
	a = a + '';
	a = a.replace("&aacute;","á");
	a = a.replace("&atilde;","ã");
	a = a.replace("&agrave;","à");
	a = a.replace("&acirc;","â");
	a = a.replace("&eacute;","é");
	a = a.replace("&egrave;","è");
	a = a.replace("&ecirc;","ê");
	a = a.replace("&iacute;","í");
	a = a.replace("&igrave;","ì");
	a = a.replace("&oacute;","ó");
	a = a.replace("&ograve;","ò");
	a = a.replace("&ocirc;","ô");
	a = a.replace("&otilde;","õ");
	a = a.replace("&uacute;","ú");
	a = a.replace("&uuml;","ü");
	a = a.replace("&ccedil;","ç");
	a = a.replace("&Aacute;;","Á");
	a = a.replace("&Atilde;","Ã");
	a = a.replace("&Agrave;","À");
	a = a.replace("&Acirc;","Â");
	a = a.replace("&Eacute;","É");
	a = a.replace("&Egrave;","È");
	a = a.replace("&Ecirc;","Ê");
	a = a.replace("&Iacute;","Í");
	a = a.replace("&Igrave;","Ì");
	a = a.replace("&Oacute;","Ó");
	a = a.replace("&Ograve;","Ò");
	a = a.replace("&Ocirc;","Ô");
	a = a.replace("&Otilde;","Õ");
	a = a.replace("&Uacute;","Ú");
	a = a.replace("&Uuml;","Ü");
	a = a.replace("&Ccedil;","Ç");
	return a;
}

function html_entity_decode (string, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: john (http://www.jd-tech.net)
    // +      input by: ger
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +   improved by: marc andreu
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Ratheous
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Nick Kolosov (http://sammy.ru)
    // +   bugfixed by: Fox
    // -    depends on: get_html_translation_table
    // *     example 1: html_entity_decode('Kevin &amp; van Zonneveld');
    // *     returns 1: 'Kevin & van Zonneveld'
    // *     example 2: html_entity_decode('&amp;lt;');
    // *     returns 2: '&lt;'
    var hash_map = {},
        symbol = '',
        tmp_str = '',
        entity = '';
    tmp_str = string.toString();

    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    }

    // fix &amp; problem
    // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
    delete(hash_map['&']);
    hash_map['&'] = '&amp;';

    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }
    tmp_str = tmp_str.split('&#039;').join("'");

    return tmp_str;
}

function htmlentities (string, quote_style, charset, double_encode) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: nobbler
    // +    tweaked by: Jack
    // +   bugfixed by: Onno Marsman
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Ratheous
    // +   improved by: Rafa Kukawski (http://blog.kukawski.pl)
    // +   improved by: Dj (http://phpjs.org/functions/htmlentities:425#comment_134018)
    // -    depends on: get_html_translation_table
    // *     example 1: htmlentities('Kevin & van Zonneveld');
    // *     returns 1: 'Kevin &amp; van Zonneveld'
    // *     example 2: htmlentities("foo'bar","ENT_QUOTES");
    // *     returns 2: 'foo&#039;bar'
    var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
        symbol = '';
    string = string == null ? '' : string + '';

    if (!hash_map) {
        return false;
    }
    
   /* if (quote_style && quote_style === 'ENT_QUOTES') {
        hash_map["'"] = '&#039;';
    }*/
    
    if (!!double_encode || double_encode == null) {
        for (symbol in hash_map) {
            if (hash_map.hasOwnProperty(symbol)) {
                string = string.split(symbol).join(hash_map[symbol]);
            }
        }
    } else {
        string = string.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g, function (ignore, text, entity) {
            for (symbol in hash_map) {
                if (hash_map.hasOwnProperty(symbol)) {
                    text = text.split(symbol).join(hash_map[symbol]);
                }
            }
            
            return text + entity;
        });
    }
    
    if (double_encode === false) {
    	//alert("q " + string)
    	return string.replace(/&amp;quot;/g,"&quot;");
    }
    else
    	return string;
}

function get_html_translation_table (table, quote_style) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: noname
    // +   bugfixed by: Alex
    // +   bugfixed by: Marco
    // +   bugfixed by: madipta
    // +   improved by: KELAN
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Frank Forte
    // +   bugfixed by: T.Wild
    // +      input by: Ratheous
    // %          note: It has been decided that we're not going to add global
    // %          note: dependencies to php.js, meaning the constants are not
    // %          note: real constants, but strings instead. Integers are also supported if someone
    // %          note: chooses to create the constants themselves.
    // *     example 1: get_html_translation_table('HTML_SPECIALCHARS');
    // *     returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}
    var entities = {},
        hash_map = {},
        decimal;
    var constMappingTable = {},
        constMappingQuoteStyle = {};
    var useTable = {},
        useQuoteStyle = {};

    // Translate arguments
    constMappingTable[0] = 'HTML_SPECIALCHARS';
    constMappingTable[1] = 'HTML_ENTITIES';
    constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
    constMappingQuoteStyle[2] = 'ENT_COMPAT';
    constMappingQuoteStyle[3] = 'ENT_QUOTES';

    useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
    useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

    if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
        throw new Error("Table: " + useTable + ' not supported');
        // return false;
    }

    entities['38'] = '&amp;';
    if (useTable === 'HTML_ENTITIES') {
        entities['160'] = '&nbsp;';
        entities['161'] = '&iexcl;';
        entities['162'] = '&cent;';
        entities['163'] = '&pound;';
        entities['164'] = '&curren;';
        entities['165'] = '&yen;';
        entities['166'] = '&brvbar;';
        entities['167'] = '&sect;';
        entities['168'] = '&uml;';
        entities['169'] = '&copy;';
        entities['170'] = '&ordf;';
        entities['171'] = '&laquo;';
        entities['172'] = '&not;';
        entities['173'] = '&shy;';
        entities['174'] = '&reg;';
        entities['175'] = '&macr;';
        entities['176'] = '&deg;';
        entities['177'] = '&plusmn;';
        entities['178'] = '&sup2;';
        entities['179'] = '&sup3;';
        entities['180'] = '&acute;';
        entities['181'] = '&micro;';
        entities['182'] = '&para;';
        entities['183'] = '&middot;';
        entities['184'] = '&cedil;';
        entities['185'] = '&sup1;';
        entities['186'] = '&ordm;';
        entities['187'] = '&raquo;';
        entities['188'] = '&frac14;';
        entities['189'] = '&frac12;';
        entities['190'] = '&frac34;';
        entities['191'] = '&iquest;';
        entities['192'] = '&Agrave;';
        entities['193'] = '&Aacute;';
        entities['194'] = '&Acirc;';
        entities['195'] = '&Atilde;';
        entities['196'] = '&Auml;';
        entities['197'] = '&Aring;';
        entities['198'] = '&AElig;';
        entities['199'] = '&Ccedil;';
        entities['200'] = '&Egrave;';
        entities['201'] = '&Eacute;';
        entities['202'] = '&Ecirc;';
        entities['203'] = '&Euml;';
        entities['204'] = '&Igrave;';
        entities['205'] = '&Iacute;';
        entities['206'] = '&Icirc;';
        entities['207'] = '&Iuml;';
        entities['208'] = '&ETH;';
        entities['209'] = '&Ntilde;';
        entities['210'] = '&Ograve;';
        entities['211'] = '&Oacute;';
        entities['212'] = '&Ocirc;';
        entities['213'] = '&Otilde;';
        entities['214'] = '&Ouml;';
        entities['215'] = '&times;';
        entities['216'] = '&Oslash;';
        entities['217'] = '&Ugrave;';
        entities['218'] = '&Uacute;';
        entities['219'] = '&Ucirc;';
        entities['220'] = '&Uuml;';
        entities['221'] = '&Yacute;';
        entities['222'] = '&THORN;';
        entities['223'] = '&szlig;';
        entities['224'] = '&agrave;';
        entities['225'] = '&aacute;';
        entities['226'] = '&acirc;';
        entities['227'] = '&atilde;';
        entities['228'] = '&auml;';
        entities['229'] = '&aring;';
        entities['230'] = '&aelig;';
        entities['231'] = '&ccedil;';
        entities['232'] = '&egrave;';
        entities['233'] = '&eacute;';
        entities['234'] = '&ecirc;';
        entities['235'] = '&euml;';
        entities['236'] = '&igrave;';
        entities['237'] = '&iacute;';
        entities['238'] = '&icirc;';
        entities['239'] = '&iuml;';
        entities['240'] = '&eth;';
        entities['241'] = '&ntilde;';
        entities['242'] = '&ograve;';
        entities['243'] = '&oacute;';
        entities['244'] = '&ocirc;';
        entities['245'] = '&otilde;';
        entities['246'] = '&ouml;';
        entities['247'] = '&divide;';
        entities['248'] = '&oslash;';
        entities['249'] = '&ugrave;';
        entities['250'] = '&uacute;';
        entities['251'] = '&ucirc;';
        entities['252'] = '&uuml;';
        entities['253'] = '&yacute;';
        entities['254'] = '&thorn;';
        entities['255'] = '&yuml;';
    }

    if (useQuoteStyle !== 'ENT_NOQUOTES') {
        entities['34'] = '&quot;';
    }
    if (useQuoteStyle === 'ENT_QUOTES') {
        entities['39'] = '&#39;';
    }
    entities['60'] = '&lt;';
    entities['62'] = '&gt;';


    // ascii decimals to real symbols
    for (decimal in entities) {
        if (entities.hasOwnProperty(decimal)) {
            hash_map[String.fromCharCode(decimal)] = entities[decimal];
        }
    }

    return hash_map;
}

/*function verificaPadrao(valor,campo){
	var expr;
	if(campo == "numero_pr"){
		expr = /01 P-[0-9]{5}-[0-9]{4}/;
	}
	return expr.test(valor);
}*/

// preenche campo com zeros a esquerda ate' atingir o tamanho maximo do campo
function preencheZeros(campo) {
	if (!$("#"+campo).val()) return;
	var contador = $("#"+campo).val().length;  
     
	if ($("#"+campo).val().length != $("#"+campo).attr('maxLength')) {  
	do {
		$("#"+campo).val("0" + $("#"+campo).val());  
		contador += 1;
    } while (contador < $("#"+campo).attr('maxLength'))  
   }  
}

// valida se tecla pressionada passada por evento e' numerica
function verificaNumero(event) {
	// permite: backspace, del, tab e escape
	if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
		// permite: Ctrl+A
		(event.keyCode == 65 && event.ctrlKey === true) || 
		// permite: home, end, seta esquerda, seta direita, enter
		(event.keyCode >= 35 && event.keyCode <= 39) || (event.keyCode == 13)) {
			// caso seja uma dessas teclas, nÃ£o faz nada e deixa o evento acontecer
			return;
	}
	else {
		// verifica se a tecla pressionada e' um numero. caso nÃ£o seja, nÃ£o permite que o caractere entre no campo
		if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) { // obs: teclas 0-9 e as do teclado numerico tem keyCode diferentes
			event.preventDefault(); 
		}   
	}
}

/**
 * valida formato de nÃºmero de processo.
 * processos da unicamp tem o formato: XX D-CCCCC-YYYY (x: dÃ­gitos 0-9, D: 'P' ou 'E', c: nÃºmeros centrais 0-9, y: ano)
 * processos da funcamp tem o formato: FU F-CCCCCCC-YYYY (FU string definida por padrÃ£o, 'F' identificador processo funcamp, c: nÃºmeros centrais 0-9, y: ano)
 * @param int numero do processo a ser validado
 * @return boolean true caso o numero esteja de acordo com o padrÃ£o, false caso contrÃ¡rio
 */
function validaNumPr(numero) {
	// pega os dois primeiros digitos numa parte e o restante fica na outra parte
	var partes = numero.split(" "); 
	// verifica se esse numero contem 2 partes
	if (partes.length != 2) return false;

	// verifica se a primeira parte contem 2 digitos
	if (partes[0].length != 2) return false;

	// pega a segunda parte e a separa por hifens.
	var pedacos = partes[1].split("-");

	// verifica se existem 3 pedacos separados por hifens
	if (pedacos.length != 3) return false;

	// faz verificacao quanto ao caractere identificador de processo
	if ((pedacos[0].length != 1) || ((pedacos[0] != 'E') && (pedacos[0] != 'F') && (pedacos[0] != 'P'))) return false;

	var patt = new RegExp("[^0-9]"); // cria padrÃ£o para busca de caracteres que nÃ£o sÃ£o nÃºmeros
	
	// verifica se o processo Ã© funcamp
	if (pedacos[0] == "F") {
		if (partes[0] != "FU") return false;
		if (pedacos[1].length != 7) return false;
	} // se nÃ£o for F, entao Ã© processo unicamp
	else {
		if (patt.test(partes[0]) == true) return false; // achou caractere nÃ£o numÃ©rico nos 2 primeiros digitos
		if (pedacos[1].length != 5) return false;

	}

	if (patt.test(pedacos[1]) == true) return false; // se patt.test retornar true, encontrou caracteres nÃ£o-numÃ©ricos

	// verifica se o ano contem 4 digitos
	if (pedacos[2].length != 4) return false;
	
	// verifica se o ano contem digitos nÃ£o-numÃ©ricos
	if (patt.test(pedacos[2]) == true) return false; // se patt.test retornar true, encontrou caracteres nÃ£o-numÃ©ricos
	
	// verifica se o ano eh maior que 1965 e menor que (ano atual) - 5
	if (pedacos[2] < 1965) return false;
	var data = (new Date).getFullYear;
	if (pedacos[2] >= data-5) return false;

	return true;
}

function trataData(campo) {
	if (campo.attr("id") == undefined || campo.attr("id") == null)
		return;
	
	if (campo.attr("id") == "dataAssinatura") {
		if (typeof(validaReuniao) == 'function') {
			validaReuniao();
			return;
		}
		if (typeof(calculaVigencia) == 'function')
			calculaVigencia();
	}
	else if (campo.attr("id") == "dataReuniao") {
		if (typeof(validaReuniao) == 'function') {
			validaReuniao();
		}
		if (typeof(calculaVigencia) == 'function')
			calculaVigencia();
		if (typeof(calculaInicio) == 'function')
			calculaInicio();
	}
	else if (campo.attr("id") == "inicioProjObra") {
		if (typeof(calculaTermino) == 'function')
			calculaTermino();
	}
	else if (campo.attr("id") == "prazoProjObra") {
		if (typeof(calculaTermino) == 'function')
			calculaTermino();
	}
}

/*=================================
FUNCOES DE GERENCIAMENTO DE JANELAS
==================================*/

function getUrlVars(){
	// Get variaveis por JS;
	var vars = [], pedaco;
	if(window.location.href.indexOf('#') == -1){
		var inteira = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	} else{
		var inteira = window.location.href.slice(window.location.href.indexOf('?') + 1, window.location.href.indexOf('#')).split('&');
	}
	for (var i = 0; i < inteira.length; i++){
		pedaco = inteira[i].split('=');
		pedaco[1] = unescape(pedaco[1]);
		vars.push(pedaco[0]);
		vars[pedaco[0]] = pedaco[1];
	}
	return vars;
}

/*=========================================
FUNCOES PARA GERENCIAMENTO DE TABELAS/LINKS
=========================================*/

function showDesp(i){
	$('#desp'+i).toggle();
}

function newEmprCadLink(data,nome){
	$.get('empresa.php?acao=cad&data='+data,function(suc){
		if(suc == '0'){
			alert("Erro ao cadastrar empresa");
		}else{
			id = suc;
			newEmprLink(id,nome);
		}
	});
	
}

function newEmprLink(id,nome){//monta link para empresa anexa
	if($("#emprAnexas").val().length == 0)
		$("#empresa").html("");
	$("#empresa").append('&nbsp;&nbsp;'+newWinLink('empresa.php?acao=ver&id='+id,'empresa'+id,950,650,nome)+'&nbsp;&nbsp;,');
	$("#emprAnexas").val($("#emprAnexas").val()+id+',');
}

function newDocLink(id,nome,target,sep){//monta um link de documento anexo
	if($("#"+target).val().length == 0)
		$("#docs").html("");
	$("#"+target+"Nomes").append(newWinLink('sgd.php?acao=ver&docID='+id,'detalhe'+id,950,650,nome)+newDelBut(id,target,'<br>')+'<br>');
	if($("#"+target).val().length == 0){
		$("#"+target).val($("#"+target).val()+id);
	} else {
		$("#"+target).val($("#"+target).val()+','+id);
	}
	
	if(target == 'ofir' || target == 'saa') {
		validateDoc(target);
	}
}

function newDelBut(id,target,sep){//cria o link para remocao do documento
	return ' <a href="javascript:void(0)" class="retirar" onclick="delDoc('+id+',\''+target+'\',\''+sep+'\')">[Retirar]</a>';
}

function delDoc(id,target,sep){//faz a remocao do documento
	var ids = $("#"+target).val().split(',');
	var nomes = $("#"+target+"Nomes").html().split(sep);
	var i = 0, idsNovos = '', nomesNovos = '';
	for(i = 0 ; i < ids.length ; i++){//varre o array de documentos e ignora o elemento a ser excluido
		if(ids[i] != id){
			idsNovos += ids[i] + ',';
			nomesNovos += nomes[i] + sep;
		}
	}
	$("#"+target).val(idsNovos.slice(0,idsNovos.length - 1));
	$("#"+target+"Nomes").html(nomesNovos);
}

function newWinLink(url,name,w,h,label){//monta o link para abrir em nova janela
	return '<a href="javascript:void(0)" id="'+name+'" onclick="window.open(\''+url+'\',\''+name+'\',\'width='+w+',height='+h+',scrollbars=yes,resizable=yes\').focus()">'+label+'</a>';
}

function reEnableButton(selector){
	$(selector).removeAttr('disabled');
	$(selector).val('Salvar');
}












function escolherDoc(target){
	window.open('sgd.php?acao=busca_mini&onclick=adicionarCampo&max=1&target='+target+'&novaJanela=1','addDoc','width='+screen.width*newWinWidth+',height='+screen.height*newWinHeight+',scrollbars=yes,resizable=yes').focus();
	$('#'+target+'Nomes').html('Documento Selecionado:');
	$('#'+target).val('');
	if(target == 'ofir') validateObra();
}

function referenciarDoc(target){
	window.open('sgd.php?acao=busca_mini&onclick=referenciar&max=1&target='+target+'&novaJanela=1','addDoc','width='+screen.width*newWinWidth+',height='+screen.height*newWinHeight+',scrollbars=yes,resizable=yes').focus();
	//$('#'+target+'Nomes').html('Documento Selecionado:');
	$('#'+target).val('');
	//if(target == 'ofir') validateObra();
}

function referDocAux(nome, target) {
	window.opener.referDoc(nome, target);
	if(confirm("Documento adicionado com sucesso.\nClique OK para fechar a janela de busca."))
		self.close();
}

function newDocInNewWindow(tipo,target,acao){
	window.open('sgd.php?acao='+acao+'_mini&tipoDoc='+tipo+'&targetInput='+target+'&desp=off&novaJanela=1', 'cadMini', 'width='+screen.width*newWinWidth+',height='+screen.height*newWinHeight+',scrollbars=yes,resizable=yes').focus();
	$('#'+target+'Nomes').html('Documento Selecionado:');
	$('#'+target).val('');
}

function updateDocID(target,value){
	$("#"+target+"Names").html("Documento "+value+" selecionado.");
	$("#"+target).val(value);
}

function visualizarDoc(acao, linkObj) {
	// pega nome do form
	var form;
	
	if (linkObj == undefined || linkObj == null) {
		form = $(".link_preview").closest('form').attr("id");
	}
	else {
		form = linkObj.closest('form').attr("id");
	}
	//alert($(".link_preview").length);
	//alert(form);
	
	// forca a atualizacao do campo do CKEDITOR
	for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();

	// transforma campos do form para passar via POST
	var param = $("#" + form).serialize();
	
	if (acao == "cad") { // se estiver cadastrando,...
		// seta variavel tipo doc
		var tipoDoc = $("#tipoDocCad").val();

		// faz a passagem de parametros e abre nova janela
		$.post("sgd.php?acao=visualizar&tipoDoc=" + tipoDoc, param, function(data) {
			
			//data = eval(data);
			try {
				data = eval(data);
			} catch(e) {
				if (e instanceof SyntaxError) {
					alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message + 'JSON Retornado: ' + data);
				}
			}
			
			var win = window.open("sgd.php?acao=preview", 'visualizar', 'width='+screen.width*newWinWidth+',height='+screen.height*newWinHeight+',scrollbars=yes,resizable=yes');
			// verifica se o browser esta bloqueando pop-ups
			if (win == null || typeof(win)=='undefined') {
				alert("O seu navegador estÃ¡ bloqueando pop-ups. Por favor, habilite pop-ups e clique em Visualizar novamente.");
			}
			else {
				//win.document.write(convertFromHTML(data[0].doc));
				//win = montaVisualizacaoDoc(win, data);
				win.focus();
			}
			
		});
	}
	else {
		// nao esta cadastrando, e sim editando
		var docID = $("#docID").html();
		
		// faz a passagem de parametros e abre nova janela
		$.post("sgd.php?acao=visualizar&docID=" + docID, param, function(data) {
			//data = eval(data);
			try {
				data = eval(data);
			} catch(e) {
				if (e instanceof SyntaxError) {
					alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message);
				}
			}
			
			var win = window.open("sgd.php?acao=preview", 'visualizar', 'width='+screen.width*newWinWidth+',height='+screen.height*newWinHeight+',scrollbars=yes,resizable=yes');
			// verifica se o browser esta bloqueando pop-ups
			if (win == null || typeof(win)=='undefined') {
				alert("O seu navegador estÃ¡ bloqueando pop-ups. Por favor, habilite pop-ups e clique em Visualizar novamente.");
			}
			else {
				//win.document.write(convertFromHTML(data[0].doc));
				//win = montaVisualizacaoDoc(win, data);
				win.focus();
			}
		});
	}
}
/*
function montaVisualizacaoDoc(win, data){
	var i = 1
	var html = data[0].page_layout.replace('{$page_content}',data[0].page_header+data[0].page_content);
	
	var elementos_internos = $("#pagina"+i).children('');
	
	
	win.document.write(html);
	return win;
}*/

function desarquivarDoc(doc) {
	// request
	$.get('sgd.php', {
		acao: 'arqAjax',
		docID: doc
	}, function(d) {
		//fb = eval(d);
		try {
			fb = eval(d);
		} catch(e) {
			if (e instanceof SyntaxError) {
				alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message);
			}
		}
		
		if (fb[0].success == true)
			$("#arq"+doc).html("Desarquivado com sucesso!");
		else
			$("#arq"+doc).html("Falha ao desarquivar o documento.");
	});
}


function descartarAutoSave() {
	$.get('autosave.php?acao=descartar', {}, function(d) {
		
		try {
			d = JSON.parse(d);
		} catch(e) {
			if (e instanceof SyntaxError) {
				alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message);
				return;
			}
		}
		
		if (d[0].success == true) {
			$("#autoSavedDocInterface").next("br").remove();
			$("#autoSavedDocInterface").remove();
		}
		else {
			alert("Problema ao descartar documento. Por favor, tente novamente mais tarde.");
		}
		
	});
}


/*==========================================================================
                      FUNCOES DA PAGINA DE USUARIO
==========================================================================*/

function showUserProfile(userID){
	$.post('sgp.php?acao=getProfileData',
		{'userID' : userID},
		function(d){
			try {
				d = JSON.parse(d);
			} catch(e) {
				if (e instanceof SyntaxError) {
					alert("Erro encontrado. Contacte seu administrador e mostre essa mensagem: " + e.message + "\nRetorno: "+ d);
					return;
				}
			}
			
			$("#userProfile").html(html_entity_decode(unescape(d[0].html)));
			
			$("#userProfile").dialog({
				resizable: false,
				title: "Perfil de "+d[0].userName,
				autoOpen: true,
				height: 500,
				width: 750,
				modal: false
			});
		});
	
	
}