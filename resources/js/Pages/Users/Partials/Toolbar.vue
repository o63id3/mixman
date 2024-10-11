<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Network, User } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'
import { usePage } from '@inertiajs/vue3'
import { roles } from '@/types/enums'

interface DataTableToolbarProps {
  table: Table<User>
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table" table-id="users">
    <div class="flex gap-2">
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
      <DataTableFacetedFilter
        v-if="table.getColumn('role') && user.role === 'ahmed'"
        :column="table.getColumn('role')"
        title="الصلاحية"
        :options="roles"
      />
    </div>
  </DataTableToolbar>
</template>
