<?php

namespace Netgen\Bundle\LayoutsRoutingBundle\Command;

use Netgen\Bundle\LayoutsRoutingBundle\API\ContentSyncerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RouteCleanupCommand extends Command
{
    private ContentSyncerInterface $contentSyncer;
    private SymfonyStyle $io;

    public function __construct(ContentSyncerInterface $contentSyncer)
    {
        parent::__construct();

        $this->contentSyncer = $contentSyncer;
    }

    protected function configure(): void
    {
        $this->setDescription('Removes orphaned routes');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->contentSyncer->removeOrphanedRoutes();

        return 0;
    }
}
