<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCreneaux extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INTEGER', 'auto_increment' => true],
            'ressource_id' => ['type' => 'INTEGER'], 
            'date_debut'   => ['type' => 'DATETIME'],
            'date_fin'     => ['type' => 'DATETIME'],
            'places_dispo' => ['type' => 'INTEGER'],
            'actif'        => ['type' => 'INTEGER', 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ressource_id', 'ressources', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('creneaux');
    }

    public function down()
    {
        $this->forge->dropTable('creneaux');
    }
}
