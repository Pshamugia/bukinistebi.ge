<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    // Command signature
    protected $signature = 'sitemap:generate';
    // Command description
    protected $description = 'Generate XML Sitemap';

    public function handle()
    {
        SitemapGenerator::create('https://bukinistebi.ge')
            ->getSitemap()
            ->writeToFile(public_path('sitemap.xml'));
        
        $this->info('Sitemap generated successfully!');
    }
}
