<x-mail::message>
# Low Stock Alert

The product **{{ $product->name }}** is running low on stock.

**Current quantity:** {{ $product->stock_quantity }} units

<x-mail::button :url="config('app.url') . '/products/' . $product->id">
View Product
</x-mail::button>

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
