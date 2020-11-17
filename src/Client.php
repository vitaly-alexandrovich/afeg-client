<?php namespace Afeg;

use Afeg\Exceptions\IncorrectResponseException;
use Afeg\Exceptions\ServerErrorException;
use Afeg\Responses\FetchResponse;
use HttpClient\Request;

/**
 * Class Client
 * @package Afeg
 */
class Client
{
    const API_ENDPOINT = 'https://afeg.ru/api';

    const GOOGLA_GQ     = 'googla.gq';
    const YANBEX_TK     = 'yanbex.tk';
    const OURMAIL_GA    = 'ourmail.ga';
    const MAIL2020_CF   = 'mail2020.cf';
    const MEIL_TK       = 'meil.tk';
    const YANBEX_GQ     = 'yanbex.gq';
    const YANBEX_GA     = 'yanbex.ga';
    const GOOGLIE_GQ    = 'googlie.gq';
    const KEERPICH_RU   = 'keerpich.ru';
    const MEIL_GA       = 'meil.ga';
    const ALLMAIL_GQ    = 'allmail.gq';
    const OURMAIL_GQ    = 'ourmail.gq';

    const FETCH_NEW    = 0;
    const FETCH_ALL    = 1;

    /**
     * @var string API Key
     */
    protected $apiKey;

    /**
     * Client constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param null $domain
     * @param null $login
     * @return Email
     * @throws ServerErrorException
     */
    public function create($domain = null, $login = null)
    {
        if (is_null($domain)) {
            $domain = static::getRandomDomain();
        }

        $params = [
            'domain' => $domain
        ];

        if (!is_null($login)) {
            $params['email'] = $login;
        }

        $response = $this->request('create', $params);
        return new Email($response, $this);
    }

    /**
     * @param $emailAddress
     * @param int $mode
     * @return FetchResponse
     * @throws IncorrectResponseException
     * @throws ServerErrorException
     */
    public function fetch($emailAddress, $mode = self::FETCH_NEW)
    {
        $params = [
            'email' => $emailAddress
        ];

        if ($mode === static::FETCH_ALL) {
            $params['all'] = 'true';
        }

        $responseBody = $this->request('fetch', $params);
        $response = json_decode($responseBody, true);

        if (!$response) {
            throw new IncorrectResponseException('Response is not JSON');
        }

        $items = [];
        $length = 0;

        foreach ($response as $key => $data) {
            if ($key === 'length') {
                $length = $data;
                continue;
            }

            $items[$key] = Message::fromArray($data);
        }

        return FetchResponse::fromArray([
            'items'     => $items,
            'length'    => $length,
            'is_all'    => $mode === self::FETCH_ALL
        ]);
    }

    /**
     * @param $emailAddress
     * @return FetchResponse
     * @throws IncorrectResponseException
     * @throws ServerErrorException
     */
    public function fetchAll($emailAddress)
    {
        return $this->fetch($emailAddress, static::FETCH_ALL);
    }

    /**
     * @return string
     */
    protected static function getRandomDomain()
    {
        $availableDomains = static::getAvailableDomains();
        return $availableDomains[array_rand($availableDomains)];
    }

    /**
     * @return string[]
     */
    public static function getAvailableDomains()
    {
        return [
            static::GOOGLA_GQ,
            static::YANBEX_TK,
            static::OURMAIL_GA,
            static::MAIL2020_CF,
            static::MEIL_TK,
            static::YANBEX_GQ,
            static::YANBEX_GA,
            static::GOOGLIE_GQ,
            static::KEERPICH_RU,
            static::MEIL_GA,
            static::ALLMAIL_GQ,
            static::OURMAIL_GQ,
        ];
    }

    /**
     * @return mixed
     */
    protected function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $action
     * @param array $params
     * @return string
     * @throws ServerErrorException
     */
    protected function request($action, $params = [])
    {
        $query      = http_build_query(array_merge(['key' => $this->getApiKey()], $params));
        $requestUri = sprintf('%s/%s?%s', static::API_ENDPOINT, $action, $query);

        $request    = new Request($requestUri);
        $client     = new \HttpClient\Client();
        
        $response = $client->sendRequest($request);

        if ($response->getCode() !== 200) {
            throw new ServerErrorException();
        }

        return $response->getBody();
    }
}