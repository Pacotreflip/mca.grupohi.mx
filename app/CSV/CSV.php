<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 09/05/2017
 * Time: 05:34 PM
 */

namespace App\CSV;

use League\Csv\Reader;
use Illuminate\Support\Facades\Schema;
use League\Csv\Writer;

class CSV
{
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var
     */
    protected $items;

    /**
     * CSV constructor.
     * @param $headers array
     * @param $items array
     */
    public function __construct(array $headers, $items)
    {
        $this->headers = $headers;
        $this->items = $items;
    }

    public function generate($name) {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        if(is_array($this->headers[0])) {
            $csv->insertAll($this->headers);
        } else {
            $csv->insertOne($this->headers);
        }
        foreach ($this->items as $item) {
            $csv->insertOne($item->toArray());
        }
        $csv->setOutputBOM(Reader::BOM_UTF8);
        $csv->output("{$name}.csv");
    }
}