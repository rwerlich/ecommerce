<?php if(!class_exists('Rain\Tpl')){exit;}?>
<head>
    <style type="text/css">
        body{font-family: Calibri;}
    </style>



<table width="100%" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td style="border-bottom:1px solid #b3b3b3; padding: 20px 0 20px 0" bgcolor="#fafafa"
                style="padding:15px;">
                <div align="center">
                    <img src="https://s3.amazonaws.com/codecademy-images/php.png" width="115px"
                         height="95px">
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding:32px 0px 27px 27px;" valign="top" >                    
                Prezado <?php echo htmlspecialchars( $name, ENT_COMPAT, 'UTF-8', FALSE ); ?>, <br><br>
                <a href="<?php echo htmlspecialchars( $link, ENT_COMPAT, 'UTF-8', FALSE ); ?>" target="_blank">Clique aqui</a> para redefinir sua senha em nosso sistema.
            </td>                
        </tr>            
        <tr>
            <td bgcolor="#fafafa" style="border-top:1px solid #b3b3b3; padding: 20px 0 20px 0">
                <div align="center">
                    Copyright &copy; Ecommerce Werlich
                    <br>Todos os direitos reservados.
                </div>
            </td>
        </tr>
    </tbody>
</table>    


