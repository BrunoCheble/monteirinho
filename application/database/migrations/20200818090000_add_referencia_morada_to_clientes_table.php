<?php

class Migration_add_referencia_morada_to_clientes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('clientes', [
            'referenciaMorada' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'referenciaMorada');
    }
}
