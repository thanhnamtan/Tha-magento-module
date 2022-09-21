<?php
namespace Tha\Call\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

// lưu ý tên class phải viết hoa ở đầu.
class Upversiondemo extends Command
{
   const NAME = 'name';
   const BIN = 'val';

   protected function configure()
   {
        $options = [
            new InputOption(
                self::NAME,
                null,
                InputOption::VALUE_REQUIRED,
                'Name'
            ),
            new InputOption(
                self::BIN,
                null,
                InputOption::VALUE_REQUIRED,
                'val version'
            )
        ];

       $this->setName('tha:updateversion')
            ->setDescription('Demo command line')
            ->setDefinition($options);
       
       parent::configure();
   }

   // run: php bin/magento tha:updateversion --name="tha nan" --val="ppppp"
   protected function execute(InputInterface $input, OutputInterface $output)
   {
    //    $output->writeln("update version before merger code");
        if ($name = $input->getOption(self::NAME) && $val = $input->getOption(self::BIN)) {

            $output->writeln("Hello " . $name);
            $output->writeln("and " . $val);


        } else {

            $output->writeln("Hello World");

        }

        return $this;
   }

}