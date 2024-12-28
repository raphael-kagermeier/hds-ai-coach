<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class RobotsController extends Controller
{
    private const DEFAULT_ROBOTS_CONTENT = "User-agent: *\nDisallow: /\n";

    private const CONTENT_TYPE = 'text/plain; charset=UTF-8';

    public function __invoke(): Response
    {
        if (! config('app.allow_robots')) {
            return $this->getDisallowAllResponse();
        }

        $contents = $this->getRobotsContents();

        return response($contents, 200)
            ->header('Content-Type', self::CONTENT_TYPE);
    }

    private function getDisallowAllResponse(): Response
    {
        return response(self::DEFAULT_ROBOTS_CONTENT, 200)
            ->header('Content-Type', self::CONTENT_TYPE);
    }

    private function getRobotsContents(): string
    {
        $robotsPath = $this->getRobotsPath();
        $disk = Storage::disk('robots');

        if (! $disk->exists($robotsPath)) {
            return self::DEFAULT_ROBOTS_CONTENT;
        }

        return $disk->get($robotsPath);
    }

    private function getRobotsPath(): string
    {
        $environment = App::environment();
        $environmentPath = "robots-{$environment}.txt";

        if (Storage::disk('robots')->exists($environmentPath)) {
            return $environmentPath;
        }

        return 'robots.txt';
    }
}
