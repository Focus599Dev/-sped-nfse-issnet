<?php

namespace NFePHP\NFSe\ISSNET\Common;

use NFePHP\NFSe\ISSNET\Soap\Soap;
use NFePHP\Common\Validator;

class Tools
{

    public $soapUrl;

    public $config;

    public $soap;

    public $pathSchemas;

    public function __construct($configJson)
    {
        $this->pathSchemas = realpath(
            __DIR__ . '/../../schemas'
        ) . '/';

        $this->config = json_decode($configJson);

        if ($this->config->tpAmb == '1') {
            $this->soapUrl = 'prod';
        } else {
            $this->soapUrl = 'homolog';
        }
    }

    protected function sendRequest($request, $soapUrl, $cnpj)
    {

        $soap = new Soap;

        $response = $soap->send($request, $soapUrl, $cnpj);

        return (string) $response;
    }

    public function envelopXML($xml, $method)
    {

        $xml = trim(preg_replace("/<\?xml.*?\?>/", "", $xml));

        $this->xml =
            '<proc:' . $method . ' soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
            <mensagemXml xsi:type="xsd:string"><![CDATA[' . $xml . ']]></mensagemXml>
            </proc:' . $method . '>';

        return $this->xml;
    }

    public function envelopSoapXML($xml)
    {
        $this->xml =
            '<soapenv:Envelope  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                                xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
                                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                                xmlns:proc="http://proces.wsnfe2.dsfnet.com.br">
                <soapenv:Header/>
                <soapenv:Body>' . $xml . '</soapenv:Body>
            </soapenv:Envelope>';

        return $this->xml;
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

    protected function getCNPJ($xml)
    {

        $xml = simplexml_load_string($xml);

        return $cnpj = (string) $xml->Cabecalho->CPFCNPJRemetente;
    }
}
