<?php if(!class_exists('Rain\Tpl')){exit;}?>
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Confirmar Pedido</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-content-right">
                    <form action="/ecommerce/checkout" class="checkout" method="post" name="checkout">
                        <div id="customer_details" class="col2-set">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="alert alert-danger">
                                        Error!
                                    </div>

                                    <div class="woocommerce-billing-fields">
                                        <h3>Endereço de entrega</h3>
                                        <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                            <label class="" for="billing_address_1">Cep <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" value="<?php echo htmlspecialchars( $cart["zipcode"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" placeholder="00000-000" name="zipcode" class="input-text" id="cep" autofocus>                                            
                                        </p>
                                        <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                            <label class="" for="billing_address_1">Endereço <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" value="" placeholder="Logradouro e número" name="desaddress" class="input-text" id="logradouro">
                                        </p>
                                        <p id="billing_address_2_field" class="form-row form-row-wide address-field">
                                            <input type="text" value="" placeholder="Complemento (opcional)" name="descomplement" class="input-text ">
                                        </p>
                                        <p id="billing_district_field" class="form-row form-row-wide address-field validate-required" data-o_class="form-row form-row-wide address-field validate-required">
                                            <label class="" for="billing_district">Bairro <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" value="" placeholder="Bairro" name="desdistrict" class="input-text" id="bairro">
                                        </p>
                                        <p id="billing_city_field" class="form-row form-row-wide address-field validate-required" data-o_class="form-row form-row-wide address-field validate-required">
                                            <label class="" for="billing_city">Cidade <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" value="" placeholder="Cidade" id="cidade" name="city" class="input-text ">
                                        </p>
                                        <p id="billing_state_field" class="form-row form-row-first address-field validate-state" data-o_class="form-row form-row-first address-field validate-state">
                                            <label class="" for="billing_state">Estado</label>
                                            <input type="text" name="state" placeholder="Estado" value="" class="input-text" id="uf">
                                        </p>
                                        <p id="billing_state_field" class="form-row form-row-first address-field validate-state" data-o_class="form-row form-row-first address-field validate-state">
                                            <label class="" for="billing_state">País</label>
                                            <input type="text" name="country" placeholder="País" value="Brasil" class="input-text ">
                                        </p>
                                        <div class="clear"></div>
                                        <h3 id="order_review_heading" style="margin-top:30px;">Detalhes do Pedido</h3>
                                        <div id="order_review" style="position: relative;">
                                            <table class="shop_table">
                                                <thead>
                                                    <tr>
                                                        <th class="product-name">Produto</th>
                                                        <th class="product-total">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>
                                                    <tr class="cart_item">
                                                        <td class="product-name">
                                                            <?php echo htmlspecialchars( $value1["product"], ENT_COMPAT, 'UTF-8', FALSE ); ?> <strong class="product-quantity">× <?php echo htmlspecialchars( $value1["nrqtd"], ENT_COMPAT, 'UTF-8', FALSE ); ?></strong> 
                                                        </td>
                                                        <td class="product-total">
                                                            <span class="amount">R$<?php echo formatPrice($value1["vltotal"]); ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr class="cart-subtotal">
                                                        <th>Subtotal</th>
                                                        <td><span class="amount">R$<?php echo formatPrice($cart["vlsubtotal"]); ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="shipping">
                                                        <th>Frete</th>
                                                        <td>
                                                            R$<?php echo formatPrice($cart["vlfreight"]); ?>
                                                            <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                                        </td>
                                                    </tr>
                                                    <tr class="order-total">
                                                        <th>Total do Pedido</th>
                                                        <td><strong><span class="amount">R$<?php echo formatPrice($cart["vltotal"]); ?></span></strong> </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div id="payment">
                                                <div class="form-row place-order">
                                                    <input type="submit" data-value="Place order" value="Continuar" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function() {
    function limpa_formulário_cep(alerta) {
        if (alerta !== undefined) {
            alert(alerta);
        }
        $("#rua").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
    }
    $("#cep").blur(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $('#cep').val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                $("#logradouro").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#uf").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#logradouro").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#uf").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });
});
</script>