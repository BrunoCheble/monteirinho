<?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) { ?>
    <a href="<?php echo base_url(); ?>index.php/produtos/adicionar" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Produto</a>
<?php } ?>

<a href="<?php echo base_url(); ?>index.php/relatorios/produtosRapid" target="_new" class="btn btn-primary span3" style="float: right;">
        <i class="fas fa-print"></i> Imprimir Produtos</a>

<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="fas fa-shopping-bag"></i>
        </span>
        <h5>Produtos</h5>
    </div>
    <div class="widget-content nopadding">
        <?= $table_produtos; ?>
    </div>
</div>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/produtos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-trash-alt"></i> Excluir Produto</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idProduto" class="idProduto" name="id" value=""/>
            <h5 style="text-align: center">Deseja realmente excluir este produto?</h5>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-danger">Excluir</button>
        </div>
    </form>
</div>

<!-- Modal Estoque -->
<div id="atualizar-estoque" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/produtos/atualizar_estoque" method="post" id="formEstoque">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-plus-square"></i> Atualizar Estoque</h5>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label for="estoqueAtual" class="control-label">Estoque Atual</label>
                <div class="controls">
                    <input id="estoqueAtual" type="text" name="estoqueAtual" value="" readonly />
                </div>
            </div>

            <div class="control-group">
                <label for="estoque" class="control-label">Adicionar Produtos<span class="required">*</span></label>
                <div class="controls">
                    <input type="hidden" id="idProduto" class="idProduto" name="id" value=""/>
                    <input id="estoque" type="text" name="estoque" value=""/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-primary">Atualizar</button>
        </div>
    </form>
</div>

<!-- Modal Etiquetas -->
<div id="modal-etiquetas" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/relatorios/produtosEtiquetas" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Gerar etiquetas com Código de Barras</h5>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Escolha o intervalo de produtos para gerar as etiquetas.</div>

            <div class="span12" style="margin-left: 0;">
                <div class="span6" style="margin-left: 0;">
                    <label for="valor">De</label>
                    <input class="span9" style="margin-left: 0" type="text" id="de_id" name="de_id" placeholder="ID do primeiro produto" value=""/>
                </div>


                <div class="span6">
                    <label for="valor">Até</label>
                    <input class="span9" type="text" id="ate_id" name="ate_id" placeholder="ID do último produto" value=""/>
                </div>

                <div class="span4">
                    <label for="valor">Qtd. do Estoque</label>
                    <input class="span12" type="checkbox" name="qtdEtiqueta" value="true"/>
                </div>

                <div class="span6">
                    <label class="span12" for="valor">Formato Etiqueta</label>
                    <select name="etiquetaCode">
                        <option value="EAN13">EAN-13</option>
                        <option value="UPCA">UPCA</option>
                        <option value="C93">CODE 93</option>
                        <option value="C128A">CODE 128</option>
                        <option value="CODABAR">CODABAR</option>
                        <option value="QR">QR-CODE</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
            <button class="btn btn-success">Gerar</button>
        </div>
    </form>
</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<!-- Modal Etiquetas e Estoque-->
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', 'a', function (event) {
            var produto = $(this).attr('produto');
            var estoque = $(this).attr('estoque');
            $('.idProduto').val(produto);
            $('#estoqueAtual').val(estoque);
        });

        $('.table').DataTable({
                order: [[ 1, "asc" ]],
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
            });
        $('#formEstoque').validate({
            rules: {
                estoque: {
                    required: true,
                    number: true
                }
            },
            messages: {
                estoque: {
                    required: 'Campo Requerido.',
                    number: 'Informe um número válido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>

