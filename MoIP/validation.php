<?php

/** MODULO CRIADO SUPORTE MOIP
 * @author Vagner Fiuza Vieira
 * @baseado MoIP 1.5 BETA by Odlanier de Souza Mendes / E-Mail: master_odlanier@hotmail.com / E-Mail: mends@prestashopbr.com
 * @copyright MoIP Labs
 * @email suporte@moip.com.br
 * @version I.HTML v1.0 MoIP Labs - BETA
 **/

	if (!function_exists('log_var')) {
		  function log_var($var, $name='', $to_file=false){
		    if ($to_file==true) {
		        $txt = @fopen('debug.txt','a');
		        if ($txt){
    		        fwrite($txt, "-----------------------------------\n");
    		        fwrite($txt, $name."\n");
    		        fwrite($txt,  print_r($var, true)."\n");
    		        fclose($txt);//
                }
		    } else {
		         echo '<pre><b>'.$name.'</b><br>'.
		              print_r($var,true).'</pre>';
		    }
		  }
	}
	

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/MoIP.php');

$errors = '';
$result = false;
$MoIP = new MoIP();



if($_POST != ""){
	
$result = "VERIFICADO";

        $id_var = explode('[',$_POST['id_transacao']);        
        $id_var_explode = $id_var[1];
        $tmp = explode(']',$id_var_explode);
        $id_transacao_moip = $tmp[0];

        $id_moeda = Configuration::get('PS_CURRENCY_DEFAULT');
		
	}else{
	
$result = "FALHOU";
		
	}
	
if ($result == 'VERIFICADO') {
	if (!isset($_POST['valor']))
		$errors .= $MoIP->getL('valor_moip');
	if (!isset($_POST['status_pagamento']))
		$errors .= $MoIP->getL('status_pagamento_moip');
	if ($_POST['status_pagamento'] == 1)
		$status = Configuration::get('MoIP_STATUS_0');
		elseif ($_POST['status_pagamento'] == 2)
		$status = Configuration::get('MoIP_STATUS_1');
		elseif ($_POST['status_pagamento'] == 3)
		$status = Configuration::get('MoIP_STATUS_2');
		elseif ($_POST['status_pagamento'] == 4)
		$status = Configuration::get('MoIP_STATUS_3');
		elseif ($_POST['status_pagamento'] == 5)
		$status = Configuration::get('MoIP_STATUS_4');
		elseif ($_POST['status_pagamento'] == 6)
		$status = Configuration::get('MoIP_STATUS_5');
		elseif ($_POST['status_pagamento'] == 7)
		$status = Configuration::get('MoIP_STATUS_6');								
		if (!isset($_POST['id_transacao']))
		$errors .= $MoIP->getL('id_transacao_moip');
	if (!isset($_POST['email_consumidor']))
		$errors .= $MoIP->getL('email_consumidor_moip');
	 if (!isset($_POST['cod_moip']))
		$errors .= $MoIP->getL('post_cod_moip'); 
	if (empty($errors))
	{
		
			
		$cart = new Cart(intval($id_transacao_moip));
		$valor_compra = number_format($_POST['valor'], 2, '.', '')/100;
		if (!$cart->id)
			$errors = $MoIP->getL('cart').' <br />';
		elseif (Order::getOrderByCartId(intval($id_transacao_moip)))
			$errors = $MoIP->getL('order').' <br />';
		else{


	$currency = new Currency
		(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));

        //Cria order, transacao.
			$MoIP->validateOrder($id_transacao_moip, $status, $valor_compra, $MoIP->displayName, $MoIP->getL('transaction'), $mailVars, $id_moeda);
			
			
            if ($MoIP->currentOrder != ""){

            	log_var("Compra gravado no BD corretamente\nId proprio: ".$_POST['id_transacao']."\nCodigo MoIP: ".$_POST['cod_moip']."\nID transacao: ".$id_transacao_moip."\nID Order PrestaShop: ".$MoIP->currentOrder, "Validacao de dados", true); 
          	    $MoIP->addOrder($id_transacao_moip);
				          
            }
			else 
			log_var("Erro ao gravar compra no BD\nId transacao: ".$id_transacao_moip, "Validacao de dados", true); 			
		}			
			
	}
	
	
		   		log_var("Resultado: ".$result."\nErro: ".$errors."\nTotal: ".$valor_compra."\nStatus MoIP: ".$_POST['status_pagamento']."\nNovo Status: ".$status."\nCart: ".$cart->id."\nOrder: ".$MoIP->currentOrder."\nMoeda: ".$id_moeda, "MoIP Labs Debug, Data: ".date("Y-m-d G:i:s"), true);  
		   		
} else {
	$errors .= $MoIP->getL('VERIFICADO');
			     log_var("NAO VERIFICADO", "MoIP Labs Debug, Data: ".date("Y-m-d G:i:s"), true);  
	
}

if (!empty($errors) AND isset($_POST['id_transacao'])){

    $id_order_db = $MoIP->getOrder($id_transacao_moip);

	$id_transacao = $id_order_db['id_order'];
	$id_transacao_proprio = $id_order_db['id_transaction'];
	
	log_var("ID transacao: ".$id_transacao_moip."\nID Compra(order) PrestaShop: ".$id_transacao, "Recuperando order do BD, Data: ".date("Y-m-d G:i:s"), true);  
	
	
	$extraVars 			= array();
	$history 			= new OrderHistory();
	$history->id_order 	= intval($id_transacao);
	$history->changeIdOrderState(intval($status), intval($id_transacao) );
	$history->addWithemail(true, $extraVars);
	
	}
			     
?>
