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

<?php
    function get_diff_percent($current, $before) {

        $current = is_string($current) ? number_format($current) : $current;
        $before = is_string($current) ? number_format($before) : $before;
        
        if ($before == 0 && $current == 0) {
            return '0';
        } else if ($before == $current) {
            return '0';
        }

        $percent = $before > 0 ? (($current - $before) / $before) * 100 : 100;

        $icon = $current > $before ? ' <i class="fa fa-level-up" style="color: green" aria-hidden="true"></i>' : ' <i class="fa fa-level-down" style="color: red" aria-hidden="true"></i>';
        
        return number_format($percent, 2).'%';
    }
?>
<body style="background-color: transparent; font-size:12px;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <h4 style="text-align: center">Produtos em Estoque</h4>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="font-size: 1em; padding: 5px;">Código</th>
                                    <th style="font-size: 1em; padding: 5px;">Nome</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Compra</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Venda</th>
                                    <th style="font-size: 1em; padding: 5px;">Margem</th>
                                    <th style="font-size: 1em; padding: 5px;">Qtt. Atual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalCompra = 0;
                                    $totalVenda = 0;
                                    $total = 0;
                                    $totalQtt = 0;
                                    foreach ($produtos as $p) {
                                        $total += ($p->precoVenda-$p->precoCompra)*$p->estoque;
                                        $totalCompra += $p->precoCompra*$p->estoque;
                                        $totalVenda += $p->precoVenda*$p->estoque;
                                        $totalQtt += $p->estoque;
                                        echo '<tr>';
                                        echo '<td>' . $p->codDeBarra . '</td>';
                                        echo '<td>' . $p->descricao . '</td>';
                                        echo '<td>R$ '.number_format($p->precoCompra, 2, ',', '.') . '</td>';
                                        echo '<td>R$ '.number_format($p->precoVenda, 2, ',', '.') . '</td>';
                                        echo '<td style="text-align: center">' . get_diff_percent($p->precoVenda, $p->precoCompra) . '</td>';
                                        echo '<td style="text-align: center">' . $p->estoque . '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align: right; font-weight: bold" colspan="2">Total</td>
                                    <td>R$ <?= number_format($totalCompra, 2, ',', '.'); ?></td>
                                    <td>R$ <?= number_format($totalVenda, 2, ',', '.'); ?></td>
                                    <td></td>
                                    <td style="text-align: center"><?= $totalQtt; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <?php $percentualLucro = $totalVenda-$totalCompra ?>
                <h5 style="text-align: right">Total de lucro: R$ <?= number_format($total, 2, ',', '.'); ?> (<?= get_diff_percent($totalVenda,$totalCompra); ?>)</h5>
                <h5 style="text-align: right">Data do Relatório: <?php echo date('d/m/Y'); ?></h5>
            </div>
        </div>
    </div>
</body>

</html>
