<!-- resources/views/admin/user_details.blade.php -->

@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')

<div class="container">
    @php
    $latestOrder = $user->orders->first(); // 👈 This is where latestOrder is defined
@endphp
    <h2>{{ __('მომხმარებლის დეტალები') }}</h2>
    
    <h4>{{ __('სახელი:') }} {{ $user->name }}</h4>
    <p>{{ __('Email:') }} {{ $user->email }}</p>
    @if($user->orders->isNotEmpty())
    @php
        $latestOrder = $user->orders->first(); // Get the latest order
    @endphp
<p>
    {{ __('ტელეფონი:') }}
    <a href="tel:{{ $latestOrder->phone }}" style="text-decoration: none; color: inherit;">
        {{ $latestOrder->phone }}
    </a>
</p>
    <p>{{ __('მისამართი:') }} {{ $latestOrder->city }},  {{ $latestOrder->address }}</p>
@else
    <p>{{ __('ტელეფონი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
    <p>{{ __('მისამართი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
@endif

    <button type="button" class="btn btn-warning"> 
        <h4>{{ __('ახალი შეკვეთა:') }} <span class="text-danger">{{ $newPurchaseTotal }} {{ __('ლარი') }}</span></h4>
        <h4>{{ __('ძველი შეკვეთების ჯამი:') }} {{ $oldTotal }} {{ __('ლარი') }}</h4>
    </button>

   

    <br>

     <table class="table">
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
            @if($user->orders->isNotEmpty())
                @foreach($user->orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td class="{{ $order->created_at >= now()->subMinutes(5) ? 'text-danger' : '' }}">
                            {{ $order->total }} {{ __('ლარი') }}
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <ul>
                                @foreach($order->orderItems as $item)
                                    <li>
                                        <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" target="_blank">
                                            {{ $item->book->title }}
                                        </a> ({{ $item->quantity }})
                                        @if($item->book->publisher)
                                           <Span style="color:red; font-weight: bold"> — <small>ბუკინისტი: {{ $item->book->publisher->name }}</small> </Span>
                                        @else
                                            — <small>ჩემი წიგნებიდან</small>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                             
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    
    <a href="{{ route('admin.users_transactions') }}" class="btn btn-primary">{{ __('დაბრუნდით უკან') }}</a>
</div>

@endsection
