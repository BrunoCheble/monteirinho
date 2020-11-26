<?php
class Agendamentos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['assistencias_model','vendas_model']);
    }

    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idAgendamentos', 'desc');
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
        $this->db->where('idAgendamentos', $id);
        $this->db->limit(1);
        return $this->db->get('agendamentos')->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
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

    public function getByIntervalosDatas($start, $end) {
        
        $condition = 'data BETWEEN ? AND ?';
        if($this->session->userdata('permissao') == 2) {
            $condition .= ' AND u.emitente_id='.$this->session->userdata('loja');
        }
        $query = $this->db->query('SELECT a.* FROM agendamentos a JOIN usuarios u ON u.idUsuarios = a.cadastradoPor WHERE '.$condition.' ORDER BY data, idAgendamentos',[$start, $end]);
        
        //$query = $this->db->query('select * from agendamentos where data BETWEEN ? AND ? order by data, idAgendamentos',[$start, $end]);
        return $query->result();
    }

    public function clearDates($id) {
        $agendamento = $this->getById($id);
        if(!empty($agendamento->assistencias_id)) {
            return $this->assistencias_model->edit(
                'assistencias',
                ['data_visita' => null],
                'idAssistencias',
                $agendamento->assistencias_id
            );
        }
        else if(!empty($agendamento->vendas_id)) {
            return $this->vendas_model->edit(
                'vendas',
                ['dataEntrega' => null],
                'idVendas',
                $agendamento->vendas_id
            );
        }
    }

    public function editDates($id) {
        $agendamento = $this->getById($id);
        if(!empty($agendamento->assistencias_id)) {
            return $this->assistencias_model->edit(
                'assistencias',
                ['data_visita' => $agendamento->data],
                'idAssistencias',
                $agendamento->assistencias_id
            );
        }
        else if(!empty($agendamento->vendas_id)) {
            return $this->vendas_model->edit(
                'vendas',
                ['dataEntrega' => $agendamento->data],
                'idVendas',
                $agendamento->vendas_id
            );
        }
    }
}
