<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-cash-register"></i>
                </span>
                <h5>Editar Venda</h5>
            </div>
            <div class="widget-content nopadding">
                <div class="span12" id="divProdutosServicos" style=" margin-left: 0">
                    <ul class="nav nav-tabs">
                        <li class="active" id="tabDetalhes"><a href="#tab1" data-toggle="tab">Detalhes da Venda</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <div class="span12" id="divEditarVenda">
                                <form action="<?php echo current_url(); ?>" method="post" id="formVendas">
                                    <?php echo form_hidden('idVendas', $result->idVendas) ?>
                                    <?php echo form_hidden('usuarios_id', $result->usuarios_id) ?>
                                    <?php echo form_hidden('dataEntrega', $result->dataEntrega) ?>
                                    <?php echo form_hidden('observacao', $result->observacao) ?>
                                    
                                    <div class="span12" style="padding: 1%; margin-left: 0">
                                        <h3 style="text-align:center; margin:0;">Nº da Venda:
                                            <?php echo $result->idVendas ?>
                                        </h3>
                                        <div class="span2" style="margin-left: 0">
                                            <label for="dataFinal">Data Venda</label>
                                            <input id="dataVenda" class="span12 " <?= $result->faturado ? 'readonly': 'datepicker' ?> type="text" name="dataVenda" value="<?php echo date('d/m/Y', strtotime($result->dataVenda)); ?>" />
                                        </div>
                                        <div class="span5">
                                            <label for="cliente">Cliente<span class="required">*</span></label>
                                            <input id="cliente" class="span12" <?= $result->faturado ? 'disabled': '' ?> type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                                            <input id="clientes_id" class="span12" type="hidden" name="clientes_id" value="<?php echo $result->clientes_id ?>" />
                                            <input id="valorTotal" type="hidden" name="valorTotal" value="" />
                                        </div>
                                        <div class="span5" style="text-align: right;">
                                            <a href="<?php echo base_url() ?>index.php/vendas/visualizar/<?php echo $result->idVendas; ?>" class="btn btn-info"><i class="fas fa-eye"></i><br>Visualizar</a>
                                            <button class="btn btn-primary" id="btnContinuar"><i class="fas fa-sync-alt"></i><br>Atualizar</button>
                                            <a href="<?php echo base_url() ?>index.php/vendas" class="btn"><i class="fas fa-backward"></i><br>Voltar</a>
                                        </div>
                                    </div>
                                </form>
                                <?php if ($result->faturado == 0): ?>
                                <div class="span12 well" style="padding: 1%; margin-left: 0">
                                    <form id="formProdutos" action="<?php echo base_url(); ?>index.php/vendas/adicionarProduto" method="post">
                                        <div class="span4">
                                            <input type="hidden" name="idProduto" id="idProduto" />
                                            <input type="hidden" name="idVendasProduto" id="idVendasProduto" value="<?php echo $result->idVendas ?>" />
                                            <input type="hidden" name="estoque" id="estoque" value="" />
                                            <label for="">Produto</label>
                                            <input type="text" class="span12" name="produto" id="produto" placeholder="Digite o nome do produto" />
                                        </div>
                                        <div class="span2">
                                            <label for="">Preço</label>
                                            <select id="preco" id="preco" name="preco" autocomplete="off" class="span12 money"></select>
                                            <!--
                                            <input type="text" placeholder="Preço" id="preco" name="preco" autocomplete="off" class="span12 money" />
                                            -->
                                        </div>
                                        <div class="span2">
                                            <label for="">Desconto</label>
                                            <select class="span12" name="desconto" id="desconto">
                                                <option>0%</option>
                                                <option value="2.5">2,5%</option>
                                                <option value="3">3%</option>
                                                <option value="5">5%</option>
                                                <option value="10">10%</option>
                                                <option value="15">15%</option>
                                                <option value="20">20%</option>
                                                <option value="50">50%</option>
                                            </select>
                                        </div>
                                        <div class="span2">
                                            <label for="">Quantidade</label>
                                            <input type="text" placeholder="Quantidade" onkeypress="return isNumber(event)" autocomplete="off" id="quantidade" name="quantidade" class="span12" />
                                        </div>
                                        <div class="span2">
                                            <label for="">&nbsp</label>
                                            <button class="btn btn-success span12" id="btnAdicionarProduto"><i class="fas fa-plus"></i> Adicionar</button>
                                        </div>
                                    </form>
                                </div>
                                <?php endif; ?>
                                <div class="span12" id="divProdutos" style="margin-left: 0">
                                    <table class="table table-bordered" id="tblProdutos">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Quantidade</th>
                                                <th>Preço</th>
                                                <th>Desconto %</th>
                                                <?= $result->faturado == 0 ? '<th>Ações</th>' : '' ?>
                                                <th>Sub-total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            foreach ($produtos as $p) {
                                                $preco = $p->preco ?: $p->precoVenda;
                                                $total = $total + $p->subTotal;
                                                echo '<tr>';
                                                echo '<td>' . $p->descricao . '</td>';
                                                echo '<td style="text-align: center">' . $p->quantidade . '</td>';
                                                echo '<td>' . $preco . '</td>';
                                                echo '<td style="text-align: center">' . ($p->desconto > 0 ? $p->desconto.'%' : '') . '</td>';
                                                echo $result->faturado == 0 ? '<td style="text-align: center"><a href="" idAcao="' . $p->idItens . '" prodAcao="' . $p->idProdutos . '" quantAcao="' . $p->quantidade . '" title="Excluir Produto" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>' : '';
                                                echo '<td>R$ ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                                echo '</tr>';
                                            } ?>
                                            <tr>
                                                <td colspan="<?= $result->faturado == 0 ? 5 : 4?>" style="text-align: right"><strong>Total:</strong></td>
                                                <td><strong>R$
                                                        <?php echo number_format($total, 2, ',', '.'); ?></strong> <input type="hidden" id="total-venda" value="<?php echo number_format($total, 2); ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="padding: 1%; margin-left: 0">
                                    <div class="span2">
                                        <label for="dataEntrega">Data Entrega</label>
                                        <input id="dataEntrega" class="span12 datepicker" type="text" value="<?php echo $result->dataEntrega ? date('d/m/Y', strtotime($result->dataEntrega)) : null; ?>" />
                                    </div>
                                    <div class="span10">
                                        <label for="observacao">Obrservação do Pedido</label>
                                        <textarea id="observacao" class="span12"><?php echo $result->observacao; ?></textarea>
                                    </div>
                                </div>
                                <div style="padding: 1%; margin-left: 0;">
                                    <?php if ($result->faturado == 0) : ?>
                                        <a href="#modal-faturar" id="btn-faturar" role="button" data-toggle="modal" class="btn btn-success"><i class="fas fa-cash-register"></i> Faturar</a>
                                    <?php
                                    else : ?>
                                        <button id="reabrir_venda" class="btn btn-warning"><i class="fas fa-cash-register"></i> Reabrir e excluir Faturamentos</button>
                                    <?php endif; ?>

                                    <?php if ($result->cancelado == 0) : ?>
                                        <button id="cancelar_venda" class="btn btn-danger pull-right"><i class="fas fa-times"></i> Cancelar Venda</button>
                                    <?php
                                    else : ?>
                                        <button disabled class="btn btn-danger pull-right"><i class="fas fa-times"></i> Cancelado</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                &nbsp
            </div>
        </div>
    </div>
