<script setup lang="ts">
import { PlusCircle } from 'lucide-vue-next'
import { Button } from '../ui/button'
import { Link } from '@inertiajs/vue3'

defineProps<{
  href: string
  btnTitle?: string
}>()
</script>

<template>
  <Link :href="href">
    <Button size="sm" class="h-7 gap-1 text-xs tracking-wide">
      <PlusCircle class="h-3.5 w-3.5" />
      <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
        <slot>إنشاء</slot>
      </span>
    </Button>
  </Link>
</template>
