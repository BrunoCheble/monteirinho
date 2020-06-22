<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>

<?php $situacao = $this->input->get('situacao');
$periodo = $this->input->get('periodo');
?>

<style type="text/css">
  label.error {
    color: #b94a48;
  }

  input.error {
    border-color: #b94a48;
  }

  input.valid {
    border-color: #5bb75b;
  }
</style>


<div class="row-fluid">
    <div class="span12">
  <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aLancamento')) { ?>
      <a href="#modalReceita" data-toggle="modal" role="button" class="btn btn-success tip-bottom" title="Cadastrar nova receita"><i class="fas fa-plus"></i> Nova Receita</a>
      <a href="#modalDespesa" data-toggle="modal" role="button" class="btn btn-danger tip-bottom" title="Cadastrar nova despesa"><i class="fas fa-minus"></i> Nova Despesa</a>
  <?php } ?>
    <a href="#modalFiltro" data-toggle="modal" role="button" style="margin-right:3px" class="btn btn-primary tip-bottom pull-right" title="Filtrar lançamentos"><i class="fas fa-filter"></i> Filtrar</a>
    </div>
</div>

<div class="span12" style="margin-left: 0;">

    <div class="widget-box">
      <div class="widget-title">
        <span class="icon">
          <i class="fas fa-hand-holding-usd"></i>
        </span>
        <h5>Lançamentos Financeiros</h5>
      </div>

      <div class="widget-content nopadding">
        <?= $table_lancamentos; ?>
      </div>
    </div>

</div>

<?php echo $this->pagination->create_links();  ?>

<!-- Modal nova receita -->
<div id="modalReceita" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form id="formReceita" action="<?php echo base_url() ?>index.php/financeiro/adicionarReceita" method="post">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Adicionar Receita</h3>
    </div>
    <div class="modal-body">
      <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
      <div class="span12" style="margin-left: 0">
        <label for="descricao">Descrição</label>
        <input class="span12" id="descricao" type="text" name="descricao" />
        <input id="urlAtual" type="hidden" name="urlAtual" value="<?php echo site_url() ?>" />
      </div>
      <div class="span12" style="margin-left: 0">
        <div class="span4" style="margin-left: 0">
          <label for="valor">Valor*</label>
          <input type="hidden" id="tipo" name="tipo" value="receita" />
          <input class="span12 money" id="valor" type="text" name="valor" />
        </div>
        <div class="span4">
          <label for="data">Data*</label>
          <input class="span12 datepicker" value="<?= date('d/m/Y'); ?> " autocomplete="off" id="data" type="text" name="data" />
        </div>
        <div class="span4">
          <label for="formaPgto">Forma Pgto</label>
          <select name="formaPgto" id="formaPgto" class="span12">
            <option value="Dinheiro">Dinheiro</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
      <button class="btn btn-success">Adicionar Receita</button>
    </div>
  </form>
</div>

<!-- Modal nova despesa -->
<div id="modalDespesa" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form id="formDespesa" action="<?php echo base_url() ?>index.php/financeiro/adicionarDespesa" method="post">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Adicionar Despesa</h3>
    </div>
    <div class="modal-body">
      <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
      <div class="span12" style="margin-left: 0">
        <label for="descricao">Descrição</label>
        <input class="span12" id="descricao" type="text" name="descricao" />
        <input id="urlAtual" type="hidden" name="urlAtual" value="<?php echo current_url() ?>" />
      </div>
      <div class="span12" style="margin-left: 0">
        <div class="span12" style="margin-left: 0">
          <label for="fornecedor">Fornecedor</label>
          <input class="span12" id="fornecedor" type="text" name="fornecedor" />
        </div>
      </div>
      <div class="span12" style="margin-left: 0">
        <div class="span4" style="margin-left: 0">
          <label for="valor">Valor*</label>
          <input type="hidden" name="tipo" value="despesa" />
          <input class="span12 money" type="text" name="valor" />
        </div>
        <div class="span4">
          <label for="vencimento">Data*</label>
          <input class="span12 datepicker" type="text" name="data" />
        </div>
        <div class="span4">
          <label for="formaPgto">Forma Pgto</label>
          <select name="formaPgto" id="formaPgto" class="span12">
              <option value="Dinheiro">Dinheiro</option>
              <option value="Cartão de Crédito">Cartão de Crédito</option>
              <option value="Boleto">Boleto</option>
              <option value="Depósito">Depósito</option>
              <option value="Débito">Débito</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
      <button class="btn btn-danger">Adicionar Despesa</button>
    </div>
  </form>
</div>

<!-- Modal Excluir lançamento-->
<div id="modalExcluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Excluir Lançamento</h3>
  </div>
  <div class="modal-body">
    <h5 style="text-align: center">Deseja realmente excluir esse lançamento?</h5>
    <input name="id" id="idExcluir" type="hidden" value="" />
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnCancelExcluir">Cancelar</button>
    <button class="btn btn-danger" id="btnExcluir">Excluir Lançamento</button>
  </div>
