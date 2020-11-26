<?php

class Migration_add_ncm_to_produtos_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('produtos', [
            'ncm' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('produtos', 'ncm');
    }
}
