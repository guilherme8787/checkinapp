<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Auto-atendimento</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='{{ url('/') }}/legacy/main.css'>
    <script src='{{ url('/') }}/legacy/main.js'></script>

    <link rel="stylesheet" href='{{ url('/') }}/legacy/assets/css/bootstrap.min.css'>
    <script src='{{ url('/') }}/legacy/assets/js/jquery-3.4.1.min.js'></script>
    <script src='{{ url('/') }}/legacy/assets/js/popper.min.js'></script>
    <script src='{{ url('/') }}/legacy/assets/js/bootstrap.min.js'></script>

    <link rel="icon" href="{{ url('/') }}/legacy/assets/img/favicon.gif" type="image/gif" sizes="16x16">

    <script>
        $(function(){
            var keyStop = {
                8: ":not(input:text, textarea, input:file, input:password)", // stop backspace = back
                // 13: "input:text, input:password", // stop enter = submit
                end: null
            };
            $(document).bind("keydown", function(event){
                var selector = keyStop[event.which];

                if(selector !== undefined && $(event.target).is(selector)) {
                    event.preventDefault(); //stop event
                }
                return true;
            });
        });
    </script>

    <style>
        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 30px;
            height: 55px;
        }
        .lds-ripple div {
            position: absolute;
            border: 4px solid {{ $data['font_color'] ?? '#000000' }};
            opacity: 1;
            border-radius: 50%;
            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }
        @keyframes lds-ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            4.9% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 0;
            }
            5% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }
    </style>
