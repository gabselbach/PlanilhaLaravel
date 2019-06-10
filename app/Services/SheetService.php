<?php

namespace App\Services;

use Google\Spreadsheet\Worksheet;
use Google\Spreadsheet\SpreadsheetService;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\DefaultServiceRequest;
use Illuminate\Support\Collection;

class SheetService
{
    protected $collection;
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
        $this->setAccessToken();
        $this->setCachedCollection();
    }

    protected function setAccessToken()
    {
        $accessToken = resolve('access.token');
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($accessToken)
        );
    }

    protected function setCachedCollection()
    {
        $this->collection
            = cache()->remember('spreadsheet-collection', 60 * 24, function () {
                return $this->combineHeaders();
            });
    }

    protected function combineHeaders()
    {
        $cellFeed = $this->getFromApi();
        $headers = array_shift($cellFeed);
        return collect(array_map(function ($cell) use ($headers) {
            return array_combine($headers, $cell);
        }, $cellFeed));
    }

    protected function getFromApi()
    {
        return $this->spreadsheetService->getSpreadsheetFeed()
            ->getByTitle('app-teste')
            ->getWorksheetFeed()
            ->getByTitle('Página1')
            ->getCellFeed()
            ->toArray();
    }

    public function get()
    {
        return $this->collection;
    }

    public function find($id)
    {
        return $this->get()
            ->where('Id', $id)
            ->first();
    }
}