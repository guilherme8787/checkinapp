function Shift() {
    if (document.getElementById(26).value == "a") {
        ind = 49;
        inicio = 1;
        fim = 49;
    } else {
        ind = -49;
        inicio = 50;
        fim = 98;
    }

    for (chr = inicio; chr <= fim; chr++) {
        aux = document.getElementById(chr).value;
        document.getElementById(chr).value = document.getElementById(
            chr + ind
        ).value;
        document.getElementById(chr + ind).value = aux;
    }
}

function Digitar(tecla) {
    document.getElementById("inputEmail").value += tecla;
}

function showBr() {
    document.getElementById("brForm").style = "display: block;";
    document.getElementById("enForm").style = "display: none;";
    document.getElementById("infoedi").innerHTML = "Identificação | QR Code";
}
function showEn() {
    document.getElementById("brForm").style = "display: none;";
    document.getElementById("enForm").style = "display: block";
    document.getElementById("infoedi").innerHTML = "Identificação | E-mail";
    document.getElementById("grupobtnEn").style = "display: block";
}
function showEs() {
    document.getElementById("brForm").style = "display: none;";
    document.getElementById("enForm").style = "display: block";
    document.getElementById("infoedi").innerHTML = "Registro | Identificación";
    document.getElementById("grupobtnEn").style = "display: none;";
}
function numPadNumero(numero) {
    document.getElementById("inputCpf").value += numero;
    somenteOnze();
}

function zerar(qual) {
    if (qual == "en") {
        document.getElementById("inputEmail").value = "";
    }
    if (qual == "br") {
        document.getElementById("inputCpf").value = "";
    }
}

function apagar(qual) {
    if (qual == "en") {
        str = document.getElementById("inputEmail").value;
        document.getElementById("inputEmail").value = str.substring(
            0,
            str.length - 1
        );
    }
    if (qual == "br") {
        str = document.getElementById("inputCpf").value;
        document.getElementById("inputCpf").value = str.substring(
            0,
            str.length - 1
        );
    }
}

function somenteOnze() {
    str = document.getElementById("inputCpf").value;
    // alert(str.length);
    if (str.length > 11) {
        document.getElementById("inputCpf").value = str.substring(
            0,
            str.length - 1
        );
    }
}
