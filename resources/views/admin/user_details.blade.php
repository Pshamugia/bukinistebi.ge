@extends('admin.layouts.app')
 

@section('title', 'User Details')

@section('content')
@php
use Illuminate\Support\Str;
$hasOrders = $user->orders && $user->orders->isNotEmpty();
$latestOrder = $hasOrders ? $user->orders->first() : null; // newest first if controller orders desc
@endphp

<div class="container">






    <h2>{{ __('მომხმარებლის დეტალები') }}</h2>

    <h4>{{ __('სახელი:') }} {{ $user->name }}</h4>
    <p>{{ __('Email:') }} {{ $user->email }}</p>

    @if($hasOrders)
    <p>
        {{ __('ტელეფონი:') }}
        @php
        // pick the source
        $raw = preg_replace('/\D+/', '', $latestOrder->phone ?? ''); // or $order->phone

        // strip country code if present
        if (strpos($raw, '995') === 0) {
        $raw = substr($raw, 3);
        }

        // Format to 599-99-99-99 if it’s 9 digits, else fallback to original
        $displayPhone = preg_match('/^\d{9}$/', $raw)
        ? preg_replace('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', '$1-$2-$3-$4', $raw)
        : ($latestOrder->phone ?? ''); // or $order->phone

        // For the clickable link, E.164 is safest (keeps +995 inside tel:),
        // but if you truly want the link without +995, set $telPhone = $raw instead.
        $telPhone = '+995' . $raw;
        @endphp

        <a href="tel:{{ $telPhone }}" style="text-decoration: none; color: inherit;">
            {{ $displayPhone }}
        </a>

    </p>
    <p>{{ __('მისამართი:') }} {{ $latestOrder->city }}, {{ $latestOrder->address }}</p>
    @else
    <p>{{ __('ტელეფონი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
    <p>{{ __('მისამართი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
    @endif

    <button type="button" class="btn btn-warning">
        <h4>{{ __('ახალი შეკვეთა:') }} <span class="text-danger">{{ $newPurchaseTotal }} {{ __('ლარი') }}</span></h4>
        <h4>{{ __('ძველი შეკვეთების ჯამი:') }} {{ $oldTotal }} {{ __('ლარი') }}</h4>
    </button>

    <br>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('შეკვეთების ID') }}</th>
                <th>{{ __('ჯამი') }}</th>
                <th>{{ __('თარიღი') }}</th>
                <th>{{ __('სტატუსი') }}</th>
                <th>{{ __('პროდუქტი') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($hasOrders)
            @foreach($user->orders as $order)
            <tr>
                <td>{{ $order->id }}



                    <a href="{{ route('admin.order.label', $order->id) }}"
                        class="btn btn-dark btn-sm mt-1" target="_blank">
                        🖨️ ბეჭდვა
                    </a>

                </td>


                <td class="{{ $order->created_at?->gte(now()->subMinutes(5)) ? 'text-danger' : '' }}">
                    {{ $order->total }} {{ __('ლარი') }}
                </td>
                <td>{{ $order->created_at?->format('M d, Y | H:i:s') }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    @if($order->orderItems->isEmpty())
                    <em>—</em>
                    @else
                    <ul>
                        @foreach($order->orderItems as $item)
                        @if($item->book)
                        {{-- Single book line --}}
                        @php
                        $bookTitle = $item->book->title ?? '—';
                        $bookId = $item->book->id ?? null;
                        $slug = Str::slug($bookTitle);
                        @endphp
                        <li>
                            @if($bookId)
                            <a href="{{ route('full', ['title' => $slug, 'id' => $bookId]) }}" target="_blank">
                                {{ $bookTitle }}
                            </a>
                            @else
                            {{ $bookTitle }}
                            @endif
                            (× {{ $item->quantity }})
                            @if($item->book->publisher ?? null)
                            <span style="color:red; font-weight:bold">
                                — <small>ბუკინისტი: {{ $item->book->publisher->name ?? $item->book->publisher->title ?? '—' }}</small>
                            </span>
                            @else
                            — <small>ჩემი წიგნებიდან</small>
                            @endif
                            @if($item->book->full ?? null)
<span style="font-size: 14px;">
    {!! \Illuminate\Support\Str::limit($item->book->full, 25) !!}
</span>
                             @endif
                        </li>

                        @elseif($item->bundle)
                        {{-- Bundle line --}}
                        <li>
                            <strong>ბანდლი:</strong> {{ $item->bundle->title ?? '—' }} (× {{ $item->quantity }})
                            @if($item->bundle->books?->isNotEmpty())
                            <ul style="margin:4px 0 0 16px;">
                                @foreach($item->bundle->books as $b)
                                <li>{{ $b->title ?? '—' }} — × {{ $b->pivot->qty ?? 1 }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </li>

                        @else
                        {{-- Missing/removed relation --}}
                        <li><em>ჩანაწერი ვერ იძებნება</em> (× {{ $item->quantity }})</li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5">— {{ __('შეკვეთები არ მოიძებნა') }} —</td>
            </tr>
            @endif
        </tbody>
    </table>

    <a href="{{ route('admin.users_transactions') }}" class="btn btn-primary">{{ __('დაბრუნდით უკან') }}</a>
</div>
@endsection