<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, Region } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'

import {
  Pagination,
  PaginationList,
  PaginationListItem,
} from '@/Components/ui/pagination'

import { Button } from '@/Components/ui/button'

defineProps<{
  regions: Paginator<Region>
}>()
</script>

<template>
  <Head title="Regions" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          المناطق
        </h2>
        <div v-if="$page.props.auth.user.can.sellers.create">
          <Link :href="route('regions.create')">
            <Button> إنشاء منطقة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="w-5 text-right">#</TableHead>
                  <TableHead class="text-right">الاسم</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="region in regions.data" :key="region.id">
                  <TableCell>{{ region.id }}</TableCell>
                  <TableCell class="font-medium hover:underline">
                    <component
                      :is="
                        $page.props.auth.user.can.sellers.update ? Link : 'span'
                      "
                      :href="route('regions.edit', region.id)"
                    >
                      {{ region.name }}
                    </component>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <Pagination
              v-slot="{ page }"
              :total="regions.meta.total"
              :sibling-count="1"
              show-edges
              :default-page="regions.meta.current_page"
            >
              <PaginationList
                v-slot="{ items }"
                class="flex items-center gap-1"
              >
                <template v-for="(item, index) in items">
                  <PaginationListItem
                    v-if="item.type === 'page'"
                    :key="index"
                    :value="item.value"
                    as-child
                  >
                    <Link
                      :href="route('sellers.index', { page: item.value })"
                      preserve-scroll
                    >
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
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
