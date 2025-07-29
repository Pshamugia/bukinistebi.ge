@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')  

<div style="padding: 2px 22px 22px; border-radius:5px; border:1px solid #ccc; background-color: #ccc"> 
    <div class="row mt-4">
        <div style="position: relative; top:-10px">
            <h5> <i class="bi bi-calculator"></i>  ბუღალტერია</h5>
        </div>
        <!-- Total Value of Products -->
        <div class="col-md-3">
            <div class="card bg-light mb-3">
                <div class="card-header">ჯამური პროდუქციის სრული ფასი</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalValueOfProducts, 2) }} ლარი </h5>
                </div>
            </div>
        </div>

        <!-- Average Price of Products -->
        <div class="col-md-3">
            <div class="card bg-light mb-3">
                <div class="card-header">საშუალო ფასი თითო პროდუქტზე</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($averagePriceOfProducts, 2) }} ლარი </h5>
                </div>
            </div>
        </div>

        <!-- Total Quantity of Products -->
        <div class="col-md-3">
            <div class="card bg-light mb-3">
                <div class="card-header">პროდუქციის სრული რაოდენობა</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalQuantityOfProducts }}</h5>
                </div>
            </div>
        </div>

        <!-- Total Sales Profit -->
        <div class="col-md-3">
            <div class="card bg-light mb-3">
                <div class="card-header">პოტენციური მოგება ჯამურად </div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalValueOfProducts * 0.3, 2) }} ლარი </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Average Profit per Unit -->
        <div class="col-md-3">
            <div class="card bg-light mb-3">
                <div class="card-header">საშუალო მოგება თითო პროდუქტზე</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($averageProfitPerUnit, 2) }} ლარი</h5>
                </div>
            </div>
        </div>


  <!-- Purchased number -->
  <div class="col-md-3">
    <div class="card bg-light mb-3">
        <div class="card-header">გაყიდული პროდუქციის ჯამური ფასი</div>
        <div class="card-body">
            <h5 class="card-title">{{ number_format($totalPurchasedPrice, 2) }} ლარი</h5>
        </div>
    </div>
</div>

 <!-- Actual profit -->
 <div class="col-md-3">
    <div class="card bg-light mb-3">
        <div class="card-header">სუფთა მოგება უკვე გაყიდულიდან</div>
        <div class="card-body">
            <h5 class="card-title">{{ number_format($totalSalesProfit, 2) }} ლარი</h5>
        </div>
    </div>
</div>

    </div>


    

<!-- Date Filter -->
<form action="{{ route('admin') }}" method="GET" class="mt-4">
    <div class="row">
        <div class="col-md-4">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-4">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-4 mt-4">
            <button type="submit" class="btn btn-primary mt-2" style="position: relative; top:-8px">გაფილტრე</button>
        </div>
    </div>
 



</form>
</div>


    <!-- Purchased Products Section -->
    <div class="mt-5" style="border:1px solid #ccc; border-radius: 5px; padding:33px">
        <h5><i class="bi bi-bar-chart-fill"></i> გაყიდვების ჩარტი</h5>

      
          
            <canvas id="ordersChart" width="400" height="200"></canvas>
      
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('ordersChart').getContext('2d');
        
                const chartData = @json($ordersData); // Inject data from the controller
        
                new Chart(ctx, {
                    type: 'line', // Line chart type
                    data: {
                        labels: ['იან', 'თებ', 'მარ', 'აპრ', 'მაი', 'ივნ', 'ივლ', 'აგვ', 'სექ', 'ოქტ', 'ნოე', 'დეკ'],
                        datasets: [{
                            label: 'გაყიდვების რიცხვი',
                            data: chartData,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue fill
                            borderColor: 'rgba(54, 162, 235, 1)', // Dark blue line
                            borderWidth: 2,
                            tension: 0.4, // Curve line
                            fill: true // Fill under the line
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true // Start the y-axis at 0
                            }
                        }
                    }
                });
            });
        </script>


         
      </div>

    

    <br><br>


    <div >
        <div class="row">
            <!-- Top Customers -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5> <i class="bi bi-fire"></i> Top 10 მხარჯველი</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>სახელი</th>
                                    <th>შეკვეთის რიცხვი</th>
                                    <th>ჯამური დანახარჯი</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td>{{ optional(App\Models\User::find($customer->user_id))->name ?? 'Unknown' }}</td>
                                        <td>{{ $customer->total_orders }}</td>
                                        <td>{{ number_format($customer->total_spent, 2) }} ლარი</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    
            <!-- Top Products -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-trophy-fill"></i> Top 10 ბესტსელერი</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>სახელწოდება</th>
                                    <th>რაოდენობა</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topBooks as $book)
                                    <tr>
                                        <td>{{ $book->author->name }} - {{ $book?->title ?? 'No title available' }}</td>
                                        <td>{{ $book->total_sold }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
 






 
        <div class="card" style="margin-top:33px; margin-bottom:29px">
            <div class="card-header">
                <h5> <i class="bi bi-fire"></i> Top 10 რეიტინგული წიგნი</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>სათაური</th>
                            <th>ხმის მიმცემთა რიცხვი</th>
                            <th>საერთო</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topRatedArticles as $topRated)
                        <tr>
                            <td>{{ optional($topRated['book'])->title ?? 'No title' }}</td>
                            <td>{{ $topRated['rating_count'] }}</td>
                            <td>
                                {{ optional($topRated['book']?->ratings)->sum('rating') ?? 0 }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> 

    </div>
     

     
  
    <!-- Footer -->
   
@endsection
