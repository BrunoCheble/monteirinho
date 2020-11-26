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
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAssistencia') && $result->cancelado == 0) { ?>
                        <a title="Icon Title" class="btn btn-mini btn-primary" href="<?= base_url() . 'index.php/assistencias/adicionar/' . $result->idVendas; ?>"><i class="fas fa-wrench"></i> Nova assistência</a>
                    <?php } ?>
                    <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda') && $result->cancelado == 0 && ($this->session->userdata('permissao') != 2 || $result->usuarios_id == $this->session->userdata('id'))) {
                        echo '<a title="Editar Venda" class="btn btn-mini btn-info" href="' . base_url() . 'index.php/vendas/editar/' . $result->idVendas . '"><i class="fas fa-edit"></i> Editar</a>';
                    } ?>
                    <a target="_blank" title="Imprimir" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/vendas/imprimir/<?php echo $result->idVendas; ?>"><i class="fas fa-print"></i> Imprimir</a>
                    <!--<a target="_blank" title="Imprimir" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/vendas/imprimirTermica/<?php echo $result->idVendas; ?>"><i class="fas fa-print"></i> Imprimir Não Fiscal</a>-->
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
                                                </br>
                                                <span>Data Venda<br><?php echo date('d/m/Y',strtotime($result->dataVenda)); ?></span>
                                                <?php if($result->faturado == 0) : ?>
                                                </br>
                                                <span class="label label-inverse">Não Faturado</span>
                                                <?php endif; ?> 
                                                <?php if($result->cancelado == 1) : ?>
                                                </br>
                                                <span class="label label-warning">VENDA CANCELADO</span>
                                                <?php endif; ?> 
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
                                <?php echo $result->numero ?> <?php echo $result->complemento ?>,
                                <?php echo $result->bairro ?> - <?php echo $result->cidade ?> / <?php echo $result->estado ?>
                            </label>
                            <?php echo $result->referenciaMorada ? '<label><b>Ponto de Referência:</b> '.$result->referenciaMorada.'</label>' : ''; ?>
                            <br>
                            <?php echo $agendamento ? '<label><b>Data de Entrega:</b> '.date('d/m/Y',strtotime($agendamento->data)).'</label>' : '' ?>
                            <label><b>Observação:</b></label>
                            <label><?php echo nl2br($result->observacao) ?></label>
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
                                            $precoUni = str_replace(',','',$p->preco);
                                            echo '<tr>';
                                            echo '<td>' . $p->descricao . '</td>';
                                            echo '<td style="text-align:center">' . $p->quantidade . '</td>';
                                            echo '<td>' . ($p->desconto ? $p->desconto.'%' : '') . '</td>';
                                            echo '<td style="text-align:right">R$ ' . number_format($precoUni, 2, ',', '.') . '</td>';
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
