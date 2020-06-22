<?php $totalProdutos = 0; ?>
<div class="row-fluid" style="margin-top: 0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-cash-register"></i>
                </span>
                <h5>Venda</h5>
                <div class="buttons">
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
    echo '<a title="Editar Venda" class="btn btn-mini btn-info" href="' . base_url() . 'index.php/vendas/editar/' . $result->idVendas . '"><i class="fas fa-edit"></i> Editar</a>';
} ?>
                    <a target="_blank" title="Imprimir" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/vendas/imprimir/<?php echo $result->idVendas; ?>"><i class="fas fa-print"></i> Imprimir</a>
                    <a target="_blank" title="Imprimir" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/vendas/imprimirTermica/<?php echo $result->idVendas; ?>"><i class="fas fa-print"></i> Imprimir Não Fiscal</a>
                </div>
            </div>
            <div class="invoice-content">
                    <div class="invoice-head">
                        <table class="table">
                            <tbody>
                                    <tr>
                                        <td style="width: 25%; border-top: 0"><img src=" <?php echo $emitente->url_logo; ?> "></td>
                                        <td style="border-top: 0">
                                                <h4><?php echo $emitente->nome; ?></h4>
                                                <label><?php echo $emitente->cnpj; ?></label>
                                                <label><?php echo $emitente->rua . ', nº:' . $emitente->numero . ', ' . $emitente->bairro . ' ' . $emitente->cidade . ' - ' . $emitente->uf; ?></label>
                                                <label><?= $emitente->telefone; ?> / <?= $emitente->celular; ?></label>
                                                <label><?= $emitente->email; ?></label>
                                                
                                            </span></td>
                                            <td style="width: 18%; text-align: center; border-top: 0">
                                                <h4>Nº <?php echo $result->idVendas ?></h4>
                                            </br><span>Data Venda<br><?php echo date('d/m/Y',strtotime($result->dataVenda)); ?></span>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                        <div style="border-top: 1px dashed #ccc;">
                            <h4>Detalhes do Cliente</h4>
                            
                            <label><b>Nome:</b> <?php echo $result->nomeCliente ?></label>
                            <label><b>CPF / CNPJ:</b> <?php echo $result->documento ?></label>
                            <label><b>E-mail:</b> <?php echo $result->email ?></label>
                            <label><b>Contato:</b> <?php echo $result->telefone ?> - <?php echo $result->celular ?></label>
                            <label><b>Endereço:</b> <?php echo $result->rua ?>,
                                <?php echo $result->numero ?>,
                                <?php echo $result->bairro ?> - <?php echo $result->cidade ?> / <?php echo $result->estado ?>
                            </label>
                            <br>
                            <label><b>Observação:</b></label>
                            <label>Entregar na parte da manhã;<br>Na portaria chamar por Bruno Azevedo</label>
                        </div>
                    </div>
                    <div>
                        <h4>Lista dos Produtos</h4>
                        <?php if ($produtos != null) { ?>
                            <table class="table table-bordered table-condensed" id="tblProdutos">
                                <thead>
                                    <tr>
                                        <th style="font-size: 1em">Produto</th>
                                        <th style="font-size: 1em; width: 100px; text-align: center">Quantidade</th>
                                        <th style="font-size: 1em; width: 100px; text-align: center">Desc. %</th>
                                        <th style="font-size: 1em; width: 150px;">Preço unit.</th>
                                        <th style="font-size: 1em; width: 150px;">Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($produtos as $p) {
                                            $totalProdutos = $totalProdutos + $p->subTotal;
                                            echo '<tr>';
                                            echo '<td>' . $p->descricao . '</td>';
                                            echo '<td style="text-align:center">' . $p->quantidade . '</td>';
                                            echo '<td>' . ($p->desconto ? $p->desconto.'%' : '') . '</td>';
                                            echo '<td style="text-align:right">R$ ' . number_format($p->precoVenda, 2, ',', '.') . '</td>';
                                            echo '<td style="text-align:right">R$ ' . number_format($p->subTotal, 2, ',', '.') . '</td>';
                                            echo '</tr>';
                                        } ?>
                                </tbody>
                            </table>
                        <?php
                        } ?>
                        <h4 style="text-align: right;">Valor Total: R$<?php echo number_format($totalProdutos, 2, ',', '.'); ?></h4>
                    </div>
                </div>
        </div>
    </div>
</div>
