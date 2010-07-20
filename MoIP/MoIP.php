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


class MoIP extends PaymentModule
{
		const		INSTALL_SQL_FILE = 'install.sql';
	
	private $_html = '';
    private $_postErrors = array();
    public $currencies;
        
    public $banners = array(
        "imgs/logo_moip.gif" => "imgs/logo_moip.gif",
		"imgs/formas_pagamento.png" => "imgs/formas_pagamento.png",
		"imgs/formas_pagamento_6x.png" => "imgs/formas_pagamento_6x.png",
		"imgs/formas_pagamento_bancos.png" => "imgs/formas_pagamento_bancos.png",
		"imgs/formas_pagamento_bancos_boleto.png" => "imgs/formas_pagamento_bancos_boleto.png",
		"imgs/formas_pagamento_boleto.png" => "imgs/formas_pagamento_boleto.png",
		"imgs/formas_pagamento_cartoes.png" => "imgs/formas_pagamento_cartoes.png",
		"imgs/formas_pagamento_cartoes_bancos.png" => "imgs/formas_pagamento_cartoes_bancos.png",
		"imgs/formas_pagamento_cartoes_boleto.png" => "imgs/formas_pagamento_cartoes_boleto.png",
    	
		'imgs/buttons/bt_pagar_c01_e01.png' => 'imgs/buttons/bt_pagar_c01_e01.png',
		'imgs/buttons/bt_pagar_c02_e01.png' => 'imgs/buttons/bt_pagar_c02_e01.png',
		'imgs/buttons/bt_pagar_c03_e01.png' => 'imgs/buttons/bt_pagar_c03_e01.png',
		'imgs/buttons/bt_pagar_c04_e01.png' => 'imgs/buttons/bt_pagar_c04_e01.png',
		'imgs/buttons/bt_pagar_c05_e01.png' => 'imgs/buttons/bt_pagar_c05_e01.png',
		'imgs/buttons/bt_pagar_c06_e01.png' => 'imgs/buttons/bt_pagar_c06_e01.png',
		'imgs/buttons/bt_pagar_c07_e01.png' => 'imgs/buttons/bt_pagar_c07_e01.png',
		'imgs/buttons/bt_pagar_c08_e01.png' => 'imgs/buttons/bt_pagar_c08_e01.png',
		'imgs/buttons/bt_pagar_c09_e01.png' => 'imgs/buttons/bt_pagar_c09_e01.png',

		'imgs/buttons/bt_pagar_c01_e04.png' => 'imgs/buttons/bt_pagar_c01_e04.png',
		'imgs/buttons/bt_pagar_c02_e04.png' => 'imgs/buttons/bt_pagar_c02_e04.png',
		'imgs/buttons/bt_pagar_c03_e04.png' => 'imgs/buttons/bt_pagar_c03_e04.png',
		'imgs/buttons/bt_pagar_c04_e04.png' => 'imgs/buttons/bt_pagar_c04_e04.png',
		'imgs/buttons/bt_pagar_c05_e04.png' => 'imgs/buttons/bt_pagar_c05_e04.png',
		'imgs/buttons/bt_pagar_c06_e04.png' => 'imgs/buttons/bt_pagar_c06_e04.png',
		'imgs/buttons/bt_pagar_c07_e04.png' => 'imgs/buttons/bt_pagar_c07_e04.png',
		'imgs/buttons/bt_pagar_c08_e04.png' => 'imgs/buttons/bt_pagar_c08_e04.png',
		'imgs/buttons/bt_pagar_c09_e04.png' => 'imgs/buttons/bt_pagar_c09_e04.png'
	);
	
	    public $ambiente_moip = array(
		"Produção" => "https://www.moip.com.br/PagamentoMoIP.do",
		"SandBox" => "https://desenvolvedor.moip.com.br/sandbox/PagamentoMoIP.do"
	);
	
	public function __construct()
    {
    	
        $this->name 			= 'MoIP';
        $this->tab 				= 'Payment';
        $this->version 			= ' I.HTML 1.0 MoIP Labs - BETA';

        $this->currencies 		= true;
        $this->currencies_mode 	= 'radio';

        parent::__construct();

        $this->page 			= basename(__file__, '.php');
        $this->displayName 		= $this->l('MoIP');
        $this->description 		= $this->l('Aceitar pagamentos via MoIP');
		$this->confirmUninstall = $this->l('Tem certeza de que pretende eliminar os seus dados?');
		$this->textButton		= $this->l('Efetuar Pagamento');
	}
	
