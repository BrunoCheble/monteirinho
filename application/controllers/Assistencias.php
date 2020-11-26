<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Assistencias extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('assistencias_model');
        $this->data['menuAssistencias'] = 'Assistencias';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        /*
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAssistencia')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Assistencias.');
            redirect(base_url());
        }
        */
        $this->data['table_assistencias'] = $this->get_table();
        $this->data['view'] = 'assistencias/assistencias';
        return $this->layout();
    }

    public function adicionar($id = '')
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAssistencia')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar Assistências.');
            redirect(base_url());
        }
        $this->data['idVendas'] = $id;

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('assistencias') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'vendas_id' => set_value('vendas_id'),
                'descricao_problema' => set_value('descricao_problema'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id')
            ];

            $dataVisita = set_value('data_visita');
            if($dataVisita) {
                $dataVisita = explode('/',set_value('data_visita'));
                $data['data_visita'] = count($dataVisita) > 0 ? $dataVisita[2].'-'.$dataVisita[1].'-'.$dataVisita[0] : null;
            }

            if ($this->assistencias_model->add('assistencias', $data)) {
                if(!empty($dataVisita)) {
                    $this->assistencias_model->saveAgendamento($this->assistencias_model->last_insert_id);
                }
                $this->session->set_flashdata('success', 'Assistencia adicionada com sucesso!');
                log_info('Adicionou uma Assistencia.');
                redirect(site_url('assistencias/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'assistencias/adicionarAssistencia';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAssistencia')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar Assistências.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('assistencias') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = [
                'vendas_id' => set_value('vendas_id'),
                'descricao_problema' => set_value('descricao_problema'),
                'dataAtualizacao' => date('Y-m-d H:i:s'),
                'atualizadoPor' => $this->session->userdata('id')
            ];
            
            $dataVisita = set_value('data_visita');
            if($dataVisita) {
                $dataVisita = explode('/',set_value('data_visita'));
                $data['data_visita'] = count($dataVisita) > 0 ? $dataVisita[2].'-'.$dataVisita[1].'-'.$dataVisita[0] : null;
            }
            else {
                $data['data_visita'] = null;
            }
            
            if(set_value('descricao_tecnico')) {
                $data['descricao_tecnico'] = set_value('descricao_tecnico');
            }

            if ($this->assistencias_model->edit('assistencias', $data, 'idAssistencias', $this->input->post('idAssistencias'))) {
                $this->assistencias_model->saveAgendamento($this->input->post('idAssistencias'));
                $this->session->set_flashdata('success', 'Assistência editada com sucesso!');
                log_info('Alterou uma Assistência. ID: ' . $this->input->post('idAssistencias'));
                redirect(site_url('assistencias/editar/') . $this->input->post('idAssistencias'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->assistencias_model->getById($this->uri->segment(3));
        $this->data['view'] = 'assistencias/editarAssistencia';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAssistencia')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar Assistências.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->data['result'] = $this->assistencias_model->getById($this->uri->segment(3));
        
        $this->data['view'] = 'assistencias/visualizar';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAssistencia')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir Assistências.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir Assistências.');
            redirect(site_url('assistencias/gerenciar/'));
        }

        $this->load->model('agendamentos_model');
        $this->assistencias_model->delete('assistencias', 'idAssistencias', $id);
        $this->agendamentos_model->delete('agendamentos', 'assistencias_id', $id);
        log_info('Removeu a Assistência. ID' . $id);

        $this->session->set_flashdata('success', 'Assistência excluida com sucesso!');
        redirect(site_url('assistencias/gerenciar/'));
    }

    public function get_table() 
    {
        $assistencias = $this->assistencias_model->get('assistencias', 'assistencias.*, clientes.nomeCliente, usuarios.nome as nomeUsuario', '', 1000, 0);
        
        foreach ($assistencias as $assistencia) {
            
            $actions = [];
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vAssistencia')) {
                $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/assistencias/imprimir/' . $assistencia->idAssistencias . '" target="_blank" class="btn btn-inverse tip-top" title="Imprimir A4"><i class="fas fa-print"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eAssistencia')) {
                $actions[] = '<a href="' . base_url() . 'index.php/assistencias/editar/' . $assistencia->idAssistencias . '" style="margin-right: 1%" class="btn btn-info tip-top" title="Editar assistência"><i class="fas fa-edit"></i></a>';
            }
            if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dAssistencia')) {
                $actions[] = '<a href="#modal-excluir" role="button" data-toggle="modal" assistencia="' . $assistencia->idAssistencias . '" style="margin-right: 1%" class="btn btn-danger tip-top" title="Excluir assistência"><i class="fas fa-trash-alt"></i></a>';
            }

            $this->table->add_row([
                ['data' => $assistencia->idAssistencias, 'style' => 'text-center'],
                $assistencia->nomeCliente,
                ['data' => $assistencia->vendas_id, 'style' => 'text-center'],
                ['data' => $assistencia->data_visita != '0000-00-00' ? date('d/m/Y',strtotime($assistencia->data_visita)) : '', 'style' => 'text-center', 'data-sort' => date('Ymd',strtotime($assistencia->data_visita))],
                ['data' => date('d/m/Y H:i',strtotime($assistencia->dataCadastro)), 'style' => 'text-center', 'data-sort' => date('YmdHi',strtotime($assistencia->dataCadastro))],
                ['data' => $assistencia->nomeUsuario, 'style' => 'text-center'],
                implode(' ',$actions)
            ]);
        }
        
        $this->table->set_template(['table_open' => '<table class="table table-bordered">']);
        $this->table->set_heading('#','Cliente', 'Nº Venda','Data Visita','Data Cadastro','Loja','Ações');
        return $this->table->generate();
    }

    public function get_cliente_by_idvenda($id) {
        $this->load->model('vendas_model');
        $venda = $this->vendas_model->getById($id);
        echo !empty($venda) ? $venda->nomeCliente.' - '.$venda->telefone: '';
        die;
    }
    
    public function imprimir()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        $this->data['custom_error'] = '';
        $this->load->model(['mapos_model','vendas_model']);

        $this->data['chamado'] = $this->assistencias_model->getById($this->uri->segment(3));
        $this->data['result'] = $this->vendas_model->getById($this->data['chamado']->vendas_id);
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->data['chamado']->vendas_id);
        
        $this->data['emitente'] = $this->mapos_model->getEmitenteById($this->data['result']->emitente_id);

        $this->load->view('assistencias/imprimirAssistencia', $this->data);
    }
}
