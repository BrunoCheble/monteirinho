<?php

class Migration_create_assistencias_table extends CI_Migration
{
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->load->helper('db');
    }
    public function up()
    {
        $this->dbforge->add_field([
          'idAssistencias' => [
              'type' => 'INT',
              'constraint' => 11,
              'null' => false,
              'auto_increment' => true
          ],
          'descricao_problema' => [
              'type' => 'VARCHAR',
              'constraint' => 255,
              'null' => false,
          ],
          'descricao_tecnico' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
          ],
          'observacao' => [
              'type' => 'VARCHAR',
              'constraint' => 255,
              'null' => true,
          ],
          'data_visita' => [
            'type' => 'DATE',
            'null' => false,
          ],
          'vendas_id' => [
            'type' => 'INT',
            'constraint' => 11,
            'null' => true,
          ],
          'cadastradoPor' => [
            'type' => 'INT',
            'constraint' => 11,
            'null' => true,
          ],
          'atualizadoPor' => [
            'type' => 'INT',
            'constraint' => 11,
            'null' => true,
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
      $this->dbforge->add_key("idAssistencias", true);
      $this->dbforge->create_table("assistencias", true);
      $this->db->query('ALTER TABLE  `assistencias` ENGINE = InnoDB');
      $this->db->query(add_foreign_key('assistencias', 'vendas_id', 'vendas(idVendas)'));
      $this->db->query(add_foreign_key('assistencias', 'cadastradoPor', 'usuarios(idUsuarios)'));
      $this->db->query(add_foreign_key('assistencias', 'atualizadoPor', 'usuarios(idUsuarios)'));
    }

    public function down()
    {
        $this->db->query(drop_foreign_key('assistencias', 'vendas_id'));
        $this->db->query(drop_foreign_key('assistencias', 'cadastradoPor'));
        $this->db->query(drop_foreign_key('assistencias', 'atualizadoPor'));
        $this->dbforge->drop_table("assistencias", true);
    }
}

