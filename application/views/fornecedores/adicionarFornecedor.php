<script src="<?php echo base_url()?>assets/js/jquery.mask.min.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url()?>assets/js/funcoes.js"></script>

<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="fas fa-industry"></i>
                </span>
                <h5>Cadastro de Fornecedor</h5>
            </div>
            <div class="widget-content nopadding">
                <?php if ($custom_error != '') {
    echo '<div class="alert alert-danger">' . $custom_error . '</div>';
} ?>
                <form action="<?php echo current_url(); ?>" id="formFornecedor" method="post" class="form-horizontal">
                  <div class="control-group">
                      <label for="documento" class="control-label">CPF/CNPJ<span class="required">*</span></label>
                      <div class="controls">
                          <input id="documento" class="cpfcnpj" type="text" name="documento" value="<?php echo set_value('documento'); ?>"  />
                          <button id="buscar_info_cnpj" class="btn btn-xs" type="button">Buscar Informações (CNPJ)</button>
                      </div>
                  </div>
                  <div class="control-group">
                        <label for="nomeFornecedor" class="control-label">Nome<span class="required">*</span></label>
                        <div class="controls">
                            <input id="nomeFornecedor" type="text" name="nomeFornecedor" value="<?php echo set_value('nomeFornecedor'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="contato" class="control-label">Contato:</label>
                        <div class="controls">
                            <input class="nomeFornecedor" type="text" name="contato" value="<?php echo set_value('contato'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="telefone" class="control-label">Telefone<span class="required">*</span></label>
                        <div class="controls">
                            <input id="telefone" type="text" name="telefone" value="<?php echo set_value('telefone'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="celular" class="control-label">Celular</label>
                        <div class="controls">
                            <input id="celular" type="text" name="celular" value="<?php echo set_value('celular'); ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="email" class="control-label">Email</label>
                        <div class="controls">
                            <input id="email" type="text" name="email" value="<?php echo set_value('email'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" class="control-label">
                        <label for="cep" class="control-label">CEP</label>
                        <div class="controls">
                            <input id="cep" type="text" name="cep" value="<?php echo set_value('cep'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" class="control-label">
                        <label for="rua" class="control-label">Rua<span class="required">*</span></label>
                        <div class="controls">
                            <input id="rua" type="text" name="rua" value="<?php echo set_value('rua'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="numero" class="control-label">Número<span class="required">*</span></label>
                        <div class="controls">
                            <input id="numero" type="text" name="numero" value="<?php echo set_value('numero'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="complemento" class="control-label">Complemento</label>
                        <div class="controls">
                            <input id="complemento" type="text" name="complemento" value="<?php echo set_value('complemento'); ?>" />
                        </div>
                    </div>

                    <div class="control-group" class="control-label">
                        <label for="bairro" class="control-label">Bairro<span class="required">*</span></label>
                        <div class="controls">
                            <input id="bairro" type="text" name="bairro" value="<?php echo set_value('bairro'); ?>" />
                        </div>
                    </div>

                    <div class="control-group" class="control-label">
                        <label for="cidade" class="control-label">Cidade<span class="required">*</span></label>
                        <div class="controls">
                            <input id="cidade" type="text" name="cidade" value="<?php echo set_value('cidade'); ?>" />
                        </div>
                    </div>

                    <div class="control-group" class="control-label">
                        <label for="estado" class="control-label">Estado<span class="required">*</span></label>
                        <div class="controls">
                            <input id="estado" type="text" name="estado" value="<?php echo set_value('estado'); ?>" />
                        </div>
                    </div>



                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
                                <a href="<?php echo base_url() ?>index.php/fornecedores" id="" class="btn"><i class="fas fa-backward"></i> Voltar</a>
                            </div>
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
        $('#formFornecedor').validate({
            rules: {
                nomeFornecedor: {
                    required: true
                },
                documento: {
                    required: true
                },
                telefone: {
                    required: true
                },
                rua: {
                    required: true
                },
                numero: {
                    required: true
                },
                bairro: {
                    required: true
                },
                cidade: {
                    required: true
                },
                estado: {
                    required: true
                },
            },
            messages: {
                nomeFornecedor: {
                    required: 'Campo Requerido.'
                },
                documento: {
                    required: 'Campo Requerido.'
                },
                telefone: {
                    required: 'Campo Requerido.'
                },
                rua: {
                    required: 'Campo Requerido.'
                },
                numero: {
                    required: 'Campo Requerido.'
                },
                bairro: {
                    required: 'Campo Requerido.'
                },
                cidade: {
                    required: 'Campo Requerido.'
                },
                estado: {
                    required: 'Campo Requerido.'
                },

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
