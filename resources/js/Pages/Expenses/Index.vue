<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import { CreateLink } from '@/Components/links'
import { Badge } from '@/Components/ui/badge'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import Toolbar from './Partials/Toolbar.vue'

import { Expense, Filter, Network, Paginator, User } from '@/types'

defineProps<{
  expenses: Paginator<Expense>
  managers: Array<User>
  networks: Array<Network>
  filters: Filter
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
    label: 'المصروفات',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ expenses.meta.total }}</Badge>
        </div>
        <CreateLink v-if="can.create" :href="route('expenses.create')">
          إضافة
        </CreateLink>
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="expenses"
      table-id="expenses"
      :initial-filters="filters"
      :initial-sorts="sorts"
    >
      <DataTableToolbar>
        <Toolbar
          v-if="$page.props.auth.user.role !== 'seller'"
          :managers="managers"
          :networks="networks"
        />
      </DataTableToolbar>
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
