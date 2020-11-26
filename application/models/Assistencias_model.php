<?php
class Assistencias_model extends CI_Model
{
    public $last_insert_id = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join('vendas', 'vendas.idVendas = assistencias.vendas_id');
        $this->db->join('clientes', 'vendas.clientes_id = clientes.idClientes');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->order_by('idAssistencias', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id)
    {
        $this->db->select('assistencias.*, clientes.nomeCliente');
        $this->db->join('vendas', 'vendas.idVendas = assistencias.vendas_id');
        $this->db->join('clientes', 'vendas.clientes_id = clientes.idClientes');
        $this->db->where('idAssistencias', $id);
        $this->db->limit(1);
        return $this->db->get('assistencias')->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        $this->last_insert_id = $this->db->insert_id();
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function saveAgendamento($assistencias_id) {
        $this->load->model('agendamentos_model');

        $assistencia = $this->getById($assistencias_id);
        $agendamento = $this->agendamentos_model->get('agendamentos', 'idAgendamentos', ['assistencias_id' => $assistencias_id], 1, 0, true);

        if(empty($assistencia->data_visita) && empty($agendamento)) {
            return true;
        }
        else if(empty($assistencia->data_visita)) {
            return $this->agendamentos_model->delete('agendamentos','idAgendamentos', $agendamento->idAgendamentos);
        }
        else if(empty($agendamento)) {
            return $this->agendamentos_model->add('agendamentos',[
                'titulo' => 'R. '.$assistencia->vendas_id.' - '.$assistencia->nomeCliente,
                'data' => $assistencia->data_visita,
                'descricao' => $assistencia->descricao_problema,
                'vendas_id' => $assistencia->vendas_id,
                'assistencias_id' => $assistencias_id,
                'cadastradoPor' => $this->session->userdata('id'),
                'dataCadastro' => date('Y-m-d H:i:s')
            ]);   
        }
        else {
            return $this->agendamentos_model->edit('agendamentos',[
                'titulo' => 'R. '.$assistencia->vendas_id.' - '.$assistencia->nomeCliente,
                'data' => $assistencia->data_visita,
                'vendas_id' => $assistencia->vendas_id,
                'descricao' => $assistencia->descricao_problema,
                'atualizadoPor' => $this->session->userdata('id'),
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ], 'idAgendamentos', $agendamento->idAgendamentos);  
        }
    }
}
