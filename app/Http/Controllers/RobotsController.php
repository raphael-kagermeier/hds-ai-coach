<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $environment = App::environment();
        $robotsPath = "robots-{$environment}.txt";
        
        $disk = Storage::disk('robots');

        // If environment-specific robots.txt doesn't exist, use default
        if (!$disk->exists($robotsPath)) {
            $robotsPath = 'robots.txt';
        }

        // If no robots file exists at all, return default content
        if (!$disk->exists($robotsPath)) {
            $contents = "User-agent: *\nDisallow: /\n";
        } else {
            $contents = $disk->get($robotsPath);
        }

        return response($contents, 200)
            ->header('Content-Type', 'text/plain');
    }
}