</head>
@if ($data['event_bg_image'] != null)
<body style="color: {{ $data['font_color'] }} !important; background: url('{{ asset('upload') . '/' . $data['event_bg_image'] }}');">
@elseif ($data['color'] != null)
<body style="color: {{ $data['font_color'] }} !important; background: {{ $data['color'] }};">
@else
<body style="color: {{ $data['font_color'] }} !important; background: {{ $data['color'] }};">
@endif
    <div class="container">

        <nav class="navbar">
            <p id="infoedi" class="navbar-brand" href="#">Identificação | QR Code</p>
        </nav>
        <div class="container mb-3">
            <center>
                @if ($data['event_img'] != null)
                    <img class="img-fluid" style="max-height: 250px;" src="{{ asset('upload') . '/' . $data['event_img'] }}" />
                @endif
            </center>
        </div>
        <br>

        <div class="row">
            <div class="col-lg-12">
                <a class="p-4" onclick="showBr();document.getElementById('inputCpf').focus();" style="cursor: pointer; border-radius: 0 !important;">
                    <img style="width: 48px;" class="img-fluid" src="{{ url('/') }}/legacy/img/qrcode-lg.png">
                </a>
                <a class="mb-3" onclick="showEn();" style="cursor: pointer; border-radius: 0 !important;">
                    <img style="width: 48px;" id="mailElementImage" class="img-fluid" src="{{ url('/') }}/legacy/img/mailyellow.png" data-toggle="tooltip" data-placement="right" title="Entrar utilizando E-mail">
                </a>
            </div>
        </div>

        <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        </script>

        <div class="container" id="brForm">
            <div class="row">
                <!-- form -->
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('get-guest-mail', ['listId' => $data['id'], 'guestList' => $eventId]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="visitorId"><h3>Escaneando</h3></label>
                            <div class="lds-ripple"><div></div><div></div></div>
                            <input type="password" name="emailAddress" id="visitorId" style="border: solid 0px;border-radius: 0;background: transparent;" class="form-control form-control-sm" aria-describedby="visitorIdHelp" autofocus>
                            <small id="visitorIdHelp" class="form-text text-muted">Aproxime a credencial do leitor.</small>
                        </div>
                    </form>
                    <br>
                </div>
                <div id="qrImg" class="col-lg-6">
                    <div style="width: 350px;margin: 0 auto;">
                        <img class="img-fluid" style="max-width: 350px; margin: 0 auto; background: #FFFFFF;" src="{{ route('qrcode-generate', 'coringa') }}?content={{ $data['url_inscricao'] }}" />
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <div class="container" id="enForm" style="display:none;">

            <div class="row">
                <div class="col-lg-12">
                    <form id="mailForm" method="POST" action="{{ route('get-guest-mail', ['listId' => $data['id'], 'guestList' => $eventId]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="inputEmail"><h4>E-mail</h4></label>
                            <input style="max-width: 1044px; border-radius: 0;" type="text" class="form-control form-control" name="emailAddress" id="inputEmail" aria-describedby="emailHelp">
                            <small id="emailHelp" class="form-text text-muted"></small>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div id="grupobtnEn" >
                        <button style="border-radius: 0; width: 100px;" class="btn btn-sm btn-warning" onclick="zerar('en')">Limpar</button>
                        <button style="border-radius: 0;" class="btn btn-sm btn-primary" onclick="document.getElementById('mailForm').submit();">Continue</button>
                    </div>
                </div>
            </div>

            <br>
            <br>
            @if ($data['teclado'] === 'yes')
            <div class="row">
                <div class="enPad">
                    <div class="row">
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="'" onclick="Digitar(this.value)" id="1"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="1" onclick="Digitar(this.value)" id="2"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="2" onclick="Digitar(this.value)" id="3"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="3" onclick="Digitar(this.value)" id="4"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="4" onclick="Digitar(this.value)" id="5"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="5" onclick="Digitar(this.value)" id="6"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="6" onclick="Digitar(this.value)" id="7"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="7" onclick="Digitar(this.value)" id="8"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="8" onclick="Digitar(this.value)" id="9"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="9" onclick="Digitar(this.value)" id="10" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="0" onclick="Digitar(this.value)" id="11" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="-" onclick="Digitar(this.value)" id="12" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="Del" onclick="apagar('en')" />
                    </div>
                    <div class="row">
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="@" onclick="Digitar(this.value)" id="100"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="q" onclick="Digitar(this.value)" id="14" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="w" onclick="Digitar(this.value)" id="15" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="e" onclick="Digitar(this.value)" id="16" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="r" onclick="Digitar(this.value)" id="17" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="t" onclick="Digitar(this.value)" id="18" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="y" onclick="Digitar(this.value)" id="19" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="u" onclick="Digitar(this.value)" id="20" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="i" onclick="Digitar(this.value)" id="21" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="o" onclick="Digitar(this.value)" id="22" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="p" onclick="Digitar(this.value)" id="23" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="´" onclick="Digitar(this.value)" id="24" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="=" onclick="Digitar(this.value)" id="13" />
                    </div>
                    <div class="row">
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="" onclick="Digitar(this.value)" id="38"  />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="a" onclick="Digitar(this.value)" id="26" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="s" onclick="Digitar(this.value)" id="27" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="d" onclick="Digitar(this.value)" id="28" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="f" onclick="Digitar(this.value)" id="29" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="g" onclick="Digitar(this.value)" id="30" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="h" onclick="Digitar(this.value)" id="31" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="j" onclick="Digitar(this.value)" id="32" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="k" onclick="Digitar(this.value)" id="33" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="l" onclick="Digitar(this.value)" id="34" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="ç" onclick="Digitar(this.value)" id="35" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="~" onclick="Digitar(this.value)" id="36" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="[" onclick="Digitar(this.value)" id="25" />
                    </div>
                    <div class="row">
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="AB@" onclick="Shift()"/>
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="z" onclick="Digitar(this.value)" id="39" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="x" onclick="Digitar(this.value)" id="40" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="c" onclick="Digitar(this.value)" id="41" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="v" onclick="Digitar(this.value)" id="42" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="b" onclick="Digitar(this.value)" id="43" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="n" onclick="Digitar(this.value)" id="44" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="m" onclick="Digitar(this.value)" id="45" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="," onclick="Digitar(this.value)" id="46" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="." onclick="Digitar(this.value)" id="47" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value=";" onclick="Digitar(this.value)" id="48" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="/" onclick="Digitar(this.value)" id="49" />
                        <input type="button" class="btn-personal btn btn-light btn-sm" value="]" onclick="Digitar(this.value)" id="37" />
                    </div>
                    <div class="row">
                        <!-- - - - - Início dos campos ocultos - - - - - -->
                        <input type="button" value='"' onclick="Digitar(this.value)" class="oculto" id="50" />
                        <input type="button" value="!" onclick="Digitar(this.value)" class="oculto" id="51" />
                        <input type="button" value="@" onclick="Digitar(this.value)" class="oculto" id="52" />
                        <input type="button" value="#" onclick="Digitar(this.value)" class="oculto" id="53" />
                        <input type="button" value="$" onclick="Digitar(this.value)" class="oculto" id="54" />
                        <input type="button" value="%" onclick="Digitar(this.value)" class="oculto" id="55" />
                        <input type="button" value="¨" onclick="Digitar(this.value)" class="oculto" id="56" />
                        <input type="button" value="&" onclick="Digitar(this.value)" class="oculto" id="57" />
                        <input type="button" value="*" onclick="Digitar(this.value)" class="oculto" id="58" />
                        <input type="button" value="(" onclick="Digitar(this.value)" class="oculto" id="59" />
                        <input type="button" value=")" onclick="Digitar(this.value)" class="oculto" id="60" />
                        <input type="button" value="_" onclick="Digitar(this.value)" class="oculto" id="61" />
                        <input type="button" value="+" onclick="Digitar(this.value)" class="oculto" id="62" />
                        <input type="button" value="Q" onclick="Digitar(this.value)" class="oculto" id="63" />
                        <input type="button" value="W" onclick="Digitar(this.value)" class="oculto" id="64" />
                        <input type="button" value="E" onclick="Digitar(this.value)" class="oculto" id="65" />
                        <input type="button" value="R" onclick="Digitar(this.value)" class="oculto" id="66" />
                        <input type="button" value="T" onclick="Digitar(this.value)" class="oculto" id="67" />
                        <input type="button" value="Y" onclick="Digitar(this.value)" class="oculto" id="68" />
                        <input type="button" value="U" onclick="Digitar(this.value)" class="oculto" id="69" />
                        <input type="button" value="I" onclick="Digitar(this.value)" class="oculto" id="70" />
                        <input type="button" value="O" onclick="Digitar(this.value)" class="oculto" id="71" />
                        <input type="button" value="P" onclick="Digitar(this.value)" class="oculto" id="72" />
                        <input type="button" value="`" onclick="Digitar(this.value)" class="oculto" id="73" />
                        <input type="button" value="{" onclick="Digitar(this.value)" class="oculto" id="74" />
                        <input type="button" value="A" onclick="Digitar(this.value)" class="oculto" id="75" />
                        <input type="button" value="S" onclick="Digitar(this.value)" class="oculto" id="76" />
                        <input type="button" value="D" onclick="Digitar(this.value)" class="oculto" id="77" />
                        <input type="button" value="F" onclick="Digitar(this.value)" class="oculto" id="78" />
                        <input type="button" value="G" onclick="Digitar(this.value)" class="oculto" id="79" />
                        <input type="button" value="H" onclick="Digitar(this.value)" class="oculto" id="80" />
                        <input type="button" value="J" onclick="Digitar(this.value)" class="oculto" id="81" />
                        <input type="button" value="K" onclick="Digitar(this.value)" class="oculto" id="82" />
                        <input type="button" value="L" onclick="Digitar(this.value)" class="oculto" id="83" />
                        <input type="button" value="Ç" onclick="Digitar(this.value)" class="oculto" id="84" />
                        <input type="button" value="^" onclick="Digitar(this.value)" class="oculto" id="85" />
                        <input type="button" value="}" onclick="Digitar(this.value)" class="oculto" id="86" />
                        <input type="button" value="|" onclick="Digitar(this.value)" class="oculto" id="87" />
                        <input type="button" value="Z" onclick="Digitar(this.value)" class="oculto" id="88" />
                        <input type="button" value="X" onclick="Digitar(this.value)" class="oculto" id="89" />
                        <input type="button" value="C" onclick="Digitar(this.value)" class="oculto" id="90" />
                        <input type="button" value="V" onclick="Digitar(this.value)" class="oculto" id="91" />
                        <input type="button" value="B" onclick="Digitar(this.value)" class="oculto" id="92" />
                        <input type="button" value="N" onclick="Digitar(this.value)" class="oculto" id="93" />
                        <input type="button" value="M" onclick="Digitar(this.value)" class="oculto" id="94" />
                        <input type="button" value="<" onclick="Digitar(this.value)" class="oculto" id="95" />
                        <input type="button" value=">" onclick="Digitar(this.value)" class="oculto" id="96" />
                        <input type="button" value=":" onclick="Digitar(this.value)" class="oculto" id="97" />
                        <input type="button" value="?" onclick="Digitar(this.value)" class="oculto" id="98" />
                    </div>
                </div>
            </div>
            @endif
        </div>
        <br>
    </div>

    <script>
        var alwaysFocusedInput = document.getElementById("visitorId");

        alwaysFocusedInput.addEventListener("blur", function () {
            setTimeout(() => {
                alwaysFocusedInput.focus();
            }, 0);
        });
    </script>

</body>
</html>
