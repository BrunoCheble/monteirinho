<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-wrench"></i>
                </span>
                <h5>Editar Assistência</h5>
            </div>
            <div class="widget-content nopadding">
                <?php if ($custom_error != '') {
                    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
                } ?>
                <form action="<?php echo current_url(); ?>" id="formAssistencia" method="post" style="padding:10px;" class="form-vertical">
                  
                    <?php echo form_hidden('idAssistencias', $result->idAssistencias) ?>
                    <div class="row-fluid">
                        <div class="span2">
                            <div class="control-group">
                                <label for="dataAssistencia" class="control-label">Data Chamado</label>
                                <div class="controls">
                                    <input autocomplete="off" class="span12 datepicker text-center" disabled type="text" value="<?php echo date('d/m/Y',strtotime($result->dataCadastro)); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group">
                                <label for="dataVisita" class="control-label">Data Visita</label>
                                <div class="controls">
                                    <input autocomplete="off" class="span12 datepicker text-center" id="dataVisita" type="text" name="data_visita" value="<?php echo $result->data_visita != '0000-00-00' ? date('d/m/Y',strtotime($result->data_visita)): ''; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="control-group">
                                <label for="dataAssistencia" class="control-label">Nº da Venda <span class="required">*</span></label>
                                <div class="controls">
                                    <input class="span12 text-center" id="idVendas" type="text" name="vendas_id" value="<?php echo $result->vendas_id ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label for="dataAssistencia" class="control-label">Nome do cliente</label>
                                <div class="controls">
                                    <input class="span12" disabled id="nomeCliente" type="text" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label for="contato" class="control-label">Descrição do Problema <span class="required">*</span></label>
                            <div class="controls">
                                <textarea maxlength="1000" class="span12" name="descricao_problema" rows="7"><?php echo $result->descricao_problema ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group">
                            <label for="contato" class="control-label">Descrição do Montador</label>
                            <div class="controls">
                                <textarea maxlength="1000" class="span12" name="descricao_tecnico" rows="7"><?php echo $result->descricao_tecnico ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="span12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                            <a href="<?php echo base_url() ?>index.php/assistencias" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#idVendas').change(function() {
            var id = $(this).val();
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/assistencias/get_cliente_by_idvenda/"+id,
                success: function(nome) {
                    if(nome != '') {
                        $('#nomeCliente').val(nome);
                    }
                    else {
                        $('#idVendas').val('');
                        $('#nomeCliente').val('');
                        alert('Venda Nº '+id+' não encontrada.');
                    }
                }
            });
        });
        $('#idVendas').change();
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $('#formAssistencia').validate({
            rules: {
                descricao_problema: {
                    required: true
                },
                id_vendas: {
                    required: true
                }
            },
            messages: {
                descricao_problema: {
                    required: 'Campo Requerido.'
                },
                id_vendas: {
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
