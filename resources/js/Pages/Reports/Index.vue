<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTablePagination,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'

import { Paginator, Report } from '@/types'

defineProps<{
  reports: Paginator<Report>
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        التقارير
        <span class="text-xs font-normal tracking-wide">
          ({{ reports.meta.total }})
        </span>
      </h2>
    </template>

    <div class="space-y-4">
      <DataTable :data="reports.data" :columns="columns">
        <template #toolBar="{ table }">
          <DataTableToolbar :table="table" table-id="reports" />
        </template>
      </DataTable>
      <DataTablePagination :links="reports.links" :meta="reports.meta" />
    </div>
  </AuthenticatedLayout>
</template>
