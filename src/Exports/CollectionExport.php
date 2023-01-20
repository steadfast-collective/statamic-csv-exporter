<?php

namespace SteadfastCollective\StatamicCsvExporter\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;

class CollectionExport implements FromCollection, WithMapping, WithHeadings
{
    protected $collectionHandle;

    public function __construct(string $collectionHandle)
    {
        $this->collectionHandle = $collectionHandle;
    }

    public function collection()
    {
        return Entry::whereCollection($this->collectionHandle);
    }

    public function headings(): array
    {
        $blueprintFields = $this->fields()->map(function ($field) {
            return $field->display();
        })->values()->toArray();

        return array_merge([
            'ID',
        ], $blueprintFields);
    }

    public function map($entry): array
    {
        $blueprintValues = $this->fields()->map(function ($field) use ($entry) {
            return $entry->get($field->handle());
        })->values()->toArray();

        return array_merge([
            $entry->id(),
        ], $blueprintValues);
    }

    protected function fields()
    {
        /** @var \Statamic\Fields\Fields */
        $fields = Collection::find($this->collectionHandle)->entryBlueprint()->fields();

        $ignoredFields = config("statamic-csv-exporter.ignored_fields.collections.{$this->collectionHandle}", []);

        return $fields->all()->except($ignoredFields);
    }
}
