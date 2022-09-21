<?php

namespace Tha\Call\Console;

use Magento\Framework\App\ResourceConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Upversionrun extends Command
{
    protected $resourceConnection;
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName("tha:updateversionrun")->setDescription("update jmango360 version before run jenkins");
        parent::configure();
    }

    // run: php bin/magento tha:updateversionrun
    protected function execute(InputInterface $inputInterface, OutputInterface $outputInterface)
    {
        # code...
        $connection = $this->resourceConnection->getConnection();
        $table = $connection->getTableName('setup_module');
        // UPDATE setup_module SET schema_version='0.1', data_version='0.1' WHERE module LIKE 'Jmango360_Japi' OR module LIKE 'Jmango360_Onepage';
        $query = "UPDATE `" . $table . "` SET schema_version='0.1"."', data_version='0.1"."' WHERE module LIKE '"."Jmango360_Japi' OR module LIKE "."'Jmango360_Onepage'";
        $connection->query($query);
    }
}