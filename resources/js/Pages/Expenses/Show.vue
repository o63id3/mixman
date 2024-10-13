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

import { formatDate, formatMoney } from '@/lib/formatters'

import { Expense } from '@/types'

defineProps<{
  expense: Expense
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
            <BreadcrumbLink :href="route('expenses.index')">
              المصروفات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>{{ expense.id }}#</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
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
