<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { computed } from 'vue'

import { Button } from '@/Components/ui/button'
import { Card } from '@/types'
import { router } from '@inertiajs/vue3'
import { toast } from '@/Components/ui/toast'
import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  row: Row<Card>
}>()

const row = computed(() => props.row.original)
</script>

<template>
  <Button
    v-if="row.pivot.can.delete"
    @click="
      () =>
        router.delete(route('order-cards.destroy', row.id), {
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
