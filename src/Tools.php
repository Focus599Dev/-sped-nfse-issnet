<?php

namespace NFePHP\NFSe\ISSNET;

use NFePHP\NFSe\ISSNET\Common\Tools as ToolsBase;
use NFePHP\Common\Strings;
use NFePHP\NFSe\ISSNET\Make;

class Tools extends ToolsBase
{
    public function enviaRPS($xml)
    {

        if (empty($xml)) {
            throw new InvalidArgumentException('$xml');
        }

        $xml = Strings::clearXmlString($xml);

        $xsd = 'ReqEnvioLoteRPS.xsd';

        $this->isValid($xml, $xsd);

        $this->lastRequest = htmlspecialchars_decode($xml);

        $cnpj = $this->getCNPJ($xml);

        $request = $this->envelopXML($xml, $servico);

        $request = $this->envelopSoapXML($request);

        // $response = $this->sendRequest($request, $this->soapUrl, $cnpj);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }

    public function CancelaNfse($std)
    {

        $make = new Make();

        $xml = $make->cancelamento($std);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopXML($xml, $servico);

        $request = $this->envelopSoapXML($request);

        $response = $this->sendRequest($request, $this->soapUrl);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }

    public function consultaSituacaoLoteRPS($std)
    {

        $make = new Make();

        $xml = $make->consulta($std, $codigoCidade);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopXML($xml);

        $request = $this->envelopSoapXML($request);

        $response = $this->sendRequest($request, $this->soapUrl);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }
}
