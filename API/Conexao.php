<?php

class Conexao {

    private $pdo;

    public function __construct($pdo = '') {

        if ($pdo) {
            $this->pdo = $pdo;
        }
        $this->pdo = $this->pdo();
    }

    private function pdo() {
        if ($this->pdo) {
            return $this->pdo;
        }

        $this->pdo = new PDO(DB_LIB . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->pdo;
    }

    private function dadosQry($dados, $insert = false) {
        $ITEM = [];
        $ITEM['sintaxe'] = [];
        $ITEM['valores'] = [];
        foreach ($dados as $coluna => $valor) {
            if (!empty($valor) || $valor === '0') {
                if ($insert) {
                    $ITEM['sintaxe'][] = "$coluna";
                } else {
                    $ITEM['sintaxe'][] = "$coluna=:$coluna";
                }
                $key = ":$coluna";
                $ITEM['valores'][$key] = $valor === 'NULL' ? null : $valor;
            }
        }
        return $ITEM;
    }

    private function where($valores) {
        $where = '';
        if ($valores) {
            $dado = $this->dadosQry($valores, false);
            $where = ' WHERE ' . implode(' AND ', $dado['sintaxe']);
            $valores = $dado['valores'];
        }

        return $where;
    }

    private function prepareExecute($sql, $dado = [], $listar = false) {
        $acao = $this->pdo->prepare($sql);
        $execute = $acao->execute($dado);
        return ($listar ? $acao : $execute);
    }

    private function listarRetorno($sql, $valores) {
        $acao = $this->prepareExecute($sql . $this->where($valores), $valores, true);
        return $acao->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function list($tabela, $valores = []) {
        $sql = "SELECT * FROM $tabela";
        return $this->listarRetorno($sql, $valores);
    }

    protected function insert($tabela, $dados) {
        $dadoIncluir = $this->dadosQry($dados, true);
        $sql = "INSERT INTO $tabela (" . implode(", ", $dadoIncluir['sintaxe']);
        $sql .= ') VALUES ( :' . implode(", :", $dadoIncluir['sintaxe']) . ')';
        return $this->prepareExecute($sql, $dadoIncluir['valores']);
    }

}
