<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
  <head content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('icon.png') }}">
    <link rel="stylesheet" href="{{ asset('fonts/cairo.css') }}" />
  </head>
  <body class="antialiased rtl:text-right">
    <div>
      من تاريخ {{ $startOfLastWeek }} حتى تاريخ {{ $endOfLastWeek }}
    </div>


    <div>
        <div>{{ $network->name }}</div>

        <div>
            الدفعات المالية الاسبوع الماضي
            {{ $network->total_payments_amount }}
        </div>

        <div>
            النفقات الاسبوع الماضي
            {{ $network->total_expenses_amount }}
        </div>

        <div>
            الصافي
            {{ $totalIncome }}
        </div>
    </div>

    <div class="mt-5">
        <div>الشركاء</div>

        @foreach ($network->partners as $partner)
        <div>
          {{ $partner->name }} {{ $partner->pivot->share * 100 }}%
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        <div>نصيب كل شريك</div>

        @foreach ($network->partners as $partner)
        <div>
          {{ $partner->name }}: {{ $totalIncome }} × {{ $partner->pivot->share }} = {{ $totalIncome * $partner->pivot->share }}
        </div>
        @endforeach
    </div>
  </body>
</html>

