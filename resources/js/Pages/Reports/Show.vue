<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'

import { formatDate, formatMoney } from '@/lib/formatters'
import { Report } from '@/types'

const props = defineProps<{
  report: Report
}>()

let totalBenefit: number = 0
Object.keys(props.report.partners_shares).forEach(
  (key) => (totalBenefit += props.report.partners_shares[key].benefit),
)

let totalShares: number = 0
Object.keys(props.report.partners_shares).forEach(
  (key) => (totalShares += props.report.partners_shares[key].share),
)
</script>

<template>
  <Head :title="`${report.network.name} تقرير رقم ${report.id}`" />

  <Card class="rounded-none shadow-none">
    <CardHeader>
      <CardTitle>{{ report.network.name }} تقرير رقم {{ report.id }}</CardTitle>
    </CardHeader>
    <CardContent>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
          <h3 class="text-lg font-semibold">مجموع الدفعات</h3>
          <p>{{ formatMoney(report.total_payments_amount) }} شيكل</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold">مجموع النفقات</h3>
          <p>{{ formatMoney(report.total_expenses_amount) }} شيكل</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold">صافي الربح</h3>
          <p>{{ formatMoney(report.network_income) }} شيكل</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold">ربح فائض</h3>
          <p>
            {{
              report.income_overflow
                ? `${formatMoney(report.income_overflow)} شيكل`
                : 'لا يوجد'
            }}
          </p>
        </div>
        <div class="md:col-span-full">
          <h3 class="mb-2 text-lg font-semibold">حصص الشركاء</h3>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr>
                  <th class="whitespace-nowrap border p-2 text-right">
                    الشريك
                  </th>
                  <th class="whitespace-nowrap border p-2 text-right">الحصة</th>
                  <th class="whitespace-nowrap border p-2 text-right">
                    حصة الربح
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="username in Object.keys(report.partners_shares)"
                  :key="username"
                >
                  <td class="whitespace-nowrap border p-2">
                    {{ username }}
                  </td>
                  <td class="whitespace-nowrap border p-2">
                    %{{
                      Math.round(report.partners_shares[username].share * 100)
                    }}
                  </td>
                  <td class="whitespace-nowrap border p-2">
                    {{ formatMoney(report.partners_shares[username].benefit) }}
                    شيكل
                  </td>
                </tr>
                <tr>
                  <td class="whitespace-nowrap border p-2"></td>
                  <td class="whitespace-nowrap border p-2">
                    %{{ Math.round(totalShares * 100) }}
                  </td>
                  <td class="whitespace-nowrap border p-2">
                    {{ totalBenefit }} شيكل
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-semibold">من تاريخ</h3>
          <p>{{ formatDate(report.start_from_date) }}</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold">حتى تاريخ</h3>
          <p>{{ formatDate(report.end_at_date) }}</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold">تاريخ إعداد التقرير</h3>
          <p>{{ formatDate(report.created_at_date) }}</p>
        </div>
      </div>
    </CardContent>
  </Card>
</template>
