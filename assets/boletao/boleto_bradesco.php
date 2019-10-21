<?php

// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//
//Glauco2
include('inc/conecta.php');

$codboletao = $_GET['cod'];
global $db;
global $DbName;

//$sql  = "SELECT * FROM ".$DbName.".rup WHERE CPFCNPJ = '$cpfcnpj'";
//$query = mysql_query($sql,$db);
//$dados = MYSQL_FETCH_ARRAY($query);

//$sql_crono  = "SELECT c.*,date_format(c.VENCIMENTO,'%d/%m/%Y') VENC,o.CODCOBRANCA,o.MODALIDADE as MODOPER FROM ".$DbName.".cronograma c ";
//$sql_crono  = $sql_crono." Inner Join ".$DbName.".operacao o";
//$sql_crono  = $sql_crono." On o.CODOPERACAO = c.CODOPERACAO";
//$sql_crono  = $sql_crono." WHERE CODCRONOGRAMA = $crono";

$sql_crono = " SELECT b.CNPJ_ADM, b.CNPJ_COND, b.NOSSONUMERO, o.CODEMPRESA, o.CODCOBRANCA, i.CONTRATO, DATEDIFF(CURDATE(),b.VENCIMENTO_ENVIO) as ATRASO,date_format(CURDATE(),'%d/%m/%Y') DATAATU,date_format(VENCIMENTO_ENVIO,'%d/%m/%Y') VENCIMENTO_ENVIO,b.CODBOLETAO,a.*,sum(i.VALOR) as TOTAL from ".$DbName.".boletao b
        inner join ".$DbName.".iboletao i
        on i.CODBOLETAO = b.CODBOLETAO
        inner join ".$DbName.".operacao o
        on i.CONTRATO = o.CODOPERACAO
        inner JOIN ".$DbName.".averbador a
        on b.CNPJ_ADM = a.CPFCNPJ
        Where i.CANCELADO = 'N'
        And b.CODBOLETAO = $codboletao";



$query_crono = mysql_query($sql_crono,$db);
$dados_crono = MYSQL_FETCH_ARRAY($query_crono);

$contrato = $dados_crono['CONTRATO'];
$codEmpresa  = $dados_crono['CODEMPRESA'];
$codcobranca = $dados_crono['CODCOBRANCA'];

$cnpj_cond = $dados_crono['CNPJ_COND'];
$cnpj_averb = $dados_crono['CNPJ_ADM'];

$codEmpresa = 1;
$codcobranca = 2;


$sql = " Select b.CNPJ_COND from ".$DbName.".boletao b
inner join ".$DbName.".iboletao i
on i.CODBOLETAO = b.CODBOLETAO
inner join ".$DbName.".operacao o
on i.CONTRATO = o.CODOPERACAO
inner JOIN ".$DbName.".averbador a
on b.CNPJ_ADM = a.CPFCNPJ
Where i.CANCELADO = 'N'
And b.CODBOLETAO = $codboletao";

IF($cnpj_cond <> '') {
    $sql = "Select e.DESCRICAO, e.ENDERECO, e.BAIRRO, e.CIDADE, e.UF, e.CEP from ".$DbName.".empregador e
    Where e.CPFCNPJEMPREGADOR = '$cnpj_cond'
    Order By e.DESCRICAO";
    $query_cond = mysql_query($sql,$db);
    $dados_cond = MYSQL_FETCH_ARRAY($query_cond);

    $nome_sac   = $dados_cond["DESCRICAO"] ;
    $endereco_1 = $dados_cond["ENDERECO"] . ' - ' . $dados_cond["BAIRRO"];
    $endereco_2 = $dados_cond["CIDADE"] . ' - ' .  $dados_cond["UF"] . '-  CEP: ' . $dados_cond["CEP"];
}

Else {
    $nome_sac   = $dados_crono["NOME"] ;
    $endereco_1 = $dados_crono["ENDERECO"] . ' - ' . $dados_crono["BAIRRO"];
    $endereco_2 = $dados_crono["CIDADE"] . ' - ' .  $dados_crono["UF"] . '-  CEP: ' . $dados_crono["CEP"];
}

IF($cnpj_averb <> '') {
    $sql = "Select NOME FROM averbador WHERE CPFCNPJ = '$cnpj_averb'";
    $query_averb = mysql_query($sql,$db);
    $dados_averb = MYSQL_FETCH_ARRAY($query_averb);

    $nome_sac_aval = $dados_averb["NOME"];
}

$sql_emp  = "SELECT * FROM ".$DbName.".empresas Where CODEMPRESA = '".$codEmpresa."' ";
$query_emp = mysql_query($sql_emp,$db);
$dados_emp = MYSQL_FETCH_ARRAY($query_emp);



//$codcobranca = 2;

