<?php

declare(strict_types=1);

namespace App;

use PDO;

abstract class CrudRepository
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");

        return $stmt->fetchAll();
    }

    public function findLast(): ?array
    {
        $stmt = $this->db->query(
            "SELECT * FROM `{$this->table}` ORDER BY `id` DESC LIMIT 1"
        );

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findById(int|string $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id=:id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findBy(array $conditions): array
    {
        $where = [];

        foreach ($conditions as $column => $value) {
            $where[] = "`{$column}` = :{$column}";
        }

        $sql = sprintf(
            "SELECT * FROM `%s` WHERE %s",
            $this->table,
            implode(' AND ', $where)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll();
    }

    public function insert(array $data): int
    {
        $columns = implode(',', array_keys($data));

        $placeholders = implode(
            ',',
            array_map(
                fn($v) => ':' . $v,
                array_keys($data)
            )
        );

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            $columns,
            $placeholders
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int)$this->db->lastInsertId();
    }

    public function update(int|string $id, array $data): bool
    {
        $fields = implode(
            ',',
            array_map(
                fn($k) => "$k=:$k",
                array_keys($data)
            )
        );

        $sql = sprintf(
            'UPDATE %s SET %s WHERE id=:id',
            $this->table,
            $fields
        );

        $data['id'] = $id;

        return $this->db
            ->prepare($sql)
            ->execute($data);
    }

    public function delete(int|string $id): bool
    {
        return $this->db
            ->prepare(
                "DELETE FROM {$this->table} WHERE id=:id"
            )
            ->execute([
                'id' => $id
            ]);
    }
}
