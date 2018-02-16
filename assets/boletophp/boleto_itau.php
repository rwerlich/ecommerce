<?php

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 5;
$taxa_boleto = 2.00;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$valor_cobrado = $order['vltotal']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
#$valor_cobrado = str_replace(".", "", $valor_cobrado);
$valor_cobrado = str_replace(",", ".", $valor_cobrado);
$valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');
$dadosboleto["nosso_numero"] = $order['idorder'];  // Nosso numero - REGRA: Máximo de 8 caracteres!
$dadosboleto["numero_documento"] = $order['idorder']; // Num do pedido ou nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto;  // Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $order['nome'];
$dadosboleto["endereco1"] = "Endereço - Bairro";
$dadosboleto["endereco2"] = "Cidade - Estado - País -  CEP: 000-000";
// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja WERLICH E-commerce";
$dadosboleto["demonstrativo2"] = "Taxa bancária - R$ 0,00";
$dadosboleto["demonstrativo3"] = "";
$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: rodrigo.werlich@gmail.com";
$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Werlich E-commerce - www.werlich.blog.br";
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";
// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS DA SUA CONTA - ITAÚ
$dadosboleto["agencia"] = "1690"; // Num da agencia, sem digito
$dadosboleto["conta"] = "48781"; // Num da conta, sem digito
$dadosboleto["conta_dv"] = "2";  // Digito do Num da conta
// DADOS PERSONALIZADOS - ITAÚ
$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157
// SEUS DADOS
$dadosboleto["identificacao"] = "Werlich Ecommerce";
$dadosboleto["cpf_cnpj"] = "00.000.000/0000-00";
$dadosboleto["endereco"] = "Rua Coletor Irineu Comelli, 777 - Centro, 88103-050";
$dadosboleto["cidade_uf"] = "São José - SC";
$dadosboleto["cedente"] = "WERLICH LTDA - ME";

// NÃO ALTERAR!
require_once("include/funcoes_itau.php");
require_once("include/layout_itau.php");

