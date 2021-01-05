<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Csv;

/**
 * @package App\Service\DAO
 */
class CsvDAO extends Base
{
    public function create(array $data)
    {
        return Csv::query()->create($data);
    }
}