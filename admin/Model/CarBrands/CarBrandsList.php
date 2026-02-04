<?php

namespace App\Admin\Model\CarBrands;


use App\Database\DatabaseInterface;
use App\Admin\Model\Model;
use App\Model\CarBrands\CarBrands;

class CarBrandsList extends Model
{
    public function __construct(
        public readonly DatabaseInterface $database,
    )
    {
    }
    public function listCarBrands(): array
    {
        $carBrands = $this->database->select('car_brands');
        $listCarBrands = array();

        foreach ($carBrands as $brand) {
            $listCarBrands[] = new CarBrands(['id' => $brand['id']]);
        }

        return $listCarBrands;
    }
}