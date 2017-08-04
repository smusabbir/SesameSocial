<?php
/**
 * Copyright (c) 2017 Sesame Communications.
 *
 * All Rights Reserved.
 */

namespace SesameSocialBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;


class UserLogin
{
    private $httpClient;
    private $serializer;
    private $apiKey;

    public function __construct(ClientInterface $httpClient, SerializerInterface $serializer, $email, $password, $apiKey)

    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->email = $email;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */

    public function getAuthentication()
    {

        $res = $this->httpClient->request('GET', 'https://app.meetsoci.com/api/login', [
            'query' => [ 'email' => $this->email, 'password' => $this->password ]
        ]);
        $json = (string) $res->getBody();

      return $json;
    }



    /**
     * @return string
     */

    public function getAccountinfo()
    {

        $res = $this->httpClient->request('GET', 'https://app.meetsoci.com/api/get_accounts',[
            'query' => [ 'api_key' => $this->apiKey ]
        ]);

        $json = (string) $res->getBody();

        return $json;
    }


    /**
     * @return string
     */

    public function getProjectinfo($accountId)
    {

        $url = \GuzzleHttp\uri_template('https://app.meetsoci.com/api/account/{id}/get_projects_list', ['id' => $accountId]);
        $res = $this->httpClient->request('GET', $url, [
            'query' => [ 'api_key' => $this->apiKey ]
        ]);

        $json = (string) $res->getBody();

        return $json;
    }

}