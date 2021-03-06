<?php

namespace SteadfastCollective\StatamicCsvExporter;

use Statamic\Facades\Collection;
use Statamic\Facades\Utility;
use Statamic\Providers\AddonServiceProvider;
use SteadfastCollective\StatamicCsvExporter\Http\Controllers\ExportController;

class ServiceProvider extends AddonServiceProvider
{
    public function bootAddon()
    {
        Utility::make('csv-exporter')
            ->title(__('CSV Exporter'))
            ->icon('download')
            ->description(__('Export your content to CSV files.'))
            ->view('statamic-csv-exporter::index', function () {
                return ['collections' => Collection::all()];
            })
            ->routes(function ($router) {
                $router->post('/export', ExportController::class)->name('export');
            })
            ->register();
    }
}
