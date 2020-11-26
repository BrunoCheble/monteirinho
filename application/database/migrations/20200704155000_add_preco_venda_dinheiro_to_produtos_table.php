<?php

class Migration_add_preco_venda_dinheiro_to_produtos_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('produtos', [
            'precoVendaDinheiro' => [
                'type' => 'DECIMAL',
                'constraint' => 10, 2,
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('produtos', 'precoVendaDinheiro');
    }
}
