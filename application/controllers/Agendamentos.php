<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Agendamentos extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('agendamentos_model');
        $this->data['menuAgendamentos'] = 'agendamentos';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        /*
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAgendamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar agendamentos.');
            redirect(base_url());
        }
        $this->data['table_agendamentos'] = $this->get_table();
        */
        $this->data['view'] = 'agendamentos/agendamentos';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aAgendamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar agendamentos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('agendamentos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $date = explode('/',set_value('data'));
            $data = [
                'titulo' => set_value('titulo'),
                'descricao' => set_value('descricao'),
                'data' => count($date) > 0 ? $date[2].'-'.$date[1].'-'.$date[0] : date('Y-m-d'),
                'dataCadastro' => date('Y-m-d H:i:s'),
                'cadastradoPor' => $this->session->userdata('id')
            ];

            if(set_value('vendas_id')) {
                $data['vendas_id'] = set_value('vendas_id');
            }
            if ($this->agendamentos_model->add('agendamentos', $data) == true) {
                $this->session->set_flashdata('success', 'Agendamento adicionado com sucesso!');
                log_info('Adicionou um Agendamento.');
                redirect(site_url('agendamentos/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'agendamentos/adicionarAgendamento';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eAgendamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar agendamentos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('agendamentos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $date = explode('/',set_value('data'));
            $data = [
                'titulo' => set_value('titulo'),
                'descricao' => set_value('descricao'),
                'data' => count($date) > 0 ? $date[2].'-'.$date[1].'-'.$date[0] : date('Y-m-d'),
                'atualizadoPor' => $this->session->userdata('id'),
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ];

            if ($this->agendamentos_model->edit('agendamentos', $data, 'idAgendamentos', $this->input->post('idAgendamentos')) == true) {
                $this->agendamentos_model->editDates($this->input->post('idAgendamentos'));
                $this->session->set_flashdata('success', 'Agendamento editado com sucesso!');
                log_info('Alterou um Agendamento. ID' . $this->input->post('idAgendamentos'));
                redirect(site_url('agendamentos/editar/') . $this->input->post('idAgendamentos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->agendamentos_model->getById($this->uri->segment(3));
        $this->data['view'] = 'agendamentos/editarAgendamento';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vAgendamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar agendamentos.');
            redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->data['result'] = $this->agendamentos_model->getById($this->uri->segment(3));
        
        $this->data['view'] = 'agendamentos/visualizar';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dAgendamento')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir agendamentos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir Agendamento.');
            redirect(site_url('agendamentos/gerenciar/'));
        }

        $this->agendamentos_model->clearDates($id);
        $this->agendamentos_model->delete('agendamentos', 'idAgendamentos', $id);
        log_info('Removeu um Agendamento. ID' . $id);

        $this->session->set_flashdata('success', 'Agendamento excluido com sucesso!');
        redirect(site_url('agendamentos/gerenciar/'));
    }

    public function ajax_agendamentos() 
    {
        $agendamentos = $this->agendamentos_model->getByIntervalosDatas($this->input->post('start'), $this->input->post('end'));

        $dataAtual = 0;
        $eventos = [];

        foreach ($agendamentos as $agendamento) {
            
            if($agendamento->data != $dataAtual) {
                $hora = 0;
                $dataAtual = $agendamento->data;
            }
            $hora++;
            $hora_inicio = $hora;
            $hora_fim = $hora+1;

            $start = date($agendamento->data.' '.str_pad($hora_inicio,2,0,STR_PAD_LEFT).':00:00');
            $end = date($agendamento->data.' '.str_pad($hora_fim,2,0,STR_PAD_LEFT).':00:00');
            
            $eventos[] = [
                'id' => $agendamento->idAgendamentos,
                'title' => $agendamento->titulo,
                'description' => nl2br($agendamento->descricao),
                'vendas_id' => $agendamento->vendas_id,
                'assistencias_id' => $agendamento->assistencias_id,
                'start' => $start,
                'end' => $end,
                'color' => !empty($agendamento->assistencias_id) ? '#009688' : '#ca0000',
                'draggable' => 0,
                'resizable' => 0,
                'pode_alterar' => $agendamento->cadastradoPor == $this->session->userdata('id') || $this->session->userdata('permissao') != 2
            ];
        }
        
        echo json_encode($eventos);
        die;
    }
}
