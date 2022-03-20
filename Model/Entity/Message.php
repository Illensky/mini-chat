<?php

class Message extends AbstractEntity
{
    private string $messageContent;
    private DateTime $sendDate;
    private User $user;

    /**
     * @return string
     */
    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    /**
     * @param string $messageContent
     * @return Message
     */
    public function setMessageContent(string $messageContent):self
    {
        $this->messageContent = $messageContent;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getSendDate(): DateTime
    {
        return $this->sendDate;
    }

    /**
     * @param DateTime $sendDate
     * @return Message
     */
    public function setSendDate(DateTime $sendDate): self
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Message
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }


}