<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { Order } from '@/types'
import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'

import DataTable from '@/Components/data-table/DataTable.vue'
import { columns } from './Partials/filesColumns'
import { orderStatues } from '@/types/enums'
import { formatDate, formatMoney } from '@/lib/formatters'

defineProps<{
  order: Order
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <Card class="rounded-none sm:rounded-xl">
      <CardHeader>
        <CardTitle>معلومات الطلب</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="text-lg font-semibold">المستفيد</h3>
            <p>{{ order.orderer.name }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">مدير الطلب</h3>
            <p>{{ order.manager.name }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">الحالة</h3>
            <div class="flex items-center gap-2">
              <component
                :is="
                  orderStatues.find(
                    (status: any) => status.value === order.status,
                  )?.icon
                "
                class="h-5 w-5"
              />
              <span>{{ order.status }}</span>
            </div>
          </div>
          <div class="md:col-span-2">
            <h3 class="mb-2 text-lg font-semibold">الكروت</h3>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr>
                    <th class="whitespace-nowrap border p-2 text-right">
                      الفئة
                    </th>
                    <th class="whitespace-nowrap border p-2 text-right">
                      السعر
                    </th>
                    <th class="whitespace-nowrap border p-2 text-right">
                      الحزم
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in order.items" :key="item.id">
                    <td class="whitespace-nowrap border p-2">
                      {{ item.card.name }}
                    </td>
                    <td class="whitespace-nowrap border p-2">
                      {{ formatMoney(item.total_price_for_seller) }}
                      <span class="text-xs leading-none tracking-wide">
                        شيكل
                      </span>
                    </td>
                    <td class="whitespace-nowrap border p-2">
                      {{ item.number_of_packages }}
                      <span class="text-xs leading-none tracking-wide"
                        >({{ item.number_of_cards_per_package }})</span
                      >
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="md:col-span-2">
            <h3 class="text-lg font-semibold">ملاحظات</h3>
            <p>{{ order.notes || 'لا توجد ملاحظات' }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">تاريخ الإضافة</h3>
            <p>{{ formatDate(order.created_at_date) }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">تاريخ آخر تحديث</h3>
            <p>{{ formatDate(order.updated_at_date) }}</p>
          </div>
        </div>
      </CardContent>
    </Card>

    <p class="mt-4 px-4 text-sm font-bold tracking-wide"># المرفقات</p>
    <DataTable :data="order.files" :columns="columns" />
  </AuthenticatedLayout>
</template>
