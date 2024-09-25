<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Filters, Paginator, Region, User } from '@/types'

import { Button } from '@/Components/ui/button'

import DataTable from '@/Components/data-table/DataTable.vue'
import DataTablePagination from '@/Components/data-table/DataTablePagination.vue'
import { columns } from './columns'
import Toolbar from './Partials/Toolbar.vue'

defineProps<{
  sellers: Paginator<User>
  regions: Array<Region>
  filters: Filters
  sorts: string
}>()
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الباعة
          <span class="text-xs tracking-wide">({{ sellers.meta.total }})</span>
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('sellers.create')">
            <Button> إنشاء بائع </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="space-y-4">
      <DataTable
        :data="sellers.data"
        :columns="columns"
        :initial-filters="filters"
        :initial-sorts="sorts"
      >
        <template v-if="$page.props.auth.user.admin" #toolBar="{ table }">
          <Toolbar :table="table" :regions="regions" />
        </template>
      </DataTable>
      <DataTablePagination
        :links="sellers.links"
        :meta="sellers.meta"
        :filters="filters"
        :sorts="sorts"
      />
    </div>
  </AuthenticatedLayout>
</template>
