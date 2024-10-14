<script setup lang="ts">
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  DoubleArrowLeftIcon,
  DoubleArrowRightIcon,
} from '@radix-icons/vue'

import { Button } from '@/Components/ui/button'
import { Filters, Links, Meta } from '@/types'

import { router } from '@inertiajs/vue3'

defineProps<{
  links: Links
  meta: Meta
  filters?: Filters
  sorts?: string
}>()
</script>

<template>
  <div
    class="flex items-center justify-between border-y bg-white px-3 py-2 shadow-sm lg:rounded-md lg:border-x rtl:flex-row-reverse"
  >
    <div
      class="flex items-center justify-center text-xs font-medium tracking-wide"
    >
      <!-- العناصر من {{ meta.from }} حتى {{ meta.to }} من أصل {{ meta.total }} عنصر -->
      <!-- صفحة {{ meta.current_page }} من {{ meta.last_page }} -->
      {{ meta.current_page }} \ {{ meta.last_page }}
    </div>

    <div class="flex items-center space-x-2 rtl:flex-row-reverse">
      <Button
        variant="outline"
        class="hidden h-8 w-8 p-0 lg:flex"
        :disabled="!links.next"
        @click="
          router.get(
            links.last,
            { filter: filters, sort: sorts?.length ? sorts : undefined },
            { preserveScroll: true, preserveState: true },
          )
        "
      >
        <span class="sr-only">Go to first page</span>
        <DoubleArrowLeftIcon class="h-4 w-4" />
      </Button>
      <Button
        variant="outline"
        class="h-8 w-8 p-0"
        :disabled="!links.next"
        @click="
          router.get(
            links.next,
            { filter: filters, sort: sorts?.length ? sorts : undefined },
            { preserveScroll: true, preserveState: true },
          )
        "
      >
        <span class="sr-only">Go to previous page</span>
        <ChevronLeftIcon class="h-4 w-4" />
      </Button>
      <Button
        variant="outline"
        class="h-8 w-8 p-0"
        :disabled="!links.prev"
        @click="
          router.get(
            links.prev,
            { filter: filters, sort: sorts?.length ? sorts : undefined },
            { preserveScroll: true, preserveState: true },
          )
        "
      >
        <span class="sr-only">Go to next page</span>
        <ChevronRightIcon class="h-4 w-4" />
      </Button>
      <Button
        variant="outline"
        class="hidden h-8 w-8 p-0 lg:flex"
        :disabled="!links.prev"
        @click="
          router.get(
            links.first,
            { filter: filters, sort: sorts?.length ? sorts : undefined },
            { preserveScroll: true, preserveState: true },
          )
        "
      >
        <span class="sr-only">Go to last page</span>
        <DoubleArrowRightIcon class="h-4 w-4" />
      </Button>
    </div>
  </div>
</template>
