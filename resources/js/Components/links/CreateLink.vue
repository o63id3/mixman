<script setup lang="ts">
import { PlusCircle } from 'lucide-vue-next'
import { Button } from '../ui/button'
import { Link } from '@inertiajs/vue3'

withDefaults(
  defineProps<{
    href: string
    btnTitle?: string
  }>(),
  {
    btnTitle: 'إنشاء',
  },
)
</script>

<template>
  <Link :href="href">
    <Button size="sm" class="h-7 gap-1">
      <PlusCircle class="h-3.5 w-3.5" />
      <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
        {{ btnTitle }}
      </span>
    </Button>
  </Link>
</template>
