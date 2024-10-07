<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Network, Order, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import { orderStatues } from '@/types/enums'
import DataTableDateRangeFilter from '@/Components/data-table/DataTableDateRangeFilter.vue'

interface DataTableToolbarProps {
  table: Table<Order>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <div class="flex gap-2">
      <DataTableFacetedFilter
        v-if="table.getColumn('orderer')"
        :column="table.getColumn('orderer')"
        title="المستفيد"
        :options="
          users.map((user) => ({
            label: user.name,
            value: String(user.id),
          }))
        "
      />
      <DataTableFacetedFilter
        v-if="table.getColumn('manager')"
        :column="table.getColumn('manager')"
        title="مدير الطلب"
        :options="
          managers.map((user) => ({
            label: user.name,
            value: String(user.id),
          }))
        "
      />
      <DataTableFacetedFilter
        v-if="table.getColumn('network')"
        :column="table.getColumn('network')"
        title="الشبكة"
        :options="
          networks.map((network) => ({
            label: network.name,
            value: String(network.id),
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
