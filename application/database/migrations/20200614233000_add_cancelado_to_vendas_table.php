<?php

class Migration_add_cancelado_to_vendas_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('vendas', [
            'cancelado' => [
              'type' => 'TINYINT',
              'constraint' => 1,
              'null' => true,
          ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('vendas', 'cancelado');
    }
}
