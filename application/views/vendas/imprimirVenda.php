<?php $totalProdutos = 0; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?php echo $this->config->item('app_name') ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-style.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/matrix-media.css" />
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.min.js"></script>
    <style>
        label { font-size: 1em;}
    </style>
</head>
<body style="padding: 0; margin:0;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
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
                                        <th style="font-size: 1em; width: 250px;">Produto</th>
                                        <th style="font-size: 1em; width: 100px; text-align: center">Quantidade</th>
                                        <th style="font-size: 1em; width: 100px; text-align: center">Desc. %</th>
                                        <th style="font-size: 1em">Preço unit.</th>
                                        <th style="font-size: 1em">Sub-total</th>
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
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/matrix.js"></script>
    <script>
        window.print();
    </script>
</body>

</html>
