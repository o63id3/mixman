<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Network, Transaction, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import DataTableDateRangeFilter from '@/Components/data-table/DataTableDateRangeFilter.vue'
import { usePage } from '@inertiajs/vue3'

interface DataTableToolbarProps {
  table: Table<Transaction>
  users: Array<User>
  managers: Array<User>
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table" table-id="transactions">
    <div v-if="$page.props.auth.user.network" class="flex gap-2">
      <DataTableFacetedFilter
        v-if="table.getColumn('user')"
        :column="table.getColumn('user')"
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
        title="المدير"
        :options="
          managers.map((user) => ({
            label: user.name,
            value: String(user.id),
          }))
        "
      />
      <DataTableFacetedFilter
        v-if="table.getColumn('network') && user.role === 'ahmed'"
        :column="table.getColumn('network')"
        title="الشبكة"
        :options="
          networks.map((network) => ({
            label: network.name,
            value: String(network.id),
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
