<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           LaravelFFMpegServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Providers;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Psr\Log\LoggerInterface;
use FFMpeg\Driver\FFMpegDriver;
use ProtoneMedia\LaravelFFMpeg\Drivers\PHPFFMpeg;
use Platform\Services\LaravelFFMpeg\MediaOpenerFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ProtoneMedia\LaravelFFMpeg\Filesystem\TemporaryDirectories;

class LaravelFFMpegServiceProvider extends BaseServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../../config/config.php' => config_path('laravel-ffmpeg.php'),
			], 'config');
		}
	}

	/**
	 * Register the application services.
	 */
	public function register()
	{
		$this->app->singleton('laravel-ffmpeg-logger', function () {
			return $this->app['config']->get('laravel-ffmpeg.enable_logging', true)
				? app(LoggerInterface::class)
				: null;
		});

		$this->app->singleton('laravel-ffmpeg-configuration', function () {
			$config = $this->app['config'];

			return [
				'ffmpeg.binaries' => $config->get('laravel-ffmpeg.ffmpeg.binaries'),
				'ffmpeg.threads' => $config->get('laravel-ffmpeg.ffmpeg.threads', 12),
				'ffprobe.binaries' => $config->get('laravel-ffmpeg.ffprobe.binaries'),
				'timeout' => $config->get('laravel-ffmpeg.timeout'),
				//'temporary_files_root' => $config->get('temporary_files_root'),
			];
		});

		$this->app->singleton(FFProbe::class, function () {
			return FFProbe::create(
				$this->app->make('laravel-ffmpeg-configuration'),
				$this->app->make('laravel-ffmpeg-logger')
			);
		});

		$this->app->singleton(FFMpegDriver::class, function () {
			return FFMpegDriver::create(
				$this->app->make('laravel-ffmpeg-logger'),
				$this->app->make('laravel-ffmpeg-configuration')
			);
		});

		$this->app->singleton(FFMpeg::class, function () {
			return new FFMpeg(
				$this->app->make(FFMpegDriver::class),
				$this->app->make(FFProbe::class)
			);
		});

		$this->app->singleton(PHPFFMpeg::class, function () {
			return new PHPFFMpeg($this->app->make(FFMpeg::class));
		});

		$this->app->singleton(TemporaryDirectories::class, function ($app) {
			return new TemporaryDirectories(
				$this->app['config']->get('laravel-ffmpeg.temporary_files_root', sys_get_temp_dir()),
			);
		});

		// Register the main class to use with the facade
		$this->app->singleton('laravel-ffmpeg', function () {
			return new MediaOpenerFactory(
				$this->app['config']->get('filesystems.default'),
				$this->app->make(PHPFFMpeg::class)
			);
		});
	}
}
