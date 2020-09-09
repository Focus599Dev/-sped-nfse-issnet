<?php

namespace NFePHP\NFSe\ISSNET;

use NFePHP\NFSe\ISSNET\Common\Tools as ToolsBase;
use NFePHP\NFSe\ISSNET\Common\Signer;
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

        $xml = Signer::sign(
            $this->certificate,
            $xml,
            'EnviarLoteRpsEnvio',
            'Id',
            $this->algorithm,
            $this->canonical
        );

        $xsd = 'servico_enviar_lote_rps_envio.xsd';

        // XSD's invalidos
        // $this->isValid($xml, $xsd);

        $this->lastRequest = htmlspecialchars_decode($xml);

        $request = $this->envelopXML($xml);

        $response = $this->sendRequest($request, $this->soapUrl);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }

    public function CancelaNfse($std)
    {

        $make = new Make();

        $xml = $make->cancelamento($std);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopXML($xml);

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

        $response = $this->sendRequest($request, $this->soapUrl);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }
}
