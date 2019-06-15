<?php

namespace App\Services;

use Google\Spreadsheet\Worksheet;
use Google\Spreadsheet\SpreadsheetService;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\DefaultServiceRequest;
use Illuminate\Support\Collection;

class Partos
{
    protected $collection;
    protected $spreadsheetServicepartos;

    public function __construct(SpreadsheetService $p)
    {
        $this->spreadsheetServicepartos = $p;
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
        return $this->spreadsheetServicepartos->getSpreadsheetFeed()
            ->getByTitle('Pl1')
            ->getWorksheetFeed()
            ->getByTitle('Página2')
            ->getCellFeed()
            ->toArray();
    }

    public function pc()
    {
        
        $valores =  $this->collection->groupBy('Data_Parto')->filter(function ($value, $key)
        {
         // $d = date('d/m/Y');
         $d = '02/02/2019';
          return strcmp($key,$d)==0; })->flatMap(function ($value)
          {
       
            $new_value = [];
            foreach($value as $v)
            {
              if($v['Tipo_Parto']=='PC')
                $new_value[] = $v;
            }
            return $new_value;
        })->count();

    return $valores;

    }
    public function pv()
    {
        
        $valores =  $this->collection->groupBy('Data_Parto')->filter(function ($value, $key)
        {
         // $d = date('d/m/Y');
         $d = '02/02/2019';
          return strcmp($key,$d)==0; })->flatMap(function ($value)
          {
            $new_value = [];
            foreach($value as $v)
            {
              if($v['Tipo_Parto']=='PV')
                $new_value[] = $v;
            }
            return $new_value;
        })->count();
    return $valores;

    }


    public function find($id)
    {
        return $this->get()
            ->where('Id', $id)
            ->first();
    }
}