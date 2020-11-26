<!DOCTYPE html>
<html>

<head>
    <title>PDV</title>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fullcalendar.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/blue.css" class="skin-color" />
</head>

<body style="background-color: transparent; font-size:10px; line-height: 10px; margin: 0; padding:0;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <h4 style="text-align: center">Relatório de Produtos</h4>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="font-size: 1em; padding: 5px;">Código</th>
                                    <th style="font-size: 1em; padding: 5px;">Nome</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Venda Cartão</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Venda Dinheiro</th>
                                    <th style="font-size: 1em; padding: 5px;">Qtt. Atual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalQtt = 0;

                                    foreach ($produtos as $p) {
                                        
                                        $totalQtt += $p->estoque;
                                        echo '<tr>';
                                        echo '<td>' . $p->codDeBarra . '</td>';
                                        echo '<td>' . $p->descricao . '</td>';
                                        echo '<td>R$ '.number_format($p->precoVenda, 2, ',', '.') . '</td>';
                                        echo '<td>R$ '.number_format($p->precoVendaDinheiro, 2, ',', '.') . '</td>';
                                        echo '<td style="text-align: center">' . $p->estoque . '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align: right; font-weight: bold" colspan="2"></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center"><?= $totalQtt; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
