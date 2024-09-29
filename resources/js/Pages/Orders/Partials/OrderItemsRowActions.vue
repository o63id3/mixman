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
import { Link, router } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { toast } from '@/Components/ui/toast'
import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  row: Row<OrderItem>
}>()

const row = computed(() => props.row.original)
</script>

<template>
  <Button
    v-if="row.can.delete"
    @click="
      () =>
        router.delete(route('order-items.destroy', row.id), {
          preserveScroll: true,
          onSuccess: () => toast({ title: 'تم حذف الرزمة' }),
        })
    "
    variant="ghost"
    class="flex h-8 w-8 p-0"
  >
    <Trash2 class="h-4 w-4 text-red-500" />
    <span class="sr-only">Delete</span>
  </Button>
</template>
