<?php

namespace Botble\Media\Commands;

use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Services\ThumbnailService;
use Botble\Media\Services\UploadsManager;
use File;
use Illuminate\Console\Command;

class GenerateThumbnailCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:media:thumbnail:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnails for images';

    /**
     * @var MediaFileInterface
     */
    protected $fileRepository;

    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * @var ThumbnailService
     */
    protected $thumbnailService;

    /**
     * GenerateThumbnailCommand constructor.
     * @param MediaFileInterface $fileRepository
     * @param UploadsManager $uploadManager
     * @param ThumbnailService $thumbnailService
     */
    public function __construct(
        MediaFileInterface $fileRepository,
        UploadsManager $uploadManager,
        ThumbnailService $thumbnailService
    ) {
        parent::__construct();
        $this->fileRepository = $fileRepository;
        $this->uploadManager = $uploadManager;
        $this->thumbnailService = $thumbnailService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->info('Starting to generate thumbnails...');

        $files = $this->fileRepository->all();

        $this->info('Processing ' . $files->count() . ' file(s)...');

        foreach ($files as $file) {
            if (!is_image($file->mime_type) || $file->mime_type === 'image/svg+xml' || !File::exists(public_path($file->url))) {
                continue;
            }

            foreach (config('media.sizes') as $size) {
                $readableSize = explode('x', $size);
                $this->thumbnailService
                    ->setImage(public_path($file->url))
                    ->setSize($readableSize[0], $readableSize[1])
                    ->setDestinationPath(str_replace('storage/uploads', '', File::dirname($file->url)))
                    ->setFileName(File::name($file->url) . '-' . $size . '.' . File::extension($file->url))
                    ->save();
            }
        }

        $this->info('Generated media thumbnails successfully!');

        return true;
    }
}
