<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendas extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('vendas_model');
        $this->data['menuVendas'] = 'Vendas';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('vendas/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->vendas_model->count('vendas');

        $this->pagination->initialize($this->data['configuration']);

        $condition = [];
        if($this->session->userdata('permissao') == 2) {
            $condition['vendas.usuarios_id'] = $this->session->userdata('id');
        }

        $this->data['table_vendas'] = $this->get_table();

        $this->data['view'] = 'vendas/vendas';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar Vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('vendas') == false) {
            $this->data['custom_error'] = (validation_errors() ? true : false);
        } else {
            $dataVenda = $this->input->post('dataVenda');

            try {
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2] . '-' . $dataVenda[1] . '-' . $dataVenda[0];
            } catch (Exception $e) {
                $dataVenda = date('Y/m/d');
            }

            $data = [
                'dataVenda' => $dataVenda,
                'clientes_id' => $this->input->post('clientes_id'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'faturado' => 0,
            ];

            if (is_numeric($id = $this->vendas_model->add('vendas', $data, true))) {
                $this->session->set_flashdata('success', 'Venda iniciada com sucesso, adicione os produtos.');
                log_info('Adicionou uma venda.');
                redirect(site_url('vendas/editar/') . $id);
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'vendas/adicionarVenda';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar vendas');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('vendas') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $dataVenda = $this->input->post('dataVenda');
            try {
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2] . '-' . $dataVenda[1] . '-' . $dataVenda[0];
            } catch (Exception $e) {
                $dataVenda = date('Y-m-d');
            }
            
            $data = [
                'dataVenda' => $dataVenda,
                'usuarios_id' => $this->input->post('usuarios_id'),
                'clientes_id' => $this->input->post('clientes_id'),
            ];

            $dataEntrega = $this->input->post('dataEntrega');
            if($dataEntrega != '') {
                try {
                    $dataEntrega = explode('/', $dataEntrega);
                    $dataEntrega = $dataEntrega[2] . '-' . $dataEntrega[1] . '-' . $dataEntrega[0];
                } catch (Exception $e) {
                    $dataEntrega = date('Y-m-d');
                }

                $data['dataEntrega'] = $dataEntrega;
            }

            $observacao = $this->input->post('observacao');
            if($dataEntrega != '') {
                $data['observacao'] = $observacao;
            }

            if ($this->vendas_model->edit('vendas', $data, 'idVendas', $this->input->post('idVendas')) == true) {
                $this->session->set_flashdata('success', 'Venda editada com sucesso!');
                log_info('Alterou uma venda. ID: ' . $this->input->post('idVendas'));
                redirect(site_url('vendas/editar/') . $this->input->post('idVendas'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['view'] = 'vendas/editarVenda';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitenteById($this->data['result']->emitente_id);

        $this->data['view'] = 'vendas/visualizarVenda';
        return $this->layout();
    }

    public function imprimir()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        
        $this->data['emitente'] = $this->mapos_model->getEmitenteById($this->data['result']->emitente_id);

        $this->load->view('vendas/imprimirVenda', $this->data);
    }

    public function imprimirTermica()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->load->view('vendas/imprimirVendaTermica', $this->data);
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir vendas');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir venda.');
            redirect(site_url('vendas/gerenciar/'));
        }

        $this->load->model('vendas_model');

        $this->vendas_model->delete('agendamentos', 'vendas_id', $id);
        $this->vendas_model->delete('itens_de_vendas', 'vendas_id', $id);
        $this->vendas_model->delete('vendas', 'idVendas', $id);

        log_info('Removeu uma venda. ID: ' . $id);

        $this->session->set_flashdata('success', 'Venda excluída com sucesso!');
        redirect(site_url('vendas/gerenciar/'));
    }

    public function autoCompleteProduto()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteProduto($q);
        }
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteCliente($q);
        }
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteUsuario($q);
        }
    }

    public function adicionarProduto()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required');
        $this->form_validation->set_rules('idProduto', 'Produto', 'trim|required');
        $this->form_validation->set_rules('idVendasProduto', 'Vendas', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false]);
        } else {
            $preco = $this->input->post('preco');
            $desconto = $this->input->post('desconto');
            $quantidade = $this->input->post('quantidade');
            $subtotal = $preco * $quantidade;

            if($desconto > 0) {
                $subtotal *= ((100-$desconto)/100);
            }

            $produto = $this->input->post('idProduto');
            $data = [
                'quantidade' => $quantidade,
                'subTotal' => $subtotal,
                'produtos_id' => $produto,
                'desconto' => $desconto,
                'preco' => $preco,
                'vendas_id' => $this->input->post('idVendasProduto'),
            ];

            if ($this->vendas_model->add('itens_de_vendas', $data) == true) {
                $this->load->model('produtos_model');
                
                if ($this->data['configuration']['control_estoque']) {
                    $this->produtos_model->updateEstoque($produto, $quantidade, '-');
                }

                log_info('Adicionou produto a uma venda.');

                echo json_encode(['result' => true]);
            } else {
                echo json_encode(['result' => false]);
            }
        }
    }

    public function excluirProduto()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas');
            redirect(base_url());
        }

        $ID = $this->input->post('idProduto');
        if ($this->vendas_model->delete('itens_de_vendas', 'idItens', $ID) == true) {
            $quantidade = $this->input->post('quantidade');
            $produto = $this->input->post('produto');

            $this->load->model('produtos_model');
            
            if ($this->data['configuration']['control_estoque']) {
                $this->produtos_model->updateEstoque($produto, $quantidade, '+');
            }

            log_info('Removeu produto de uma venda.');
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false]);
        }
    }

    public function faturar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Vendas');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        
        try {
            $venda_id = set_value('vendas_id');
            $vencimento = set_value('vencimento');
            $recebimento = set_value('recebimento');
            
            if($this->form_validation->run('receita') == false) {
                throw new Exception(validation_errors() ? validation_errors() : 'Houve um erro interno');
            }

            $recebimento = explode('/', $recebimento);
            $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
            
            $this->vendas_model->updateTotalVenda($venda_id);
            
            $venda = $this->vendas_model->getById($venda_id);
            if(empty($venda)) {
                throw new Exception('Venda não encontrada');
            }
            
            $valorPago = number_format(set_value('valor'),2,'.',',');

            if($valorPago <= 0) {
                throw new Exception('Valor pago inválido');
            }

            $lancamentosAnteriores = $this->db->select('SUM(valor) as total')->where('vendas_id', $venda_id)->get('lancamentos');
            $totalAnterior = 0;
            if($lancamentosAnteriores->num_rows() == 1) {
                $totalAnterior = $lancamentosAnteriores->row()->total;
            }

            if(($totalAnterior+$valorPago) > $venda->valorTotal) {
                throw new Exception('Valor pago superior ao valor da venda');
            }


            
            $data = [
                'vendas_id' => $venda_id,
                'descricao' => set_value('descricao'),
                'valor' => $valorPago,
                'clientes_id' => set_value('clientes_id'),
                'data_vencimento' => $recebimento,
                'data_pagamento' => $recebimento,
                'baixado' => 1,
                'cliente_fornecedor' => set_value('cliente'),
                'forma_pgto' => set_value('formaPgto'),
                'tipo' => set_value('tipo'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id'),
                'dataAtualizacao' => date('Y-m-d H:i:s'),
                'atualizadoPor' => $this->session->userdata('id')
            ];

            if ($this->vendas_model->add('lancamentos', $data)) {
    
                if(($totalAnterior+$valorPago) == $venda->valorTotal) {
                    $this->db->set('faturado', 1);
                    $this->db->where('idVendas', $venda_id);
                    $this->db->update('vendas');
                }
    
                log_info('Faturou R$'.set_value('valor').' na venda com o cliente: '.set_value('cliente').' - ID da Venda: '.$venda_id);
                $this->session->set_flashdata('success', 'Venda faturada com sucesso!');
                echo json_encode(['result' => true]);
            } else {
                throw new Exception('Ocorreu um erro ao tentar faturar venda.');
            }
        }
        catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            echo json_encode(['result' => false, 'message' => $e->getMessage()]);
        }
        die();
    }

    public function get_table() {
        $condition = [];
        if($this->session->userdata('permissao') == 2) {
            $condition['vendas.usuarios_id'] = $this->session->userdata('id');
        }
        else if($this->input->get('loja') != 0) {
            $condition['vendas.usuarios_id'] = $this->input->get('loja');
        }

        $this->data['loja'] = $this->input->get('loja');
        $this->data['data_inicio'] = $this->input->get('data_inicio');
        $this->data['data_fim'] = $this->input->get('data_fim');

        if($this->input->get('data_inicio')) {
            $dataInicio = explode('/',$this->input->get('data_inicio'));
            $condition['vendas.dataVenda >='] = count($dataInicio) == 3 ? $dataInicio[2].'-'.$dataInicio[1].'-'.$dataInicio[0] : date('Y-m-d');
        }
            
        if($this->input->get('data_fim')) {
            $dataFim = explode('/',$this->input->get('data_fim'));
            $condition['vendas.dataVenda <='] = count($dataFim) == 3 ? $dataFim[2].'-'.$dataFim[1].'-'.$dataFim[0] : date('Y-m-d');
        }

        $vendas = $this->vendas_model->get('vendas', 'vendas.*', $condition, 1000, 0);

        foreach ($vendas as $venda) {
            $actions = [];
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/visualizar/' . $venda->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimir/' . $venda->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir A4"><i class="fas fa-print"></i></a>';
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimirTermica/' . $venda->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir Não Fiscal"><i class="fas fa-print"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/editar/' . $venda->idVendas . '" class="btn btn-info tip-top" title="Editar venda"><i class="fas fa-edit"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda')) {
                $actions[] = '<a href="#modal-excluir" role="button" data-toggle="modal" venda="' . $venda->idVendas . '" class="btn btn-danger tip-top" title="Excluir Venda"><i class="fas fa-trash-alt"></i></a>';
            }
            
            $this->table->add_row([
                $venda->idVendas,
                ['data' => date(('d/m/Y'), strtotime($venda->dataVenda)), 'data-sort' => date(('Ymd'), strtotime($venda->dataVenda))],
                '<a href="' . base_url() . 'index.php/clientes/visualizar/' . $venda->idClientes . '">' . $venda->nomeCliente . '</a>',
                $venda->faturado == 1 ? 'Sim' : 'Não',
                ['data' => 'R$ '.number_format($venda->valorTotal, 2, ',', '.'), 'style' => 'text-align: right'],
                $venda->usuario,
                ['data' => implode(' ',$actions), 'style' => 'width: 250px; text-align: center']
            ]);
        }
        $this->table->set_template(['table_open' => '<table class="table table-bordered">']);
        $this->table->set_heading('Cod. Venda','Data da Venda', 'Cliente','Faturado','Preço Total','Loja','Ações');
        return $this->table->generate();
    }
}
