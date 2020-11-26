<?php

class Migration_add_num_parcelas_to_produtos_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('produtos', [
            'numParcelas' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('produtos', 'numParcelas');
    }
}
