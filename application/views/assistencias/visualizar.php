<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab1">Dados do Cliente</a></li>
            <li><a data-toggle="tab" href="#tab2">Vendas</a></li>
            <div class="buttons">
                <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
                    echo '<a title="Icon Title" class="btn btn-mini btn-info" href="' . base_url() . 'index.php/clientes/editar/' . $result->idClientes . '"><i class="fas fa-edit"></i> Editar</a>';
                } ?>
            </div>
        </ul>
    </div>
    <div class="widget-content tab-content">
        <div id="tab1" class="tab-pane active" style="min-height: 300px">

            <div class="accordion" id="collapse-group">
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                                <span class="icon"><i class="fas fa-user"></i></span>
                                <h5>Dados Pessoais</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse in accordion-body" id="collapseGOne">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>Nome</strong></td>
                                        <td>
                                            <?php echo $result->nomeCliente ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Documento</strong></td>
                                        <td>
                                            <?php echo $result->documento ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Data de Cadastro</strong></td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($result->dataCadastro)) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse">
                                <span class="icon"><i class="fas fa-phone-alt"></i></span>
                                <h5>Contatos</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGTwo">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>Contato:</strong></td>
                                        <td>
                                            <?php echo $result->contato ?>
                                        </td>
                                    </tr>
                                        <tr>
                                            <td style="text-align: right; width: 30%"><strong>Telefone</strong></td>
                                            <td>
                                                <?php echo $result->telefone ?>
                                            </td>
                                        </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Celular</strong></td>
                                        <td>
                                            <?php echo $result->celular ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Email</strong></td>
                                        <td>
                                            <?php echo $result->email ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-group widget-box">
                    <div class="accordion-heading">
                        <div class="widget-title">
                            <a data-parent="#collapse-group" href="#collapseGThree" data-toggle="collapse">
                                <span class="icon"><i class="fas fa-map-marked-alt"></i></span>
                                <h5>Endereço</h5>
                            </a>
                        </div>
                    </div>
                    <div class="collapse accordion-body" id="collapseGThree">
                        <div class="widget-content">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right; width: 30%"><strong>Rua</strong></td>
                                        <td>
                                            <?php echo $result->rua ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Número</strong></td>
                                        <td>
                                            <?php echo $result->numero ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Complemento</strong></td>
                                        <td>
                                            <?php echo $result->complemento ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Bairro</strong></td>
                                        <td>
                                            <?php echo $result->bairro ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>Cidade</strong></td>
                                        <td>
                                            <?php echo $result->cidade ?> -
                                            <?php echo $result->estado ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right"><strong>CEP</strong></td>
                                        <td>
                                            <?php echo $result->cep ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




        </div>


        <!--Tab 2-->
        <div id="tab2" class="tab-pane" style="min-height: 300px">
            <?php if (!$results) { ?>

                <table class="table table-bordered ">
                    <thead>
                        <tr style="backgroud-color: #2D335B">
                            <th>N° Venda</th>
                            <th>Data da Venda</th>
                            <th>Faturado</th>
                            <th>Loja</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td colspan="6">Nenhuma Venda Cadastrada</td>
                        </tr>
                    </tbody>
                </table>

            <?php
            } else { ?>




                <table class="table table-bordered ">
                    <thead>
                        <tr style="backgroud-color: #2D335B">
                            <th>N° Venda</th>
                            <th>Data da Venda</th>
                            <th>Faturado</th>
                            <th>Loja</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($results as $r) {
                                $dataVenda = date(('d/m/Y'), strtotime($r->dataVenda));
                                if ($r->faturado == 1) {
                                    $faturado = 'Sim';
                                } else {
                                    $faturado = 'Não';
                                }
                                echo '<tr>';
                                echo '<td style="font-weight: bold; text-align:center">' . $r->idVendas . '</td>';
                                echo '<td style="text-align:center">' . $dataVenda . '</td>';
                                echo '<td style="text-align:center">' . $faturado . '</td>';
                                echo '<td style="text-align:center">' . $r->usuario . '</td>';
                                echo '<td style="width: 250px; text-align: center;">';
                                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
                                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/visualizar/' . $r->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimir/' . $r->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir A4"><i class="fas fa-print"></i></a>';
                                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimirTermica/' . $r->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir Não Fiscal"><i class="fas fa-print"></i></a>';
                                }
                                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
                                    echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/editar/' . $r->idVendas . '" class="btn btn-info tip-top" title="Editar venda"><i class="fas fa-edit"></i></a>';
                                }
                                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda')) {
                                    echo '<a href="#modal-excluir" role="button" data-toggle="modal" venda="' . $r->idVendas . '" class="btn btn-danger tip-top" title="Excluir Venda"><i class="fas fa-trash-alt"></i></a>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            } ?>
                        <tr>

                        </tr>
                    </tbody>
                </table>


            <?php
            } ?>

        </div>
    </div>
</div>