$sql_cob  = "SELECT * FROM ".$DbName.".contascobranca WHERE CODCOBRANCA = $codcobranca";
$query_cob = mysql_query($sql_cob,$db);
$dados_cob = MYSQL_FETCH_ARRAY($query_cob);


// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 0;
$data_venc = $dados_crono["VENCIMENTO_ENVIO"] ;  // Prazo de X dias OU informe data: "13/04/2006";

IF($dados_crono["ATRASO"] > 0) {
    //Juros/Multa
    $valormultaat = ($dados_crono["TOTAL"] )*($dados_crono["MULTA"] / 100);
    $valorjurosat = ($dados_crono["TOTAL"] )* ((($dados_crono["JUROSMORA"] / 100) /30) * $dados_crono["ATRASO"]);

    $valor_cobrado = $dados_crono["TOTAL"] + $valormultaat + $valorjurosat; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
    $data_venc = $dados_crono["DATAATU"] ;  // Prazo de X dias OU informe data: "13/04/2006";

}

Else {
    $valor_cobrado = $dados_crono["TOTAL"]; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
}

IF($dados_crono["NOSSONUMERO"] <> '') {
    $dadosboleto["nosso_numero"] = str_pad(substr($dados_crono["NOSSONUMERO"],0,11), 11, "0", STR_PAD_LEFT);  // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!
}

Else {
    $dadosboleto["nosso_numero"] = str_pad($dados_crono["CODBOLETAO"], 11, "0", STR_PAD_LEFT);  // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!
}

$dadosboleto["numero_documento"] = $dados_crono["CODBOLETAO"];	// Num do pedido ou do documento = Nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $nome_sac;
$dadosboleto["cnpjsacado"] = $cnpj_cond;
$dadosboleto["endereco1"] = $endereco_1;
$dadosboleto["endereco2"] = $endereco_2;
// DADOS SACADOR AVALISTA
$dadosboleto["sacadoravalista"] = $nome_sac_aval;
$dadosboleto["cnpj"] = $cnpj_averb;

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "";
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = "";


$multadia = $dados_emp["JUROSMORA"] / 30 ;
$dadosboleto["instrucoes1"] = "MORA DIA/COM.PERMANENC " . "R$ " .number_format( ($dados_crono["TOTAL"]  * $multadia) / 100, 2,',','.'); ;
$dadosboleto["instrucoes2"] = "APOS " . $dados_crono["VENCIMENTO_ENVIO"] . " MULTA " . "R$ " .  number_format((($dados_crono["TOTAL"] * $dados_emp["MULTA"]) /100), 2,',','.');;
$dadosboleto["instrucoes3"] = "*O PAGAMENTO DESTA PARCELA NÃO QUITA POSSÍVEIS DÉBITOS ANTERIORES";


//$obj = new CalcDataHora($dados_crono["VENCIMENTO_ENVIO"] ,"");
//$vencatual = $obj->somaDia(5);
//$vencatual = $obj->getData();


//$dadosboleto["instrucoes4"] = "*NÃO RECEBER APÓS O DIA **".$vencatual."** TELEFONE PARA CONTATO "  .  $dados_emp["FONE"];



// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "001";
$dadosboleto["valor_unitario"] = $valor_boleto;
$dadosboleto["aceite"] = "";
$dadosboleto["uso_banco"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DS";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = $dados_cob["NUM_AGENCIA"]; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = $dados_cob["DIG_AGENCIA"];// Digito do Num da agencia
$dadosboleto["conta"] = $dados_cob["NUM_CONTA"]; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $dados_cob["DIG_CONTA"]; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - Bradesco
$dadosboleto["conta_cedente"] = $dados_cob["NUM_CONTA"];; // ContaCedente do Cliente, sem digito (Somente Números)
$dadosboleto["conta_cedente_dv"] = $dados_cob["DIG_CONTA"]; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = "09";  // Código da Carteira: pode ser 06 ou 03

// SEUS DADOS
/*
$dadosboleto["identificacao"] = $dados_emp["RAZAOSOCIAL"];
$dadosboleto["cpf_cnpj"] = $dados_emp["CNPJ"];
$dadosboleto["endereco"] = $dados_emp["ENDERECO"];
$dadosboleto["cidade_uf"] = $dados_emp["CIDADE"] . ' / ' . $dados_emp["UF"];
$dadosboleto["cedente"] = $dados_emp["RAZAOSOCIAL"];
*/

$dadosboleto["identificacao"] = "FUNDO DE INVESTIMENTO EM DIREITOS CREDITORIOS EMPIRICA RPW MICROFINANCAS";
$dadosboleto["cpf_cnpj"] = "10768419000141";
$dadosboleto["endereco"] = "";
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"] = "FUNDO DE INVESTIMENTO EM DIREITOS CREDITORIOS EMPIRICA RPW MICROFINANCAS";

// NÃO ALTERAR!
include("include/funcoes_bradesco.php");
include("include/layout_bradesco.php");

?>
