<script setup lang="ts">
import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Report } from '@/types'
import { Head } from '@inertiajs/vue3'
import { columns } from './columns'
import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

defineProps<{
  reports: Paginator<Report>
}>()
</script>

<template>
  <Head title="Reports" />

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
