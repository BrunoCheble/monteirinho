<style>
    /* Hiding the checkbox, but allowing it to be focused */
    .badgebox {
        opacity: 0;
    }

    .badgebox+.badge {
        /* Move the check mark away when unchecked */
        text-indent: -999999px;
        /* Makes the badge's width stay the same checked and unchecked */
        width: 27px;
    }

    .badgebox:focus+.badge {
        /* Set something to make the badge looks focused */
        /* This really depends on the application, in my case it was: */

        /* Adding a light border */
        box-shadow: inset 0px 0px 5px;
        /* Taking the difference out of the padding */
    }

    .badgebox:checked+.badge {
        /* Move the check mark back when checked */
        text-indent: 0;
    }
</style>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <h5>Editar Produto</h5>
            </div>
            <div class="widget-content nopadding">
                <?php echo $custom_error; ?>
                <form action="<?php echo current_url(); ?>" id="formProduto" method="post" class="form-horizontal">
                    <div class="control-group">
                        <?php echo form_hidden('idProdutos', $result->idProdutos) ?>
                        <label for="codDeBarra" class="control-label">Código<span class=""></span></label>
                        <div class="controls">
                            <input id="codDeBarra" type="text" name="codDeBarra" value="<?php echo $result->codDeBarra; ?>" />
                            <input id="ncm" type="text" placeholder="NCM" name="ncm" value="<?php echo $result->ncm; ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="descricao" class="control-label">Descrição<span class="required">*</span></label>
                        <div class="controls">
                            <input id="descricao" class="span8" type="text" name="descricao" value="<?php echo $result->descricao; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Tipo de Movimento</label>
                        <div class="controls">
                            <label for="entrada" class="btn btn-default" style="margin-top: 5px;">Entrada
                                <input type="checkbox" id="entrada" name="entrada" class="badgebox" value="1" <?= ($result->entrada == 1) ? 'checked' : '' ?>>
                                <span class="badge">&check;</span>
                            </label>
                            <label for="saida" class="btn btn-default" style="margin-top: 5px;">Saída
                                <input type="checkbox" id="saida" name="saida" class="badgebox" value="1" <?= ($result->saida == 1) ? 'checked' : '' ?>>
                                <span class="badge">&check;</span>
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="precoCompra" class="control-label">Preço de Compra<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoCompra" class="money" type="text" name="precoCompra" value="<?php echo str_replace([',','.'],['',','],$result->precoCompra); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="precoVenda" class="control-label">P.V. no Cartão<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoVenda" class="money" type="text" name="precoVenda" value="<?php echo str_replace([',','.'],['',','],$result->precoVenda); ?>" />
                            <input id="numParcelas" style="margin-left: 10px" type="text" placeholder="Nº de Parcelas" name="numParcelas" value="<?php echo $result->numParcelas; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="precoVendaDinheiro" class="control-label">P.V. no Dinheiro<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoVendaDinheiro" class="money" type="text" name="precoVendaDinheiro" value="<?php echo str_replace([',','.'],['',','],$result->precoVendaDinheiro); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="estoque" class="control-label">Estoque<span class="required">*</span></label>
                        <div class="controls">
                            <input id="estoque" onkeypress="return isNumber(event)" type="text" name="estoque" value="<?php echo $result->estoque; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="estoqueMinimo" class="control-label">Mostruário</label>
                        <div class="controls">
                            <input id="estoqueMinimo" onkeypress="return isNumber(event)" type="text" name="estoqueMinimo" value="<?php echo $result->estoqueMinimo; ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="foto" class="control-label">Link da foto</label>
                        <div class="controls">
                            <input id="foto" class="span8" type="text" name="foto" value="<?php echo $result->foto; ?>" />
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                                <a href="<?php echo base_url() ?>index.php/produtos" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".money").maskMoney({prefix:'', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

        $('#formProduto').validate({
            rules: {
                descricao: {
                    required: true
                },
                precoCompra: {
                    required: true
                },
                precoVenda: {
                    required: true
                },
                precoVendaDinheiro: {
                    required: true
                },
                estoque: {
                    required: true
                }
            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                precoCompra: {
                    required: 'Campo Requerido.'
                },
                precoVenda: {
                    required: 'Campo Requerido.'
                },
                precoVendaDinheiro: {
                    required: 'Campo Requerido.'
                },
                estoque: {
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
    });
</script>
