<script setup lang="ts" generic="TData">
import type { Table } from '@tanstack/vue-table'
import { computed } from 'vue'
import { MixerHorizontalIcon } from '@radix-icons/vue'

import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

interface DataTableViewOptionsProps {
  table: Table<TData>
}

const props = defineProps<DataTableViewOptionsProps>()

const columns = computed(() =>
  props.table
    .getAllColumns()
    .filter(
      (column) =>
        typeof column.accessorFn !== 'undefined' && column.getCanHide(),
    ),
)
</script>

<template>
  <DropdownMenu v-if="columns.length">
    <DropdownMenuTrigger as-child>
      <Button
        variant="outline"
        size="sm"
        class="ml-auto hidden h-8 lg:flex rtl:flex-row-reverse"
      >
        <MixerHorizontalIcon class="mr-2 h-4 w-4" />
        الحقول
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[150px]">
      <DropdownMenuLabel>اظهار الحقول</DropdownMenuLabel>
      <DropdownMenuSeparator />

      <DropdownMenuCheckboxItem
        v-for="column in columns"
        :key="column.id"
        class="capitalize"
        :checked="column.getIsVisible()"
        @update:checked="(value) => column.toggleVisibility(!!value)"
      >
        <span class="truncate">{{ column.id }}</span>
      </DropdownMenuCheckboxItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
