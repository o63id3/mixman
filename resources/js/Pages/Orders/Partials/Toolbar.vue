<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Order, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import { orderStatues } from '@/types/enums'
import DataTableDateRangeFilter from '@/Components/data-table/DataTableDateRangeFilter.vue'

interface DataTableToolbarProps {
  table: Table<Order>
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
      <DataTableFacetedFilter
        v-if="table.getColumn('status')"
        :column="table.getColumn('status')"
        title="الحالة"
        :options="orderStatues"
      />
      <DataTableDateRangeFilter
        v-if="table.getColumn('updated_at')"
        :column="table.getColumn('updated_at')"
      />
    </div>
  </DataTableToolbar>
</template>
