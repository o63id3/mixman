<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Region, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

interface DataTableToolbarProps {
  table: Table<User>
  regions: Array<Region>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <DataTableFacetedFilter
      v-if="table.getColumn('region')"
      :column="table.getColumn('region')"
      title="المنطقة"
      :options="
        regions.map((region) => ({
          label: region.name,
          value: String(region.id),
        }))
      "
    />
  </DataTableToolbar>
</template>
