<?php

class Migration_create_fornecedores_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
          'idFornecedores' => [
              'type' => 'INT',
              'constraint' => 11,
              'null' => false,
              'auto_increment' => true
          ],
          'nomeFornecedor' => [
              'type' => 'VARCHAR',
              'constraint' => 255,
              'null' => false,
          ],
          'documento' => [
              'type' => 'VARCHAR',
              'constraint' => 20,
              'null' => false,
          ],
          'telefone' => [
              'type' => 'VARCHAR',
              'constraint' => 20,
              'null' => false,
          ],
          'celular' => [
              'type' => 'VARCHAR',
              'constraint' => 20,
              'null' => true,
          ],
          'email' => [
              'type' => 'VARCHAR',
              'constraint' => 100,
              'null' => false,
          ],
          'dataCadastro' => [
              'type' => 'DATETIME',
              'null' => true,
          ],
          'rua' => [
              'type' => 'VARCHAR',
              'constraint' => 70,
              'null' => true,
          ],
          'numero' => [
              'type' => 'VARCHAR',
              'constraint' => 15,
              'null' => true,
          ],
          'bairro' => [
              'type' => 'VARCHAR',
              'constraint' => 45,
              'null' => true,
          ],
          'cidade' => [
              'type' => 'VARCHAR',
              'constraint' => 45,
              'null' => true,
          ],
          'estado' => [
              'type' => 'VARCHAR',
              'constraint' => 20,
              'null' => true,
          ],
          'cep' => [
              'type' => 'VARCHAR',
              'constraint' => 20,
              'null' => true,
          ],
          'ativo' => [
              'type' => 'TINYINT',
              'constraint' => 1,
              'null' => false,
              'default' => '1',
          ],
      ]);
      $this->dbforge->add_key("idFornecedores", true);
      $this->dbforge->create_table("fornecedores", true);
      $this->db->query('ALTER TABLE  `fornecedores` ENGINE = InnoDB');
    }

    public function down()
    {
      $this->dbforge->drop_table("fornecedores", true);
    }
}

