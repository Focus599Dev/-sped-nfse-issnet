<?php

namespace NFePHP\NFSe\ISSNET\Common;

use NFePHP\Common\Certificate;
use NFePHP\NFSe\ISSNET\Soap\Soap;
use NFePHP\Common\Validator;

class Tools
{

    public $soapUrl;

    public $config;

    public $soap;

    public $pathSchemas;

    protected $algorithm = OPENSSL_ALGO_SHA1;

    protected $canonical = [false, false, null, null];

    public function __construct($configJson, Certificate $certificate)
    {
        $this->pathSchemas = realpath(
            __DIR__ . '/../../schemas'
        ) . '/';

        $this->certificate = $certificate;

        $this->config = json_decode($configJson);

        if ($this->config->tpAmb == '1') {
            $this->soapUrl = 'prod';
        } else {
            $this->soapUrl = 'homolog';
        }
    }

    protected function sendRequest($request, $soapUrl)
    {

        $soap = new Soap($this->certificate);

        $response = $soap->send($request, $soapUrl);

        return (string) $response;
    }

    public function envelopXML($xml)
    {

        $xml = trim(preg_replace("/<\?xml.*?\?>/", "", $xml));

        $this->xml =
            '<GerarNfseEnvio xmlns="http://www.abrasf.org.br/nfse.xsd">
                <Rps xmlns="http://www.abrasf.org.br/nfse.xsd">'
            . $xml .
            '</Rps>
            </GerarNfseEnvio>';

        return $this->xml;
    }

    public function removeStuffs($xml)
    {

        if (preg_match('/<SOAP-ENV:Body>/', $xml)) {

            $tag = '<SOAP-ENV:Body>';
            $xml = substr($xml, (strpos($xml, $tag) + strlen($tag)), strlen($xml));

            $tag = '</SOAP-ENV:Body>';
            $xml = substr($xml, 0, strpos($xml, $tag));
        }

        $xml = trim($xml);

        return $xml;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    protected function isValid($body, $method)
    {
        $pathschemes = realpath(__DIR__ . '/../../schemas/') . '/';

        $schema = $pathschemes . $method;

        if (!is_file($schema)) {
            return true;
        }

        return Validator::isValid(
            $body,
            $schema
        );
    }
}
