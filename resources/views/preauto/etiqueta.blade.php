<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Auto-atendimento Impressão</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
      html {
        display: block;
        color: black;
        background: white;
      }
      body {
        width: 985px;
        height: 385px;
        padding: 15px;
        word-wrap: break-word;
      }
      p {
        line-height: 1.0;
        font-family: sans-serif;
      }
    </style>
</head>
<body onafterprint="window.close()">

<table style="width: 943px; height: 134.75px; margin: 0 auto;">
    <tr>
        <td style="width:80vh;">
            <p>
                <b><span style='font-size: {{ $tamanhonome }}'>{{ $nome }}</span></b><br>
                <span style='font-size: {{ $tamanhocargo }}'>{{ $empresa }}</span><br>
                <span style='font-size: {{ $tamanhoempresa }}'><b>{{ $cargocracha }}</b></span>
            </p>
            <br>
            <img width="750" height="80" src="data:image/png;base64,{{ base64_encode($qrcode->getBarcode('081231723897', $qrcode::TYPE_CODE_39)) }}">
            <span style="font-size: 24px;">{{ $barcode }}</span>
        </td>
        <td>
            <img style="max-width: 400px; position: relative; padding-right: 35px;" src='{{ url('/') . '/qrcode-generate/manual?content=' .  urlencode(route('v-card-route', ['listId' => $listId, 'hash' => $hash]))}}'>
        </td>
    </tr>
</table>

<script>
    window.print();
</script>
</body>
</html>
