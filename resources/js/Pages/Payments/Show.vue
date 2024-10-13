<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'

import { Payment } from '@/types'
import { formatDate, formatMoney } from '@/lib/formatters'

defineProps<{
  payment: Payment
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
            <BreadcrumbLink :href="route('payments.index')">
              الدفعات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>{{ payment.id }}#</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <Card class="rounded-none sm:rounded-xl">
      <CardHeader>
        <CardTitle>معلومات الدفعة</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="text-lg font-semibold">المستلم</h3>
            <p>{{ payment.recipient.name }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">المبلغ</h3>
            <p>{{ formatMoney(payment.amount) }} شيكل</p>
          </div>
          <div class="md:col-span-2">
            <h3 class="text-lg font-semibold">ملاحظات</h3>
            <p>{{ payment.notes || 'لا توجد ملاحظات' }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">تاريخ الإضافة</h3>
            <p>
              {{ formatDate(payment.created_at_date) }}
            </p>
          </div>
        </div>
      </CardContent>
    </Card>
  </AuthenticatedLayout>
</template>
