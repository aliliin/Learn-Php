<?php


namespace Core\http;


class Response
{

    protected $swooleReponse;
    protected $body;

    public function __construct($swooleReponse)
    {
        $this->swooleReponse = $swooleReponse;
        $this->setHeader('content-type', 'text/plain;charset=utf-8', true);
    }

    public static function init(\Swoole\Http\Response $response)
    {
        return new self($response);
    }

    public function end()
    {
        // string array object
        $jsonConver = ['array'];
        $result = $this->getBody();
        if (in_array(gettype($result), $jsonConver)) {
            $this->swooleReponse->header("Content-type", "application/json;charset=uft-8");
            $this->swooleReponse->write(json_encode($result));
        } else {
            $this->swooleReponse->write($result);
        }

        $this->swooleReponse->end();
    }

    public function setHeader(string $key, string $value, bool $ucwords = true)
    {
        $this->swooleReponse->header($key, $value, $ucwords);
    }

    public function writeHttpStatus(int $httpStatusCode)
    {
        $this->swooleReponse->status($httpStatusCode);
    }

    public function redirect(string $url, int $http_code = 302)
    {
        $this->writeHttpStatus($http_code);
        $this->setHeader("Location", $url);
    }

    public function writeHtml($html)
    {
        $this->swooleReponse->write($html);
    }

    public function testWrite($html)
    {
        $this->swooleReponse->write($html);
    }

    public function getBody()
    {
        return $this->body;
    }


    public function setBody($body): void
    {
        $this->body = $body;
    }


}