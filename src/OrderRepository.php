<?php

declare(strict_types=1);

namespace App;

final class OrderRepository extends CrudRepository
{
    public string $table = 'orders';

    public function countByPlaceWithQty(int $placeId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
         FROM `{$this->table}`
         WHERE `place_id` = :place_id
           AND `qty` > 0"
        );

        $stmt->execute([
            'place_id' => $placeId
        ]);

        return (int)$stmt->fetchColumn();
    }
}
