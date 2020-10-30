<?php

namespace NFePHP\NFSe\ISSNET;

use NFePHP\Common\DOMImproved as Dom;
use stdClass;

class Make
{

    public $dom;

    public $xml;

    public function __construct()
    {

        $this->dom = new Dom();

        $this->dom->preserveWhiteSpace = false;

        $this->dom->formatOutput = false;

        $this->enviarLoteRpsEnvio = $this->dom->createElement('EnviarLoteRpsEnvio');
        $this->enviarLoteRpsEnvio->setAttribute('xmlns', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_enviar_lote_rps_envio.xsd');
        $this->enviarLoteRpsEnvio->setAttribute('xmlns:tc', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd');
        $this->dom->appendChild($this->enviarLoteRpsEnvio);

        $this->loteRps = $this->dom->createElement('LoteRps');
        $this->enviarLoteRpsEnvio->appendChild($this->loteRps);

        $this->listaRps = $this->dom->createElement('tc:ListaRps');

        $this->rps = $this->dom->createElement('tc:Rps');

        $this->infRps = $this->dom->createElement('tc:InfRps');

        $this->identificacaoRps = $this->dom->createElement('tc:IdentificacaoRps');

        $this->Servico = $this->dom->createElement('tc:Servico');

        $this->Valores = $this->dom->createElement('tc:Valores');

        $this->Prestador  = $this->dom->createElement('tc:Prestador');

        $this->CpfCnpjPrestador = $this->dom->createElement('tc:CpfCnpj');

        $this->CpfCnpjTomador = $this->dom->createElement('tc:CpfCnpj');

        $this->Tomador = $this->dom->createElement('tc:Tomador');

        $this->IdentificacaoTomador = $this->dom->createElement('tc:IdentificacaoTomador');

        $this->Endereco = $this->dom->createElement('tc:Endereco');
    }

    public function getXML()
    {
        if (empty($this->xml)) {

            $this->monta();
        }

        return $this->xml;
    }

    public function monta()
    {

        $this->loteRps->appendChild($this->listaRps);

        $this->listaRps->appendChild($this->rps);

        $this->rps->appendChild($this->infRps);

        $items = $this->rps->getElementsByTagName('tc:InfRps');

        $firstItem = $items->item(0);

        $firstItem->insertBefore($this->identificacaoRps, $firstItem->firstChild);

        $this->infRps->appendChild($this->Servico);

        $items = $this->infRps->getElementsByTagName('tc:Servico');

        $firstItem = $items->item(0);

        $firstItem->insertBefore($this->Valores, $firstItem->firstChild);

        $this->infRps->appendChild($this->Prestador);

        $items = $this->infRps->getElementsByTagName('tc:Prestador');

        $firstItem = $items->item(0);

        $firstItem->insertBefore($this->CpfCnpjPrestador, $firstItem->firstChild);

        $this->infRps->appendChild($this->Tomador);

        $items = $this->infRps->getElementsByTagName('tc:Tomador');

        $firstItem = $items->item(0);

        $firstItem->insertBefore($this->IdentificacaoTomador, $firstItem->firstChild);

        $this->IdentificacaoTomador->appendChild($this->CpfCnpjTomador);

        $this->Tomador->appendChild($this->Endereco);

        $this->xml = $this->dom->saveXML();

        return $this->xml;
    }

    public function buildCabec($std)
    {

        $this->dom->addChild(
            $this->loteRps,
            "tc:NumeroLote",
            $std->NumeroLote,
            true,
            "Número do Lote de RPS"
        );

        $cpfCnpj = $this->dom->createElement('tc:CpfCnpj');
        $this->loteRps->appendChild($cpfCnpj);

        $this->dom->addChild(
            $cpfCnpj,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número CNPJ"
        );

        $this->dom->addChild(
            $this->loteRps,
            "tc:InscricaoMunicipal",
            $std->InscricaoMunicipal,
            true,
            "Inscrição Municipal"
        );

        $this->dom->addChild(
            $this->loteRps,
            "tc:QuantidadeRps",
            $std->QuantidadeRps,
            true,
            "Quantidade de RPS do Lote"
        );
    }

    public function buildIdentificacaoRps($std)
    {

        $this->dom->addChild(
            $this->identificacaoRps,
            "tc:Numero",
            $std->Numero,
            true,
            "Número do RPS"
        );

        $this->dom->addChild(
            $this->identificacaoRps,
            "tc:Serie",
            $std->Serie,
            true,
            "Número de série do RPS"
        );

        $this->dom->addChild(
            $this->identificacaoRps,
            "tc:Tipo",
            $std->Tipo,
            true,
            "Código de tipo de RPS | 1 - RPS | 2 – Nota Fiscal Conjugada (Mista) | 3 – Cupom"
        );
    }

    public function buildInfRps($std)
    {

        $this->dom->addChild(
            $this->infRps,
            "tc:DataEmissao",
            $std->DataEmissao,
            true,
            "Formato AAAA-MM-DDTHH:mm:ss 
            onde:
            AAAA = ano com 4 caracteres 
            MM = mês com 2 caracteres 
            DD = dia com 2 caracteres 
            T = caractere de formatação que deve existir separando a data da hora 
            HH = hora com 2 caracteres 
            mm: minuto com 2 caracteres 
            ss: segundo com 2 caracteres"
        );

        $this->dom->addChild(
            $this->infRps,
            "tc:NaturezaOperacao",
            $std->NaturezaOperacao,
            true,
            "Código de natureza da operação
            1 – Tributação no município
            2 - Tributação fora do município
            3 - Isenção
            4 - Imune
            5 –Exigibilidade suspensa por decisão judicial
            6 – Exigibilidade suspensa por procedimento administrativo"
        );

        $this->dom->addChild(
            $this->infRps,
            "tc:OptanteSimplesNacional",
            $std->OptanteSimplesNacional,
            true,
            "Identificação de Sim/Não
            1 - Sim
            2 – Não"
        );

        $this->dom->addChild(
            $this->infRps,
            "tc:IncentivadorCultural",
            $std->IncentivadorCultural,
            true,
            "Identificação de Sim/Não
            1 - Sim
            2 – Não"
        );

        $this->dom->addChild(
            $this->infRps,
            "tc:Status",
            $std->Status,
            true,
            "Código de status da NFS-e
            1 – Normal
            2 – Cancelado"
        );

        // $this->dom->addChild(
        //     $this->infRps,
        //     "tc:RegimeEspecialTributacao",
        //     $std->RegimeEspecialTributacao,
        //     true,
        //     "Código de identificação do regime especial de tributação
        //     1 – Microempresa municipal
        //     2 - Estimativa
        //     3 – Sociedade de profissionais
        //     4 – Cooperativa"
        // );
    }

    public function buildServico($std)
    {

        $this->dom->addChild(
            $this->Servico,
            "tc:ItemListaServico",
            $std->ItemListaServico,
            true,
            "Código de item da lista de serviço"
        );

        $this->dom->addChild(
            $this->Servico,
            "tc:CodigoCnae",
            $std->CodigoCnae,
            true,
            "Código CNAE"
        );

        $this->dom->addChild(
            $this->Servico,
            "tc:CodigoTributacaoMunicipio",
            $std->CodigoTributacaoMunicipio,
            true,
            "Código de Tributação"
        );

        $this->dom->addChild(
            $this->Servico,
            "tc:Discriminacao",
            $std->Discriminacao,
            true,
            "Discriminação do conteúdo da NFS-e"
        );

        $this->dom->addChild(
            $this->Servico,
            "tc:MunicipioPrestacaoServico",
            $std->CodigoMunicipio,
            true,
            "Código de identificação do município conforme tabela do IBGE"
        );
    }

    public function buildValores($std)
    {

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorServicos",
            $std->ValorServicos,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorPis",
            $std->ValorPis,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorCofins",
            $std->ValorCofins,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorInss",
            $std->ValorInss,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorIr",
            $std->ValorIr,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorCsll",
            $std->ValorCsll,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:IssRetido",
            $std->IssRetido,
            true,
            "dentificação de Sim/Não
            1 - Sim
            2 – Não"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorIss",
            $std->ValorIss,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:BaseCalculo",
            $std->BaseCalculo,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:Aliquota",
            $std->Aliquota,
            true,
            "Alíquota. Valor percentual.
            Formato: 0.0000
            Ex:
            1% = 0.01
            25,5% = 0.255
            100% = 1.0000 ou 1"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:ValorLiquidoNfse",
            $std->ValorLiquidoNfse,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:DescontoIncondicionado",
            $std->DescontoIncondicionado,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );

        $this->dom->addChild(
            $this->Valores,
            "tc:DescontoCondicionado",
            $std->DescontoCondicionado,
            true,
            "Valor monetário.
            Formato: 0.00 (ponto separando casa decimal)
            Ex:
            1.234,56 = 1234.56
            1.000,00 = 1000.00
            1.000,00 = 1000"
        );
    }

    public function buildPrestador($std)
    {

        $this->dom->addChild(
            $this->Prestador,
            "tc:InscricaoMunicipal",
            $std->InscricaoMunicipal,
            true,
            "Inscrição Municipal da empresa/pessoa"
        );
    }

    public function buildTomador($std)
    {

        $this->dom->addChild(
            $this->Tomador,
            "tc:RazaoSocial",
            $std->RazaoSocial,
            true,
            "Razão social"
        );
    }

    public function buildCpfCnpjPrestador($std)
    {

        $this->dom->addChild(
            $this->CpfCnpjPrestador,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número do Cnpj"
        );
    }

    public function buildCpfCnpjTomador($std)
    {

        $this->dom->addChild(
            $this->CpfCnpjTomador,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número do Cnpj"
        );
    }

    public function buildEndereco($std)
    {

        $this->dom->addChild(
            $this->Endereco,
            "tc:Endereco",
            $std->Endereco,
            true,
            "Tipo e nome do logradouro"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Numero",
            $std->Numero,
            true,
            "Número do imóvel"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Complemento",
            $std->Complemento,
            true,
            "Complemento do Endereço"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Bairro",
            $std->Bairro,
            true,
            "Nome do bairro"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Cidade",
            $std->CodigoMunicipio,
            true,
            "Código da cidade"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Estado",
            $std->Uf,
            true,
            "Sigla do estado"
        );

        $this->dom->addChild(
            $this->Endereco,
            "tc:Cep",
            $std->Cep,
            true,
            "CEP da localidade"
        );
    }

    public function cancelamento($std)
    {

        $req = $this->dom->createElement('p1:CancelarNfseEnvio');
        $req->setAttribute('xmlns:p1', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_cancelar_nfse_envio.xsd');
        $req->setAttribute('xmlns:tc', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd');
        $req->setAttribute('xmlns:ts', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_simples.xsd');
        $this->dom->appendChild($req);

        $pedido = $this->dom->createElement('Pedido');
        $req->appendChild($pedido);

        $infPedidoCancelamento = $this->dom->createElement('tc:InfPedidoCancelamento');
        $pedido->appendChild($infPedidoCancelamento);

        $identificacaoNfse = $this->dom->createElement('tc:IdentificacaoNfse');
        $infPedidoCancelamento->appendChild($identificacaoNfse);

        $this->dom->addChild(
            $identificacaoNfse,
            "tc:Numero",
            $std->CodigoMunicipio,
            true,
            "Número da Nota Fiscal de Serviço Eletrônica, formado pelo ano com 04 (quatro) dígitos e um número seqüencial com 11 posições – 
            Formato AAAANNNNNNNNNNN"
        );

        $this->dom->addChild(
            $identificacaoNfse,
            "tc:Cnpj",
            $std->CodigoMunicipio,
            true,
            "Número CNPJ"
        );

        $this->dom->addChild(
            $identificacaoNfse,
            "tc:InscricaoMunicipal",
            $std->CodigoMunicipio,
            true,
            "Inscrição Municipal"
        );

        $this->dom->addChild(
            $identificacaoNfse,
            "tc:CodigoMunicipio",
            $std->CodigoMunicipio,
            true,
            "Código de identificação do município conforme tabela do IBGE"
        );

        $this->dom->addChild(
            $infPedidoCancelamento,
            "tc:CodigoCancelamento",
            $std->CodigoMunicipio,
            true,
            "Código de cancelamento com base na tabela de Erros e alertas."
        );

        // $this->dom->addChild(
        //     $infPedidoCancelamento,
        //     "tc:MotivoCancelamentoNfse",
        //     $std->CodigoMunicipio,
        //     true,
        //     "Motivo de cancelamento"
        // );

        $this->xml = $this->dom->saveXML();

        return $this->xml;
    }

    public function consulta($std)
    {
        $req = $this->dom->createElement('ConsultarNfseEnvio');
        $req->setAttribute('xmlns', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_consultar_nfse_envio.xsd');
        $req->setAttribute('xmlns:tc', 'http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd');
        $this->dom->appendChild($req);

        $prestador = $this->dom->createElement('Prestador');
        $req->appendChild($prestador);

        $cpfCnpj = $this->dom->createElement('tc:CpfCnpj');
        $prestador->appendChild($cpfCnpj);

        $this->dom->addChild(
            $cpfCnpj,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número CNPJ"
        );

        $this->dom->addChild(
            $prestador,
            "tc:InscricaoMunicipal",
            $std->inscricaoMunicipal,
            true,
            "Inscrição Municipal"
        );

        $this->dom->addChild(
            $req,
            "NumeroNfse",
            $std->numeroNfse,
            true,
            "Inscrição Municipal"
        );

        $periodoEmissao = $this->dom->createElement('PeriodoEmissao');
        $req->appendChild($periodoEmissao);

        $this->dom->addChild(
            $periodoEmissao,
            "DataInicial",
            $std->numeroNfse,
            true,
            "Inscrição Municipal"
        );

        $this->dom->addChild(
            $periodoEmissao,
            "DataFinal",
            $std->numeroNfse,
            true,
            "Inscrição Municipal"
        );

        $tomador = $this->dom->createElement('Tomador');
        $req->appendChild($tomador);

        $cpfCnpj = $this->dom->createElement('tc:CpfCnpj');
        $tomador->appendChild($cpfCnpj);

        $this->dom->addChild(
            $cpfCnpj,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número CNPJ"
        );

        $this->dom->addChild(
            $tomador,
            "tc:InscricaoMunicipal",
            $std->inscricaoMunicipal,
            true,
            "Inscrição Municipal"
        );

        $intermediarioServico = $this->dom->createElement('IntermediarioServico');
        $req->appendChild($intermediarioServico);

        $cpfCnpj = $this->dom->createElement('tc:CpfCnpj');
        $intermediarioServico->appendChild($cpfCnpj);

        $this->dom->addChild(
            $cpfCnpj,
            "tc:Cnpj",
            $std->Cnpj,
            true,
            "Número CNPJ"
        );

        $this->dom->addChild(
            $intermediarioServico,
            "tc:RazaoSocial",
            $std->RazaoSocial,
            true,
            "Razão Social"
        );

        $this->dom->addChild(
            $intermediarioServico,
            "tc:InscricaoMunicipal",
            $std->inscricaoMunicipal,
            true,
            "Inscrição Municipal"
        );

        $this->xml = $this->dom->saveXML();

        return $this->xml;
    }
}
