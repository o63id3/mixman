<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { DataTable, DataTablePagination } from '@/Components/data-table'
import Toolbar from './Partials/Toolbar.vue'
import { columns } from './definitions'

import { Filters, Network, Paginator, Transaction, User } from '@/types'

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
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        الحركات
        <span class="text-xs font-normal tracking-wide">
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
