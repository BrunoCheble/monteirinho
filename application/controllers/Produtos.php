<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Produtos extends MY_Controller
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
        $this->load->model('produtos_model');
        $this->data['menuProdutos'] = 'Produtos';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }
        
        $this->data['table_produtos'] = $this->get_table();

        $this->data['view'] = 'produtos/produtos';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar produtos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('produtos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(",", "", $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => set_value('descricao'),
                'unidade' => set_value('unidade'),
                'precoCompra' => $precoCompra,
                'precoVenda' => $precoVenda,
                'estoque' => set_value('estoque'),
                'estoqueMinimo' => set_value('estoqueMinimo'),
                'saida' => set_value('saida'),
                'entrada' => set_value('entrada'),
            ];

            if ($this->produtos_model->add('produtos', $data) == true) {
                $this->session->set_flashdata('success', 'Produto adicionado com sucesso!');
                log_info('Adicionou um produto');
                redirect(site_url('produtos/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
            }
        }
        $this->data['view'] = 'produtos/adicionarProduto';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar produtos.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('produtos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(",", "", $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => $this->input->post('descricao'),
                'unidade' => $this->input->post('unidade'),
                'precoCompra' => $precoCompra,
                'precoVenda' => $precoVenda,
                'estoque' => $this->input->post('estoque'),
                'estoqueMinimo' => $this->input->post('estoqueMinimo'),
                'saida' => set_value('saida'),
                'entrada' => set_value('entrada'),
            ];

            if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $this->input->post('idProdutos')) == true) {
                $this->session->set_flashdata('success', 'Produto editado com sucesso!');
                log_info('Alterou um produto. ID: ' . $this->input->post('idProdutos'));
                redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
            }
        }

        $this->data['result'] = $this->produtos_model->getById($this->uri->segment(3));

        $this->data['view'] = 'produtos/editarProduto';
        return $this->layout();
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }

        $this->data['result'] = $this->produtos_model->getById($this->uri->segment(3));

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Produto não encontrado.');
            redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
        }

        $this->data['view'] = 'produtos/visualizarProduto';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir produtos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir produto.');
            redirect(base_url() . 'index.php/produtos/gerenciar/');
        }

        $this->produtos_model->delete('produtos_os', 'produtos_id', $id);
        $this->produtos_model->delete('itens_de_vendas', 'produtos_id', $id);
        $this->produtos_model->delete('produtos', 'idProdutos', $id);

        log_info('Removeu um produto. ID: ' . $id);

        $this->session->set_flashdata('success', 'Produto excluido com sucesso!');
        redirect(site_url('produtos/gerenciar/'));
    }

    public function atualizar_estoque()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar estoque de produtos.');
            redirect(base_url());
        }

        $idProduto = $this->input->post('id');
        $novoEstoque = $this->input->post('estoque');
        $estoqueAtual = $this->input->post('estoqueAtual');

        $estoque = $estoqueAtual + $novoEstoque;

        $data = [
            'estoque' => $estoque,
        ];

        if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $idProduto) == true) {
            $this->session->set_flashdata('success', 'Estoque de Produto atualizado com sucesso!');
            log_info('Atualizou estoque de um produto. ID: ' . $idProduto);
            redirect(site_url('produtos/visualizar/') . $idProduto);
        } else {
            $this->data['custom_error'] = '<div class="alert">Ocorreu um erro.</div>';
        }
    }

    public function get_table() {
        $produtos = $this->produtos_model->get('produtos', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));

        foreach ($produtos as $produto) {
            
            if($this->session->userdata('permission') == 2) {
                $actions = [];
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
                    $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/produtos/visualizar/' . $produto->idProdutos . '" class="btn tip-top" title="Visualizar Produto"><i class="fas fa-eye"></i></a>  ';
                }
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
                    $actions[] = '<a style="margin-right: 1%" href="' . base_url() . 'index.php/produtos/editar/' . $produto->idProdutos . '" class="btn btn-info tip-top" title="Editar Produto"><i class="fas fa-edit"></i></a>';
                }
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
                    $actions[] = '<a style="margin-right: 1%" href="#modal-excluir" role="button" data-toggle="modal" produto="' . $produto->idProdutos . '" class="btn btn-danger tip-top" title="Excluir Produto"><i class="fas fa-trash-alt"></i></a>';
                }
                if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
                    $actions[] = '<a href="#atualizar-estoque" role="button" data-toggle="modal" produto="' . $produto->idProdutos . '" estoque="' . $produto->estoque . '" class="btn btn-primary tip-top" title="Atualizar Estoque"><i class="fas fa-plus-square"></i></a>';
                }

                $data = [
                    $produto->codDeBarra,
                    $produto->descricao,
                    ['data' => $produto->estoque, 'style' => 'text-align: center'],
                    ['data' => $produto->estoqueMinimo, 'style' => 'text-align: center'],
                    ['data' => 'R$ '.number_format($produto->precoCompra, 2, ',', '.'), 'style' => 'text-align: right'],
                    ['data' => 'R$ '.number_format($produto->precoVenda, 2, ',', '.'), 'style' => 'text-align: right'],
                    ['data' => implode(' ',$actions), 'style' => 'text-align: center; width: 200px'],
                ];
            }
            else {
                $data = [
                    $produto->codDeBarra,
                    $produto->descricao,
                    ['data' => $produto->estoque, 'style' => 'text-align: center'],
                    ['data' => 'R$ '.number_format($produto->precoVenda, 2, ',', '.'), 'style' => 'text-align: right'],
                ];
            }

            $this->table->add_row($data);
        }
        
        $this->table->set_template(['table_open' => '<table class="table table-bordered">']);
        
        if($this->session->userdata('permission') == 2) {
            $this->table->set_heading('Cod. Produto','Nome', 'Estoque Atual','Estoque Mín','Preço Compra', 'Preço Venda','Ações');
        }
        else {
            $this->table->set_heading('Cod. Produto','Nome', 'Estoque Atual','Preço Venda');
        }
        return $this->table->generate();
    }
}
