<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { Transaction } from '@/types'
import DataTableFacetedFilter from '@/Components/data-table/DataTableFacetedFilter.vue'

import { transactionTypes } from '@/types/enums'
import DataTableToolbar from '@/Components/data-table/DataTableToolbar.vue'

interface DataTableToolbarProps {
  table: Table<Transaction>
}

defineProps<DataTableToolbarProps>()
</script>

<template>
  <DataTableToolbar :table="table">
    <DataTableFacetedFilter
      v-if="table.getColumn('type')"
      :column="table.getColumn('type')"
      title="النوع"
      :options="transactionTypes"
    />
  </DataTableToolbar>
</template>
