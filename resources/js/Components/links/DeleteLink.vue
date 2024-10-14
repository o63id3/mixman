<script setup lang="ts">
import { cn } from '@/lib/utils'
import { buttonVariants } from '../ui/button'
import { Link } from '@inertiajs/vue3'
import { AreaHTMLAttributes } from 'vue'

defineProps<{
  href: AreaHTMLAttributes['href']
}>()
</script>

<template>
  <Link
    :href="href ?? ''"
    method="delete"
    as="button"
    type="button"
    :class="cn(buttonVariants({ variant: 'destructive' }))"
  >
    <slot />
  </Link>
</template>
