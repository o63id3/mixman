<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Expense, Filters, Paginator } from '@/types'
import { Head, Link } from '@inertiajs/vue3'
import Button from '@/Components/ui/button/Button.vue'
import { DataTable, DataTablePagination } from '@/Components/data-table/index'

import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  expenses: Paginator<Expense>
  can: {
    create: boolean
  }
}>()
</script>

<template>
  <Head title="Expenses" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المصروفات
          <span class="text-xs tracking-wide">({{ expenses.meta.total }})</span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('expenses.create')">
            <Button> إضافة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="expenses.data" :columns="columns" />
      <DataTablePagination :links="expenses.links" :meta="expenses.meta" />
    </div>
  </AuthenticatedLayout>
</template>
