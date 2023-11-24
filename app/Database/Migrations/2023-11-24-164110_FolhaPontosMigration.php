<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class FolhaPontosMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                               
            ],
            'entry_date' => [
                'type' => 'date',
                
            ],
            'exit_date' => [
                'type' => 'date',
                
            ],
            'entry_hour' => [
                'type' => 'time',
                
            ],
            'exit_hour' => [
                'type' => 'time',
                
            ],
            'break_entry' => [
                'type' => 'time',
                
            ],
            'break_exit' => [
                'type' => 'time',
                
            ], 'created_at' => [
                'type' => 'timestamp',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'timestamp',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],

        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->addForeignKey('user_id','users','id');
        $this->forge->createTable('folha_pontos');
    }

    public function down()
    {
        //$this->forge->dropTable('folha_pontos');
    }
}
