<?php

class Migration_add_emitente_to_usuarios_table extends CI_Migration
{
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->load->helper('db');
    }
    public function up()
    {
        $this->dbforge->add_column('usuarios', [
            'emitente_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);
        $this->db->query(add_foreign_key('usuarios', 'emitente_id', 'emitente(id)'));
    }

    public function down()
    {
        $this->dbforge->drop_column('usuarios', 'emitente_id');
    }
}
