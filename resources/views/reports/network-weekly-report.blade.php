<!doctype html>
<html lang="ar">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>
  </head>

  <body dir="rtl" class="antialiased">
    <div class="border bg-card text-card-foreground shadow">
      <div class="flex flex-col gap-y-1.5 p-6">
        <h3 class="font-semibold leading-none tracking-tight">{{ $report->network->name }} تقرير رقم {{ $report->id }}</h3>
      </div>

      <div class="p-6 pt-0">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="text-lg font-semibold">مجموع الدفعات</h3>
            <p>{{ $report->total_payments_amount }} شيكل</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">مجموع النفقات</h3>
            <p>{{ $report->total_expenses_amount }} شيكل</p>
          </div>
          <div>
            <h3 class="col-span-full text-lg font-semibold">صافي الربح</h3>
            <p>{{ $report->network_income }} شيكل</p>
          </div>
          <div class="md:col-span-full">
            <h3 class="mb-2 text-lg font-semibold">حصص الشركاء</h3>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr>
                    <th class="whitespace-nowrap border p-2 text-right">الشريك</th>
                    <th class="whitespace-nowrap border p-2 text-right">الحصة</th>
                    <th class="whitespace-nowrap border p-2 text-right">حصة الربح</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($report->partners_shares as $username => $value)
                    <tr>
                        <td class="whitespace-nowrap border p-2">
                            {{ $username }}
                        </td>
                        <td class="whitespace-nowrap border p-2">
                            %{{ $value['share'] * 100 }}
                        </td>
                        <td class="whitespace-nowrap border p-2">
                            {{ $value['benefit'] }} شيكل
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div>
            <h3 class="text-lg font-semibold">من تاريخ</h3>
            <p>{{ $report->start_from }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">حتى تاريخ</h3>
            <p>{{ $report->end_at }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">تاريخ إعداد التقرير</h3>
            <p>{{ $report->created_at }}</p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
