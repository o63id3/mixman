<script setup lang="ts">
import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Filters, Paginator, Transaction, User } from '@/types'
import { Head } from '@inertiajs/vue3'
import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  transactions: Paginator<Transaction>
  sellers: Array<User>
  filters: Filters
  sorts: string
}>()
</script>

<template>
  <Head title="Transactions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">الحركات</h2>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="transactions.data"
        :columns="columns"
        :filters="filters"
        :sorts="sorts"
        href="transactions.index"
      >
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <Toolbar :table="table" :sellers="sellers" />
        </template>
      </DataTable>
      <DataTablePagination
        :links="transactions.links"
        :meta="transactions.meta"
        :filters="filters"
        :sorts="sorts"
      />
    </div>
  </AuthenticatedLayout>
</template>
