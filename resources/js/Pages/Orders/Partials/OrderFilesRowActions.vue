<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { computed } from 'vue'

import { OrderFile } from '@/types'
import { Link } from '@inertiajs/vue3'
import { Trash2 } from 'lucide-vue-next'

const props = defineProps<{
  row: Row<OrderFile>
}>()

const row = computed(() => props.row.original)
</script>

<template>
  <Link
    v-if="row.can.delete"
    :href="route('order-files.destroy', row.id)"
    method="delete"
    as="button"
  >
    <Trash2 class="size-5 rounded p-0.5 text-red-500 hover:bg-red-50" />
  </Link>
</template>
