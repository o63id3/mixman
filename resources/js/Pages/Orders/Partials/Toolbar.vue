<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Order, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import { orderStatues } from '@/types/enums'

interface DataTableToolbarProps {
  table: Table<Order>
  sellers: Array<User>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <div class="flex gap-2">
      <DataTableFacetedFilter
        v-if="table.getColumn('status')"
        :column="table.getColumn('status')"
        title="الحالة"
        :options="orderStatues"
      />
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
    </div>
  </DataTableToolbar>
</template>
