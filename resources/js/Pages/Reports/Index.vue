<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
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
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>
              التقارير
              <span class="text-xs font-normal tracking-wide">
                ({{ reports.meta.total }})
              </span>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
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
