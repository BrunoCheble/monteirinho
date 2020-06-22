<?php

class Migration_add_celular_to_emitente_table extends CI_Migration
{
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->load->helper('db');
    }
    public function up()
    {
        $this->dbforge->add_column('emitente', [
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('emitente', 'celular');
    }
}
