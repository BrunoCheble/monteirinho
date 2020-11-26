<?php
defined('BASEPATH') OR exit('No direct script access allowed'); //Loading url helper
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= $this->config->item('app_name') ?> </title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--===============================================================================================-->
  <!-- Font Awesome -->
  <link rel="icon" type="image/png" href="<?= base_url() ?>assets/img/banner-login.png" />
  <!--===============================================================================================-->
  <!--===============================================================================================-->
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300&display=swap" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/util.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/main.css">
    <link href="<?= base_url() ?>assets/pnotify/dist/pnotify.css" media="all" rel="stylesheet" type="text/css" />

  <style>
      * {font-family: 'Roboto Slab', serif!important;}
    .form-color {
      /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#dddddd+0,f7f7f7+52 */
      background: #dddddd;
      /* Old browsers */
      background: -moz-linear-gradient(top, #dddddd 0%, #f7f7f7 52%);
      /* FF3.6-15 */
      background: -webkit-linear-gradient(top, #dddddd 0%, #f7f7f7 52%);
      /* Chrome10-25,Safari5.1-6 */
      background: linear-gradient(to bottom, #dddddd 0%, #f7f7f7 52%);
      /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dddddd', endColorstr='#f7f7f7', GradientType=0);
      /* IE6-9 */
    }

    .login100-more {
      background-image: url('<?= base_url() ?>assets/img/banner-login.jpg')
    }

    .login100-form-btn {
      background: #e43228
    }
    .has-val + .focus-input100 + .label-input100 {
        top: 10px;
    }
    .alert-validate::after{
        content: "❌";
        font-family: initial;
    }

    .focus-input100 {
        border: 1px solid #eeeeee;
    }
  </style>
  <link href="<?= base_url() ?>assets/login/customer.css" rel="stylesheet">
</head>

<body style="background-color: #666666;">
<div id="loading" style="z-index: 99999999999; background: #000; width: 100%; display: none; height: 100vh; opacity: .8; position: fixed; text-align: center;"></div>
 
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <form class="login100-form" id="formLogin">
        <span class="login100-form-title p-b-43">
          <img style="max-width: 190px;" src="<?= base_url() ?>assets/img/logo.png" />
        </span>

        <div id="message" style="display:none" class="alert alert-danger"></div>
        <div class="wrap-input100">
          <input class="input100" name="email" id="email" type="text" >
          <span class="focus-input100"></span>
          <span class="label-input100">Usuário</span>
        </div>

        <div class="wrap-input100">
          <input class="input100" name="senha" type="password" id="password">
          <span class="focus-input100"></span>
          <span class="label-input100">Senha</span>
        </div>

            <div class="container-login100-form-btn p-t-43">
                <button id="btn-acessar" type="submit" class="login100-form-btn">Entrar</button>
            </div>
            <div style="text-align:center;font-size:.8em; margin-top: 1em">Esqueci a senha ou ainda não tenho utilizador.</div>
        </form>
        <div class="login100-more"></div>
        <span style="display:none">Photo by rawpixel.com from Pexels</span>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="<?= base_url() ?>assets/js/jquery-1.12.4.min.js"></script>
  <script src="<?= base_url() ?>assets/login/js/main.js"></script>
  <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
  <script src="<?= base_url() ?>assets/js/validate.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/pnotify/dist/pnotify.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {

      $('#email').keyup(function () {
        $(this).val($(this).val().toLowerCase().trim());
      });

      $('#email').focus();

      $("#formLogin").submit(function(e) {
        e.preventDefault();
        var dados = $(this).serializeArray();
        
        if($('#password').val().trim() == '' || $('#email').val().trim() == '') {
          new PNotify({
              title: 'Usuário e senha são obrigatórios',
              type: 'error',
              styling: 'bootstrap3',
          });
          return false;
        }
        
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: "<?= site_url('login/verificarLogin?ajax=true'); ?>",
            data: dados,
            dataType: 'json',
            success: function (data) {
                if (data.result == true) {
                window.location.href = "<?= site_url('agendamentos'); ?>";
                } else {

                    $('#btn-acessar').removeAttr('disabled');
                    
                    $('#loading').hide();
                    var data = {
                        title: 'Erro na autenticação',
                        text: data.message || 'Os dados de acesso estão incorretos, por favor tente novamente!',
                        type: 'error',
                        styling: 'bootstrap3',
                    };

                    new PNotify(data);
                }
            }
        });
        return false;
      });
    });
  </script>
</body>

</html>