<script setup lang="ts">
import { Network, User } from '@/types'
import { DataTableFacetedFilter } from '@/Components/data-table'

import { usePage } from '@inertiajs/vue3'
import { roles } from '@/types/enums'
import { inject } from 'vue'

interface DataTableToolbarProps {
  networks: Array<Network>
}

const user = usePage().props.auth.user

defineProps<DataTableToolbarProps>()
const table = inject<import('@tanstack/table-core').Table<User>>('table')
</script>

<template>
  <DataTableFacetedFilter
    v-if="table && table.getColumn('network') && user.role === 'ahmed'"
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
    v-if="table && table.getColumn('role') && user.role === 'ahmed'"
    :column="table.getColumn('role')"
    title="الصلاحية"
    :options="roles"
  />
</template>
