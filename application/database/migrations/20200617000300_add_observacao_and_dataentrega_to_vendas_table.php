<?php

class Migration_add_observacao_and_dataentrega_to_vendas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('vendas', [
            'dataEntrega' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'observacao' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('vendas', 'dataEntrega');
        $this->dbforge->drop_column('vendas', 'observacao');
    }
}
