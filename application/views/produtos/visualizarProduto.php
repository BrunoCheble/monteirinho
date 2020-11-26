<div class="accordion" id="collapse-group">
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title">
                <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                    <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                    <h5>Dados do Produto</h5>
                </a>
            </div>
        </div>
        <div class="collapse in accordion-body">
            <div class="widget-content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Cod. Produto</strong></td>
                            <td>
                                <?php echo $result->codDeBarra ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Descrição</strong></td>
                            <td>
                                <?php echo $result->descricao ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Compra</strong></td>
                            <td>R$
                                <?php echo str_replace('.',',',$result->precoCompra); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Venda no Cartão</strong></td>
                            <td>R$
                                <?php echo str_replace('.',',',$result->precoVenda); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Venda no Dinheiro</strong></td>
                            <td>R$
                                <?php echo str_replace('.',',',$result->precoVendaDinheiro); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Estoque</strong></td>
                            <td>
                                <?php echo $result->estoque; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Mostruário</strong></td>
                            <td>
                                <?php echo $result->estoqueMinimo; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php 
                if($result->foto != '') {
                    echo '<img src="'.$result->foto.'" width="100%" />';
                }
                ?>
            </div>
        </div>
    </div>
</div>
