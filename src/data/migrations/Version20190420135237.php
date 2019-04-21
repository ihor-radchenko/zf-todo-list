<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190420135237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['Autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->addColumn('email', 'string');
        $table->addColumn('password', 'string');
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('users');
    }
}
