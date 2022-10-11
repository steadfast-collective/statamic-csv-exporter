<?php

namespace SteadfastCollective\StatamicCsvExporter\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Statamic\Facades\Collection;
use SteadfastCollective\StatamicCsvExporter\Exports\CollectionExport;
use SteadfastCollective\StatamicCsvExporter\Http\Requests\ExportRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class ExportController
{
    public function __invoke(ExportRequest $request)
    {
        $collections = $request->input('collections');

        return new StreamedResponse(function () use ($collections) {
            $options = new Archive();
            $options->setZeroHeader(true);
            $options->setSendHttpHeaders(true);

            $zipFilename = 'export-' . now()->format('Y-m-d-H-i-s') . '.zip';

            $zip = new ZipStream($zipFilename, $options);

            foreach ($collections as $collection) {
                $collection = Collection::find($collection);

                $export = new CollectionExport($collection->handle());

                $csvFilename = $collection->handle() . '-' . now()->format('Y-m-d-H-i-s') . '.csv';
                $csvData = Excel::raw($export, \Maatwebsite\Excel\Excel::CSV);

                $zip->addFile($csvFilename, $csvData);
            }

            $zip->finish();
        });
    }
}
