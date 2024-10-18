<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import { DataTable, DataTableTable } from '@/Components/data-table'

import { columns } from './Partials/filesColumns'
import { orderStatues } from '@/types/enums'
import { formatDate, formatMoney } from '@/lib/formatters'

import { Order } from '@/types'

const props = defineProps<{
  order: Order
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الطلبات',
    route: route('orders.index'),
  },
  {
    label: `${props.order.id}#`,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <Card class="rounded-none sm:rounded-xl">
      <CardHeader>
        <CardTitle>معلومات الطلب</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="text-lg font-semibold">المستفيد</h3>
            <p>{{ order.user.name }}</p>
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
                      <span
                        class="text-xs font-normal leading-none tracking-wide"
                      >
                        (الكروت)
                      </span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="card in order.cards" :key="card.id">
                    <td class="whitespace-nowrap border p-2">
                      {{ card.name }}
                    </td>
                    <td class="whitespace-nowrap border p-2">
                      {{ formatMoney(card.pivot.total_price_for_seller) }}
                    </td>
                    <td class="whitespace-nowrap border p-2">
                      {{ card.pivot.number_of_packages }}
                      <span class="text-xs leading-none tracking-wide">
                        ({{ card.pivot.number_of_cards_per_package }})
                      </span>
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

    <p
      v-if="order.files.length"
      class="mt-4 px-4 text-sm font-bold tracking-wide"
    >
      # المرفقات
    </p>

    <DataTable
      v-if="order.files.length"
      :columns="columns"
      :data="order.files"
      table-id="order.files"
    >
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
