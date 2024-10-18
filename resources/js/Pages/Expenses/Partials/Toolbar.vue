<script setup lang="ts">
import { inject } from 'vue'
import { usePage } from '@inertiajs/vue3'

import {
  DataTableFacetedFilter,
  DataTableDateRangeFilter,
} from '@/Components/data-table'

import { Expense, Network, User } from '@/types'
import type { Table } from '@tanstack/vue-table'

interface DataTableToolbarProps {
  managers: Array<User>
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
const table = inject('table') as Table<Expense>
</script>

<template>
  <DataTableFacetedFilter
    v-if="table.getColumn('user')"
    :column="table.getColumn('user')"
    title="المدخل"
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
</template>
