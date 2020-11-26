<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clientes extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('clientes_model');
        $this->data['menuClientes'] = 'clientes';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }
        
        $this->data['table_clientes'] = $this->get_table();

        $this->data['view'] = 'clientes/clientes';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('documento','CPF/CNPJ','trim|verific_cpf_cnpj|is_unique[clientes.documento]');
        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nomeCliente' => set_value('nomeCliente'),
                'contato' => set_value('contato'),
                'documento' => set_value('documento'),
                'telefone' => set_value('telefone'),
                'celular' => set_value('celular'),
                'email' => set_value('email'),
                'rua' => set_value('rua'),
                'numero' => set_value('numero'),
                'complemento' => set_value('complemento'),
                'referenciaMorada' => set_value('referenciaMorada'),
                'bairro' => set_value('bairro'),
                'cidade' => set_value('cidade'),
                'estado' => set_value('estado'),
                'cep' => set_value('cep'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id')
            ];

            if ($this->clientes_model->add('clientes', $data) == true) {
                $this->session->set_flashdata('success', 'Cliente adicionado com sucesso!');
                log_info('Adicionou um cliente.');
                redirect(site_url('clientes/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'clientes/adicionarCliente';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        $cliente = $this->clientes_model->getById($this->uri->segment(3));

        if($cliente->emitente_id != $this->session->userdata('loja') && $this->session->userdata('permissao') == 2) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar este cliente!');
            redirect(base_url());
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar clientes.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if($cliente->documento != $this->input->post('documento')) {
            $this->form_validation->set_rules('documento','CPF/CNPJ','trim|verific_cpf_cnpj|is_unique[clientes.documento]');
        }

        if ($this->form_validation->run('clientes') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'nomeCliente' => $this->input->post('nomeCliente'),
                'contato' => $this->input->post('contato'),
                'documento' => $this->input->post('documento'),
                'telefone' => $this->input->post('telefone'),
                'celular' => $this->input->post('celular'),
                'email' => $this->input->post('email'),
                'rua' => $this->input->post('rua'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
                'referenciaMorada' => $this->input->post('referenciaMorada'),
                'bairro' => $this->input->post('bairro'),
                'cidade' => $this->input->post('cidade'),
                'estado' => $this->input->post('estado'),
                'cep' => $this->input->post('cep'),
                'atualizadoPor' => $this->session->userdata('id'),
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ];

            if ($this->clientes_model->edit('clientes', $data, 'idClientes', $this->input->post('idClientes')) == true) {
                $this->session->set_flashdata('success', 'Cliente editado com sucesso!');
                log_info('Alterou um cliente. ID' . $this->input->post('idClientes'));
                redirect(site_url('clientes/editar/') . $this->input->post('idClientes'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }
        $this->data['result'] = $cliente;
        $this->data['view'] = 'clientes/editarCliente';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar clientes.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->data['result'] = $this->clientes_model->getById($this->uri->segment(3));
        
        $this->data['table_vendas'] = $this->get_table_vendas_by_cliente($this->uri->segment(3));

        $this->data['view'] = 'clientes/visualizar';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir clientes.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir cliente.');
            redirect(site_url('clientes/gerenciar/'));
        }
/*
        $os = $this->clientes_model->getAllOsByClient($id);
        if ($os != null) {
            $this->clientes_model->removeClientOs($os);
        }
*/
        // excluindo Vendas vinculadas ao cliente
/*
        $vendas = $this->clientes_model->getAllVendasByClient($id);
        if ($vendas != null) {
            $this->clientes_model->removeClientVendas($vendas);
        }
*/
        $valida = $this->clientes_model->jaVendido($id);
        if ($valida) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir o cliente, pois já teve venda.');
            redirect(site_url('clientes/gerenciar/'));
        }

        $this->clientes_model->delete('clientes', 'idClientes', $id);
        log_info('Removeu um cliente. ID' . $id);

        $this->session->set_flashdata('success', 'Cliente excluido com sucesso!');
        redirect(site_url('clientes/gerenciar/'));
    }

    public function get_table() 
    {
        $condition = [];
        /*
        if($this->session->userdata('permissao') == 2) {
            $condition['usuarios.emitente_id'] = $this->session->userdata('loja');
        }*/

        $clientes = $this->clientes_model->get(
            'clientes', 
            'clientes.*, usuarios.nome as usuario, usuarios.emitente_id, (select count(vendas.clientes_id) from vendas where vendas.clientes_id = clientes.idClientes limit 1) as jaVendido', 
            $condition, 
            1000, 
            0
        );
        foreach ($clientes as $cliente) {
               
            $actions = [];
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vCliente')) {
                $actions[] = '<a href="' . base_url() . 'index.php/clientes/visualizar/' . $cliente->idClientes . '" style="margin-right: 1%" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eCliente') && ($cliente->emitente_id == $this->session->userdata('loja') || $this->session->userdata('permissao') != 2)) {
                $actions[] = '<a href="' . base_url() . 'index.php/clientes/editar/' . $cliente->idClientes . '" style="margin-right: 1%" class="btn btn-info tip-top" title="Editar Cliente"><i class="fas fa-edit"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dCliente') && $cliente->jaVendido == 0 && ($cliente->cadastradoPor == $this->session->userdata('id') || $this->session->userdata('permissao') != 2)) {
                $actions[] = '<a href="#modal-excluir" role="button" data-toggle="modal" cliente="' . $cliente->idClientes . '" style="margin-right: 1%" class="btn btn-danger tip-top" title="Excluir Cliente"><i class="fas fa-trash-alt"></i></a>';
            }

            $this->table->add_row([
                $cliente->idClientes,
                $cliente->nomeCliente,
                $cliente->documento,
                $cliente->telefone,
                $cliente->rua.', '.$cliente->numero.' '.($cliente->complemento != '' ? ', '.$cliente->complemento : '').' '.($cliente->referenciaMorada != '' ? ' ('.$cliente->referenciaMorada.')' : '').', '.$cliente->bairro.' - '.$cliente->cidade.' / '.$cliente->estado,
                $cliente->usuario,
                implode(' ',$actions)
            ]);
        }
        
        $this->table->set_template(['table_open' => '<table class="table table-bordered">']);
        $this->table->set_heading('Cod.','Nome', 'CPF/CNPJ','Telefone','Endereço','Loja','Ações');
        return $this->table->generate();
    }

    
    public function get_table_vendas_by_cliente($clientes_id) 
    {
        $this->load->model('vendas_model');
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
            return "";
        }

        $condition = ['vendas.clientes_id' => $clientes_id];
        if($this->session->userdata('permissao') == 2) {
            $condition['usuarios.emitente_id'] = $this->session->userdata('loja');
        }
        
        $vendas = $this->vendas_model->get('vendas', 'vendas.*', $condition, 1000, 0);

        foreach ($vendas as $venda) {
            $actions = [];
            $admin_ou_o_proprio = $this->session->userdata('permissao') != 2 || $venda->usuarios_id == $this->session->userdata('id');

            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vVenda')) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/visualizar/' . $venda->idVendas . '" class="btn tip-top" title="Ver mais detalhes"><i class="fas fa-eye"></i></a>';
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/imprimir/' . $venda->idVendas . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir A4"><i class="fas fa-print"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aAssistencia') && $venda->cancelado == 0) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/assistencias/adicionar/' . $venda->idVendas . '" class="btn btn-warning tip-top" title="Abrir Assistência"><i class="fas fa-wrench"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eVenda') && $venda->cancelado == 0 && $admin_ou_o_proprio) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/vendas/editar/' . $venda->idVendas . '" class="btn btn-info tip-top" title="Editar venda"><i class="fas fa-edit"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dVenda') && empty($venda->valorTotal) && $admin_ou_o_proprio) {
                $actions[] = '<a href="#modal-excluir" role="button" data-toggle="modal" venda="' . $venda->idVendas . '" class="btn btn-danger tip-top" title="Excluir Venda"><i class="fas fa-trash-alt"></i></a>';
            }
            
            $this->table->add_row([
                ['data' => $venda->idVendas, 'style' => 'width: 100px; text-align: center'],
                ['data' => date(('d/m/Y'), strtotime($venda->dataVenda)), 'data-sort' => date(('Ymd'), strtotime($venda->dataVenda))],
                ['data' => $venda->faturado == 1 ? 'Sim' : 'Não', 'style' => 'width: 100px; text-align: center'],
                ['data' => $venda->cancelado == 1 ? 'Sim' : 'Não', 'style' => 'width: 100px; text-align: center'],
                ['data' => 'R$ '.number_format($venda->valorTotal, 2, ',', '.'), 'style' => 'text-align: right'],
                ['data' => implode(' ',$actions), 'style' => 'width: 250px; text-align: left']
            ]);
        }
        $this->table->set_template(['table_open' => '<table class="table table-bordered">']);
        $this->table->set_heading('Cod. Venda','Data da Venda','Faturado','Cancelado','Preço Total','Ações');
        return $this->table->generate();
    }
}
