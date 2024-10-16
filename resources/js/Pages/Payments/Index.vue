<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import { Badge } from '@/Components/ui/badge'
import { CreateLink } from '@/Components/links'

import { Filters, Network, Paginator, Payment, User } from '@/types'

defineProps<{
  payments: Paginator<Payment>
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
    label: 'المدفوعات',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ payments.meta.total }}</Badge>
        </div>
        <CreateLink
          v-if="can.create"
          :href="route('payments.create')"
          btn-title="إضافة"
        />
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="payments"
      table-id="payments"
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
