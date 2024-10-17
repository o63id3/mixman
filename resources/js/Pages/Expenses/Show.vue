<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Card, CardHeader, CardTitle, CardContent } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { formatDate, formatMoney } from '@/lib/formatters'

import { Expense } from '@/types'

const props = defineProps<{
  expense: Expense
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'المصروفات',
    route: route('expenses.index'),
  },
  {
    label: `${props.expense.id}#`,
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
        <CardTitle>معلومات المصروف</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="text-lg font-semibold">تم الادخال بواسطة</h3>
            <p>{{ expense.user.name }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">الشبكة</h3>
            <p>{{ expense.network.name }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">الوصف</h3>
            <p>{{ expense.description }}</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">المبلغ</h3>
            <p>{{ formatMoney(expense.amount) }} شيكل</p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">تاريخ الإضافة</h3>
            <p>
              {{ formatDate(expense.created_at_date) }}
            </p>
          </div>
        </div>
      </CardContent>
    </Card>
  </AuthenticatedLayout>
</template>
