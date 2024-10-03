<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Transaction, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import DataTableDateRangeFilter from '@/Components/data-table/DataTableDateRangeFilter.vue'

interface DataTableToolbarProps {
  table: Table<Transaction>
  users: Array<User>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <div class="flex gap-2">
      <DataTableFacetedFilter
        v-if="table.getColumn('seller')"
        :column="table.getColumn('seller')"
        title="البائع"
        :options="
          users.map((seller) => ({
            label: seller.name,
            value: String(seller.id),
          }))
        "
      />
      <DataTableDateRangeFilter
        v-if="table.getColumn('created_at')"
        :column="table.getColumn('created_at')"
      />
    </div>
  </DataTableToolbar>
</template>
