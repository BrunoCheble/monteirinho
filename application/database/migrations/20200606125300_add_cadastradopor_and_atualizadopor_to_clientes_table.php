<?php

class Migration_add_cadastradopor_and_atualizadopor_to_clientes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('clientes', [
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
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'contato');
        $this->dbforge->drop_column('clientes', 'complemento');
    }
}
