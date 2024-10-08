<script setup lang="ts">
import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Filters, Network, Paginator, Transaction, User } from '@/types'
import { Head } from '@inertiajs/vue3'
import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  transactions: Paginator<Transaction>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
}>()
</script>

<template>
  <Head title="Transactions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        الحركات
        <span class="text-xs tracking-wide">
          ({{ transactions.meta.total }})
        </span>
      </h2>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="transactions.data"
        :columns="columns"
        :initialFilters="filters"
        :initialSorts="sorts"
      >
        <template
          v-if="$page.props.auth.user.role !== 'seller'"
          #toolBar="{ table }"
        >
          <Toolbar
            :table="table"
            :users="users"
            :managers="managers"
            :networks="networks"
          />
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
