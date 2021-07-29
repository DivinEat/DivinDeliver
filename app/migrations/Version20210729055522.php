<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\UserRole;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729055522 extends AbstractMigration
{
    private ObjectManager $manager;

    public function __construct(Connection $connection, LoggerInterface $logger, ObjectManager $manager)
    {
        parent::__construct($connection, $logger);

        $this->manager = $manager;
    }

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $role = (new UserRole())
            ->setCode('ROLE_ADMIN')
            ->setLibelle('Admin');
        $this->manager->persist($role);

        $role = (new UserRole())
            ->setCode('ROLE_WAITER')
            ->setLibelle('Waiter');
        $this->manager->persist($role);

        $role = (new UserRole())
            ->setCode('ROLE_RESTAURATEUR')
            ->setLibelle('Restaurateur');
        $this->manager->persist($role);

        $this->manager->flush();

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