</div>

<!-- Modal Faturar-->
<div id="modal-faturar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formFaturar" action="<?php echo current_url() ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Faturar Venda</h3>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Obrigatório o preenchimento dos campos com asterisco.</div>
            <div class="span12" style="margin-left: 0">
                <label for="descricao">Descrição</label>
                <input class="span12" id="descricao" type="text" name="descricao" value="Fatura de Venda - #<?php echo $result->idVendas; ?> " />
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span12" style="margin-left: 0">
                    <label for="cliente">Cliente*</label>
                    <input class="span12" id="cliente" type="text" name="cliente" value="<?php echo $result->nomeCliente ?>" />
                    <input type="hidden" name="clientes_id" id="clientes_id" value="<?php echo $result->clientes_id ?>">
                    <input type="hidden" name="vendas_id" id="vendas_id" value="<?php echo $result->idVendas; ?>">
                </div>
            </div>
            <div class="span12" style="margin-left: 0">
                <div class="span4" style="margin-left: 0">
                    <label for="valor">Valor*</label>
                    <input type="hidden" id="tipo" name="tipo" value="receita" />
                    <input class="span12 money" id="valor" type="text" name="valor" value="" />
                </div>
                <div class="span4">
                    <label for="data">Data Recebimento</label>
                    <input class="span12 datepicker" value="<?php echo date('d/m/Y'); ?>" autocomplete="off" id="recebimento" type="text" name="data" />
                </div>
                <div id="divRecebimento" class="span4">
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
            <button class="btn" data-dismiss="modal" aria-hidden="true" id="btn-cancelar-faturar">Cancelar</button>
            <button class="btn btn-primary">Faturar</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".money").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

        $('#dataEntrega').change(function(){
            $('#formVendas [name="dataEntrega"]').val($(this).val());
        });
        
        $('#observacao').change(function(){
            $('#formVendas [name="observacao"]').val($(this).val());
        });

        $('#observacao, #dataEntrega').change();
        
        $('#cancelar_venda').click(function(){
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/vendas/cancelarVenda",
                data: {
                    vendas_id: "<?php echo $result->idVendas; ?>"
                },
                success: function() {
                    window.location.reload(true);
                }
            });
        });
        $('#reabrir_venda').click(function(){
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/vendas/reabrirVenda",
                data: {
                    vendas_id: "<?php echo $result->idVendas; ?>"
                },
                success: function() {
                    window.location.reload(true);
                }
            });
        });

        $('#recebido').click(function(event) {
            var flag = $(this).is(':checked');
            if (flag == true) {
                $('#divRecebimento').show();
            } else {
                $('#divRecebimento').hide();
            }
        });
        $(document).on('click', '#btn-faturar', function(event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>index.php/vendas/calcularValorPendente",
                data: {
                    vendas_id: "<?php echo $result->idVendas; ?>"
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result == true) {
                        $('#formFaturar #valor').val(data.pending_value);
                    } else {
                        window.location.reload(true);
                    }
                }
            });
        });
        $("#formFaturar").validate({
            rules: {
                descricao: {
                    required: true
                },
                cliente: {
                    required: true
                },
                valor: {
                    required: true
                },
                vencimento: {
                    required: true
                }
            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                cliente: {
                    required: 'Campo Requerido.'
                },
                valor: {
                    required: 'Campo Requerido.'
                },
                vencimento: {
                    required: 'Campo Requerido.'
                }
            },
            submitHandler: function(form) {
                var dados = $(form).serialize();
                $('#btn-cancelar-faturar').trigger('click');
                $('#loading').show();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/vendas/faturar",
                    data: dados,
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            window.location.reload(true);
                        } else {
                            $('#loading').hide();
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: data.message
                            });
                            $('#progress-fatura').hide();
                        }
                    }
                });
                return false;
            }
        });
        $("#produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteProdutoSaida",
            minLength: 2,
            select: function(event, ui) {
                $("#idProduto").val(ui.item.id);
                $("#estoque").val(ui.item.estoque);
                $("#preco").html('');
                $("#preco").append('<option value="'+ui.item.preco+'">'+ui.item.preco+'</option>');
                $("#preco").append('<option value="'+ui.item.preco_dinheiro+'">'+ui.item.preco_dinheiro+'</option>');
                $("#quantidade").focus();
            }
        });
        $("#cliente").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteCliente",
            minLength: 2,
            select: function(event, ui) {
                $("#clientes_id").val(ui.item.id);
            }
        });
        $("#tecnico").autocomplete({
            source: "<?php echo base_url(); ?>index.php/os/autoCompleteUsuario",
            minLength: 2,
            select: function(event, ui) {
                $("#usuarios_id").val(ui.item.id);
            }
        });
        $("#formVendas").validate({
            rules: {
                cliente: {
                    required: true
                },
                tecnico: {
                    required: true
                },
                dataVenda: {
                    required: true
                }
            },
            messages: {
                cliente: {
                    required: 'Campo Requerido.'
                },
                tecnico: {
                    required: 'Campo Requerido.'
                },
                dataVenda: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $('#loading').hide();
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
        $("#formProdutos").validate({
            rules: {
                quantidade: {
                    required: true
                }
            },
            messages: {
                quantidade: {
                    required: 'Insira a quantidade'
                }
            },
            highlight: function(element, errorClass, validClass) {
                $('#loading').hide();
            },
            submitHandler: function(form) {
                var quantidade = parseInt($("#quantidade").val());
                var estoque = parseInt($("#estoque").val());
                $('#loading').hide();
                    
                <?php if (!$configuration['control_estoque']) {
                                                echo 'estoque = 1000000';
                                            }; ?>
                
                if (estoque < quantidade) {
                    Swal.fire({
                        type: "warning",
                        title: "Atenção",
                        text: "Você não possui estoque suficiente."
                    });
                } else {
                    var dados = $(form).serialize();
                    $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>index.php/vendas/adicionarProduto",
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            if (data.result == true) {
                                $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                                $("#quantidade").val('');
                                $("#preco").html('');
                                $("#produto").val('').focus();
                            } else {
                                Swal.fire({
                                    type: "error",
                                    title: "Atenção",
                                    text: "Ocorreu um erro ao tentar adicionar produto."
                                });
                            }
                        }
                    });
                    return false;
                }
            }
        });
        $(document).on('click', 'a', function(event) {
            var idProduto = $(this).attr('idAcao');
            var quantidade = $(this).attr('quantAcao');
            var produto = $(this).attr('prodAcao');
            if ((idProduto % 1) == 0) {
                $("#divProdutos").html("<div class='progress progress-info progress-striped active'><div class='bar' style='width: 100%'></div></div>");
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>index.php/vendas/excluirProduto",
                    data: "idProduto=" + idProduto + "&quantidade=" + quantidade + "&produto=" + produto + "&idVenda=<?= $result->idVendas ?>",
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == true) {
                            $("#divProdutos").load("<?php echo current_url(); ?> #divProdutos");
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "Atenção",
                                text: "Ocorreu um erro ao tentar excluir produto."
                            });
                        }
                    }
                });
                return false;
            }
        });
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
</script>
