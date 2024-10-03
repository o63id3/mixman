<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { Button } from '@/Components/ui/button'
import { Network, User } from '@/types'
import { router } from '@inertiajs/vue3'
import { toast } from '@/Components/ui/toast'
import { Trash2 } from 'lucide-vue-next'

defineProps<{
  row: Row<User>
  network: Network
}>()
</script>

<template>
  <Button
    @click="
      () =>
        router.delete(
          route('network.partners.destroy', [network.id, row.original.id]),
          {
            preserveScroll: true,
            onSuccess: () => toast({ title: 'تم حذف الشريك' }),
          },
        )
    "
    variant="ghost"
    class="flex h-8 w-8 p-0"
  >
    <Trash2 class="h-4 w-4 text-red-500" />
    <span class="sr-only">حذف</span>
  </Button>
</template>
