<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729063422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $pwd = '$argon2id$v=19$m=65536,t=4,p=1$rKB0PNfQjtI/ZBgy+Ia98Q$YvF9EZJ2pQuRFugi9BibV68gjAB8tRqAcbXYjLpnjKY';
        $this->addSql("INSERT INTO user (
                  id, 
                  first_name, 
                  last_name, 
                  email, 
                  password, 
                  roles, 
                  is_valid
                  ) VALUES (
                            " . rand(900, 9999) . ", 
                            'Admin', 
                            'Admin', 
                            'admin@admin.com', 
                            '" . $pwd . "', 
                            '[\"ROLE_ADMIN\"]',
                            true)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
