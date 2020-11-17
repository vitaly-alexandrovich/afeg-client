<?php namespace Afeg;

use Generator;

/**
 * Class Email
 * @package Afeg
 */
class Email
{
    /** @var Client */
    protected $client;

    /** @var string */
    protected $address;

    /**
     * Email constructor.
     * @param $emailAddress
     * @param $client
     */
    public function __construct($emailAddress, $client)
    {
        $this->address = $emailAddress;
        $this->client = $client;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param int $mode
     * @return Responses\FetchResponse
     * @throws Exceptions\IncorrectResponseException
     * @throws Exceptions\ServerErrorException
     */
    public function fetch($mode = Client::FETCH_NEW)
    {
        return $this->client->fetch($this->getAddress(), $mode);
    }

    /**
     * @return Responses\FetchResponse
     * @throws Exceptions\IncorrectResponseException
     * @throws Exceptions\ServerErrorException
     */
    public function fetchAll()
    {
        return $this->fetch(Client::FETCH_ALL);
    }

    /**
     * @param int $interval
     * @return Generator|Message[]
     * @throws Exceptions\IncorrectResponseException
     * @throws Exceptions\ServerErrorException
     */
    public function waitNewMessage($interval = 60)
    {
        do {
            $response = $this->fetch();
            $messages = $response->getItems();

            if (!empty($messages)) {
                foreach($messages as $id => $message) {
                    yield $id => $message;
                }
            }

            sleep($interval);
        } while(true);
    }
}