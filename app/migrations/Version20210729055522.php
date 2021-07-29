<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\UserRole;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729055522 extends AbstractMigration implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO user_role (code, libelle) VALUES  ('ROLE_ADMIN', 'Admin')");
        $this->addSql("INSERT INTO user_role (code, libelle) VALUES  ('ROLE_WAITER', 'Waiter')");
        $this->addSql("INSERT INTO user_role (code, libelle) VALUES  ('ROLE_RESTAURATEUR', 'Restaurateur')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }// TODO: Implement setContainer() method.
}
