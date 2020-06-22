<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-industry"></i>
                </span>
                <h5>Editar Fornecedor</h5>
            </div>
            <div class="widget-content nopadding">
                <?php if ($custom_error != '') {
    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
} ?>
                <form action="<?php echo current_url(); ?>" id="formAgendamento" method="post" style="padding:10px;" class="form-vertical">
                  
                    <?php echo form_hidden('idAgendamentos', $result->idAgendamentos) ?>
                  <div class="row-fluid">
                      <div class="span8">
                          <div class="control-group">
                              <label for="tituloAgendamento" class="control-label">Título<span class="required">*</span></label>
                              <div class="controls">
                                  <input class="span12" id="tituloAgendamento" type="text" name="titulo" value="<?php echo $result->titulo; ?>" />
                              </div>
                          </div>
                      </div>
                      <div class="span2">
                          <div class="control-group">
                              <label for="dataAgendamento" class="control-label">Data<span class="required">*</span></label>
                              <div class="controls">
                                  <input autocomplete="off" class="span12 datepicker" id="dataAgendamento" type="text" name="data" value="<?php echo date("d/m/Y", strtotime($result->data)); ?>" />
                              </div>
                          </div>
                      </div>
                      <div class="span2">
                          <div class="control-group">
                              <label for="dataAgendamento" class="control-label">Nº da Venda</label>
                              <div class="controls">
                                  <input class="span12" id="vendas_id" type="text" name="vendas_id" value="<?php echo $result->vendas_id; ?>" />
                              </div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="row-fluid">
                      <div class="control-group">
                          <label for="contato" class="control-label">Descrição</label>
                          <div class="controls">
                              <textarea maxlength="1000" class="span12" name="descricao" rows="7"><?php echo $result->descricao; ?></textarea>
                          </div>
                      </div>
                  </div>
                  <div class="form-actions">
                      <div class="span12 text-center">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Atualizar</button>
                          <a href="<?php echo base_url() ?>index.php/agendamentos" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
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
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $('#formAgendamento').validate({
            rules: {
                titulo: {
                    required: true
                },
                descricao: {
                    required: true
                },
                data: {
                    required: true
                }
            },
            messages: {
                titulo: {
                    required: 'Campo Requerido.'
                },
                descricao: {
                    required: 'Campo Requerido.'
                },
                data: {
                    required: 'Campo Requerido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>
