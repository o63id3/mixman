<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Paginator, Region } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'

defineProps<{
  regions: Paginator<Region>
  can: {
    create: boolean
  }
}>()
</script>

<template>
  <Head title="Regions" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المناطق
          <span class="text-xs tracking-wide">({{ regions.meta.total }})</span>
        </h2>
        <div v-if="can.create">
          <Link :href="route('regions.create')">
            <Button> إنشاء منطقة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable :data="regions.data" :columns="columns" />
      <DataTablePagination :links="regions.links" :meta="regions.meta" />
    </div>
  </AuthenticatedLayout>
</template>
