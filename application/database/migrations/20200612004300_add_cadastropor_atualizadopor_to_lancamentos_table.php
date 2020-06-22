<?php

class Migration_add_cadastropor_atualizadopor_to_lancamentos_table extends CI_Migration
{
  public function __construct($config = array()) {
      parent::__construct($config);
      $this->load->helper('db');
  }
    public function up()
    {
        $this->dbforge->add_column('lancamentos', [
            'cadastradoPor' => [
                'type' => 'int',
                'constraint' => 45,
                'null' => true,
                'default' => null,
            ],
            'atualizadoPor' => [
                'type' => 'int',
                'constraint' => 45,
                'null' => true,
                'default' => null,
            ],
            'dataCadastro' => [
              'type' => 'DATETIME',
              'null' => true,
            ],
            'dataAtualizacao' => [
              'type' => 'DATETIME',
              'null' => true,
            ],
        ]);
        $this->db->query(add_foreign_key('lancamentos', 'cadastradoPor', 'usuarios(idUsuarios)'));
        $this->db->query(add_foreign_key('lancamentos', 'atualizadoPor', 'usuarios(idUsuarios)'));
    }

    public function down()
    {
        $this->db->query(drop_foreign_key('lancamentos', 'cadastradoPor'));
        $this->db->query(drop_foreign_key('lancamentos', 'atualizadoPor'));
        $this->dbforge->drop_column('lancamentos', 'cadastradoPor');
        $this->dbforge->drop_column('lancamentos', 'atualizadoPor');
        $this->dbforge->drop_column('lancamentos', 'dataCadastro');
        $this->dbforge->drop_column('lancamentos', 'dataAtualizacao');
    }
}
