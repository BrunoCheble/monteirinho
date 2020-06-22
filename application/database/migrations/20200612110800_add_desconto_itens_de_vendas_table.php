<?php

class Migration_add_desconto_itens_de_vendas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('itens_de_vendas', [
            'desconto' => [
                'type' => 'float',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('itens_de_vendas', 'desconto');
    }
}
