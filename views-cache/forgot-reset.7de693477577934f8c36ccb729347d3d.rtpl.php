<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Esqueceu a Senha?</h2>
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
                <?php if( $valido > 0 ){ ?>
                <form id="login-form-wrap" class="login" method="post" action="/ecommerce/forgot/reset">
                    <input type="hidden" name="code" value="<?php echo htmlspecialchars( $code, ENT_COMPAT, 'UTF-8', FALSE ); ?>">                    
                    <p class="form-row form-row-first">
                        <label for="password">Nova senha <span class="required">*</span>
                        </label>
                        <input type="password" id="password" name="password" class="input-text" style="width:350px">
                    </p>
                    <div class="clear"></div>
                    <p class="form-row">
                        <input type="submit" value="Enviar" name="login" class="button">
                        
                    </p>

                    <div class="clear"></div>
                </form>  
                <?php }else{ ?>
                <div class="alert alert-danger">
                    <h4 class="alert-heading">Código de redefinição inválido!</h4>
                    <p>O código não existe ou expirou.</p>
                </div>      
                <?php } ?>
            </div>
        </div>
    </div>
</div>