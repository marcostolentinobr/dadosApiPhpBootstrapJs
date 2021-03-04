//Limpa formulário
function limpa_formulário_cep() {
    document.getElementById('rua').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('uf').value = ("");
}

function meu_callback(conteudo) {

    //Remover css de campo inválido
    document.getElementById('cep').classList.remove('is-invalid');
    document.getElementById('bairro').classList.remove('is-invalid');
    document.getElementById('rua').classList.remove('is-invalid');

    //Esconder mensagem de cep inválido ou inexistente
    document.getElementById('div-cep-invalido').style.display = 'none';
    document.getElementById('div-rua-invalido').style.display = 'none';
    document.getElementById('div-bairro-invalido').style.display = 'none';

    //garantir que seja dado readonly para rua e bairro
    document.getElementById("rua").setAttribute("readonly", 'readonly');
    document.getElementById("bairro").setAttribute("readonly", 'readonly');

    if (conteudo != undefined && !("erro" in conteudo)) {

        //Caso não exista dados de rua ou bairro
        if (conteudo.logradouro == '' || conteudo.logradouro == null) {

            //Remove o readonly para acrescentar na mão bairro e rua
            document.getElementById("rua").removeAttribute("readonly", 0);
            document.getElementById("bairro").removeAttribute("readonly", 0);

            //Acrescenta css de campo inválido para rua a bairro
            document.getElementById('rua').classList.add('is-invalid')
            document.getElementById('bairro').classList.add('is-invalid')

            //Acrescenta mensagem para informar rua e bairro manualmente
            document.getElementById('div-rua-invalido').style.display = 'inline-block';
            document.getElementById('div-bairro-invalido').style.display = 'inline-block';

        } else {
            document.getElementById("rua").setAttribute("readonly", 'readonly');
            document.getElementById("bairro").setAttribute("readonly", 'readonly');
        }

        document.getElementById('rua').value = (conteudo.logradouro);
        document.getElementById('bairro').value = (conteudo.bairro);
        document.getElementById('cidade').value = (conteudo.localidade);
        document.getElementById('uf').value = (conteudo.uf);
    } //end if.
    else {
        //CEP não Encontrado.
        limpa_formulário_cep();

        //Demostrar para o usuário sobre o cep inválido ou inexistente
        document.getElementById('cep').classList.add('is-invalid')
        document.getElementById('div-cep-invalido').style.display = 'inline-block';

    }

    //terminando com loading
    loadingHide();
}

function pesquisacep(valor) {
    //Mostrando loading
    loadingShow();

    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '').trim();

    //Verifica se campo cep possui valor informado.
    if (cep.length != 8 && cep != "") {
        meu_callback();
    } else if (cep != "") {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if (validacep.test(cep)) {

            //Cria um elemento javascript.
            var script = document.createElement('script');

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "API/Cep/get/" + cep);
            ajax.send();
            ajax.addEventListener("readystatechange", function () {
                script.innerHTML = ajax.response;
                document.body.appendChild(script);
            });
        }
    } else {
        meu_callback();
    }
}