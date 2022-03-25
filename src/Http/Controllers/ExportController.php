<?php

namespace SteadfastCollective\StatamicCsvExporter\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Statamic\Facades\Collection;
use SteadfastCollective\StatamicCsvExporter\Exports\CollectionExport;
use SteadfastCollective\StatamicCsvExporter\Http\Requests\ExportRequest;
use ZipArchive;

class ExportController
{
    public function __invoke(ExportRequest $request)
    {
        $zipFilename = 'export-' . now()->format('Y-m-d-H-i-s') . '.zip';

        $zip = new ZipArchive;
        $zip->open(storage_path($zipFilename), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($request->input('collections') as $collection) {
            $collection = Collection::find($collection);

            $export = new CollectionExport($collection->handle());

            $filename = $collection->handle() . '-' . now()->format('Y-m-d-H-i-s');

            Excel::store($export, $filename . '.csv', 'local');

            $zip->addFile(storage_path('app/' . $filename . '.csv'), $filename . '.csv');
        }

        $zip->close();

        return response()->download(storage_path($zipFilename));
    }
}
