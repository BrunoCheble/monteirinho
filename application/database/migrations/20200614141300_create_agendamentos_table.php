<?php

class Migration_create_agendamentos_table extends CI_Migration
{
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->load->helper('db');
    }
    public function up()
    {
        $this->dbforge->add_field([
          'idAgendamentos' => [
              'type' => 'INT',
              'constraint' => 11,
              'null' => false,
              'auto_increment' => true
          ],
          'titulo' => [
              'type' => 'VARCHAR',
              'constraint' => 100,
              'null' => false,
          ],
          'descricao' => [
              'type' => 'VARCHAR',
              'constraint' => 1000,
              'null' => false,
          ],
          'data' => [
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
      $this->dbforge->add_key("idAgendamentos", true);
      $this->dbforge->create_table("agendamentos", true);
      $this->db->query('ALTER TABLE  `agendamentos` ENGINE = InnoDB');
      $this->db->query(add_foreign_key('agendamentos', 'vendas_id', 'vendas(idVendas)'));
      $this->db->query(add_foreign_key('agendamentos', 'cadastradoPor', 'usuarios(idUsuarios)'));
      $this->db->query(add_foreign_key('agendamentos', 'atualizadoPor', 'usuarios(idUsuarios)'));
    }

    public function down()
    {
        $this->db->query(drop_foreign_key('agendamentos', 'vendas_id'));
        $this->db->query(drop_foreign_key('agendamentos', 'cadastradoPor'));
        $this->db->query(drop_foreign_key('agendamentos', 'atualizadoPor'));
        $this->dbforge->drop_table("agendamentos", true);
    }
}

