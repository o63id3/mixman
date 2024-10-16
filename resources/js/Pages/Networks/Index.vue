<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import { Network, Paginator } from '@/types'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import { Badge } from '@/Components/ui/badge'
import { CreateLink } from '@/Components/links'

defineProps<{
  networks: Paginator<Network>
  can: {
    create: boolean
    update: boolean
  }
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الشبكات',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ networks.meta.total }}</Badge>
        </div>
        <CreateLink v-if="can.create" :href="route('networks.create')" />
      </div>
    </template>

    <DataTable
      :columns="columns(can.update)"
      :data="networks"
      table-id="networks"
    >
      <DataTableToolbar />
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
