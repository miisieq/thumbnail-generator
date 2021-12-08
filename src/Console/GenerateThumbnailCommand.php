<?php

declare(strict_types=1);

namespace App\Console;

use App\SourceImage\ImagePropertiesDTO;
use App\SourceImage\ImagePropertiesDTOConsoleDecorator;
use App\SourceImage\SourceImagesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class GenerateThumbnailCommand extends Command
{
    public const COMMAND_NAME = 'generate-thumbnail';

    public function __construct(
        private SourceImagesService $sourceImageService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $selectedImagePropertiesDTO = $this->askAboutSourceImage($input, $output, $this->getHelper('question'));
        var_dump($selectedImagePropertiesDTO);

        return self::SUCCESS;
    }

    private function askAboutSourceImage(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): ImagePropertiesDTO
    {
        $imagePropertiesDTOCollection = $this->sourceImageService->findAll();

        $question = new ChoiceQuestion(
            'Please select an image to generate a thumbnail:',
            $imagePropertiesDTOCollection->map(function (ImagePropertiesDTO $imagePropertiesDTO) {
                return new ImagePropertiesDTOConsoleDecorator($imagePropertiesDTO);
            })->toArray()
        );

        /** @var ImagePropertiesDTOConsoleDecorator $decoratedAnswer */
        $decoratedAnswer = $questionHelper->ask($input, $output, $question);

        return $decoratedAnswer->getImagePropertiesDTO();
    }
}