</div>

<!-- Modal Filtro lançamento-->
<div id="modalFiltro" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="<?php echo current_url(); ?>" method="get">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Filtrar Lançamentos</h3>
    </div>
    <div class="modal-body">
      <div class="row-fluid">
        <div class="span6">
          <label><i class="fas fa-calendar-day tip-top" title="Lançamentos com vencimento no período."></i> Data Início</label>
          <input name="data_inicio" class="span12 datepicker" value="<?= isset($data_inicio) ? $data_inicio : date('d/m/Y'); ?>" type="text">
        </div>
        <div class="span6">
          <label><i class="fas fa-calendar-day tip-top" title="Lançamentos com vencimento no período."></i> Data Fim</label>
          <input name="data_fim" class="span12 datepicker" value="<?= isset($data_fim) ? $data_fim : date('d/m/Y'); ?>" type="text">
        </div>
      </div>
        
      <?php if ($this->session->userdata('permissao') != 2) : ?>
      <div class="row-fluid">
        <div class="span6">
          <label><i class="fas fa-store tip-top" title="Lançamentos com vencimento no período."></i> Loja</label>
          <select name="loja" class="span12">
            <option value="0">Todas</option>
            <option value="3">Austin</option>
            <option value="2">Philomeno</option>
          </select>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnCancelarEditar">Cancelar</button>
      <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
    </div>
  </form>
</div>

<!--

      

            -->




<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function($) {

    $('[name="urlAtual"]').val(location.href);
    <?php if($periodo) : ?>
    $('[name="periodo"]').val('<?= $periodo; ?>');
    <?php endif; ?>
    <?php if($situacao) : ?>
    $('[name="situacao"]').val('<?= $situacao; ?>');
    <?php endif; ?>
    $(".money").maskMoney();

    $('#pago').click(function(event) {
      var flag = $(this).is(':checked');
      if (flag == true) {
        $('#divPagamento').show();
      } else {
        $('#divPagamento').hide();
      }
    });


    $('#recebido').click(function(event) {
      var flag = $(this).is(':checked');
      if (flag == true) {
        $('#divRecebimento').show();
      } else {
        $('#divRecebimento').hide();
      }
    });

    $('#pagoEditar').click(function(event) {
      var flag = $(this).is(':checked');
      if (flag == true) {
        $('#divPagamentoEditar').show();
      } else {
        $('#divPagamentoEditar').hide();
      }
    });


    $("#formReceita").validate({
      rules: {
        descricao: {
          required: true
        },
        valor: {
          required: true
        },
        data: {
          required: true
        }

      },
      messages: {
        descricao: {
          required: 'Campo Requerido.'
        },
        valor: {
          required: 'Campo Requerido.'
        },
        data: {
          required: 'Campo Requerido.'
        }
      }
    });



    $("#formDespesa").validate({
      rules: {
        descricao: {
          required: true
        },
        valor: {
          required: true
        },
        data: {
          required: true
        }

      },
      messages: {
        descricao: {
          required: 'Campo Requerido.'
        },
        valor: {
          required: 'Campo Requerido.'
        },
        data: {
          required: 'Campo Requerido.'
        }
      }
    });


    $(document).on('click', '.excluir', function(event) {
      $("#idExcluir").val($(this).attr('idLancamento'));
    });


    $(document).on('click', '.editar', function(event) {
      $("#idEditar").val($(this).attr('idLancamento'));
      $("#descricaoEditar").val($(this).attr('descricao'));
      $("#fornecedorEditar").val($(this).attr('cliente'));
      $("#valorEditar").val($(this).attr('valor'));
      $("#vencimentoEditar").val($(this).attr('vencimento'));
      $("#pagamentoEditar").val($(this).attr('pagamento'));
      $("#formaPgtoEditar").val($(this).attr('formaPgto'));
      $("#tipoEditar").val($(this).attr('tipo'));
      $("#urlAtualEditar").val(location.href);
      var baixado = $(this).attr('baixado');
      if (baixado == 1) {
        $("#pagoEditar").attr('checked', true);
        $("#divPagamentoEditar").show();
      } else {
        $("#pagoEditar").attr('checked', false);
        $("#divPagamentoEditar").hide();
      }


    });

    $(document).on('click', '#btnExcluir', function(event) {
      var id = $("#idExcluir").val();

      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>index.php/financeiro/excluirLancamento",
        data: "id=" + id,
        dataType: 'json',
        success: function(data) {
          if (data.result == true) {
            $("#btnCancelExcluir").trigger('click');
            $("#divLancamentos").html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');
            $("#divLancamentos").load(location.href + " #divLancamentos");

          } else {
            $("#btnCancelExcluir").trigger('click');
            Swal.fire({
              type: "error",
              title: "Atenção",
              text: "Ocorreu um erro ao tentar excluir produto."
            });
          }
        }
      });
      return false;
    });

    $(".datepicker").datepicker({
      dateFormat: 'dd/mm/yy'
    });

  });
</script>
