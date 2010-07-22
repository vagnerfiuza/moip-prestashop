Módulo integração HTML - PrestaShop
====================================

Módulo desenvolvido pela equipe MoIP Pagamentos (http://www.moip.com.br ), testado na versão 1.2.5.0 (http://www.prestashop.com/ ).


Este módulo possui:
- Inclusão de layout personalizado da pagina de pagamento MoIP.
- Retorno do cliente a sua loja.
- Atualização automática de status do pagamento.
- Envio automático de e-mail quando ocorre alteração de status.

Instalação
===========

1. Acesse sua conta MoIP e certifique-se que a ferramenta HTML está devidamente habilitada. Caso a mesma não esteja acesse sua conta no menu *Ferramentas* >> *Ferramentas disponíveis” >> *Integração HTML* em **Visão geral** clique no link para ativar a ferramenta.

2. Envie a pasta MoIP para a pasta **modules** de sua loja PrestaShop

3. Habilite a opção de pagamento MoIP em seu administrativo PrestaShop, *Payment* , escolha a opção **MoIP v I.HTML 1.0 MoIP Labs** e clique no botão **Install** (assim como imagem a seguir):

![img1](http://labs.moip.com.br/imagens_documentacao/moip_prestashop1.png)

4. Após instalar o módulo você irá visualizar a pagina de visão geral com a mensagem de módulo instalado com sucesso. 
Assim como imagem abaixo, clique no botão **>> Configure** ou **>> Configurar** para configurar e habilitar seu novo Módulo de pagamento MoIP.

 
![img2](http://labs.moip.com.br/imagens_documentacao/moip_prestashop2.png)

5. Logo você irá visualizar a pagina onde estão presentes os campos para configuração assim como imagem a seguir. Você deverá preencher os campos assim como descreve as legendas.

6. No Campo “E-mail com o MoIP”, você deverá preencher com seu e-mail cadastrado com o MoIP. 

7. No campo “Layout” você poderá preencher com o nome do layout criado em sua conta MoIP, para criar ou alterar um layout acesse sua conta MoIP no menu “Meus Dados” >> “ Preferências “ >> “ Layout personalizado da pagina de pagamento”.

8. No campo “Ambiente”, você poderá escolher o ambiente de atuação, seja “Produção” para efetuar fendas concretas ambiente real ou “SandBox” para realizar algum tipo de teste em nosso ambiente de tentes.

Obs: Para utilizar o “SandBox” é necessário que você tenha conta no mesmo, ou seja uma conta de teste, caso não tenha acesse o endereço “https://desenvolvedor.moip.com.br/sandbox/MainMenu.do?method=home” e realize um cadastro em nosso Sandbox.


![img3](http://labs.moip.com.br/imagens_documentacao/moip_prestashop3.png)

9. Em “Banner”, você poderá escolher qual o banner a ser exibido para o cliente como botão de pagamento, ou seja escolher o botão que irá levar o cliente para a pagina do MoIP.

10. **ATENÇÃO:** Não se esqueça de habilitar e configurar o NASP (Notificação de alteração de status) em sua conta MoIP, para que o Módulo atualiza o status de seus pagamentos recebidos.
 
Acesse sua conta MoIP no menu "Meus dados" >> "Preferências" >> "Notificação das transações", e marque a opção "Receber notificação instantânea de transação".
Em "URL de notificação" coloque a URL de sua loja onde está o E-Commece “PrestaShop” instalado seguido pelo endereço do módulo MoIP pagamentos e o arquivo que faz a validação dos dados, URL do módulo com o arquivo **“/modules/MoIP/validation.php”**, segue exemplo abaixo.

**Ex:** http://www.sualojaprestashop.com.br/modules/MoIP/validation.php


**Obs:** O Módulo MoIP Pagamentos I.HTML v1.0 lhe oferece a URL correta em seu administrativo, para que não ocorra nenhum erro sugerimos que copie e cole o endereço em sua conta MoIP.

11. Pronto, sua loja PrestaShop está configurado com a forma de pagamento MoIP, Bons Negócios.

Módulo em funcionamento:


![img4](http://labs.moip.com.br/imagens_documentacao/moip_prestashop4.png)
