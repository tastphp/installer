<?php

namespace Tastphp\Installer\Console;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class InstallerCommand extends Command
{
    private $version = '1.3.7';

    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('install a new Tastphp application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $version = 'v' . $this->version;
        $helper = $this->getHelper('question');
        $question = new Question('Please enter install directory (default[/var/www]):', '/var/www');
        $installDirectory = $helper->ask($input, $output, $question);

        $output->writeln('<info>Crafting application...</info>');

        $this->download($zipFile = $this->makeFilename($installDirectory), $version)
            ->extract($zipFile, $installDirectory)
            ->cleanUp($zipFile);
        $output->writeln('<info>Composer install...</info>');
        exec("cd {$installDirectory}/tastphp-{$this->version}&&composer install&&composer update");
        $output->writeln("<fg=black;bg=green>You have successfully installed Tastphp! </>");
    }

    protected function download($zipFile, $version)
    {
        $response = (new Client())->request('GET', "https://github.com/tastphp/tastphp/archive/{$version}.zip");

        file_put_contents($zipFile, $response->getBody());

        return $this;
    }

    protected function extract($zipFile, $installDirectory)
    {
        $archive = new \ZipArchive;

        $archive->open($zipFile);

        $archive->extractTo($installDirectory);

        $archive->close();

        return $this;
    }

    protected function cleanUp($zipFile)
    {
        @chmod($zipFile, 0777);

        @unlink($zipFile);

        return $this;
    }

    protected function makeFilename($installDirectory)
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists($installDirectory)) {
            $filesystem->mkdir($installDirectory);
        }
        return $installDirectory . '/tastphp_' . md5(time() . uniqid()) . '.zip';
    }
}