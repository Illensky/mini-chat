<?php

use App\Model\DBSingleton;

class UserManager
{
    public const TABLE = 'user';



    /**
     * @param array $data
     * @return User
     */
    private static function hydrateUser (array $data) : User
    {
        return (new User())
            ->setId($data['id'])
            ->setUsername($data['username'])
            ->setPassword($data['password'])
            ->setEmail($data['mail'])
            ;
    }



    /**
     * @param int $id
     * @return User|null
     */
    public static function getUserById (int $id): ?User
    {
        $user = new User;

        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE . "
            WHERE id = $id
        ");

        return $query ? self::hydrateUser($query->fetch()) : null;
    }



    /**
     * Check if a user exists.
     * @param User $user
     * @return bool
     */
    public static function userExists(User $user): bool
    {
        $result = DBSingleton::PDO()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE id = ".$user->getId());
        return $result ? $result->fetch()['cnt'] : 0;
    }



    /**
     * Delete a user from user db.
     * @param User $user
     * @return bool
     */
    public static function deleteUser(User $user): bool {
        if(self::userExists($user)) {
            return (bool)DBSingleton::PDO()->query("
            DELETE FROM " . self::TABLE . " WHERE id = {$user->getId()}
        ");
        }
        return false;
    }



    /**
     * Check if a user exists with its email.
     * @param string $mail
     * @return bool
     */
    public static function userMailExists(string $mail): bool
    {
        $result = DBSingleton::PDO()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE mail = \"$mail\"");
        return $result ? $result->fetch()['cnt'] : 0;
    }



    /**
     * @param User $user
     * @return bool
     */
    public static function addUser(User &$user): bool
    {
        $stmt = DBSingleton::PDO()->prepare("
            INSERT INTO ".self::TABLE." (mail, username, password) 
            VALUES (:mail, :username, :password)
        ");

        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':mail', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());

        $result = $stmt->execute();

        $user->setId(DBSingleton::PDO()->lastInsertId());

        return $result;
    }



    /**
     * Fetch a user by mail
     * @param string $mail
     * @return User|null
     */
    public static function getUserByMail(string $mail): ?User
    {
        $stmt = DBSingleton::PDO()->prepare("SELECT * FROM " . self::TABLE . " WHERE mail = :mail LIMIT 1");
        $stmt->bindParam(':mail', $mail);
        return $stmt->execute() ? self::hydrateUser($stmt->fetch()) : null;
    }
}