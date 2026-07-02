<?php

declare(strict_types=1);

namespace App;

final class UserRepository extends CrudRepository
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email=:email"
        );

        $stmt->execute([
            'email' => $email
        ]);

        return $stmt->fetch() ?: null;
    }
}