<?php

use App\Model\DBSingleton;

class MessageManager
{
    public const TABLE = 'message';


    /**
     * @param array $data
     * @return Message
     */
    public static function hydrateMessage (array $data) : Message
    {
        return (new Message())
            ->setId($data['id'])
            ->setMessageContent($data['message_content'])
            ->setSendDate(DateTime::createFromFormat('Y-m-d H:i:s', $data['send_date']))
            ->setUser(UserManager::getUserById($data['user_fk']))
            ;
    }



    /**
     * @param Message $message
     * @return bool
     */
    public static function addMessage(Message &$message): bool
    {
        $stmt = DBSingleton::PDO()->prepare("
            INSERT INTO ".self::TABLE." (message_content, user_fk) 
            VALUES (:message_content, :user_fk)
        ");

        $stmt->bindValue(':message_content', $message->getMessageContent());
        $stmt->bindValue(':user_fk', $message->getUser()->getId());

        $result = $stmt->execute();

        $message->setId(DBSingleton::PDO()->lastInsertId());

        return $result;
    }



    public static function getAll() : array
    {
        $messages = [];
        $query = DBSingleton::PDO()->query("
            SELECT *
            FROM " . self::TABLE
        );

        if ($query) {
            foreach ($query->fetchAll() as $articleData) {
                $messages[] = self::hydrateMessage($articleData);
            }
        }

        return $messages;
    }
}