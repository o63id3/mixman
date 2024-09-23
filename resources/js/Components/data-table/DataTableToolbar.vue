<script setup lang="ts">
import type { Table } from '@tanstack/vue-table'
import { computed } from 'vue'
import { Transaction } from '@/types'

import { Cross2Icon } from '@radix-icons/vue'
import { Button } from '@/Components/ui/button'

interface DataTableToolbarProps {
  table: Table<Transaction>
}

const props = defineProps<DataTableToolbarProps>()

const isFiltered = computed(
  () => props.table.getState().columnFilters.length > 0,
)
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex flex-1 items-center space-x-2">
      <slot />

      <Button
        v-if="isFiltered"
        variant="ghost"
        class="flex h-8 items-end gap-1 px-2 lg:px-3"
        @click="table.resetColumnFilters()"
      >
        مسح
        <Cross2Icon class="ml-2 h-4 w-4" />
      </Button>
    </div>
  </div>
</template>
