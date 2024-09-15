<script setup lang="ts" generic="TData, TValue">
import {
  Pagination,
  PaginationList,
  PaginationListItem,
} from '@/Components/ui/pagination'
import { Button } from '@/Components/ui/button'
import { Link } from '@inertiajs/vue3'
import { Meta } from '@/types'

defineProps<{
  meta: Meta
  href: string
}>()
</script>

<template>
  <div class="mt-3 flex items-center justify-between px-4 md:px-0">
    <Pagination
      v-slot="{ page }"
      :total="meta.total"
      :sibling-count="1"
      show-edges
      :default-page="meta.current_page"
    >
      <PaginationList v-slot="{ items }" class="flex items-center gap-1">
        <template v-for="(item, index) in items">
          <PaginationListItem
            v-if="item.type === 'page'"
            :key="index"
            :value="item.value"
            as-child
          >
            <Link :href="route(href, { page: item.value })" preserve-scroll>
              <Button
                class="h-10 w-10 p-0"
                :variant="item.value === page ? 'default' : 'outline'"
              >
                {{ item.value }}
              </Button>
            </Link>
          </PaginationListItem>
        </template>
      </PaginationList>
    </Pagination>

    <div class="text-sm">عدد النتائج {{ meta.total }}</div>
  </div>
</template>
