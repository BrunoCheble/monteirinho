<?php
class Migration_add_clientes_foreigns_clientes extends CI_Migration
{
    public function __construct($config = array()) {
        parent::__construct($config);
        $this->load->helper('db');
    }
    public function up()
    {
      $this->db->query(add_foreign_key('clientes', 'cadastradoPor', 'usuarios(idUsuarios)'));
      $this->db->query(add_foreign_key('clientes', 'atualizadoPor', 'usuarios(idUsuarios)'));
    }

    public function down()
    {
      $this->db->query(drop_foreign_key('clientes', 'cadastradoPor'));
      $this->db->query(drop_foreign_key('clientes', 'atualizadoPor'));
    }
}
