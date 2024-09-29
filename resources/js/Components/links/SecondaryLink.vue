<script setup lang="ts">
import { cn } from '@/lib/utils'
import { buttonVariants } from '../ui/button'
import { Link } from '@inertiajs/vue3'

defineProps<{
  href: string
}>()
</script>

<template>
  <Link
    :href="href"
    as="button"
    type="button"
    :class="cn(buttonVariants({ variant: 'outline' }))"
  >
    <slot />
  </Link>
</template>
