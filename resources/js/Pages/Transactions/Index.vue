<script setup lang="ts">
import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Transaction } from '@/types'
import { Head } from '@inertiajs/vue3'
import { columns } from './columns'

defineProps<{
  transactions: Paginator<Transaction>
}>()
</script>

<template>
  <Head title="Transactions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">الحركات</h2>
    </template>

    <div class="space-y-4">
      <DataTable :data="transactions.data" :columns="columns" />
      <DataTablePagination
        :links="transactions.links"
        :meta="transactions.meta"
      />
    </div>
  </AuthenticatedLayout>
</template>
