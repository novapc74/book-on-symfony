<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:book-parser',
    description: 'Add new books to database',
)]
class BookParserCommand extends Command
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('arg1', InputArgument::OPTIONAL, 'link');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $io->title('Book Parser');

        if (!$arg1) {
            $arg1 = $io->ask('Please, enter your link.');
            $io->note(sprintf('You passed an argument: %s', $arg1));
        } else {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $validator = \Symfony\Component\Validator\Validation::createValidator();
        $violations = $validator->validate($arg1, new \Symfony\Component\Validator\Constraints\Url());

        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                throw new \Exception($violation->getMessage());
            }
        }

        $doctrine = $this->registry;

        $parser = new \App\Utils\Uploader\UploadUrl($arg1);
        $collection = $parser->toArray();

        // filter existing books, normalize new books categories.
        $newBooks = array_reduce($collection, function ($acc, $book) use ($doctrine) {
            $needleBook = $doctrine->getRepository(\App\Entity\Book::class)->findOneBy(['title' => $book['title']]);

            if (null === $needleBook) {
                $normalizer = new \App\Utils\CategoryMaker\Normalizer();
                $authors = $normalizer->normalize($book['authors']);
                $categories = $normalizer->normalize($book['categories']);
                $book['authors'] = $authors;
                $book['categories'] = $categories;
                $acc[] = $book;
            }

            return $acc;
        }, []);

        // add new books to database. generate new categories an authors.
        array_map(function ($book) use ($doctrine) {
                $newBook = new \App\Utils\CategoryMaker\BookMaker($doctrine);
                $newBook->add($book);
            },
            $newBooks
        );

        $io->success('All new books added to database successfuly.');

        return Command::SUCCESS;
        // https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json
    }
}
