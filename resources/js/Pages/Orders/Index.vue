<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'
import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import { Badge } from '@/Components/ui/badge'
import { CreateLink } from '@/Components/links'

import { Filters, Network, Order, Paginator, User } from '@/types'

defineProps<{
  orders: Paginator<Order>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
  can: {
    create: boolean
  }
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الطلبات',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ orders.meta.total }}</Badge>
        </div>
        <CreateLink v-if="can.create" :href="route('orders.create')" />
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="orders"
      table-id="orders"
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
