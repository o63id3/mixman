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

import { Paginator, Report } from '@/types'

defineProps<{
  reports: Paginator<Report>
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'التقارير',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ reports.meta.total }}</Badge>
        </div>
      </div>
    </template>

    <DataTable :columns="columns" :data="reports" table-id="reports">
      <DataTableToolbar />
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
