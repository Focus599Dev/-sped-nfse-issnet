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
            $this->soapUrl = 'http://www.issnetonline.com.br/webserviceabrasf/homologacao/servicos.asmx?WSDL';
        }
    }

    protected function sendRequest($request, $soapUrl, $soapAction)
    {

        $soap = new Soap($this->certificate);

        $response = $soap->send($request, $soapUrl, $soapAction);

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

    public function envelopSOAP($xml, $service)
    {
        $this->xml =
            '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:nfd="http://www.issnetonline.com.br/webservice/nfd">
            <soap:Header/>
            <soap:Body>
                <nfd:' . $service . '>
                    <nfd:xml>' . $xml . '</nfd:xml>
                </nfd:' . $service . '>
            </soap:Body>
        </soap:Envelope>';

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
