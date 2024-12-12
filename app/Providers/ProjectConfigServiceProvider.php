<?php
namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class ProjectConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $projectConfig = Config::get('app.project_config_path', base_path('project.yml'));
        if (!file_exists($projectConfig)) {
            return;
        }

        // Use PragmaRX Yaml to load the config directly
        app('pragmarx.yaml')->loadToConfig(
            base_path('project.yml'),
            'yml-config'
        );


        $this->updateCoreConfigs();
    }

    protected function updateCoreConfigs(): void
    {
        $appConfig = Config::get('yml-config', []);

        foreach ($appConfig as $section => $config) {
            if(Str::startsWith($section, '_')) {
                continue;
            }
            $this->recursivelyUpdateConfig($section, $config);
        }

        // remove yml config
        Config::set('yml-config');
    }

    protected function recursivelyUpdateConfig(string $prefix, mixed $config): void
    {
        if (!is_array($config)) {
            if ($config) {
                Config::set($prefix, Blade::render($config));
            }
            return;
        }

        foreach ($config as $key => $value) {
            $newPrefix = "{$prefix}.{$key}";
            $this->recursivelyUpdateConfig($newPrefix, $value);
        }
    }
}
