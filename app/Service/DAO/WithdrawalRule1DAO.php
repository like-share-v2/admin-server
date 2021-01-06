<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Model\WithdrawalRule1;

/**
 * WithdrawalRule1DAO
 *
 * @author
 * @package App\Service\DAO
 */
class WithdrawalRule1DAO
{
    public function get()
    {
        return WithdrawalRule1::query()->paginate(10);
    }

    public function create(array $data)
    {
        return WithdrawalRule1::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        return WithdrawalRule1::query()->where('id', $id)->update($data);
    }

    public function delete(int $id)
    {
        return WithdrawalRule1::query()->where('id', $id)->delete();
    }
}