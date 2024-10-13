<script setup lang="ts" generic="TData">
import type { Table } from '@tanstack/vue-table'
import { computed } from 'vue'

import { Button } from '@/Components/ui/button'
import DataTableViewOptions from './DataTableViewOptions.vue'

import { Cross2Icon } from '@radix-icons/vue'

interface DataTableToolbarProps {
  table: Table<TData>
  tableId: string
}

const props = defineProps<DataTableToolbarProps>()

const isFiltered = computed(
  () => props.table.getState().columnFilters.length > 0,
)
</script>

<template>
  <div class="flex items-center justify-between gap-2">
    <div class="flex flex-1 items-center gap-2">
      <slot />

      <Button
        v-if="isFiltered"
        variant="ghost"
        class="h-8 gap-1"
        @click="table.resetColumnFilters()"
      >
        <div class="flex items-center gap-x-1">
          مسح
          <Cross2Icon class="mr-2 h-4 w-4" />
        </div>
      </Button>
    </div>

    <DataTableViewOptions :table="table" :table-id="tableId" />
  </div>
</template>
