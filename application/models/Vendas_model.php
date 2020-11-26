<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendas_model extends CI_Model
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();
    }

    
    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields.', clientes.nomeCliente, clientes.idClientes, usuarios.nome as usuario, usuarios.emitente_id as id_loja');
        $this->db->from($table);
        $this->db->limit($perpage, $start);
        $this->db->join('clientes', 'clientes.idClientes = '.$table.'.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->order_by('idVendas', 'desc');
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }

    public function getById($id)
    {
        $this->db->select("
            vendas.*, 
            clientes.*, 
            clientes.email as emailCliente, 
            clientes.telefone, 
            clientes.celular, 
            clientes.email, 
            clientes.documento, 
            usuarios.emitente_id
        ");
        $this->db->from('vendas');
        $this->db->join('clientes', 'clientes.idClientes = vendas.clientes_id');
        $this->db->join('usuarios', 'usuarios.idUsuarios = vendas.usuarios_id');
        $this->db->where('vendas.idVendas', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function getProdutos($id = null)
    {
        $this->db->select('itens_de_vendas.*, produtos.*');
        $this->db->from('itens_de_vendas');
        $this->db->join('produtos', 'produtos.idProdutos = itens_de_vendas.produtos_id');
        $this->db->where('vendas_id', $id);
        return $this->db->get()->result();
    }
    
    public function add($table, $data, $returnId = false)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            if ($returnId == true) {
                return $this->db->insert_id($table);
            }
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

    public function autoCompleteProduto($q)
    {
        $this->db->select('*');
        $this->db->limit(10);
        $this->db->like('descricao', $q);
        $query = $this->db->get('produtos');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['descricao'].' | Preço: R$ '.$row['precoVenda'].' | Estoque: '.$row['estoque'],'estoque'=>$row['estoque'],'id'=>$row['idProdutos'],'preco'=>str_replace('.',',',$row['precoVenda'])];
            }
            echo json_encode($row_set);
        }
    }

    public function autoCompleteCliente($q)
    {
        $this->db->select('*');
        $this->db->limit(10);
        $this->db->like('nomeCliente', $q);
        $this->db->or_like('documento', $q);
        $query = $this->db->get('clientes');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['nomeCliente'].' | Doc.: '.$row['documento'],'id'=>$row['idClientes']];
            }
            echo json_encode($row_set);
        }
    }

    public function autoCompleteUsuario($q)
    {
        $this->db->select('*');
        $this->db->limit(10);
        $this->db->like('nome', $q);
        $this->db->where('situacao', 1);
        $query = $this->db->get('usuarios');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['nome'].' | Telefone: '.$row['telefone'],'id'=>$row['idUsuarios']];
            }
            echo json_encode($row_set);
        }
    }

    public function updateTotalVenda($venda_id) {
        $totalProdutos = $this->db->select('SUM(subTotal) as total')->where('vendas_id', $venda_id)->get('itens_de_vendas');
        if($totalProdutos->num_rows() == 1) {
            $total = $totalProdutos->row()->total;
            $this->db->set('valorTotal', number_format($total,2,'.',''));
            $this->db->where('idVendas', $venda_id);
            $this->db->update('vendas');
        }
    }

    public function saveAgendamento($vendas_id) 
    {
        $this->load->model('agendamentos_model');

        $venda = $this->getById($vendas_id);
        $agendamento = $this->agendamentos_model->get('agendamentos', 'idAgendamentos', ['vendas_id' => $vendas_id, 'assistencias_id' => null], 1, 0, true);

        if(empty($venda->dataEntrega) && empty($agendamento)) {
            return true;
        }
        else if(empty($agendamento)) {
            return $this->agendamentos_model->add('agendamentos',[
                'titulo' => 'V. '.$vendas_id.' - '.$venda->nomeCliente,
                'data' => $venda->dataEntrega,
                'descricao' => !empty($venda->observacao) ? $venda->observacao : !empty($venda->referenciaMorada) ? $venda->referenciaMorada : '-',
                'vendas_id' => $vendas_id,
                'cadastradoPor' => $this->session->userdata('id'),
                'dataCadastro' => date('Y-m-d H:i:s')
            ]);   
        }
        else if(empty($venda->dataEntrega)) {
            return $this->agendamentos_model->delete('agendamentos','idAgendamentos', $agendamento->idAgendamentos);
        }
        else {
            return $this->agendamentos_model->edit('agendamentos',[
                'titulo' => 'V. '.$vendas_id.' - '.$venda->nomeCliente,
                'data' => $venda->dataEntrega,
                'descricao' => !empty($venda->observacao) ? $venda->observacao : !empty($venda->referenciaMorada) ? $venda->referenciaMorada : '-',
                'atualizadoPor' => $this->session->userdata('id'),
                'dataAtualizacao' => date('Y-m-d H:i:s')
            ], 'idAgendamentos', $agendamento->idAgendamentos);  
        }
    }

    public function calculatePendingValueByVendasID($vendas_id)
    {
        $this->db->select("valorTotal");
        $this->db->where('idVendas', $vendas_id);
        $venda = $this->db->get('vendas')->row();

        if(empty($venda->valorTotal) || $venda->valorTotal < 0) {
            return 0;
        }
        
        $this->db->select("sum(valor) as valorPago");
        $this->db->where('vendas_id', $vendas_id);
        $lancamento = $this->db->get('lancamentos')->row();

        return $lancamento->valorPago > 0 ? floatval($venda->valorTotal)-floatval($lancamento->valorPago) : $venda->valorTotal;
    }
}

/* End of file vendas_model.php */
/* Location: ./application/models/vendas_model.php */
