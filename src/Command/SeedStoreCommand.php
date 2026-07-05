<?php

namespace App\Command;

use App\Entity\Key;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed-store')]
class SeedStoreCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = new Key();
        $key->setKey(bin2hex(random_bytes(16)));
        $this->em->persist($key);

        $category = new Category();
        $category->setTitle('Electronics');
        $category->setApikey($key);
        $this->em->persist($category);

        $product = new Product();
        $product->setTitle('Laptop');
        $product->setDescription('A powerful laptop');
        $product->setPrice(999);
        $product->setCategory($category);
        $this->em->persist($product);

        $this->em->flush();

        $output->writeln('Done! Your token: ' . $key->getKey());

        return Command::SUCCESS;
    }
}