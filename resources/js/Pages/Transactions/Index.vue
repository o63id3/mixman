<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Transaction, User } from '@/types'
import DataTable from './Table/DataTable.vue'
import { columns } from './Table/columns'
import { Head } from '@inertiajs/vue3'

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

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <DataTable
              :columns="columns"
              :data="transactions.data"
              :meta="transactions.meta"
              :sellers="sellers"
              :filters="filters"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
