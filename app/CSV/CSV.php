<?php

/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 09/05/2017
 * Time: 05:34 PM
 */

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
     * @var array
     */
    protected $items;

    /**
     * CSV constructor.
     * @param $headers array
     * @param $items array
     */
    public function __construct(array $headers,array $items)
    {
        $this->headers = $headers;
        $this->items = $items;
    }

    public function generate($name) {
        $csv = Writer::createFromFileObject(new \SplFileObject());
        $csv->insertOne(Schema::getColumnListing('origenes'));
        $csv->insertOne($this->headers);
        foreach ($this->items as $item) {
            $csv->insertOne($item->toArray());
        }
        $csv->setOutputBOM(Reader::BOM_UTF8);
        $csv->output("{$name}.csv");
    }
}