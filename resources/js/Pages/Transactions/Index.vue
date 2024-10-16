<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import { Badge } from '@/Components/ui/badge'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import Toolbar from './Partials/Toolbar.vue'

import { Filters, Network, Paginator, Transaction, User } from '@/types'

defineProps<{
  transactions: Paginator<Transaction>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الحركات',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ transactions.meta.total }}</Badge>
        </div>
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="transactions"
      table-id="transactions"
      :initial-filters="filters"
      :initial-sorts="sorts"
    >
      <DataTableToolbar>
        <Toolbar
          v-if="$page.props.auth.user.role !== 'seller'"
          :users="users"
          :managers="managers"
          :networks="networks"
        />
      </DataTableToolbar>
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
