<?php

class MessageController
{
    /**
     * Add message method from API.
     * @return void
     */
    public static function index() : void
    {
        $payload = file_get_contents('php://input');
        $payload = json_decode($payload);

        // On quitte si tous les paramètres ne sont pas la...
        if (empty($payload->message)) {
            // 400 = Bad Request.
            http_response_code(400);
            exit();
        }
        else {
            $message = (new Message())
                ->setUser($_SESSION['user'])
                ->setMessageContent($payload->message)
                ->setSendDate(new DateTime())
                ;
            if (MessageManager::addMessage($message)) {
                self::getAll();
            }
        }
        exit;
    }


    /**
     * get all the messages from DB to JS
     * @return void
     */
    public static function getAll() : void
    {
        $messages = [];

        foreach (MessageManager::getAll() as $key => $message) {
            /* @var Message $message */
            $messages[$key]['messageContent'] = $message->getMessageContent();
            $messages[$key]['user'] = $message->getUser()->getUsername();
            $messages[$key]['date'] = $message->getSendDate()->format('Y-m-d H:i:s');
        }

        $messages = array_reverse($messages);

        $messages = array_slice($messages, 0, 50);

        echo json_encode($messages);
        http_response_code(200);
        exit;
    }
}