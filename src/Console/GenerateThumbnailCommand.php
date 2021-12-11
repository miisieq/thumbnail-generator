<?php

declare(strict_types=1);

namespace App\Console;

use App\Common\FileNameGenerator;
use App\ImageProperties\ImagePropertiesDTO;
use App\ImageProperties\ImagePropertiesDTOConsoleDecorator;
use App\ImageProperties\ImagePropertiesDTOConsoleDecoratorFactory;
use App\ImageResizer\ImageDimensionsDTO;
use App\ImageResizer\ImageResizerService;
use App\SourceImage\SourceImagesService;
use App\ThumbnailStorage\ThumbnailStorageService;
use App\ThumbnailStorage\ThumbnailStorageStrategyCollection;
use App\ThumbnailStorage\ThumbnailStorageStrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class GenerateThumbnailCommand extends Command
{
    public const COMMAND_NAME = 'generate-thumbnail';

    private const DEFAULT_MAX_DIMENSION = 150;

    private InputInterface $input;

    private OutputInterface $output;

    private QuestionHelper $questionHelper;

    public function __construct(
        private FileNameGenerator $fileNameGenerator,
        private ImagePropertiesDTOConsoleDecoratorFactory $imagePropertiesDTOConsoleDecoratorFactory,
        private ImageResizerService $imageResizerService,
        private SourceImagesService $sourceImageService,
        private ThumbnailStorageService $thumbnailStorageService,
        private string $defaultSourceDirectory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $this->getHelper('question');

        $selectedImagePropertiesDTO = $this->askAboutSourceImage(
            $this->askAboutSourceDirectory()
        );

        $scaledImageDimensions = $this->imageResizerService->calculateSizeScaledToMaxDimension(
            new ImageDimensionsDTO($selectedImagePropertiesDTO->getWidth(), $selectedImagePropertiesDTO->getHeight()),
            $this->askAboutMaxDimension()
        );

        $temporaryFilePath = $this->imageResizerService->generateThumbnail(
            $selectedImagePropertiesDTO->getFilePath(),
            $scaledImageDimensions
        );

        $targetFileName = $this->fileNameGenerator->generateRandomFileNameWithExtensionFromMimeType(
            $selectedImagePropertiesDTO->getMimeType()
        );

        /** @var ThumbnailStorageStrategyInterface $strategy */
        foreach ($this->askAboutPersistenceStrategy() as $strategy) {
            $strategy->persist($temporaryFilePath, $targetFileName);

            $output->writeln("Successfully saved to <info>$strategy</info> as <info>$targetFileName</info>!");
        }

        return self::SUCCESS;
    }

    private function askAboutSourceDirectory(): string
    {
        $question = new Question(
            "Please select a source directory <fg=gray>(default: {$this->defaultSourceDirectory})</>: ",
            $this->defaultSourceDirectory
        );

        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    private function askAboutSourceImage(string $sourceDirectory): ImagePropertiesDTO
    {
        $imagePropertiesDTOCollection = $this->sourceImageService->findAll($sourceDirectory);

        $question = new ChoiceQuestion(
            'Please select an image to generate a thumbnail:',
            $imagePropertiesDTOCollection->map(function (ImagePropertiesDTO $imagePropertiesDTO) {
                return $this->imagePropertiesDTOConsoleDecoratorFactory->create($imagePropertiesDTO);
            })->toArray()
        );

        /** @var ImagePropertiesDTOConsoleDecorator $decoratedAnswer */
        $decoratedAnswer = $this->questionHelper->ask($this->input, $this->output, $question);

        return $decoratedAnswer->getImagePropertiesDTO();
    }

    public function askAboutMaxDimension(): int
    {
        $question = new Question(
            'Please select a max dimension of the thumbnail <fg=gray>(default: ' . self::DEFAULT_MAX_DIMENSION . ')</>: ',
            self::DEFAULT_MAX_DIMENSION
        );

        $question->setValidator(function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_INT)) {
                throw new \InvalidArgumentException('Maximum dimension must be an integer!');
            }

            return (int)$answer;
        });

        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    private function askAboutPersistenceStrategy(): ThumbnailStorageStrategyCollection
    {
        $question = new ChoiceQuestion(
            'Please select target storage:',
            $this->thumbnailStorageService->getStrategies()->toArray()
        );
        $question->setMultiselect(true);

        return new ThumbnailStorageStrategyCollection(
            $this->questionHelper->ask($this->input, $this->output, $question)
        );
    }
}
