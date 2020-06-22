<?php

class Migration_add_complemento_to_fornecedores_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('fornecedores', [
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('fornecedores', 'complemento');
    }
}
