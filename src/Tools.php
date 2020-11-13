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

        $xml = Signer::sign(
            $this->certificate,
            $xml,
            'EnviarLoteRpsEnvio',
            'Id',
            $this->algorithm,
            $this->canonical
        );

        $xml = Strings::clearXmlString($xml);

        $xsd = 'servico_enviar_lote_rps_envio.xsd';
        
        $this->isValid($xml, $xsd);

        $this->lastRequest = htmlspecialchars_decode($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($this->soapUrl, $soapAction, 'RecepcionarLoteRps', 3, [], [] , $request);
        
        $response = html_entity_decode($response);

        $response = trim(preg_replace("/<\?xml.*?\?>/", "", $response));

        $response = $this->removeStuffs($response);

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

        $xsd = 'servico_cancelar_nfse_envio.xsd';
        
        $this->isValid($xml, $xsd);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($this->soapUrl, $soapAction, $service, 3, [], [] , $request);

        $response = html_entity_decode($response);

        $response = trim(preg_replace("/<\?xml.*?\?>/", "", $response));

        $response = $this->removeStuffs($response);

        return $response;
    }

    public function consultaSituacaoLoteRPS($nprot, \stdClass $std){

        $std->protocolo = $nprot;

        $make = new Make();

        $service = 'ConsultarSituacaoLoteRPS';

        $soapAction = "http://www.issnetonline.com.br/webservice/nfd/ConsultarSituacaoLoteRPS";

        $xml = $make->consultaSituacao($std);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($this->soapUrl, $soapAction, $service, 3, [], [] , $request);

        $response = html_entity_decode($response);

        $response = trim(preg_replace("/<\?xml.*?\?>/", "", $response));

        $response = $this->removeStuffs($response);

        return $response;
    }

    public function ConsultarNfsePorRps($indenRPS, $data){

        $make = new Make();

        $service = 'ConsultaNFSePorRPS';

        $soapAction = "http://www.issnetonline.com.br/webservice/nfd/ConsultaNFSePorRPS";

        $xml = $make->consultaNFSePorRPS($indenRPS, $data);

        $xsd = 'servico_consultar_nfse_rps_envio.xsd';
        
        $this->isValid($xml, $xsd);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($this->soapUrl, $soapAction, $service, 3, [], [] , $request);

        $response = html_entity_decode($response);

        $response = trim(preg_replace("/<\?xml.*?\?>/", "", $response));

        $response = $this->removeStuffs($response);

        return $response;

    }

    public function consultaLoteRPS($nprot, $data){

        $make = new Make();

        $service = 'ConsultarLoteRps';

        $soapAction = "http://www.issnetonline.com.br/webservice/nfd/ConsultarLoteRps";

        $xml = $make->consultaLoteRPS($nprot, $data);

        $xml = Strings::clearXmlString($xml);

        $request = $this->envelopSOAP($xml, $service);

        $response = $this->sendRequest($this->soapUrl, $soapAction, $service, 3, [], [] , $request);

        $response = html_entity_decode($response);

        $response = trim(preg_replace("/<\?xml.*?\?>/", "", $response));

        $response = $this->removeStuffs($response);

        return $response;

    }
}
