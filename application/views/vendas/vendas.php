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
          <input class="span12 datepicker" name="data_inicio" value="<?= $data_inicio; ?>" type="text">
        </div>
        <div class="span6">
          <label><i class="fas fa-calendar-day tip-top" title="Data fim"></i> Data Fim</label>
          <input class="span12 datepicker" name="data_fim" value="<?= $data_fim; ?>" type="text">
        </div>
      </div>
        
      <div class="row-fluid">
        <div class="span6">
          <label><i class="fas fa-store tip-top" title="Lançamentos com vencimento no período."></i> Loja</label>
          <select name="loja" class="span12">
            <option value="0">Todas</option>
            <option value="2">Loja A</option>
            <option value="3">Loja B</option>
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
        $(document).on('click', 'a', function(event) {
            var venda = $(this).attr('venda');
            $('#idVenda').val(venda);
        });

        $('[name="loja"]').val('<?= $loja; ?>');
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $('.table').DataTable({
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
                        }
                    },
                ]
            });
    });
</script>
