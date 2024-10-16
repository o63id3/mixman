<script setup lang="ts">
import type { Column } from '@tanstack/vue-table'
import {
  ArrowDownIcon,
  ArrowUpIcon,
  CaretSortIcon,
  EyeNoneIcon,
} from '@radix-icons/vue'

import { cn } from '@/lib/utils'
import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

defineProps<{
  column: Column<any, any>
  title: string
}>()
</script>

<script lang="ts">
export default {
  inheritAttrs: false,
}
</script>

<template>
  <div
    v-if="column.getCanSort() || column.getCanHide()"
    :class="cn('flex items-center space-x-2', $attrs.class ?? '')"
  >
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="ghost" size="sm" class="-ml-3 h-8">
          <span>{{ title }}</span>
          <ArrowDownIcon
            v-if="column.getIsSorted() === 'desc'"
            class="ml-2 h-4 w-4"
          />
          <ArrowUpIcon
            v-else-if="column.getIsSorted() === 'asc'"
            class="ml-2 h-4 w-4"
          />
          <CaretSortIcon v-else class="ml-2 h-4 w-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="start">
        <DropdownMenuItem
          @click="column.toggleSorting(false)"
          v-if="column.getCanSort()"
        >
          <ArrowUpIcon
            class="h-3.5 w-3.5 text-muted-foreground/70 ltr:mr-2 rtl:ml-2"
          />
          تصاعدي
        </DropdownMenuItem>
        <DropdownMenuItem
          @click="column.toggleSorting(true)"
          v-if="column.getCanSort()"
        >
          <ArrowDownIcon
            class="h-3.5 w-3.5 text-muted-foreground/70 ltr:mr-2 rtl:ml-2"
          />
          تنازلي
        </DropdownMenuItem>
        <DropdownMenuSeparator
          v-if="column.getCanSort() && column.getCanHide()"
        />
        <DropdownMenuItem
          v-if="column.getCanHide()"
          @click="column.toggleVisibility(false)"
        >
          <EyeNoneIcon
            class="h-3.5 w-3.5 text-muted-foreground/70 ltr:mr-2 rtl:ml-2"
          />
          اخفاء
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>

  <div v-else :class="$attrs.class">
    {{ title }}
  </div>
</template>
