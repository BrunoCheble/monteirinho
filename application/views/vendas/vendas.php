<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>

<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) { ?>
    <a href="<?php echo base_url(); ?>index.php/vendas/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Venda</a>
<?php } ?>

<a href="#modalFiltro" data-toggle="modal" role="button" style="margin-right:3px" class="btn btn-primary tip-bottom pull-right" title="Filtrar lançamentos"><i class="fas fa-filter"></i> Filtrar</a>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-cash-register"></i>
        </span>
        <h5>Vendas</h5>
    </div>
    <div class="widget-content nopadding">
        <?= $table_vendas; ?>
    </div>
</div>

<!-- Modal Filtro lançamento-->
<div id="modalFiltro" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="<?php echo current_url(); ?>" method="get">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Filtrar Vendas</h3>
    </div>
    <div class="modal-body">
      <div class="row-fluid">
        <div class="span6">
          <label><i class="fas fa-calendar-day tip-top" title="Data início"></i> Data Início</label>
          <input class="span12 datepicker" autocomplete="off" name="data_inicio" value="<?= isset($data_inicio) ? $data_inicio : date('01/m/Y'); ?>" type="text">
        </div>
        <div class="span6">
          <label><i class="fas fa-calendar-day tip-top" title="Data fim"></i> Data Fim</label>
          <input class="span12 datepicker" autocomplete="off" name="data_fim" value="<?= isset($data_fim) ? $data_fim : date('d/m/Y'); ?>" type="text">
        </div>
      </div>
        
      <div class="row-fluid">
      
        <?php if ($this->session->userdata('permissao') != 2) : ?>
          <div class="span6">
            <label><i class="fas fa-store tip-top" title="Lançamentos com vencimento no período."></i> Loja</label>
            <select name="loja" class="span12">
              <option selected value="0">Todas</option>
              <option value="<?= getenv('loja_austin'); ?>">Austin</option>
              <option value="<?= getenv('loja_philomeno'); ?>">Philomeno</option>
            </select>
          </div>
        <?php endif; ?>
        
        <div class="span6">
          <label><i class="fas fa-flag tip-top" title="Situações das vendas"></i> Situação</label>
          <select name="estado" class="span12">
            <option selected value="0">Todas</option>
            <option value="nao_faturados">Não faturados</option>
            <option value="cancelados">Cancelados</option>
          </select>
        </div>

      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" aria-hidden="true" id="btnCancelarEditar">Cancelar</button>
      <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
    </div>
  </form>
</div>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/vendas/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Venda</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idVenda" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir esta Venda?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
      <?php if(isset($loja_filtrada)) : ?>
      $('#modalFiltro [name="loja"]').val(<?= $loja_filtrada; ?>);
      <?php endif; ?>
      
      <?php if(isset($estado_filtrado)) : ?>
      $('#modalFiltro [name="estado"]').val('<?= $estado_filtrado; ?>');
      <?php endif; ?>

        $(document).on('click', 'a', function(event) {
            var venda = $(this).attr('venda');
            $('#idVenda').val(venda);
        });

        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $('.table').DataTable({
                order: [[ 0, "desc" ]],
                language: {
                    info: "Exibindo _START_ a _END_ de _TOTAL_ registos",
                    zeroRecords: "Nenhum registos foi encontrado",
                    lengthMenu: "Exibir _MENU_ registos por página",
                    infoEmpty: "",
                    infoFiltered: "(busca aplicada em _MAX_ registos)",
                    search: "Buscar: ",
                    paginate: {
                        next: '&#8594;', // or '→'
                        previous: '&#8592;' // or '←' 
                    }
                },
                recordsTotal: 1000,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "Blfrtip",
                buttons: [
                    {
                        extend: "print",
                        text: 'Imprimir Relatório',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        customize: function(win) {
                            $(win.document.body).find('h1').text('Clientes');
                            $(win.document.body).append('<h3 style="text-align: right;">Total das vendas: '+$('#total-vendas').text()+'</h3>');
                        }
                    },
                ]
            });
    });
</script>
