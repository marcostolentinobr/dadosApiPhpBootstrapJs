<?

class Cep extends Conexao {

    private $tabela = 'CEP';
    private $idChave = 'cep';
    private $valorChave;
    private $dados;

    public function get() {
        $this->valorChave = CHAVE;

        $this->setDados();
        if (!$this->dados) {
            $this->setDadosViaCep();
            $this->incluir();
        }

        $this->retornar();
    }

    private function setDados() {
        $where = [$this->idChave => $this->valorChave];
        $this->dados = @$this->list($this->tabela, $where)[0];
    }

    private function setDadosViaCep() {
        $conteudoViaCepXml = simplexml_load_string(file_get_contents("https://viacep.com.br/ws/$this->valorChave/xml/"));
        $this->dados = json_decode(json_encode($conteudoViaCepXml), true);
    }

    private function incluir() {
        if (!isset($this->dados['erro'])) {
            $dados = [
                'cep' => str_replace('-', '', $this->dados['cep']),
                'uf' => $this->dados['uf'],
                'localidade' => $this->dados['localidade'],
                'bairro' => $this->dados['bairro'],
                'logradouro' => $this->dados['logradouro'],
            ];
            $this->insert($this->tabela, $dados);
        }
    }

    private function retornar() {
        exit(" meu_callback(" . json_encode($this->dados) . ") ");
    }

}
