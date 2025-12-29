<x-mail::message>
    # Daily Sales Report

    **Date:** {{ $date }}

    ## Summary
    - **Total Orders:** {{ $totalOrders }}
    - **Total Sales:** ${{ number_format($totalSales, 2) }}

    @if($orders->isNotEmpty())
        ## Orders

        <x-mail::table>
            | Order # | Customer | Items | Total |
            |:--------|:---------|:------|------:|
            @foreach($orders as $order)
                | #{{ $order->id }} | {{ $order->user->name }} | {{ $order->items->count() }} | ${{ number_format($order->total, 2) }} |
            @endforeach
        </x-mail::table>
    @else
        No sales recorded today.
    @endif

    <x-mail::button :url="config('app.url')">
        View Dashboard
    </x-mail::button>

    Regards,<br>
    {{ config('app.name') }}
</x-mail::message>
