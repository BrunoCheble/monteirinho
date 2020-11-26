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

        $current = is_string($current) ? floatval($current) : $current;
        $before = is_string($current) ? floatval($before) : $before;
                
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
<body style="background-color: transparent; font-size:10px; line-height: 10px; margin: 0; padding:0;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <h4 style="text-align: center">Relatório de Produtos - Admin</h4>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="font-size: 1em; padding: 5px;">Código</th>
                                    <th style="font-size: 1em; padding: 5px;">Nome</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Compra</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Venda Cartão</th>
                                    <th style="font-size: 1em; padding: 5px;">Margem no Cartão</th>
                                    <th style="font-size: 1em; padding: 5px;">P. Venda Dinheiro</th>
                                    <th style="font-size: 1em; padding: 5px;">Margem no Dinheiro</th>
                                    <th style="font-size: 1em; padding: 5px;">Qtt. Atual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalQtt = 0;

                                    $resumo = [
                                      'lucro' => [
                                        'totalCartao' => 0,
                                        'totalDinheiro' => 0,
                                      ],
                                      'no_estoque' => [
                                        'totalCompra' => 0,
                                        'totalVendaNoCartao' => 0,
                                        'totalVendaNoDinheiro' => 0,
                                      ],
                                      'geral' => [
                                        'totalCompra' => 0,
                                        'totalVendaNoCartao' => 0,
                                        'totalVendaNoDinheiro' => 0,
                                      ],
                                    ];

                                    foreach ($produtos as $p) {

                                        $resumo['lucro']['totalCartao'] += ($p->precoVenda-$p->precoCompra)*$p->estoque;
                                        $resumo['lucro']['totalDinheiro'] += ($p->precoVendaDinheiro-$p->precoCompra)*$p->estoque;

                                        $resumo['no_estoque']['totalCompra']  += $p->precoCompra*$p->estoque;
                                        $resumo['no_estoque']['totalVendaNoCartao'] += $p->precoVenda*$p->estoque;
                                        $resumo['no_estoque']['totalVendaNoDinheiro'] += $p->precoVendaDinheiro*$p->estoque;

                                        $resumo['geral']['totalCompra'] += $p->precoCompra;
                                        $resumo['geral']['totalVendaNoCartao'] += $p->precoVenda;
                                        $resumo['geral']['totalVendaNoDinheiro'] += $p->precoVendaDinheiro;

                                        $totalQtt += $p->estoque;
                                        echo '<tr>';
                                        echo '<td>' . $p->codDeBarra . '</td>';
                                        echo '<td>' . $p->descricao . '</td>';
                                        echo '<td>R$ '.number_format($p->precoCompra, 2, ',', '.') . '</td>';
                                        echo '<td>R$ '.number_format($p->precoVenda, 2, ',', '.') . '</td>';
                                        echo '<td style="text-align: center">' . get_diff_percent($p->precoVenda, $p->precoCompra) . '</td>';
                                        echo '<td>R$ '.number_format($p->precoVendaDinheiro, 2, ',', '.') . '</td>';
                                        echo '<td style="text-align: center">' . get_diff_percent($p->precoVendaDinheiro, $p->precoCompra) . '</td>';
                                        echo '<td style="text-align: center">' . $p->estoque . '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td style="text-align: right; font-weight: bold" colspan="2">Total (sem considerar a quantidade)</td>
                                    <td style="text-align: center">R$<?= number_format($resumo['geral']['totalCompra'], 2, ',', '.'); ?></td>
                                    <td style="text-align: center">R$<?= number_format($resumo['geral']['totalVendaNoCartao'], 2, ',', '.'); ?></td>
                                    <td></td>
                                    <td style="text-align: center">R$<?= number_format($resumo['geral']['totalVendaNoDinheiro'], 2, ',', '.'); ?></td>
                                    <td></td>
                                    <td style="text-align: center"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-weight: bold" colspan="2">Total (considerando a quantidade)</td>
                                    <td style="text-align: center">R$<?= number_format($resumo['no_estoque']['totalCompra'], 2, ',', '.'); ?></td>
                                    <td style="text-align: center">R$<?= number_format($resumo['no_estoque']['totalVendaNoCartao'], 2, ',', '.'); ?></td>
                                    <td style="text-align: center"><?= get_diff_percent($resumo['no_estoque']['totalVendaNoCartao'],$resumo['no_estoque']['totalCompra']); ?></td>
                                    <td style="text-align: center">R$<?= number_format($resumo['no_estoque']['totalVendaNoDinheiro'], 2, ',', '.'); ?></td>
                                    <td style="text-align: center"><?= get_diff_percent($resumo['no_estoque']['totalVendaNoDinheiro'],$resumo['no_estoque']['totalCompra']); ?></td>
                                    <td style="text-align: center"><?= $totalQtt; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <h5 style="text-align: right">
                  Total de lucro no cartão: R$ <?= number_format($resumo['lucro']['totalCartao'], 2, ',', '.'); ?> 
                  (<?= get_diff_percent($resumo['no_estoque']['totalVendaNoCartao'],$resumo['no_estoque']['totalCompra']); ?>)
                </h5>

                <h5 style="text-align: right">
                  Total de lucro no dinheiro: R$ <?= number_format($resumo['lucro']['totalDinheiro'], 2, ',', '.'); ?> 
                  (<?= get_diff_percent($resumo['no_estoque']['totalVendaNoDinheiro'],$resumo['no_estoque']['totalCompra']); ?>)
                </h5>
                
                <h5 style="text-align: right">Data do Relatório: <?php echo date('d/m/Y'); ?></h5>
            </div>
        </div>
    </div>
</body>

</html>
