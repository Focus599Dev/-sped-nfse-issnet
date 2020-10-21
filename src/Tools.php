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

        $service = 'RecepcionarLoteRps';

        $soapAction = 'http://www.issnetonline.com.br/webservice/nfd/RecepcionarLoteRps';

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

        $this->isValid($xml, $xsd);

        $this->lastRequest = htmlspecialchars_decode($xml);

        $request = $this->envelopXML($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($request, $this->soapUrl, $soapAction);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }

    public function CancelaNfse($std)
    {

        $make = new Make();

        $service = 'CancelarNfse';

        $soapAction = "http://www.issnetonline.com.br/webservice/nfd/CancelarNfse";

        $xml = $make->cancelamento($std);

        $xml = Signer::sign(
            $this->certificate,
            $xml,
            'Pedido',
            'Id',
            $this->algorithm,
            $this->canonical
        );

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopXML($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($request, $this->soapUrl, $soapAction);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }

    public function consultaSituacaoLoteRPS($std)
    {

        $make = new Make();

        $service = 'ConsultarNfse';

        $soapAction = "http://www.issnetonline.com.br/webservice/nfd/ConsultarNfse";

        $xml = $make->consulta($std);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopXML($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($request, $this->soapUrl, $soapAction);

        $response = strip_tags($response);

        $response = htmlspecialchars_decode($response);

        return $response;
    }
}
