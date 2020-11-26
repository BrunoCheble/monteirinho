<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) { ?>
    <a href="<?php echo base_url(); ?>index.php/clientes/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Cliente</a>
<?php } ?>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-user"></i>
        </span>
        <h5>Clientes</h5>
    </div>

    <div class="widget-content nopadding">
        <?= $table_clientes; ?>
    </div>
</div>


<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Excluir Cliente</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idCliente" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este cliente ?</h5>
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
            var cliente = $(this).attr('cliente');
            $('#idCliente').val(cliente);
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
