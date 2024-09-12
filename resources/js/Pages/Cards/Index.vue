<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Card, Paginator, User } from '@/types'
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
import { CheckCircle2Icon, XCircleIcon } from 'lucide-vue-next'

defineProps<{
  cards: Paginator<Card>
}>()
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الكروت
        </h2>
        <div v-if="$page.props.auth.user.can.cards.create">
          <Link :href="route('cards.create')">
            <Button> إنشاء كرت </Button>
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
                  <TableHead class="w-[50px] text-right">#</TableHead>
                  <TableHead class="w-1/3 text-right">الاسم</TableHead>
                  <TableHead class="w-1/3 text-right">السعر</TableHead>
                  <TableHead class="w-1/3 text-right">فعال</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="card in cards.data" :key="card.id">
                  <TableCell class="font-medium hover:underline">
                    <component
                      :is="
                        $page.props.auth.user.can.cards.update ? Link : 'span'
                      "
                      :href="route('cards.edit', card.id)"
                    >
                      {{ card.id }}
                    </component>
                  </TableCell>
                  <TableCell>{{ card.name }}</TableCell>
                  <TableCell>{{ card.price }}</TableCell>
                  <TableCell>
                    <component
                      :is="card.active ? CheckCircle2Icon : XCircleIcon"
                      :class="[card.active ? 'text-green-500' : 'text-red-500']"
                    />
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <Pagination
              v-slot="{ page }"
              :total="cards.meta.total"
              :sibling-count="1"
              show-edges
              :default-page="cards.meta.current_page"
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
                      :href="route('cards.index', { page: item.value })"
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
