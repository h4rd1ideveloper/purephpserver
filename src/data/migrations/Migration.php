<?php

namespace App\data\migrations;

use App\infra\database\Connection;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    public Builder $schema;
    public Capsule $capsule;

    public function init(): void
    {
        $database = Connection::getInstance();
        $this->setCapsule($database->capsule)
            ->setSchema($database->schema);
    }

    /**
     * @param Builder $schema
     */
    private function setSchema(Builder $schema): void
    {
        $this->schema = $schema;
    }

    /**
     * @param Capsule $capsule
     * @return Migration
     */
    private function setCapsule(Capsule $capsule): Migration
    {
        $this->capsule = $capsule;
        return $this;
    }
}