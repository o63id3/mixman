<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Payment, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

interface DataTableToolbarProps {
  table: Table<Payment>
  sellers: Array<User>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <DataTableFacetedFilter
      v-if="table.getColumn('seller')"
      :column="table.getColumn('seller')"
      title="البائع"
      :options="
        sellers.map((seller) => ({
          label: seller.name,
          value: String(seller.id),
        }))
      "
    />
  </DataTableToolbar>
</template>
