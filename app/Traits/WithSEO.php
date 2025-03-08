<?php

namespace App\Traits;

use RalphJSmit\Laravel\SEO\Support\SEOData;

trait WithSEO
{
    public function getSEOData(): SEOData
    {
        $title = $this->getSEOTitle() ?? config('app.name', 'Speed Cartel BMX Racing');
        $description = $this->getSEODescription() ?? 'Speed Cartel BMX Racing Team - Building champions and pushing the limits of BMX racing.';
        
        return new SEOData(
            title: $title,
            description: $description,
            author: $this->getSEOAuthor() ?? config('seo.default_author', 'Speed Cartel'),
            image: $this->getSEOImage() ?? asset('images/default-og.jpg'),
            url: url()->current(),
            site_name: config('app.name', 'Speed Cartel BMX Racing'),
            type: 'website',
            locale: app()->getLocale(),
            published_time: now(),
            modified_time: now(),
        );
    }

    protected function getSEOTitle(): ?string
    {
        return null;
    }

    protected function getSEODescription(): ?string
    {
        return null;
    }

    protected function getSEOAuthor(): ?string
    {
        return null;
    }

    protected function getSEOImage(): ?string
    {
        return null;
    }
} 