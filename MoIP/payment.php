<?php

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



/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/MoIP.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
$MoIP = new MoIP();
echo $MoIP->execPayment($cart);



		    log_var("cookie: ".$cookie->isLogged().
   
	        "\ncart: ".$MoIP->execPayment($cart), "payment.php", true);


include_once(dirname(__FILE__).'/../../footer.php');

?>