<tr>
    <th scope="row">{{ $i }}</th>
    <td><a href="{{ route('recibos.editar', $recibo['id']) }}">{{ $recibo['user']['name'] }}</a></td>
    <td>{{ $recibo['servico'] }}</td>
    <td>{{ $recibo['valor'] }}</td>
    <td>{{ $recibo['data'] }}</td>
</tr>
