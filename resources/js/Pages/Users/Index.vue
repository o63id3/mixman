<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import { CreateLink } from '@/Components/links'
import { Badge } from '@/Components/ui/badge'
import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import Toolbar from './Partials/Toolbar.vue'

import { Filters, Network, Paginator, User } from '@/types'

defineProps<{
  users: Paginator<User>
  networks: Array<Network>
  filters: Filters
  sorts: string
  can: { create: boolean }
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'المستخدمين',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ users.meta.total }}</Badge>
        </div>
        <CreateLink v-if="can.create" :href="route('users.create')" />
      </div>
    </template>

    <DataTable
      :columns="columns"
      :data="users"
      table-id="users"
      :initial-filters="filters"
      :initial-sorts="sorts"
    >
      <DataTableToolbar>
        <Toolbar :networks="networks" />
      </DataTableToolbar>
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
