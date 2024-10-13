<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Order } from '@/types'
import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'

import { DataTable } from '@/Components/data-table'
import { columns } from './Partials/filesColumns'
import { orderStatues } from '@/types/enums'
import { formatDate, formatMoney } from '@/lib/formatters'

defineProps<{
  order: Order
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('orders.index')">
              الطلبات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>
              {{ order.id }}#
              <span class="text-xs font-normal tracking-wide">
                ({{ order.manager.name }})
              </span>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
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
                      <span class="text-xs leading-none tracking-wide">
                        شيكل
                      </span>
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
      :data="order.files"
      :columns="columns"
    />
  </AuthenticatedLayout>
</template>
