<script setup lang="ts">
import { inject } from 'vue'
import { usePage } from '@inertiajs/vue3'

import { DataTableFacetedFilter } from '@/Components/data-table'

import { Network, User } from '@/types'
import { roles } from '@/types/enums'
import type { Table } from '@tanstack/vue-table'

interface DataTableToolbarProps {
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
const table = inject('table') as Table<User>
</script>

<template>
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
</template>
