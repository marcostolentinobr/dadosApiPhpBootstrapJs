
CREATE TABLE CEP (
                cep VARCHAR(8) NOT NULL,
                uf CHAR(2) NOT NULL,
                localidade VARCHAR(100) NOT NULL,
                bairro VARCHAR(100),
                logradouro VARCHAR(500),
                PRIMARY KEY (cep)
);