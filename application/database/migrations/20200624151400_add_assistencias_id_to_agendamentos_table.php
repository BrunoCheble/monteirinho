<?php

class Migration_add_assistencias_id_to_agendamentos_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('agendamentos', [
            'assistencias_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
              ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('agendamentos', 'assistencias_id');
    }
}
