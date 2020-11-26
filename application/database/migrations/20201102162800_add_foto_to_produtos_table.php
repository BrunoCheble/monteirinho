<?php

class Migration_add_ncm_to_produtos_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('produtos', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('produtos', 'foto');
    }
}
