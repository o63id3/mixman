<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Badge } from '@/Components/ui/badge'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import {
  DataTable,
  DataTableSummary,
  DataTableTable,
} from '@/Components/data-table'

import { columns, summaryFields } from './partners'

import { Network } from '@/types'

const props = defineProps<{
  network: Network
}>()

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الشبكات',
    route: route('networks.index'),
  },
  {
    label: props.network.name,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex items-center gap-2">
        <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
        <Badge
          variant="outline"
          class="mr-auto sm:ml-0"
          :class="[network.active ? 'text-green-500' : 'text-red-500']"
        >
          {{ network.active ? 'فعالة' : 'معطلة' }}
        </Badge>
      </div>
    </template>

    <p class="px-4 text-sm font-medium tracking-wide"># الشركاء</p>
    <DataTable
      v-if="network.partners"
      class="mt-4"
      :columns="columns(network)"
      :data="network.partners"
      table-id="network.partners"
    >
      <DataTableTable>
        <DataTableSummary
          :summaryFields="network.partners.length ? summaryFields : undefined"
        />
      </DataTableTable>
    </DataTable>
  </AuthenticatedLayout>
</template>
