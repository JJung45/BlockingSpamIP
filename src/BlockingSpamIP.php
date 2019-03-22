<?php

namespace Junghakyoung\BlockingKisaSpam;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Junghakyoung\BlockingKisaSpam\Exceptions\WrongIPException;

class BlockingSpamIP
{
    /**
     * @var GuzzleClient
     */
    private $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $ip
     * @return bool
     * @throws \Exception
     */
    public function isOk(string $ip): bool
    {
        $this->checkIP($ip);

        $client = new Client();
        $client->setClient($this->client);

        $crawler = $client->request('POST', 'https://spam.kisa.or.kr/rbl/sub4.do', [
            'IP' => $ip,
        ]);

        $filterd = $crawler->filter('.orange')->first();


        $response = $filterd->text();

        if (strpos($response, "등록되어 있지 않습니다.") !== false) {
            //블랙리스트 등록되어 있지 않음.
            return true;
        }

        //블랙리스트 등록되어 있음.
        return false;
    }

    /**
     * @param string $ip
     * @throws WrongIPException
     */
    protected function checkIP(string $ip)
    {
        if (! preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/'
            , $ip)) {
            throw new WrongIPException($ip . "는 유효하지 않은 값입니다.");
        }
    }
}