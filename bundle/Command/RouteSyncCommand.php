<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsRoutingBundle\Command;

use Netgen\Bundle\LayoutsRoutingBundle\API\ContentSyncerInterface;
use Netgen\Bundle\LayoutsRoutingBundle\API\Value\ContentSyncStruct;
use Netgen\Bundle\LayoutsRoutingBundle\API\Value\TranslationSyncStruct;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RouteSyncCommand extends Command
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
        $this->setDescription('Syncs remote content objects');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @throws \JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $structs = $this->getContentSyncStructs();

        $contentItems = $this->contentSyncer->syncMultiple($structs);

        $this->io->note('Synced ' . count($contentItems) . ' content items');

        return 0;
    }

    /**
     * @throws \JsonException
     */
    private function getContentSyncStructs(): array
    {
        $fixtures = [
            [
                'id' => 'jure',
                'type' => 'type1',
                'translations' => [
                    [
                        'name' => 'Jure HR 2',
                        'locale' => 'hr_HR',
                        'payload' => ['payload' => 'payload'],
                    ],
                    [
                        'name' => 'George EN 2',
                        'locale' => 'en_GB',
                        'payload' => ['payload' => 'payload'],
                    ],
                ],
            ],
            [
                'id' => 'mate',
                'type' => 'type2',
                'translations' => [
                    [
                        'name' => 'Mate HR',
                        'locale' => 'hr_HR',
                        'payload' => ['payload' => 'payload'],
                    ],
                    [
                        'name' => 'Mattias ES',
                        'locale' => 'es_ES',
                        'payload' => ['payload' => 'payload'],
                    ],
                ],
            ],
            [
                'id' => 'ante',
                'type' => 'type1',
                'translations' => [
                    [
                        'name' => 'Ante HR',
                        'locale' => 'hr_HR',
                        'payload' => ['payload' => 'payload'],
                    ],
                    [
                        'name' => 'Anton DE',
                        'locale' => 'de_DE',
                        'payload' => ['payload' => 'payload'],
                    ],
                ],
            ],
        ];

        $structs = [];

        foreach ($fixtures as $content) {
            $contentSyncStruct = new ContentSyncStruct($content['id'], $content['type']);

            foreach ($content['translations'] as $translation) {
                $translationSyncStruct = new TranslationSyncStruct(
                    $translation['name'],
                    $translation['locale'],
                    json_encode($translation['payload'], JSON_THROW_ON_ERROR)
                );

                $contentSyncStruct->addTranslation($translationSyncStruct);
            }

            $structs[] = $contentSyncStruct;
        }

        return $structs;
    }
}
