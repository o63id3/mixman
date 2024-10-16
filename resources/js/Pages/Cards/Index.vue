<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  DataTable,
  DataTableTable,
  DataTableToolbar,
} from '@/Components/data-table'
import { columns } from './definitions'
import { Badge } from '@/Components/ui/badge'
import { CreateLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Card, Paginator } from '@/types'

defineProps<{
  cards: Paginator<Card>
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
    label: 'الكروت',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex flex-1 items-center justify-between">
        <div class="flex items-center gap-2">
          <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
          <Badge>{{ cards.meta.total }}</Badge>
        </div>
        <CreateLink v-if="can.create" :href="route('cards.create')" />
      </div>
    </template>

    <DataTable :columns="columns" :data="cards" table-id="cards">
      <DataTableToolbar />
      <DataTableTable />
    </DataTable>
  </AuthenticatedLayout>
</template>
