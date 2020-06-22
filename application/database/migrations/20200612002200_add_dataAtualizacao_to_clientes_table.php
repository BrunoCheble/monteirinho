<?php

class Migration_add_dataAtualizacao_to_clientes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('clientes', [
            'dataAtualizacao' => [
              'type' => 'DATETIME',
              'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'dataAtualizacao');
    }
}
