<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { computed } from 'vue'
import { DotsHorizontalIcon } from '@radix-icons/vue'

import { Button, buttonVariants } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { OrderItem } from '@/types'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { toast } from '@/Components/ui/toast'

const props = defineProps<{
  row: Row<OrderItem>
}>()

const row = computed(() => props.row.original)
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button
        variant="ghost"
        class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
      >
        <DotsHorizontalIcon class="h-4 w-4" />
        <span class="sr-only">Open menu</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[160px]">
      <DropdownMenuItem v-if="row.can.delete">
        <Link
          :href="route('order-items.destroy', row.id)"
          method="delete"
          as="button"
          type="button"
          class="w-full"
          :class="cn(buttonVariants({ variant: 'destructive' }))"
          @success="toast({ title: 'تم حذف الرزمة' })"
        >
          حذف
        </Link>
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
