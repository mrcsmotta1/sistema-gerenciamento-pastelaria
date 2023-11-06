@component('mail::message')
# Detalhes do Pedido

**Número do Pedido:** {{ $order->id }}
**Data do Pedido:** {{ $order->created_at->format('d/m/Y H:i:s') }}

**Cliente:**
- **Nome:** {{ $order->customer->name }}
- **E-mail:** {{ $order->customer->email }}
- **Telefone:** {{ $order->customer->phone }}
- **Endereço:** {{ $order->customer->address }}
- **Complemento:** {{ $order->customer->complement }}
- **Bairro:** {{ $order->customer->neighborhood }}
- **CEP:** {{ $order->customer->zipcode }}
- **Data de Nascimento:** {{ $dateOfBirth }}

**Produtos no Pedido:**
@foreach ($order->items as $item)
- **Produto:** {{$item->product->name }}
- **Quantidade:** {{ $item->quantity }}
- **Preço Unitário:** R${{ number_format($item->product->price, 2, ',', '.') }}
- **Subtotal:** R${{ number_format($item->quantity * $item->product->price, 2, ',', '.') }}
<br>
<br>
@endforeach

**Total do Pedido:** R${{ number_format($order->items->sum(function ($item) {
return $item->quantity * $item->product->price;
}), 2, ',', '.') }}


Agradecemos por escolher a nossa pastelaria. O seu pedido está em processamento e entraremos em contato em breve para
confirmar a entrega.

Tenha um ótimo dia!

Thanks,
{{ config('app.name') }}
@endcomponent