	public function install()
	{
				// SQL Table
		if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			die('lol');
		elseif (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			die('lal');
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$sql = preg_split("/;\s*[\r\n]+/", $sql);
		foreach ($sql as $query)
			if ($query AND sizeof($query) AND !Db::getInstance()->Execute(trim($query)))
		return false;
					// SQL Table
				
				
		if ( !Configuration::get('MoIP_STATUS_1') )
			$this->create_states();
	
		if 
		(
			!parent::install()
			OR 	!Configuration::updateValue('MoIP_BUSINESS', 'seu_email_moip@dominio.com.br') 
			OR 	!Configuration::updateValue('ambiente_moip', 'https://www.moip.com.br/PagamentoMoIP.do') 			
			OR 	!Configuration::updateValue('LAYOUT', 'default') 			
			OR 	!Configuration::updateValue('MoIP_BANNER', 'imgs/logo_moip.gif')
//			OR 	!Configuration::updateValue('MoIP_BUTTON', 0)
			OR 	!$this->registerHook('payment') 
			OR 	!$this->registerHook('paymentReturn')
			OR 	!$this->registerHook('home')
		)
					return false;
		return true;
	
	}
	
	public function create_states()
	{
		
		$this->order_state = array(
		array( 'c9fecd', '11110', 'MoIP - Autorizado',		'payment'	),
		array( 'ffffff', '00100', 'MoIP - Iniciado', 				 ''	),
		array( 'fcffcf', '00100', 'MoIP - Boleto Impresso',			 ''	),
		array( 'c9fecd', '00100', 'MoIP - Concluido',		  'bankwire' ),
		array( 'fec9c9', '11110', 'MoIP - Cancelado',  'order_canceled'	),
		array( 'fcffcf', '00100', 'MoIP - Em Analise',				 ''	),
		array( 'ffe0bb', '11100', 'MoIP - Estornado', 		   'refund'	),
		array( 'd6d6d6', '00100', 'MoIP - Em Aberto', 		   		 ''	)
		);
		
		/** OBTENDO UMA LISTA DOS IDIOMAS  **/
		$languages = Db::getInstance()->ExecuteS('
		SELECT `id_lang`, `iso_code`
		FROM `'._DB_PREFIX_.'lang`
		');
		/** /OBTENDO UMA LISTA DOS IDIOMAS  **/
		
		/** INSTALANDO STATUS MOIP **/		
		foreach ($this->order_state as $key => $value)
		{
			/** CRIANDO OS STATUS NA TABELA order_state **/
			Db::getInstance()->Execute
			('
				INSERT INTO `' . _DB_PREFIX_ . 'order_state` 
			( `invoice`, `send_email`, `color`, `unremovable`, `logable`, `delivery`) 
				VALUES
			('.$value[1][0].', '.$value[1][1].', \'#'.$value[0].'\', '.$value[1][2].', '.$value[1][3].', '.$value[1][4].');
			');
			/** /CRIANDO OS STATUS NA TABELA order_state **/
			
			$this->figura 	= mysql_insert_id();
			
			foreach ( $languages as $language_atual )
			{
				/** CRIANDO AS DESCRI��ES DOS STATUS NA TABELA order_state_lang  **/
				Db::getInstance()->Execute
				('
					INSERT INTO `' . _DB_PREFIX_ . 'order_state_lang` 
				(`id_order_state`, `id_lang`, `name`, `template`)
					VALUES
				('.$this->figura .', '.$language_atual['id_lang'].', \''.$value[2].'\', \''.$value[3].'\');
				');
				/** /CRIANDO AS DESCRI��ES DOS STATUS NA TABELA order_state_lang  **/	
			}
			
			
				/** COPIANDO O ICONE ATUAL **/
				$file 		= (dirname(__file__) . "/icons/$key.gif");
				$newfile 	= (dirname( dirname (dirname(__file__) ) ) . "/img/os/$this->figura.gif");
				if (!copy($file, $newfile)) {
    			return false;
    			}
    			/** /COPIANDO O ICONE ATUAL **/
			   		
    		/** GRAVA AS CONFIGURA��ES  **/
    		Configuration::updateValue("MoIP_STATUS_$key", 	$this->figura);
    		   				
		}
		
		return true;
		
	}	

	public function uninstall()
	{
		
		if (
			!Configuration::deleteByName('MoIP_BUSINESS')
		OR 	!Configuration::deleteByName('ambiente_moip')			 
		OR 	!Configuration::deleteByName('MoIP_BANNER')
		OR 	!Configuration::deleteByName('LAYOUT')
		OR 	!parent::uninstall()) return false;
		return true;
	}

	public function getContent()
	{
		
		$this->_html = '<h2>MoIP</h2>';
		if (isset($_POST['submitMoIP']))
		{
			if (empty($_POST['business'])) 
			$this->_postErrors[] = $this->l('Digite seu e-mail cadastrado com o MoIP');
	
			elseif (!Validate::isEmail($_POST['business'])) 
			$this->_postErrors[] = $this->l('Digite um e-mail válido.');
			
			if (!sizeof($this->_postErrors)) {
				Configuration::updateValue('MoIP_BUSINESS', $_POST['business']);
				Configuration::updateValue('ambiente_moip', $_POST['ambiente_moip']);
				Configuration::updateValue('LAYOUT', $_POST['layout']);
				
				
				$this->displayConf();
			}
			else $this->displayErrors();
		}
			elseif (isset($_POST['submitMoIP_Banner']))
		{
			Configuration::updateValue('MoIP_BANNER', 	$_POST['banner']);
			$this->displayConf();
		}

		$this->displayMoIP();
		$this->displayFormSettingsMoIP();
		return $this->_html;
	}
	
	public function displayConf()
	{
		
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Configurações atualizadas').'
		</div>';
	}
	
	public function displayErrors()
	{
		
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	public function displayMoIP()
	{
		
		
		
		$this->_html .= '
		<img src="https://www.moip.com.br/imgs/logo_moip.gif" style="float:left; margin-right:15px;" />
		<b>'.$this->l('Este módulo permite aceitar pagamentos via MoIP.').'</b><br /><br />
		'.$this->l('Se o cliente escolher o módulo de pagamento, a conta do MoIP sera automaticamente creditado.').'<br />
		'.$this->l('Você precisa configurar o seu e-mail do MoIP, para depois usar este módulo.').'<br /><br />
		'.$this->l('Será necessário cadastrar a URL de notificação em sua conta MoIP para que o módulo atualize o status de seus pagamentos.').'<br />
		'.$this->l('Acesse sua conta MoIP no menu "Meus dados" >> "Preferências" >> "Notificação das transações", e marque a opção "Receber notificação instantânea de transação".').'<br />
		'.$this->l('Em "<b>URL de notificação</b>" coloque a seguinte URL: <b>http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/MoIP/validation.php</b>').'
		'.$this->l('<br /><br /><b>Pronto</b>, sua loja está integrada com o MoIP !!!').'		
		<br /><br /><br />';
	}

	public function displayFormSettingsMoIP()
	{
		
		$conf 			= Configuration::getMultiple(array('MoIP_BUSINESS', 'LAYOUT', 'ambiente_moip', 'MoIP_BANNER', 'MoIP_BUTTON'));
		$id_carteira 	= array_key_exists('business', $_POST) ? $_POST['business'] : (array_key_exists('MoIP_BUSINESS', $conf) ? $conf['MoIP_BUSINESS'] : '');
		$layout_moip 	= array_key_exists('layout', $_POST) ? $_POST['layout'] : (array_key_exists('LAYOUT', $conf) ? $conf['LAYOUT'] : '');
		$ambiente_moip 	= array_key_exists('ambiente_moip', $_POST) ? $_POST['ambiente_moip'] : (array_key_exists('ambiente_moip', $conf) ? $conf['ambiente_moip'] : '');
		$banner 		= array_key_exists('banner', $_POST) ? $_POST['banner'] : (array_key_exists('MoIP_BANNER', $conf) ? $conf['MoIP_BANNER'] : '');
		$button 		= array_key_exists('button', $_POST) ? $_POST['button'] : (array_key_exists('MoIP_BUTTON', $conf) ? $conf['MoIP_BUTTON'] : '');
		
		/** CONFIGURAÇÕES **/
		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Configurações').'</legend>
			<label>'.$this->l('E-mail com o MoIP').':</label>
			<div class="margin-form"><input type="text" size="33" name="business" value="'.htmlentities($id_carteira, ENT_COMPAT, 'UTF-8').'" /></div><br>
			<label>'.$this->l('Layout').':</label>
			<div class="margin-form"><input type="text" size="33" name="layout" value="'.htmlentities($layout_moip, ENT_COMPAT, 'UTF-8').'" /></div><br>
			<label>'.$this->l('Ambiente').':</label>
			 <div class="margin-form">
			  <select name="ambiente_moip">';

		foreach ( $this->ambiente_moip as $id => $value )
		{
			if ($ambiente_moip ==  $value){
				$check = 'selected'; 
			}else{
				$check = '';
			}			
			$this->_html .=  '
<option value="'.$value.'" '.$check.'>'.$id.'</option>';
			}			

		$this->_html .= '
		</select>
			</div>
			<br />
		<br /><center><input type="submit" name="submitMoIP" value="'.$this->l('Atualizar').'" class="button" /></center>
		</fieldset>
		</form>';
		
		/** BANNER **/
		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/themes.gif" />'.$this->l('Banner').'</legend>';
			
		foreach ( $this->banners as $id => $value )
		{
			if ($banner ==  $id){
				$check = 'checked="checked"'; 
			}else{
				$check = '';
			}
			
			$this->_html .=  '
			<div>
			<input type="radio" name="banner" value="'.$id.'" '.$check.' >
			<img src="http://www.moip.com.br/'.$value.'" />
			</div>
			<br />';
			
		}

		$this->_html .= '<br /><center><input type="submit" name="submitMoIP_Banner" value="'.$this->l('Salvar').'" 
			class="button" />
		</center>
		</fieldset>
		</form>';
	
	
	} 

	
	public function hookPayment($params)
	{		
		global $smarty, $cookie;		

		
		$address = new Address(intval($params['cart']->id_address_invoice));
		$customer = new Customer(intval($params['cart']->id_customer));
		$business = Configuration::get('MoIP_BUSINESS');
		$ambiente_moip = Configuration::get('ambiente_moip');
		$layout = Configuration::get('LAYOUT');
		$banner = Configuration::get('MoIP_BANNER');
		$header = Configuration::get('PAYPAL_HEADER');
		$currency = $this->getCurrency();		
		
		
		
		if (!Validate::isLoadedObject($address) OR !Validate::isLoadedObject($customer) OR !Validate::isLoadedObject($currency))
			return $this->l('MoIP erro: (Endere�o referente ao usuario n�o encontrato.)');
			
			$products = $params['cart']->getProducts();

		foreach ($products as $key => $product)
		{
			$products[$key]['name'] = str_replace('"', '\'', $product['name']);
			if (isset($product['attributes']))
				$products[$key]['attributes'] = str_replace('"', '\'', $product['attributes']);
			$products[$key]['name'] = htmlentities(utf8_decode($product['name']));
			$products[$key]['MoIPAmount'] = number_format(Tools::convertPrice($product['price_wt'], $currency), 2, '.', '');
		}						
       
		
		$smarty->assign(array(
		    'address' => $address,
			'country' => new Country(intval($address->id_country)),
			'customer' => $customer,
			'id_carteira' => $business,
			'header' => $header,
			'currency' => $currency, 
			// products + discounts - shipping cost
			'amount' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 4), $currency), 2, '', ''),
			// shipping cost + wrapping
			'shipping' =>  number_format(Tools::convertPrice(($params['cart']->getOrderShippingCost() + $params['cart']->getOrderTotal(true, 6)), $currency), 2, '', ''),
			'discounts' => $params['cart']->getDiscounts(),
			'valor_total' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 4), $currency), 2, '', '') + number_format(Tools::convertPrice(($params['cart']->getOrderShippingCost() + $params['cart']->getOrderTotal(true, 6)), $currency), 2, '', ''),
			'products' => $products,
		    'produto' => $product['name'],
		    'atributo' => ' [ '.$product['attributes'].' ]',
			'ambiente_moip' => $ambiente_moip,
			'total' => number_format(Tools::convertPrice($params['cart']->getOrderTotal(true, 3), $currency), 2, '.', ''),
			'id_cart' => intval($params['cart']->id),
		    'url_retorno' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'order-confirmation.php?id_cart='.intval($params['cart']->id).'&id_module='.intval($this->id).'&id_order='.intval($this->currentOrder).'&key='.$customer->secure_key,
		    'url_notificacao' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/MoIP/validation.php',
		    'url_retorno_valida' => 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/MoIP/validation.php',
			'imgBtn_1' 	=> "https://www.moip.com.br/imgs/logo_moip.gif",
			'imgBtn' 	=> "https://www.moip.com.br/".$banner,	
			'layout' 	=> $layout,
			'server_name' 	=>  $_SERVER[SERVER_NAME],
			'teste' 	=>  'IDlang: '.$order->id_lang,
			'this_path' => $this->_path, 'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ?	'https://' : 'http://') . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT,'UTF-8') . __PS_BASE_URI__ . 'modules/' . $this->name . '/'));

 //            echo "Valor: ".$total." ID CART: ".$id_cart." Nome: ".$products[$key]['name']." Param: ".$params['objOrder']->id;
		
	
		return $this->display(__file__, 'payment.tpl');
		
					    
	}
	    
	public function getL($key)
	{
		$translations = array(
			'valor_moip' => $this->l('Valor nao especificado corretamente.'),
			'status_pagamento_moip' => $this->l('Status do Pagamento nao defifido corretamente, ou invalido.'),
			'payment' => $this->l('MoIP Pagamentos '),
			'id_transacao_moip' => $this->l('ID Proprio invalido ou nao relacionado a uma ordem de pagamento'),
			'email_consumidor_moip' => $this->l('E-Mail do cliente nao informado, POST invalido.'),
			'post_cod_moip' => $this->l('Codigo MoIP nao informado corretamente, ATENCAO ESSE POST PODE SER FRAUDOLENTO.'),
			'cart' => $this->l('Carrinho nao validado.'),
			'order' => $this->l('Transacao ja processada anteriormente com esse carrinho.'),
			'transaction' => $this->l('Pagamento processado pelo MoIP <br />Codigo MoIP: <b>'.$_POST['cod_moip'].'</b><br />E-mail utilizado na compra: <b>'.$_POST['email_consumidor'].'</b>'),
			'verified' => $this->l('Transacao MoIP nao VERIFICADA.'),
			'mail' => $this->l('Processo de envio, email de notificacao.'),
		);
		return $translations[$key];
	}
    

	function validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special, $dont_touch_amount = false)
	{
		if (!$this->active)
			return ;

		$currency = $this->getCurrency();
		$cart = new Cart(intval($id_cart));
		$cart->id_currency = $currency->id;
		$cart->save();
		parent::validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod, $message, $extraVars, $currency_special, true);
	}

    
	public function addOrder($id_transaction)
	{
		return Db::getInstance()->Execute('
		INSERT INTO `'._DB_PREFIX_.'moip_order` (`id_order`, `id_transaction`)
		VALUES('.intval($this->currentOrder).', \''.pSQL($id_transaction).'\')');
	}

	public function getOrder($id_transaction)
	{
		$rq = Db::getInstance()->getRow('
		SELECT `id_order` FROM `'._DB_PREFIX_.'moip_order`
		WHERE id_transaction = \''.pSQL($id_transaction).'\'');
		return $rq;
		
	}
	
    function getStatus($param)
    {
    	global $cookie;
    		
    		$sql_status = Db::getInstance()->Execute
		('
			SELECT `name`
			FROM `'._DB_PREFIX_.'order_state_lang`
			WHERE `id_order_state` = '.$param.'
			AND `id_lang` = '.$cookie->id_lang.'
			
		');
		
		return mysql_result($sql_status, 0);
    }
    
    public function enviar($mailVars, $template, $assunto, $DisplayName, $idCustomer, $idLang, $CustMail, $TplDir)
	{
		
		Mail::Send
			( intval($idLang), $template, $assunto, $mailVars, $CustMail, null, null, null, null, null, $TplDir);
		
	}
	
	public function getUrlByMyOrder( $myOrder )
	{

			$module				= Module::getInstanceByName($myOrder->module);			
			$pagina_qstring		= __PS_BASE_URI__."order-confirmation.php?id_cart="
								  .$myOrder->id_cart."&id_module=".$module->id."&id_order="
								  .$myOrder->id."&key=".$myOrder->secure_key;			
			
			if	(	$_SERVER['HTTPS']	!=	"on"	)
			$protocolo			=	"http";
			
			else
			$protocolo			=	"https";
			
			$retorno 			= $protocolo . "://" . $_SERVER['SERVER_NAME'] . $pagina_qstring;			
			return $retorno;
	
	}
    
}
?>