<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Financeiro extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('financeiro_model');
        $this->load->helper('codegen_helper');
        $this->data['menuFinanceiro'] = 'financeiro';
    }
    public function index()
    {
        $this->lancamentos();
    }

    public function lancamentos()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vLancamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar lançamentos.');
            redirect(base_url());
        }

        $where = '';
        $periodo = $this->input->get('periodo');
        $situacao = $this->input->get('situacao');
       

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url("financeiro/lancamentos/?periodo=$periodo&situacao=$situacao");
        $this->data['configuration']['total_rows'] = $this->financeiro_model->count('lancamentos', $where);
        $this->data['configuration']['page_query_string'] = true;

        $this->pagination->initialize($this->data['configuration']);

        $this->data['table_lancamentos'] = $this->get_table();
        $this->data['totals'] = $this->financeiro_model->getTotals('');

        $this->data['view'] = 'financeiro/lancamentos';
        return $this->layout();
    }

    public function adicionarReceita()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aLancamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar lançamentos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $recebimento = $this->input->post('data');

            try {
                $recebimento = explode('/', $recebimento);
                $recebimento = explode('/', $recebimento);
                $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
            } catch (Exception $e) {
                $recebimento = date('Y-m-d');
            }

            $valor = $this->input->post('valor');
            $valor = floatval(str_replace(['.', ','], ['', '.'], $valor));

            $data = [
                'descricao' => set_value('descricao'),
                'valor' => $valor,
                'data_vencimento' => $recebimento,
                'data_pagamento' => $recebimento,
                'baixado' => 1,
                'cliente_fornecedor' => 'Monteirinho',
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => set_value('tipo'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id')
            ];

            if ($this->financeiro_model->add('lancamentos', $data) == true) {
                $this->session->set_flashdata('success', 'Receita adicionada com sucesso!');
                log_info('Adicionou uma receita');
                redirect($urlAtual);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar adicionar receita.');
        redirect($urlAtual);
    }

    public function adicionarDespesa()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aLancamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar lançamentos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');
        if ($this->form_validation->run('despesa') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $recebimento = $this->input->post('data');

            try {
                $recebimento = explode('/', $recebimento);
                $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
            } catch (Exception $e) {
                $recebimento = date('Y-m-d');
            }

            $valor = $this->input->post('valor');
            $valor = floatval(str_replace(['.', ','], ['', '.'], $valor));

            $data = [
                'descricao' => set_value('descricao'),
                'valor' => $valor,
                'data_vencimento' => $recebimento,
                'data_pagamento' => $recebimento,
                'baixado' => 1,
                'cliente_fornecedor' => set_value('fornecedor') != '' ? set_value('fornecedor') : 'Monteirinho',
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => set_value('tipo'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id')
            ];
            
            if ($this->financeiro_model->add('lancamentos', $data) == true) {
                $this->session->set_flashdata('success', 'Despesa adicionada com sucesso!');
                log_info('Adicionou uma despesa');
                redirect($urlAtual);
            } else {
                $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar adicionar despesa!');
                redirect($urlAtual);
            }
        }

        $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar adicionar despesa.');
        redirect($urlAtual);
    }
/*
    public function editar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eLancamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar lançamentos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $urlAtual = $this->input->post('urlAtual');

        $this->form_validation->set_rules('descricao', '', 'trim|required');
        $this->form_validation->set_rules('fornecedor', '', 'trim|required');
        $this->form_validation->set_rules('valor', '', 'trim|required');
        $this->form_validation->set_rules('vencimento', '', 'trim|required');
        $this->form_validation->set_rules('pagamento', '', 'trim');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $vencimento = $this->input->post('vencimento');
            $pagamento = $this->input->post('pagamento');

            try {
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2] . '-' . $vencimento[1] . '-' . $vencimento[0];

                $pagamento = explode('/', $pagamento);
                $pagamento = $pagamento[2] . '-' . $pagamento[1] . '-' . $pagamento[0];
            } catch (Exception $e) {
                $vencimento = date('Y/m/d');
            }

            $data = [
                'descricao' => $this->input->post('descricao'),
                'valor' => $this->input->post('valor'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $pagamento,
                'baixado' => $this->input->post('pago') ?: 0,
                'cliente_fornecedor' => $this->input->post('fornecedor'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => $this->input->post('tipo'),
                'dataAtualizacao' => date('Y-m-d H:i:s'),
                'atualizadoPor' => $this->session->userdata('id')
            ];

            if ($this->financeiro_model->edit('lancamentos', $data, 'idLancamentos', $this->input->post('id')) == true) {
                $this->session->set_flashdata('success', 'lançamento editado com sucesso!');
                log_info('Alterou um lançamento no financeiro. ID' . $this->input->post('id'));
                redirect($urlAtual);
            } else {
                $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar editar lançamento!');
                redirect($urlAtual);
            }
        }

        $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar editar lançamento.');
        redirect($urlAtual);

        $data = [
            'descricao' => $this->input->post('descricao'),
            'valor' => $this->input->post('valor'),
            'data_vencimento' => $this->input->post('vencimento'),
            'data_pagamento' => $this->input->post('pagamento'),
            'baixado' => $this->input->post('pago'),
            'cliente_fornecedor' => set_value('fornecedor'),
            'forma_pgto' => $this->input->post('formaPgto'),
            'tipo' => $this->input->post('tipo'),
        ];
        print_r($data);
    }
*/
    public function excluirLancamento()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dLancamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir lançamentos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');

        if ($id == null || !is_numeric($id)) {
            $json = ['result' => false];
            echo json_encode($json);
        } else {
            $result = $this->financeiro_model->delete('lancamentos', 'idLancamentos', $id);
            if ($result) {
                log_info('Removeu um lançamento. ID: ' . $id);
                $json = ['result' => true];
                echo json_encode($json);
            } else {
                $json = ['result' => false];
                echo json_encode($json);
            }
        }
    }

    protected function getThisYear()
    {
        $dias = date("z");
        $primeiro = date("Y-m-d", strtotime("-" . ($dias) . " day"));
        $ultimo = date("Y-m-d", strtotime("+" . (364 - $dias) . " day"));
        return [$primeiro, $ultimo];
    }

    protected function getThisWeek()
    {
        return [date("Y/m/d", strtotime("last sunday", strtotime("now"))), date("Y/m/d", strtotime("next saturday", strtotime("now")))];
    }

    protected function getLastSevenDays()
    {
        return [date("Y-m-d", strtotime("-7 day", strtotime("now"))), date("Y-m-d", strtotime("now"))];
    }

    protected function getThisMonth()
    {
        $mes = date('m');
        $ano = date('Y');
        $qtdDiasMes = date('t');
        $inicia = $ano . "-" . $mes . "-01";

        $ate = $ano . "-" . $mes . "-" . $qtdDiasMes;
        return [$inicia, $ate];
    }
    
    public function get_table() {
        $condition = [];
        if($this->session->userdata('permissao') == 2) {
            $condition['usuarios.emitente_id'] = $this->session->userdata('loja');
        }
        else if($this->input->get('loja') != 0) {
            $condition['usuarios.emitente_id'] = $this->input->get('loja');
            $this->data['loja_filtrada'] = $this->input->get('loja');
        }

        if($this->input->get('data_inicio')) {
            $dataInicio = explode('/',$this->input->get('data_inicio'));
            $condition['lancamentos.data_pagamento >='] = count($dataInicio) == 3 ? $dataInicio[2].'-'.$dataInicio[1].'-'.$dataInicio[0] : date('Y-m-d');
            $this->data['data_inicio'] = $this->input->get('data_inicio');
        }

        if($this->input->get('data_fim')) {
            $dataFim = explode('/',$this->input->get('data_fim'));
            $condition['lancamentos.data_pagamento <='] = count($dataFim) == 3 ? $dataFim[2].'-'.$dataFim[1].'-'.$dataFim[0] : date('Y-m-d');
            $this->data['data_fim'] = $this->input->get('data_fim');
        }

        if($this->input->get('data_inicio') == '' && $this->input->get('data_fim') == '') {
            $condition['lancamentos.data_pagamento'] = date('Y-m-d');
        }

        if($this->input->get('formaPgto') != '') {
            $condition['lancamentos.forma_pgto'] = $this->input->get('formaPgto');
            $this->data['pagto_filtrado'] = $this->input->get('formaPgto');
        }

        $lancamentos = $this->financeiro_model->get('lancamentos', 'lancamentos.*, usuarios.nome as loja', $condition, 1000, 0);
        $receitas = 0;
        $despesas = 0;
        foreach ($lancamentos as $lancamento) {
                        
            $admin_ou_o_proprio = $this->session->userdata('permissao') != 2 || $lancamento->cadastradoPor == $this->session->userdata('id');
            $actions = [];
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dLancamento') && $admin_ou_o_proprio) {
                $actions[] = '<a href="#modalExcluir" data-toggle="modal" role="button" idLancamento="' . $lancamento->idLancamentos . '" class="btn btn-danger tip-top excluir" title="Excluir Lançamento"><i class="fas fa-trash-alt"></i></a>';
            }
            
            $label_tipo = $lancamento->tipo == 'receita' ? 'success' : 'important';

            if($lancamento->tipo == 'receita') {
                $receitas += $lancamento->valor;
            }
            else {
                $despesas += $lancamento->valor;
            }

            $this->table->add_row([
                ['data' => $lancamento->idLancamentos, 'style' => 'text-align: center'],
                '<span style="text-align: center" class="label label-' . $label_tipo . '">' . ucfirst($lancamento->tipo) . '</span>',
                $lancamento->cliente_fornecedor,
                $lancamento->descricao,
                $lancamento->forma_pgto,
                ['data' => date('d/m/Y', strtotime($lancamento->data_pagamento)), 'style' => 'text-align: center'],
                ['data' => $lancamento->loja, 'style' => 'text-align: center'],
                ['data' => 'R$ ' . number_format($lancamento->valor, 2, ',', '.'), 'style' => 'text-align: right'],
                ['data' => implode(' ',$actions), 'style' => 'text-align: center']
            ]);

        }
        
        $this->table->set_template([
            'table_open' => '<table class="table table-bordered" id="divLancamentos">',
            'table_close' => '<tr>
                <td colspan="7" style="text-align: right; color: green"> <strong>Total Receitas:</strong></td>
                <td colspan="3" style="text-align: left; color: green"><strong>R$ '.number_format($receitas, 2, ',', '.').'</strong></td>
                </tr>
                <tr>
            <td colspan="7" style="text-align: right; color: red"> <strong>Total Despesas:</strong></td>
            <td colspan="3" style="text-align: left; color: red"><strong>R$ '.number_format($despesas, 2, ',', '.').'</strong></td>
          </tr>
          <tr>
            <td colspan="7" style="text-align: right"> <strong>Saldo:</strong></td>
            <td colspan="3" style="text-align: left;"><strong id="total-saldo">R$ '.number_format($receitas-$despesas, 2, ',', '.').'</strong></td>
          </tr></table>'
        ]);
        $this->table->set_heading('#','Tipo', 'Cliente','Descrição','Forma Pag.','Data','Loja','Valor','Ações');
        return $this->table->generate();
    }
}
