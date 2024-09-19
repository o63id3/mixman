<script setup lang="ts">
import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Transaction, User } from '@/types'
import { Head } from '@inertiajs/vue3'
import { columns } from './columns'

defineProps<{
  transactions: Paginator<Transaction>
  sellers: User[]
  filters: {
    seller: Pick<User, 'id'>
  }
}>()
</script>

<template>
  <Head title="Transactions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">الحركات</h2>
    </template>

    <div class="py-8">
      <div class="mx-auto max-w-7xl lg:px-2">
        <div class="space-y-4">
          <div class="overflow-hidden bg-white shadow-sm lg:rounded-md">
            <DataTable :data="transactions.data" :columns="columns" />
          </div>
          <DataTablePagination
            :links="transactions.links"
            :meta="transactions.meta"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
