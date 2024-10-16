<script setup lang="ts" generic="TData">
import { computed, inject } from 'vue'

import { Button } from '@/Components/ui/button'
import DataTableViewOptions from './DataTableViewOptions.vue'

import { Cross2Icon } from '@radix-icons/vue'

const table = inject<import('@tanstack/table-core').Table<TData>>('table')

const isFiltered = computed(() =>
  table ? table.getState().columnFilters.length > 0 : false,
)
</script>

<template>
  <div
    v-if="table"
    class="flex items-center justify-between gap-2 overflow-x-auto px-2 lg:px-0"
  >
    <div class="flex flex-1 items-center gap-2">
      <slot />

      <Button
        v-if="isFiltered"
        variant="ghost"
        class="h-8"
        size="icon"
        @click="table.resetColumnFilters()"
      >
        <Cross2Icon class="h-4 w-4" />
      </Button>
    </div>

    <DataTableViewOptions />
  </div>
</template>
