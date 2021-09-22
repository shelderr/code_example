<?php

namespace App\Console\Commands;

use Guizoxxv\LaravelMultiSizeImage\MultiSizeImage;
use Illuminate\Console\Command;

class ImageSizesChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imageSize:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Resizing all images...');

        $eventsImgs     = \Storage::files('public/uploads/events/posters');
        $personsImgs    = \Storage::files('public/uploads/persons/photo');
        $venuesImgs     = \Storage::files('public/uploads/venue/images');
        $collectiveImgs = \Storage::files('public/uploads/collectives/images');

        foreach ($eventsImgs as $poster) {
            $this->processImages($poster);
        }

        $this->info('25%');

        foreach ($personsImgs as $poster) {
            $this->processImages($poster);
        }

        $this->info('50%');

        foreach ($venuesImgs as $poster) {
            $this->processImages($poster);
        }

        $this->info('75%');

        foreach ($collectiveImgs as $poster) {
            $this->processImages($poster);
        }

        $this->info('DONE!');
    }

    private function processImages($poster)
    {
        $sizes                   = ['@tb', '@sm', '@lg'];
        $multiSizeImage          = new MultiSizeImage();
        $isContainsDsStoreFolder = strpos($poster, '.DS') !== false ? true : false;
        $isContainsBin           = strpos($poster, '.bin') !== false ? true : false;
        $isContainsCrdownload    = strpos($poster, '.crdownload') !== false ? true : false;
        $isMultisizedImage       = strpos($poster, '@') !== false ? true : false;

        //Check if original image
        if ($isContainsDsStoreFolder == false && $isContainsBin == false &&
            $isContainsCrdownload == false && $isMultisizedImage == false
        ) {
            $originalImage = explode('.', $poster);

            //check if resizedImages exists
            foreach ($sizes as $size) {
                $sizeImagePath        = "$originalImage[0]$size.$originalImage[1]";
                $isResizedImageExists = \Storage::exists($sizeImagePath);
            }

            //if resized images not exists -> create
            if ($isResizedImageExists == false) {
                $path = \Storage::path('') . $poster;
                $multiSizeImage->processImage($path);
            }
        }
    }
}
