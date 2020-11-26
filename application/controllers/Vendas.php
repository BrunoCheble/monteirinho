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
            else {
                $data['dataEntrega'] = null;
            }

            $observacao = $this->input->post('observacao');
            if($observacao != '') {
                $data['observacao'] = $observacao;
            }
            else {
                $data['observacao'] = null;
            }

            if ($this->vendas_model->edit('vendas', $data, 'idVendas', $this->input->post('idVendas')) == true) {
                $this->vendas_model->saveAgendamento($this->input->post('idVendas'));
                $this->session->set_flashdata('success', 'Venda editada com sucesso!');
                log_info('Alterou uma venda. ID: ' . $this->input->post('idVendas'));
                redirect(site_url('vendas/editar/') . $this->input->post('idVendas'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $venda = $this->vendas_model->getById($this->uri->segment(3));

        if(
            empty($venda) || 
            $venda->cancelado == 1 || 
            ($this->session->userdata('permissao') == 2 && $venda->usuarios_id != $this->session->userdata('id'))
        ) {
            redirect(site_url('vendas/gerenciar/'));
        }

        $this->data['result'] = $venda;
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

        $venda = $this->vendas_model->getById($this->uri->segment(3));

        if($this->session->userdata('permissao') == 2 && $venda->emitente_id != $this->session->userdata('loja')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar esta venda!');
            redirect(base_url());
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model(['mapos_model', 'agendamentos_model']);

        $this->data['agendamento'] = $this->agendamentos_model->get('agendamentos','*',['vendas_id' => $venda->idVendas],1,0,true);
        $this->data['result'] = $venda;
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

        $venda = $this->vendas_model->getById($this->uri->segment(3));

        if($this->session->userdata('permissao') == 2 && $venda->emitente_id != $this->session->userdata('loja')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar esta venda!');
            redirect(base_url());
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar vendas.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model(['mapos_model', 'agendamentos_model']);

        $this->data['agendamento'] = $this->agendamentos_model->get('agendamentos','*',['vendas_id' => $venda->idVendas],1,0,true);

        $this->data['result'] = $venda;
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

        $venda = $this->vendas_model->getById($id);
        if($this->session->userdata('permissao') == 2 && $venda->usuarios_id != $this->session->userdata('id')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir esta venda!');
            redirect(site_url('vendas/gerenciar/'));
        }

        $this->load->model('vendas_model');

        $this->vendas_model->delete('agendamentos', 'vendas_id', $id);
        $this->vendas_model->delete('itens_de_vendas', 'vendas_id', $id);
        $this->vendas_model->delete('lancamentos', 'vendas_id', $id);
        $this->vendas_model->delete('assistencias', 'vendas_id', $id);
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
        die;
    }

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteCliente($q);
        }
        die;
    }

    public function autoCompleteUsuario()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteUsuario($q);
        }
        die;
    }

    public function adicionarProduto()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar vendas.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('idProduto', 'Produto', 'trim|required');
        $this->form_validation->set_rules('idVendasProduto', 'Vendas', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(['result' => false]);
        } else {
            $preco = $this->input->post('preco');
            $desconto = $this->input->post('desconto');
            $quantidade = $this->input->post('quantidade');

            $preco = str_replace(['.',','],['','.'],$preco);
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
                'preco' => number_format($preco,2),
                'vendas_id' => $this->input->post('idVendasProduto'),
            ];

            if ($this->vendas_model->add('itens_de_vendas', $data) == true) {
                $this->load->model('produtos_model');
                
                if ($this->data['configuration']['control_estoque']) {
                    $this->produtos_model->updateEstoque($produto, $quantidade, '-');
                }

                $this->vendas_model->updateTotalVenda($this->input->post('idVendasProduto'));

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

            $this->vendas_model->updateTotalVenda($this->input->post('idVenda'));

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
            $recebimento = set_value('data');
            
            if($this->form_validation->run('receita') == false) {
                throw new Exception(validation_errors() ? validation_errors() : 'Houve um erro interno');
            }

            $recebimento = explode('/', $recebimento);
            $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
                        
            $venda = $this->vendas_model->getById($venda_id);
            if(empty($venda)) {
                throw new Exception('Venda não encontrada');
            }
            $venda->valorTotal = floatval(str_replace(',','',$venda->valorTotal));

            $valorPago = floatval(str_replace(['.',','],['','.'], set_value('valor')));
            if($valorPago <= 0) {
                throw new Exception('Valor pago é inválido');
            }

            $lancamentosAnteriores = $this->db->select('SUM(valor) as total')->where('vendas_id', $venda_id)->get('lancamentos')->row();
            $totalAnterior = 0.00;
            if(!empty($lancamentosAnteriores->total)) {
                $totalAnterior = floatval($lancamentosAnteriores->total);
            }

            $valorTotalPrevisto = floatval($totalAnterior+$valorPago);

            if($valorTotalPrevisto > floatval($venda->valorTotal)) {
                throw new Exception('Valor pago é superior ao valor da venda');
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
    
                if($valorTotalPrevisto == floatval($venda->valorTotal)) {
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

    public function get_table() 
    {
        $condition = [];
        if($this->session->userdata('permissao') == 2) {
            $condition['usuarios.emitente_id'] = $this->session->userdata('loja');
        }
        else if($this->input->get('loja') != 0) {
            $condition['usuarios.emitente_id'] = $this->input->get('loja');
            $this->data['loja_filtrada'] = $this->input->get('loja');
        }

        $this->data['loja'] = $this->input->get('loja');
        $this->data['data_inicio'] = $this->input->get('data_inicio');
        $this->data['data_fim'] = $this->input->get('data_fim');

        if($this->input->get('estado') == 'nao_faturados') {
            $condition['vendas.faturado'] = 0;
            $this->data['estado_filtrado'] = 'nao_faturados';
        }
        else if($this->input->get('estado') == 'cancelados') {
            $condition['vendas.cancelado'] = 1;
            $this->data['estado_filtrado'] = 'cancelados';
        }

        if($this->input->get('data_inicio')) {
            $dataInicio = explode('/',$this->input->get('data_inicio'));
            $condition['vendas.dataVenda >='] = count($dataInicio) == 3 ? $dataInicio[2].'-'.$dataInicio[1].'-'.$dataInicio[0] : date('Y-m-01');
        }
            
        if($this->input->get('data_fim')) {
            $dataFim = explode('/',$this->input->get('data_fim'));
            $condition['vendas.dataVenda <='] = count($dataFim) == 3 ? $dataFim[2].'-'.$dataFim[1].'-'.$dataFim[0] : date('Y-m-d');
        }

        $vendas = $this->vendas_model->get('vendas', 'vendas.*', $condition, 1000, 0);

        $valorTotal = 0;
        foreach ($vendas as $venda) {
            $actions = [];
            $admin_ou_o_proprio = $this->session->userdata('permissao') != 2 || $venda->usuarios_id == $this->session->userdata('id');

            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/visualizar/' . $venda->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimir/' . $venda->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir A4"><i class="fas fa-print"></i></a>';
                $actions[] = $venda->cancelado == 0 ? '<a style="margin-right: 1%" href="' . base_url() . 'index.php/assistencias/adicionar/' . $venda->idVendas . '" class="btn btn-warning tip-top" title="Abrir Assistência"><i class="fas fa-wrench"></i></a>' : '';
                //$actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimirTermica/' . $venda->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir Não Fiscal"><i class="fas fa-print"></i></a>';
            }

            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda') && $venda->cancelado == 0 && $admin_ou_o_proprio) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/editar/' . $venda->idVendas . '" class="btn btn-info tip-top" title="Editar venda"><i class="fas fa-edit"></i></a>';
            }

            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda') && $venda->cancelado == 0 && $venda->valorTotal == 0 && $admin_ou_o_proprio) {
                $actions[] = '<a href="#modal-excluir" role="button" data-toggle="modal" venda="' . $venda->idVendas . '" class="btn btn-danger tip-top" title="Excluir Venda"><i class="fas fa-trash-alt"></i></a>';
            }
            
            $this->table->add_row([
                $venda->idVendas,
                ['data' => date('d/m/Y',strtotime($venda->dataVenda)), 'style' => 'text-center', 'data-sort' => date('Ymd',strtotime($venda->dataVenda))],
                '<a href="' . base_url() . 'index.php/clientes/visualizar/' . $venda->idClientes . '">' . $venda->nomeCliente . '</a>',
                ['data' => $venda->faturado == 1 ? 'Sim' : 'Não','style' => 'text-align: center'],
                ['data' => $venda->cancelado == 1 ? 'Sim' : 'Não','style' => 'text-align: center'],
                ['data' => 'R$ '.number_format($venda->valorTotal, 2, ',', '.'), 'style' => 'text-align: right', 'data-sort' => floatval($venda->valorTotal)],
                $venda->usuario,
                ['data' => implode(' ',$actions), 'style' => 'width: 250px; text-align: left']
            ]);
            
            $valorTotal += $venda->faturado == 1 && $venda->cancelado == 0 ? $venda->valorTotal : 0;
        }
        $this->table->set_template([
            'table_open' => '<table class="table table-bordered">',
            'table_close' => '<tfoot><tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right"><b>Total das vendas:</b></td>
            <td><b class="text-right" id="total-vendas">R$ '.number_format($valorTotal, 2, ',', '.').'</b></td>
            <td></td>
            <td></td>
            </tr></tfoot></table>'
        ]);
        $this->table->set_heading('Cod. Venda','Data da Venda', 'Cliente','Faturado','Cancelado','Preço Total','Loja','Ações');
        return $this->table->generate();
    }

    public function calcularValorPendente()
    {
        $vendas_id = $this->input->post('vendas_id');
        if(!empty($vendas_id)) {
            $pending_value = $this->vendas_model->calculatePendingValueByVendasID($vendas_id);
            echo json_encode(['result' => true, 'pending_value' => str_replace([',','.'],['',','],$pending_value)]);
        }
        else {
            echo json_encode(['result' => false]);
        }
        die;
    }

    public function reabrirVenda()
    {
        $vendas_id = $this->input->post('vendas_id');

        $venda = $this->vendas_model->getById($vendas_id);
        if(
            empty($venda) || 
            ($this->session->userdata('permissao') == 2 && $venda->cadastradoPor != $this->session->userdata('id'))
        ) {
            echo 'Venda não encontrada';
            die;
        }

        if(!empty($vendas_id)) {
            $this->vendas_model->delete('lancamentos', 'vendas_id', $vendas_id);
            $this->vendas_model->edit('vendas',['faturado' => 0], 'idVendas', $vendas_id);
        }
        die;
    }
    
    public function cancelarVenda()
    {
        $vendas_id = $this->input->post('vendas_id');

        $venda = $this->vendas_model->getById($vendas_id);
        if(
            empty($venda) || 
            ($this->session->userdata('permissao') == 2 && $venda->cadastradoPor != $this->session->userdata('id'))
        ) {
            die;
        }

        $this->load->model(['produtos_model','agendamentos_model']);

        $this->agendamentos_model->delete('agendamentos', 'vendas_id', $vendas_id);
        $this->vendas_model->edit('vendas',['cancelado' => 1], 'idVendas', $vendas_id);

        $itens_venda = $this->vendas_model->getProdutos($vendas_id);
        if(is_array($itens_venda) && count($itens_venda) > 0) {
            foreach($itens_venda as $produto) {   
                if ($this->data['configuration']['control_estoque']) {
                    $this->produtos_model->updateEstoque($produto->produtos_id, $produto->quantidade, '+');
                }
            }
        }
        
        die;
    }
}
