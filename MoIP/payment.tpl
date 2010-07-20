<p class="payment_module">
	<a href="javascript:$('#envia_moip').submit();" title="{l s='Pague com MoIP' mod='MoIP'}">
		<img src="{$imgBtn}" alt="{l s='Pague com MoIP' mod='MoIP'}" />
		{l s='Pague com MoIP' mod='MoIP'}
	</a>
</p>

<form action="{$ambiente_moip}" method="post" id="envia_moip" class="hidden">

	<input type="hidden" name="id_carteira" value="{$id_carteira}" />
	<input type="hidden" name="pagador_nome" value="{$address->firstname} {$address->lastname}" />
	<input type="hidden" name="pagador_logradouro" value="{$address->address1}" />
	<input type="hidden" name="pagador_cidade" value="{$address->city}" />
    <input type="hidden" name="pagador_numero" value="1" />
    <input type="hidden" name="pagador_estado" value="" />
	<input type="hidden" name="pagador_cep" value="{$address->postcode}" />
	<input type="hidden" name="pagador_bairro" value="{$country->iso_code}" />
    <input type="hidden" name="pagador_telefone" value="{$address->phone}" />
    <input type="hidden" name="pagador_email" value="{$customer->email}" />
	<input type="hidden" name="valor" value="{$valor_total}" />
	<input type="hidden" name="nome" value="Pedido: {$id_cart} - {$shop_name}" />
 
{if ($cart_qties == "1")}
	<input type="text" name="descricao" value="Produto: {$produto}{$atributo}" />

{else}
	<input type="text" name="descricao" value="Pedido de compra contendo ({$cart_qties}) itens, produto indicativo: {$produto}" />
{/if}

	<input type="hidden" name="id_cliente" value="{$customer->id}" />	
	<input type="hidden" name="id_transacao" value="{$server_name} [{$id_cart}]" />	
	<input type="hidden" name="layout" value="{$layout}" />	
    <input type="hidden" name="url_retorno" value="{$url_retorno}" />    
		
</form>
